import * as React from 'react';
import { Fragment, MouseEventHandler } from 'react';
import CalendarPopover from './CalendarPopover';
import {
	__experimentalInputControl as InputControl,
	__experimentalInputControlSuffixWrapper as SuffixWrapper,
} from '@wordpress/components';
import CalendarIcon from './CalendarIcon';
import { format } from '@wordpress/date';
import { VirtualElement } from '@wordpress/components/build-types/popover/types';
import { StartOfWeek } from '../../types/StartOfWeek';
import { DateUpdateType } from '@tec/common/classy/types/FieldProps';

export type DatePickerProps = {
	anchor: Element | VirtualElement | null;
	dateWithYearFormat: string;
	endDate: Date;
	isSelectingDate: DateUpdateType | false;
	isMultiday: boolean;
	onChange: ( selecting: DateUpdateType, newDate: string ) => void;
	onClick: MouseEventHandler< HTMLInputElement >;
	onClose: () => void;
	showPopover: boolean;
	startDate: Date;
	startOfWeek: StartOfWeek;
	currentDate: Date;
};

export default function DatePicker( props: DatePickerProps ) {
	const {
		anchor,
		dateWithYearFormat,
		endDate,
		isSelectingDate,
		isMultiday,
		onChange,
		onClick,
		onClose,
		showPopover,
		startDate,
		startOfWeek,
		currentDate,
	} = props;

	const input = (
		<InputControl
			__next40pxDefaultSize
			className="classy-field__control classy-field__control--input classy-field__control--date-picker"
			value={ format( dateWithYearFormat, currentDate ) }
			onClick={ onClick }
			suffix={
				<SuffixWrapper onClick={ onClick } style={ { cursor: 'pointer' } }>
					<CalendarIcon />
				</SuffixWrapper>
			}
		/>
	);

	return (
		<Fragment>
			{ input }

			{ showPopover && (
				<CalendarPopover
					anchor={ anchor }
					date={ currentDate }
					endDate={ endDate }
					isSelectingDate={ isSelectingDate as DateUpdateType }
					isMultiday={ isMultiday }
					startDate={ startDate }
					startOfWeek={ startOfWeek }
					onChange={ onChange }
					onClose={ onClose }
				/>
			) }
		</Fragment>
	);
}
