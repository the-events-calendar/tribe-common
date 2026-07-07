import React from 'react';

export const DayPicker = ( props ) => (
	<div className="DayPicker-mock" data-testid="day-picker">
		<span className="DayPicker-mode">{ props.mode }</span>
		{ props.selected && (
			<span className="DayPicker-selected">
				{ props.selected instanceof Date ? props.selected.toISOString() : String( props.selected ) }
			</span>
		) }
		{ props.month && (
			<span className="DayPicker-month">
				{ props.month instanceof Date ? props.month.toISOString() : String( props.month ) }
			</span>
		) }
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
		{ props.disabled && (
			<span className="DayPicker-disabled">{ JSON.stringify( props.disabled ) }</span>
		) }
		{ props.modifiers && (
			<span className="DayPicker-modifiers">{ JSON.stringify( props.modifiers ) }</span>
		) }
		<button
			className="DayPicker-select"
			onClick={ () => props.onSelect?.( props.selected || new Date( '2026-01-15' ), new Date(), {}, {} ) }
		>
			Select
		</button>
	</div>
);

export default DayPicker;
