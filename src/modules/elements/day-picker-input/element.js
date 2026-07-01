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
	registerDatePickerCloseHandler,
	registerDatePickerOpen,
} from '../../utils/date-picker-popover-state';
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
	const popoverRef = useRef( null ); // Ref for the popover portal element
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

	const { value, onDayChange, formatDate, format, dayPickerProps } = props;

	// Convert the format from the moment.js one to date-fns one using Unicode characters.
	const dateFnsFormat = momentToDateFnsFormatter( format );

	const getSelectedDateInitialState = useCallback(
		( value ) => {
			if ( ! value ) {
				return undefined;
			}

			// Try and parse the value using the date-fns format.
			const d = parseDate( value, dateFnsFormat, new Date() );

			if ( d instanceof Date && ! isNaN( d ) ) {
				return d;
			}

			// Try and parse the value using the PHP date format.
			return parsePhpDate( value );
		},
		[ dateFnsFormat, parsePhpDate ]
	);

	const [ selectedDate, setSelectedDate ] = useState( () => getSelectedDateInitialState( value ) );

	// Sync selectedDate when the value prop changes (e.g. Redux state arrives after mount in React 17).
	useEffect( () => {
		setSelectedDate( getSelectedDateInitialState( value ) );
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

	useEffect( () => registerDatePickerCloseHandler( closeCalendar ), [ closeCalendar ] );

	const clickOutsideHandlerRef = useRef( null );
	clickOutsideHandlerRef.current = ( event ) => {
		const { target } = event;
		const anchorEl = popoverAnchor.current;
		const popoverEl = popoverRef.current;

		if ( anchorEl && anchorEl.contains( target ) ) {
			return;
		}

		if ( popoverEl && popoverEl.contains( target ) ) {
			return;
		}

		closeCalendar();
	};

	useEffect( () => {
		if ( ! isVisible ) {
			return;
		}

		const onMouseDown = ( event ) => clickOutsideHandlerRef.current( event );

		const timerId = setTimeout( () => {
			document.addEventListener( 'mousedown', onMouseDown );
		}, 0 );

		return () => {
			clearTimeout( timerId );
			document.removeEventListener( 'mousedown', onMouseDown );
		};
	}, [ isVisible ] );

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
				value={ value || formatDatepickerValue( selectedDate ) }
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
					<div ref={ popoverRef }>
						<DayPicker
							mode="single"
							selected={ selectedDate }
							onSelect={ ( date ) => {
								onDayChange( date, {}, formatDatepickerValue( date ) );
								setSelectedDate( date );
								closeCalendar();
							} }
							disabled={ dayPickerProps?.disabledDays }
							modifiers={ dayPickerProps?.modifiers }
							month={ selectedDate || dayPickerProps?.month || new Date() }
							startMonth={ dayPickerProps?.fromMonth }
							endMonth={ dayPickerProps?.toMonth }
							isSelected={ true }
						/>
					</div>
				</Popover>
			) }
		</>
	);
};

export default DayPickerInput;
