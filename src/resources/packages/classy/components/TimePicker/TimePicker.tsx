import * as React from 'react';
import { format } from '@wordpress/date';
import { useState, useMemo, useCallback, useRef } from '@wordpress/element';
import { ComboboxControl } from '@wordpress/components';
import { ComboboxControlOption } from '@wordpress/components/build-types/combobox-control/types';
import { getValidDateOrNull } from '../../functions';

function getTimeOptions(
	currentDate: Date,
	startDate: Date | null = null,
	endDate: Date | null = null,
	timeInterval: number,
	timeFormat: string
): ComboboxControlOption[] {
	const times: ComboboxControlOption[] = [];

	let start: Date, end: Date;

	// If we have a start date constraint, use it as the lower boundary.
	if ( startDate ) {
		start = new Date( startDate );
	} else {
		start = new Date( currentDate );
		start.setHours( 0, 0, 0 );
	}

	// If we have an end date constraint, use it as the upper boundary.
	if ( endDate ) {
		end = new Date( endDate );
	} else {
		end = new Date( currentDate );
		end.setHours( 23, 59, 0 );
	}

	// Adjust start time to the nearest interval.
	let hStart = start.getHours();
	let mStart = 0;
	if ( startDate ) {
		mStart = Math.ceil( start.getMinutes() / timeInterval ) * timeInterval;
		if ( mStart === 60 ) {
			mStart = 0;
			hStart += 1;
		}
	}

	// Loop through hours and minutes.
	for ( let h = hStart; h < 24; h++ ) {
		let m = h === hStart ? mStart : 0;
		while ( m < 60 ) {
			const date = new Date( currentDate );
			date.setHours( h, m, 0, 0 );

			// Compare using minutes for better accuracy.
			const timeValue = date.getHours() * 60 + date.getMinutes();
			const startValue = start.getHours() * 60 + start.getMinutes();
			const endValue = end.getHours() * 60 + end.getMinutes();

			// Check if time is within range.
			const isAfterStart = ! startDate || timeValue > startValue;
			const isBeforeEnd = ! endDate || timeValue < endValue;

			if ( isAfterStart && isBeforeEnd ) {
				times.push( {
					label: format( timeFormat, date ),
					value: format( 'H:i:s', date ),
				} );
			}

			m += timeInterval;
		}
	}
	return times;
}

function getOptions( currentDate: Date, timeFormat: string, timeOptions: ComboboxControlOption[] ) {
	const formattedCurrentDate = format( 'H:i:s', currentDate );

	// First check if the current time exists in the options.
	const existingOption = timeOptions.find( ( option ) => option.value === formattedCurrentDate );
	if ( existingOption ) {
		return timeOptions;
	}

	// If not found, create a custom option and merge with existing options this is the case where user types a time.
	const customOption = {
		label: format( timeFormat, currentDate ),
		value: format( 'H:i:s', currentDate ),
		isCustom: true,
	};

	// Return all options plus the custom one.
	return [ ...timeOptions, customOption ];
}

export default function TimePicker( props: {
	currentDate: Date;
	endDate?: Date | null;
	highlight: boolean;
	onChange: ( date: Date ) => void;
	startDate?: Date | null;
	timeFormat: string;
	timeInterval: number; // In minutes.
} ) {
	const { currentDate, endDate = null, highlight, onChange, startDate = null, timeFormat, timeInterval } = props;

	// Keep a reference to the start and end date to spot changes coming from the parent component.
	const dateRef = useRef( { startDate, endDate } );

	// Did either date change?
	const datesChanged = dateRef.current.startDate !== startDate || dateRef.current.endDate !== endDate;

	if ( datesChanged ) {
		dateRef.current = { startDate, endDate };
	}

	const currenDateYearMonthDayPrefix = format( 'Y-m-d ', currentDate );

	let [ selectedTime, setSelectedTime ] = useState( () => format( 'H:i:s', currentDate ) );

	if ( datesChanged ) {
		// Start or end date changed: use a new value.
		selectedTime = format( 'H:i:s', currentDate );
	}

	// Calculate all the available time options.
	const timeOptions = useMemo( (): ComboboxControlOption[] => {
		return getTimeOptions( currentDate, startDate, endDate, timeInterval, timeFormat );
	}, [ currentDate, timeFormat, timeInterval, startDate, endDate ] );

	// Set the initial options to all available time options.
	let [ options, setOptions ] = useState( () => getOptions( currentDate, timeFormat, timeOptions ) );

	if ( datesChanged ) {
		// Start or end date changed: use a new set of options.
		options = getOptions( currentDate, timeFormat, timeOptions );
	}

	const onChangeProxy = useCallback(
		( value: string | null | undefined ): void => {
			if ( ! value ) {
				return;
			}

			const date = getValidDateOrNull( currenDateYearMonthDayPrefix + value );

			if ( date === null ) {
				return;
			}

			setSelectedTime( value );
			onChange( date );
		},
		[ currenDateYearMonthDayPrefix, onChange ]
	);

	const onFilterValueChange = useCallback(
		( value: string | null | undefined ): void => {
			if ( ! value ) {
				setOptions( timeOptions );
				return;
			}

			// Reduce the options to only those whose label start with the value.
			const newOptions = timeOptions.filter( ( option ) =>
				option.label.toLowerCase().startsWith( value.toLowerCase() )
			);

			if ( newOptions.length > 0 ) {
				// There are still matching options.
				setOptions( newOptions );
			} else {
				// Try to parse the value as a time.
				const date = getValidDateOrNull( currenDateYearMonthDayPrefix + value );
				if ( date ) {
					// If it's a valid time, create a custom option.
					const customOption = {
						label: format( timeFormat, date ),
						value: format( 'H:i:s', date ),
						isCustom: true,
					};
					setOptions( [ customOption ] );
					setSelectedTime( customOption.value );
				} else {
					// If not a valid time, show all options.
					setOptions( timeOptions );
				}
			}
		},
		[ timeOptions ]
	);

	let className = 'classy-field__control classy-field__control--input classy-field__control--time-picker';

	// This is a hack to make the component highlight again on successive renders when the dates changed.
	// By setting a new key on the component, we force a re-render and thus re-apply the highlight effect.
	const highlightKey = useRef< number >( Math.random() );
	if ( datesChanged && highlight ) {
		className += ' classy-highlight';
		highlightKey.current = Math.random();
	}

	return (
		<ComboboxControl
			key={ highlightKey.current }
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			className={ className }
			allowReset={ false }
			value={ selectedTime }
			options={ options }
			onChange={ onChangeProxy }
			onFilterValueChange={ onFilterValueChange }
			expandOnFocus={ ! ( options.length === 1 && options[ 0 ].isCustom ) }
		/>
	);
}
