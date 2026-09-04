import React, { useState } from 'react';

/**
 * Shifts a Date by a number of months, preserving the UTC day-of-month
 * representation used throughout these tests (dates are built from ISO strings).
 *
 * @param {Date}   date   The date to shift.
 * @param {number} offset Number of months to shift (negative goes back).
 * @return {Date|undefined} The shifted date, or undefined when the input is invalid.
 */
const shiftMonth = ( date, offset ) => {
	if ( ! ( date instanceof Date ) || isNaN( date ) ) {
		return undefined;
	}

	return new Date( Date.UTC( date.getUTCFullYear(), date.getUTCMonth() + offset, 1 ) );
};

/**
 * Mock for react-day-picker that mirrors its month-navigation semantics:
 *
 * - When `month` is provided (controlled), the displayed month always comes
 *   from the prop and navigation only fires `onMonthChange`.
 * - When `month` is omitted, the calendar keeps its own display month,
 *   initialized from `defaultMonth`, and navigation updates it internally.
 *
 * This lets tests catch regressions where a controlled `month` prop is passed
 * without an `onMonthChange` handler, which makes month navigation dead.
 *
 * @param {Object} props DayPicker props.
 */
export const DayPicker = ( props ) => {
	const isControlled = props.month !== undefined;
	const [ internalMonth, setInternalMonth ] = useState( props.month || props.defaultMonth || new Date() );

	const displayMonth = isControlled ? props.month : internalMonth;

	const goToMonth = ( offset ) => {
		const nextMonth = shiftMonth( displayMonth, offset );

		if ( isControlled ) {
			props.onMonthChange?.( nextMonth );
			return;
		}

		setInternalMonth( nextMonth );
	};

	return (
		<div className="DayPicker-mock" data-testid="day-picker">
			<span className="DayPicker-mode">{ props.mode }</span>
			{ props.selected && (
				<span className="DayPicker-selected">
					{ props.selected instanceof Date ? props.selected.toISOString() : String( props.selected ) }
				</span>
			) }
			<span className="DayPicker-month">
				{ displayMonth instanceof Date ? displayMonth.toISOString() : String( displayMonth ) }
			</span>
			{ props.startMonth && (
				<span className="DayPicker-startMonth">
					{ props.startMonth instanceof Date ? props.startMonth.toISOString() : String( props.startMonth ) }
				</span>
			) }
			{ props.endMonth && (
				<span className="DayPicker-endMonth">
					{ props.endMonth instanceof Date ? props.endMonth.toISOString() : String( props.endMonth ) }
				</span>
			) }
			{ props.disabled && <span className="DayPicker-disabled">{ JSON.stringify( props.disabled ) }</span> }
			{ props.modifiers && <span className="DayPicker-modifiers">{ JSON.stringify( props.modifiers ) }</span> }
			<button className="DayPicker-nav-prev" onClick={ () => goToMonth( -1 ) }>
				Previous month
			</button>
			<button className="DayPicker-nav-next" onClick={ () => goToMonth( 1 ) }>
				Next month
			</button>
			<button
				className="DayPicker-select"
				onClick={ () => props.onSelect?.( props.selected || new Date( '2026-01-15' ), new Date(), {}, {} ) }
			>
				Select
			</button>
		</div>
	);
};

export default DayPicker;
