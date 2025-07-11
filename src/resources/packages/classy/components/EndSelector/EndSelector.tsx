import * as React from 'react';
import { Fragment } from 'react';
import { MouseEventHandler } from 'react';
import type { StartOfWeek } from '../../types/StartOfWeek';
import { DatePicker } from '../DatePicker';
import { TimePicker } from '../TimePicker';
import { RefObject, useRef } from '@wordpress/element';
import { format } from '@wordpress/date';
import { _x } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { DateTimeUpdateType, DateUpdateType } from '../../types/FieldProps.ts';
import { StoreSelect } from '../../types/Store';

export default function EndSelector( props: {
	dateWithYearFormat: string;
	endDate: Date;
	highlightTime: boolean;
	isAllDay: boolean;
	isMultiday: boolean;
	isSelectingDate: DateUpdateType | false;
	onChange: ( selecting: DateTimeUpdateType, date: string ) => void;
	onClick: MouseEventHandler;
	onClose: () => void;
	startDate: Date;
	startOfWeek: StartOfWeek;
	timeFormat: string;
} ) {
	const {
		dateWithYearFormat,
		endDate,
		highlightTime,
		isAllDay,
		isMultiday,
		isSelectingDate,
		onChange,
		onClick,
		onClose,
		startDate,
		startOfWeek,
		timeFormat,
	} = props;

	const ref: RefObject< HTMLDivElement > = useRef( null );
	const timeInterval = useSelect( ( select ) => {
		const store: StoreSelect = select( 'tec/classy' );
		return store.getTimeInterval();
	}, [] );

	const onTimeChange = ( date: Date ): void => {
		onChange( 'endTime', format( 'Y-m-d H:i:s', date ) );
	};

	return (
		<Fragment>
			{ isMultiday && (
				<Fragment>
					<span className="classy-field__separator classy-field__separator--dates">
						{ _x( 'to', 'multi-day start and end date separator', 'tribe-common' ) }
					</span>

					<div
						className="classy-field__input classy-field__input--start-date classy-field__input--grow"
						ref={ ref }
					>
						<div className="classy-field__input-title">
							<h4>{ _x( 'Date', 'Event date selection input title', 'tribe-common' ) }</h4>
						</div>

						<DatePicker
							anchor={ ref.current }
							dateWithYearFormat={ dateWithYearFormat }
							endDate={ endDate }
							isSelectingDate={ isSelectingDate }
							isMultiday={ isMultiday }
							onChange={ onChange }
							onClick={ onClick }
							onClose={ onClose }
							showPopover={ isSelectingDate === 'endDate' }
							startDate={ startDate }
							startOfWeek={ startOfWeek }
							currentDate={ endDate }
						/>
					</div>
				</Fragment>
			) }

			{ isAllDay ? (
				<span className="classy-field__separator classy-field__separator--dates">
					{ _x( 'All Day', 'All day label in the date/time Classy selection field', 'tribe-common' ) }
				</span>
			) : (
				<div className="classy-field__input classy-field__input--end-time">
					<div className="classy-field__input-title">
						<h4>{ _x( 'End Time', 'Event end time selection input title', 'tribe-common' ) }</h4>
					</div>

					<TimePicker
						currentDate={ endDate }
						highlight={ highlightTime }
						startDate={ isMultiday ? null : startDate }
						timeFormat={ timeFormat }
						timeInterval={ timeInterval }
						onChange={ onTimeChange }
					/>
				</div>
			) }
		</Fragment>
	);
}
