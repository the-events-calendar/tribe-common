import { Currency } from './Currency';
import { Hours } from './Hours';
import { Minutes } from './Minutes';
import { StartOfWeek } from './StartOfWeek';
import { WPDataRegistry } from '@wordpress/data/build-types/registry';

export type Settings = {
	defaultCurrency: Currency;
	timezoneString: string;
	timezoneChoice: string;
	startOfWeek: StartOfWeek;
	endOfDayCutoff: {
		hours: Hours;
		minutes: Minutes;
	};
	dateWithYearFormat: string;
	dateWithoutYearFormat: string;
	monthAndYearFormat: string;
	compactDateFormat: string;
	dataTimeSeparator: string;
	timeRangeSeparator: string;
	timeFormat: string;
	timeInterval: number;
};

export type LocalizedData = {
	settings: Settings;
};

// This type will be used for the `window.tec` object.
export type TecGlobal = {
	common: {
		classy: {
			data: LocalizedData;
			registry: WPDataRegistry;
		};
	};
};
