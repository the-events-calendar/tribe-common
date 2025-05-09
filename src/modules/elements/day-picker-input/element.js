/**
 * External dependencies
 */
import React, { useState, useRef, useMemo, useCallback } from 'react';
import classNames from 'classnames';
import 'react-day-picker/src/style.css';
import { DayPicker } from 'react-day-picker';
import { Popover } from '@wordpress/components';
import { getSettings as getDateSettings } from '@wordpress/date';
import { parse as parseDate } from 'date-fns';

/**
 * Internal dependencies
 */
import './style.pcss';

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
	const [ isVisible, setIsVisible ] = useState( false );
	// Do not memoize this: it could be changed in the context of the Block Editor elsewhere.
	const phpDateFormat = getDateSettings()?.formats?.date ?? 'MMMM d, y';
	const dateFormatter = useMemo( () => new DateFormatter(), [] );
	const parsePhpDate = useCallback( ( value ) => dateFormatter.parseDate( value, phpDateFormat ), [ phpDateFormat ] );
	const formatPhpDate = useCallback( ( date ) => dateFormatter.formatDate( date, phpDateFormat ), [ phpDateFormat ] );

	const toggleVisible = () => {
		setIsVisible( ( state ) => ! state );
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
				value={ formatDatepickerValue( selectedDate ) }
				onDayChange={ onDayChange }
			/>
			{ isVisible && (
				<>
					<Popover.Slot />
					<Popover
						className={ classNames( 'tribe-editor__date-input__popover' ) }
						anchor={ popoverAnchor.current }
						noArrow={ false }
					>
						<DayPicker
							mode="single"
							selected={ selectedDate }
							onSelect={ ( date ) => {
								onDayChange( date, {}, formatDatepickerValue( date ) );
								setSelectedDate( date );
								toggleVisible();
							} }
							isSelected={ true }
						/>
					</Popover>
				</>
			) }
		</>
	);
};

export default DayPickerInput;
