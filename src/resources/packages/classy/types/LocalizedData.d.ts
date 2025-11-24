import { Currency } from './Currency';
import { StartOfWeek } from './StartOfWeek';
import { WPDataRegistry } from '@wordpress/data/build-types/registry';

export type Settings = {
	defaultCurrency: Currency;
	timezoneString: string;
	timezoneChoice: string;
	startOfWeek: StartOfWeek;
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
