import * as React from 'react';
import { Fragment } from 'react';
import { DatePicker } from '../DatePicker';
import { TimePicker } from '../TimePicker';
import { RefObject, useRef } from '@wordpress/element';
import { format } from '@wordpress/date';
import { _x } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { StoreSelect } from '../../types/Store';
import { DateSelectorProps } from '../../types/DateSelectorProps';

type EndSelectorProps = DateSelectorProps & {
	/**
	 * Whether to show a separator between the date and time pickers.
	 *
	 * @default true
	 */
	showSeparator?: boolean;

	/**
	 * Whether to show the "All Day" label when the event is marked as all day.
	 *
	 * @default true
	 */
	showAllDayLabel?: boolean;
};

const defaultTitle = _x( 'Date', 'Event date selection input title', 'tribe-common' );

/**
 * EndSelector component for selecting the end date and time.
 *
 * @since TBD
 *
 * @param {EndSelectorProps} props The properties for the EndSelector component.
 * @return {React.JSX.Element} The rendered EndSelector component.
 */
export default function EndSelector( props: EndSelectorProps ): React.JSX.Element {
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
		showAllDayLabel = true,
		showTitle = true,
		showSeparator = true,
		startDate,
		startOfWeek,
		timeFormat,
		title = defaultTitle,
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
					{ showSeparator && (
						<span className="classy-field__separator classy-field__separator--dates">
							{ _x( 'to', 'multi-day start and end date separator', 'tribe-common' ) }
						</span>
					) }

					<div
						className="classy-field__input classy-field__input--start-date classy-field__input--grow"
						ref={ ref }
					>
						{ showTitle && (
							<div className="classy-field__input-title">
								<h4>{ title }</h4>
							</div>
						) }

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

			{ isAllDay && showAllDayLabel && (
				<span className="classy-field__separator classy-field__separator--dates">
					{ _x( 'All Day', 'All day label in the date/time Classy selection field', 'tribe-common' ) }
				</span>
			) }

			{ ! isAllDay && (
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
