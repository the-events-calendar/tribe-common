/**
 * External dependencies
 */
import React, { useState, useRef } from 'react';
import classNames from 'classnames';
import "react-day-picker/src/style.css";
import { DayPicker } from 'react-day-picker';
import { Popover } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './style.pcss';

const DatePickerInput = ( props ) => {
	const { setPopoverAnchor, inputRef, onDayChange, ...inputProps } = props;

	return (
		<div
			ref={ setPopoverAnchor }
			className={ classNames( 'tribe-editor__date-input__container' ) }
		>
			<input
				ref={ inputRef } // Attach the ref to the input element
				className={ classNames( 'tribe-editor__date-input' ) }
				onChange={ ( event ) => {

				} }
				{ ...inputProps }
			/>
		</div>
	);
};

const DayPickerInput = ( props ) => {
	const popoverAnchor = useRef( null ); // Ref for the Popover anchor
	const inputRef = useRef( null ); // Ref for the input field
	const [ isVisible, setIsVisible ] = useState( false );

	const toggleVisible = () => {
		setIsVisible( ( state ) => ! state );
	};

	const { value, onDayChange, formatDate, format } = props;
	const [ selectedDate, setSelectedDate ] = useState(
		value ? new Date( value ) : new Date()
	);

	const datepickerFormat = 'MMMM d, y';
	const formatDatepickerValue = ( date ) => {
		return date ? formatDate( date, datepickerFormat ) : '';
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
					<Popover.Slot/>
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
						/>
					</Popover>
				</>
			) }
		</>
	);
};

export default DayPickerInput;
