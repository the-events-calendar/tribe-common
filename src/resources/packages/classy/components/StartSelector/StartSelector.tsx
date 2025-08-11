import { Fragment, MouseEventHandler } from 'react';
import { RefObject, useRef } from '@wordpress/element';
import type { StartOfWeek } from '../../types/StartOfWeek';
import { DatePicker } from '../DatePicker';
import { TimePicker } from '../TimePicker';
import { format } from '@wordpress/date';
import { _x } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { DateTimeUpdateType, DateUpdateType } from '../../types/FieldProps.ts';
import { StoreSelect } from '../../types/Store';

type StartSelectorProps = {
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
	title?: string;
};

const defaultTitle = _x( 'Start Date', 'Event start date selection input title', 'tribe-common' );
const currentDate = new Date();

/**
 * StartSelector component for selecting the start date and time of an event.
 *
 * @since TBD
 *
 * @param {StartSelectorProps} props The properties for the StartSelector component.
 * @return {JSX.Element} The rendered StartSelector component.
 */
export default function StartSelector( props: StartSelectorProps ) {
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
		title = defaultTitle,
	} = props;

	const ref: RefObject< HTMLDivElement > = useRef( null );
	const timeInterval = useSelect( ( select ) => {
		const store: StoreSelect = select( 'tec/classy' );
		return store.getTimeInterval();
	}, [] );

	const onTimeChange = ( date: Date ): void => {
		onChange( 'startTime', format( 'Y-m-d H:i:s', date ) );
	};

	const wrapperClassName =
		isAllDay && ! isMultiday
			? 'classy-field__input classy-field__input--start-date classy-field__input-full-width'
			: 'classy-field__input classy-field__input--start-date classy-field__input--grow';

	return (
		<Fragment>
			<div className={ wrapperClassName } ref={ ref }>
				<div className="classy-field__input-title">
					<h4>{ title }</h4>
				</div>

				<DatePicker
					anchor={ ref.current }
					dateWithYearFormat={ dateWithYearFormat }
					endDate={ endDate }
					isSelectingDate={ isSelectingDate }
					isMultiday={ isMultiday }
					onClick={ onClick }
					onClose={ onClose }
					onChange={ onChange }
					showPopover={ isSelectingDate === 'startDate' }
					startDate={ startDate }
					startOfWeek={ startOfWeek }
					currentDate={ currentDate }
				/>
			</div>

			{ ! isAllDay && (
				<div className="classy-field__input classy-field__input--start-time">
					<div className="classy-field__input-title">
						<h4>{ _x( 'Start Time', 'Event start time selection input title', 'tribe-common' ) }</h4>
					</div>

					<TimePicker
						currentDate={ startDate }
						endDate={ isMultiday ? null : endDate }
						highlight={ highlightTime }
						onChange={ onTimeChange }
						timeFormat={ timeFormat }
						timeInterval={ timeInterval }
					/>
				</div>
			) }
		</Fragment>
	);
}
