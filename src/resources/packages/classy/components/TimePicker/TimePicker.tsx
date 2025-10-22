import * as React from 'react';
import { format } from '@wordpress/date';
import { useState, useMemo, useCallback, useRef, useEffect } from '@wordpress/element';
import { ComboboxControl } from '@wordpress/components';
import { ComboboxControlOption } from '@wordpress/components/build-types/combobox-control/types';
import { getValidDateOrNull } from '../../functions';
import { TimeUpdateType } from '../../types/FieldProps';
import { phpDateMysqlFormat, phpTimeMysqlFormat } from '../../constants';

/**
 * Generate all possible time options for the start time.
 *
 * @since TBD
 *
 * @param {Date} currentDate The currently selected date.
 * @param {number} timeInterval The time interval in minutes.
 * @param {string} timeFormat The time format to display.
 * @return {ComboboxControlOption[]} The generated time options.
 */
function getStartTimeOptions( currentDate: Date, timeInterval: number, timeFormat: string ): ComboboxControlOption[] {
	const times: ComboboxControlOption[] = [];

	// Loop through hours and minutes according to the time interval.
	for ( let h = 0; h < 24; h++ ) {
		let m = 0;
		while ( m < 60 ) {
			const date = new Date( currentDate );
			date.setHours( h, m, 0, 0 );

			times.push( {
				label: format( timeFormat, date ),
				value: format( phpTimeMysqlFormat, date ),
			} );

			m += timeInterval;
		}
	}

	return times;
}

/**
 * Generate all possible time options for the end time, considering an optional start date constraint.
 *
 * @since TBD
 *
 * @param {Date} currentDate The currently selected date.
 * @param {Date|null} startDate The optional start date constraint.
 * @param {number} timeInterval The time interval in minutes.
 * @param {string} timeFormat The time format to display.
 * @return {ComboboxControlOption[]} The generated time options.
 */
function getEndTimeOptions(
	currentDate: Date,
	startDate: Date | null = null,
	timeInterval: number,
	timeFormat: string
): ComboboxControlOption[] {
	const times: ComboboxControlOption[] = [];

	// Set up the latest possible date.
	const end = new Date( currentDate );
	end.setHours( 23, 59, 0 );

	// If we have a start date constraint, use it as the lower boundary.
	let start: Date;
	if ( startDate ) {
		start = new Date( startDate );
	} else {
		start = new Date( currentDate );
		start.setHours( 0, 0, 0 );
	}

	// Adjust start time to the nearest interval.
	let hStart = start.getHours();
	let mStart = Math.ceil( start.getMinutes() / timeInterval ) * timeInterval;

	if ( mStart === 60 ) {
		mStart = 0;
		hStart += 1;
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
			const isAfterStart = timeValue >= startValue;
			const isBeforeEnd = timeValue <= endValue;

			if ( isAfterStart && isBeforeEnd ) {
				times.push( {
					label: format( timeFormat, date ),
					value: format( phpTimeMysqlFormat, date ),
				} );
			}

			m += timeInterval;
		}
	}

	return times;
}

function getOptions( currentDate: Date, timeFormat: string, timeOptions: ComboboxControlOption[] ) {
	const formattedCurrentDate = format( phpTimeMysqlFormat, currentDate );

	// First check if the current time exists in the options.
	const existingOption = timeOptions.find( ( option ) => option.value === formattedCurrentDate );
	if ( existingOption ) {
		return timeOptions;
	}

	// If not found, create a custom option and merge with existing options this is the case where user types a time.
	const customOption = {
		label: format( timeFormat, currentDate ),
		value: format( phpTimeMysqlFormat, currentDate ),
		isCustom: true,
	};

	// Return all options plus the custom one.
	return [ ...timeOptions, customOption ];
}

type TimePickerProps = {
	/**
	 * The currently selected date.
	 */
	currentDate: Date;

	/**
	 * The optional end date constraint.
	 */
	endDate?: Date | null;

	/**
	 * Whether to highlight the field. Pass this as true to trigger the highlight animation.
	 */
	highlight: boolean;

	/**
	 * Callback when the date changes.
	 * @param {Date} date The new date.
	 */
	onChange: ( date: Date ) => void;

	/**
	 * The optional start date constraint.
	 */
	startDate?: Date | null;

	/**
	 * The time format to display.
	 */
	timeFormat: string;

	/**
	 * The time interval in minutes.
	 */
	timeInterval: number;

	/**
	 * The type of date update (e.g., 'startTime' or 'endTime').
	 */
	type?: TimeUpdateType;
};

/**
 * TimePicker component.
 *
 * Displays a time picker allowing users to select a time within optional start and end date constraints.
 *
 * @since TBD
 *
 * @param {TimePickerProps} props The properties for the TimePicker component.
 * @return {React.JSX.Element} The rendered TimePicker component.
 */
export default function TimePicker( props: TimePickerProps ): React.JSX.Element {
	const {
		currentDate,
		endDate = null,
		highlight,
		onChange,
		startDate = null,
		timeFormat,
		timeInterval,
		type = 'startTime',
	} = props;

	// Keep a reference to the start and end date to spot changes coming from the parent component.
	const dateRef = useRef( { startDate, endDate } );

	// Did either date change?
	const datesChanged = dateRef.current.startDate !== startDate || dateRef.current.endDate !== endDate;

	if ( datesChanged ) {
		dateRef.current = { startDate, endDate };
	}

	const currenDateYearMonthDayPrefix = format( `${ phpDateMysqlFormat } `, currentDate );

	let [ selectedTime, setSelectedTime ] = useState( () => format( phpTimeMysqlFormat, currentDate ) );

	// Use useEffect to properly handle date changes
	useEffect( () => {
		// Update selectedTime when currentDate changes
		setSelectedTime( format( phpTimeMysqlFormat, currentDate ) );
	}, [ currentDate ] );

	// Calculate all the available time options.
	const timeOptions = useMemo( (): ComboboxControlOption[] => {
		if ( type === 'startTime' ) {
			return getStartTimeOptions( currentDate, timeInterval, timeFormat );
		} else {
			return getEndTimeOptions( currentDate, startDate, timeInterval, timeFormat );
		}
	}, [ currentDate, timeFormat, timeInterval, startDate, type ] );

	// Set the initial options to all available time options.
	const [ options, setOptions ] = useState( () => getOptions( currentDate, timeFormat, timeOptions ) );

	// Update options when dates or time options change.
	useEffect( () => {
		setOptions( getOptions( currentDate, timeFormat, timeOptions ) );
	}, [ currentDate, timeFormat, timeOptions ] );

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
						value: format( phpTimeMysqlFormat, date ),
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
	if ( highlight ) {
		className += ' classy-highlight';
	}

	// Force re-render when highlight changes to restart the CSS animation.
	const highlightKey = useMemo( () => Math.random(), [ highlight, currentDate ] );

	return (
		<ComboboxControl
			key={ highlightKey }
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
