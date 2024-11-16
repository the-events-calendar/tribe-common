/**
 * External dependencies
 */
import React from 'react';
import classNames from 'classnames';
import "react-day-picker/src/style.css";
import { DayPicker } from 'react-day-picker';

/**
 * Internal dependencies
 */
import './style.pcss';

const DayPickerInput = ( props ) => (
	<DayPicker
		classNames={ {
			container     : classNames(
				'tribe-editor__day-picker-input',
				'DayPickerInput',
			),
			overlayWrapper: classNames(
				'tribe-editor__day-picker-input__overlay-wrapper',
				'DayPickerInput-OverlayWrapper',
			),
			overlay       : classNames(
				'tribe-editor__day-picker-input__overlay',
				'DayPickerInput-Overlay',
			),
		} }
		{ ...props }
	/>
);

export default DayPickerInput;
