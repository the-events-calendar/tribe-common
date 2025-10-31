export type FieldProps = {
	title: string;
};

export type DateUpdateType = 'startDate' | 'endDate';
export type TimeUpdateType = 'startTime' | 'endTime';
export type DateTimeUpdateType = DateUpdateType | TimeUpdateType;
