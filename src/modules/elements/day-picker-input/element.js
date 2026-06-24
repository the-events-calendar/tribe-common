/**
 * External dependencies
 */
import React, { useState, useRef, useMemo, useCallback, useEffect } from 'react';
import classNames from 'classnames';
import 'react-day-picker/src/style.css';
import { DayPicker } from 'react-day-picker';
import { Popover } from '@wordpress/components';
import { getSettings as getDateSettings } from '@wordpress/date';
import { parse as parseDate } from 'date-fns';

/**
 * Internal dependencies
 */
import {
	markDatePickerInteractionPending,
	registerDatePickerClose,
	registerDatePickerOpen,
} from '../../utils/date-picker-popover-state';

const DatePickerInput = ( props ) => {
	const { setPopoverAnchor, inputRef, onDayChange, ...inputProps } = props;

	return (
		<div ref={ setPopoverAnchor } className={ classNames( 'tribe-editor__date-input__container' ) }>
			<input
				ref={ inputRef } // Attach the ref to the input element
				className={ classNames( 'tribe-editor__date-input' ) }
				onChange={ ( event ) => {} }
				{ ...inputProps }
			/>
		</div>
	);
};

/**
 * Converts the date format from moment.js to the Unicode one used in date-fns.
 *
 * @see https://github.com/date-fns/date-fns/blob/main/docs/unicodeTokens.md
 *
 * @param {string} momentFormat The moment.js format string to convert.
 *
 * @return {string} The converted format string.
 */
const momentToDateFnsFormatter = ( momentFormat ) => {
	return momentFormat.replace( 'DD', 'dd' ).replace( 'D', 'd' ).replace( 'YYYY', 'yyyy' ).replace( 'YY', 'yy' );
};

const DayPickerInput = ( props ) => {
	const popoverAnchor = useRef( null ); // Ref for the Popover anchor
	const inputRef = useRef( null ); // Ref for the input field
	const isVisibleRef = useRef( false );
	const [ isVisible, setIsVisible ] = useState( false );
	// Do not memoize this: it could be changed in the context of the Block Editor elsewhere.
	const phpDateFormat = getDateSettings()?.formats?.date ?? 'MMMM d, y';
	const dateFormatter = useMemo( () => new DateFormatter(), [] );
	const parsePhpDate = useCallback( ( value ) => dateFormatter.parseDate( value, phpDateFormat ), [ phpDateFormat ] );
	const formatPhpDate = useCallback( ( date ) => dateFormatter.formatDate( date, phpDateFormat ), [ phpDateFormat ] );

	const handleInputMouseDown = () => {
		markDatePickerInteractionPending();
	};

	const toggleVisible = () => {
		setIsVisible( ( state ) => {
			if ( state ) {
				registerDatePickerClose();
				isVisibleRef.current = false;
				return false;
			}

			registerDatePickerOpen();
			isVisibleRef.current = true;
			return true;
		} );
	};

	const { value, onDayChange, formatDate, format } = props;

	// Convert the format from the moment.js one to date-fns one using Unicode characters.
	const dateFnsFormat = momentToDateFnsFormatter( format );

	const getSelectedDateInitialState = useCallback(
		( value ) => {
			// Try and parse the value using teh date-fns format.
			const d = parseDate( value, dateFnsFormat, new Date() );

			if ( d instanceof Date && ! isNaN( d ) ) {
				return d;
			}

			// Try and parse the value using the PHP date format.
			const parsed = parsePhpDate( value );

			return parsed;
		},
		[ dateFnsFormat, parsePhpDate ]
	);

	const [ selectedDate, setSelectedDate ] = useState( value ? getSelectedDateInitialState( value ) : new Date() );

	useEffect( () => {
		if ( ! value ) {
			return;
		}

		const nextDate = getSelectedDateInitialState( value );

		setSelectedDate( ( previousDate ) => {
			if (
				previousDate?.getTime?.() === nextDate?.getTime?.() &&
				! isNaN( previousDate?.getTime?.() )
			) {
				return previousDate;
			}

			return nextDate;
		} );
	}, [ value, getSelectedDateInitialState ] );

	useEffect( () => {
		return () => {
			if ( isVisibleRef.current ) {
				registerDatePickerClose();
			}
		};
	}, [] );

	const closeCalendar = useCallback( () => {
		setIsVisible( ( state ) => {
			if ( state ) {
				registerDatePickerClose();
				isVisibleRef.current = false;
			}

			return false;
		} );
	}, [] );

	/**
	 * Formats the datepicker Date object to the datepicker format.
	 *
	 * @param {Date|null} date The date to format to the datepicker format.
	 *
	 * @return {string} The formatted date.
	 */
	const formatDatepickerValue = ( date ) => {
		// return date ? formatDate( date, 'MMMM d, y', new Date() ) : '';
		return date ? formatPhpDate( date ) : '';
	};

	return (
		<>
			<DatePickerInput
				setPopoverAnchor={ popoverAnchor }
				inputRef={ inputRef } // Pass the ref to DatePickerInput
				onClick={ toggleVisible }
				onMouseDown={ handleInputMouseDown }
				value={ formatDatepickerValue( selectedDate ) }
				onDayChange={ onDayChange }
			/>
			{ isVisible && (
				<Popover
					className={ classNames( 'tribe-editor__date-input__popover' ) }
					anchor={ popoverAnchor.current }
					focusOnMount={ false }
					noArrow={ false }
					onClose={ closeCalendar }
				>
					<DayPicker
						mode="single"
						selected={ selectedDate }
						onSelect={ ( date ) => {
							onDayChange( date, {}, formatDatepickerValue( date ) );
							setSelectedDate( date );
							closeCalendar();
						} }
						isSelected={ true }
					/>
				</Popover>
			) }
		</>
	);
};

export default DayPickerInput;
