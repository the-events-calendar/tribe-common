/**
 * External dependencies
 */
import React from 'react';
import PropTypes from 'prop-types';
import moment from 'moment';
import { noop } from 'lodash';
import classNames from 'classnames';
import { ScrollTo, ScrollArea } from 'react-scroll-to';

/**
 * WordPress dependencies
 */
import {
	Dropdown,
	Dashicon,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { PreventBlockClose } from '@moderntribe/common/components';
import {
	moment as momentUtil,
	time as timeUtil,
	TribePropTypes,
} from '@moderntribe/common/utils';
import './style.pcss';

const TimePicker = ( {
	current,
	min,
	max,
	start,
	end,
	step,
	timeFormat,
	allDay,
	onChange,
	onClick,
	showAllDay,
	disabled,
} ) => {

	const renderLabel = ( onToggle ) => {
		if ( allDay ) {
			return (
				<button
					className="tribe-editor__timepicker__all-day-btn"
					onClick={ onToggle }
					disabled={ disabled }
				>
					{ __( 'All Day', 'events-gutenberg' ) }
				</button>
			);
		}

		const additionalProps = {};
		if ( min ) {
			additionalProps.min = min;
		}

		if ( max ) {
			additionalProps.max = max;
		}

		return (
			<input
				className="tribe-editor__btn-input"
				type="time"
				value={ current }
				onChange={ onChange }
				disabled={ disabled }
				{ ...additionalProps }
			/>
		);
	};

	const toggleDropdown = ( { onToggle, isOpen } ) => (
		<div className="tribe-editor__timepicker-label-container">
			{ renderLabel( onToggle ) }
			<button
				type="button"
				aria-expanded={ isOpen }
				onClick={ onToggle }
				disabled={ disabled }
			>
				<Dashicon className="btn--icon" icon={ isOpen ? 'arrow-up' : 'arrow-down' } />
			</button>
		</div>
	);

	const getItems = () => {
		const items = [];

		const startSeconds = timeUtil.toSeconds( start, timeUtil.TIME_FORMAT_HH_MM );
		const endSeconds = timeUtil.toSeconds( end, timeUtil.TIME_FORMAT_HH_MM );

		for ( let time = startSeconds; time <= endSeconds; time += step ) {
			items.push( {
				value: time,
				text: formatLabel( time ),
				isCurrent: current
					? time === timeUtil.toSeconds( current, timeUtil.TIME_FORMAT_HH_MM )
					: false,
			} );
		}

		return items;
	};

	const formatLabel = ( seconds ) => {
		return momentUtil.setTimeInSeconds( moment(), seconds ).format( momentUtil.toFormat( timeFormat ) );
	};

	const renderItem = ( item, onClose ) => {
		const itemClasses = {
			'tribe-editor__timepicker__item': true,
			'tribe-editor__timepicker__item--current': item.isCurrent && ! allDay,
		};

		return (
			<button
				key={ `time-${ item.value }` }
				role="menuitem"
				className={ classNames( itemClasses ) }
				value={ item.value }
				onClick={ () => onClick( item.value, onClose ) }
			>
				{ item.text }
			</button>
		);
	};

	const renderDropdownContent = ( { onClose } ) => (
		<ScrollTo>
			{ () => (
				<PreventBlockClose>
					<ScrollArea
						id="tribe-element-timepicker-items"
						key="tribe-element-timepicker-items"
						role="menu"
						className={ classNames( 'tribe-editor__timepicker__items' ) }
					>
						{ showAllDay && renderItem(
							{ text: __( 'All Day', 'events-gutenberg' ), value: 'all-day' },
							onClose,
						) }
						{ getItems().map( ( item ) => renderItem( item, onClose ) ) }
					</ScrollArea>
				</PreventBlockClose>
			) }
		</ScrollTo>
	);

	return (
		<div
			key="tribe-element-timepicker"
			className="tribe-editor__timepicker"
		>
			<Dropdown
				className="tribe-element-timepicker-label"
				position="bottom center"
				contentClassName="tribe-editor__timepicker__dialog"
				renderToggle={ toggleDropdown }
				renderContent={ renderDropdownContent }
			/>
		</div>
	);
};

TimePicker.defaultProps = {
	step: timeUtil.HALF_HOUR_IN_SECONDS,
	timeFormat: 'H:i',
	allDay: false,
	onChange: noop,
	onClick: noop,
};

TimePicker.propTypes = {
	/**
	 * TribePropTypes.timeFormat check for string formatted as a time
	 * using 24h clock in hh:mm format
	 * e.g. 00:24, 03:57, 21:12
	 */
	current: TribePropTypes.timeFormat.isRequired,
	min: TribePropTypes.timeFormat,
	max: TribePropTypes.timeFormat,
	start: TribePropTypes.timeFormat.isRequired,
	end: TribePropTypes.timeFormat.isRequired,
	step: PropTypes.number,
	timeFormat: PropTypes.string,
	allDay: PropTypes.bool,
	onChange: PropTypes.func.isRequired,
	onClick: PropTypes.func.isRequired,
	showAllDay: PropTypes.bool,
	disabled: PropTypes.bool,
};

export default TimePicker;
