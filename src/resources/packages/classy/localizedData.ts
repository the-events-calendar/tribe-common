import { LocalizedData } from './types/LocalizedData';
import { WPDataRegistry } from '@wordpress/data/build-types/registry';

declare global {
	interface Window {
		tec: {
			common: {
				classy: {
					data: LocalizedData;
					registry: WPDataRegistry;
				};
			};
		};
	}
}

export const localizedData: LocalizedData = window?.tec?.common?.classy?.data ?? {
	settings: {
		defaultCurrency: {
			code: 'USD',
			symbol: '$',
			position: 'prefix',
		},
		timezoneString: 'UTC',
		timezoneChoice: '',
		startOfWeek: 0,
		endOfDayCutoff: {
			hours: 0,
			minutes: 0,
		},
		dateWithYearFormat: 'F j, Y',
		dateWithoutYearFormat: 'F j',
		monthAndYearFormat: 'F Y',
		compactDateFormat: 'n/j/Y',
		dataTimeSeparator: ' @ ',
		timeRangeSeparator: ' - ',
		timeFormat: 'g:i A',
		timeInterval: 15,
	},
};
