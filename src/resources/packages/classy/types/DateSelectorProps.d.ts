import { MouseEventHandler } from 'react';
import { DateTimeUpdateType, DateUpdateType } from './FieldProps';
import type { StartOfWeek } from './StartOfWeek';

export type DateSelectorProps = {
	dateWithYearFormat: string;
	endDate: Date;
	highlightTime: boolean;
	isAllDay: boolean;
	isMultiday: boolean;
	isSelectingDate: DateUpdateType | false;
	onChange: ( selecting: DateTimeUpdateType, date: string ) => void;
	onClick: MouseEventHandler;
	onClose: () => void;
	showTitle?: boolean;
	startDate: Date;
	startOfWeek: StartOfWeek;
	timeFormat: string;
	title?: string;
};
