import { LocalizedData, Settings, TecGlobal } from './types/LocalizedData';

declare global {
	interface Window {
		tec: TecGlobal;
	}
}

/**
 * Returns the default localized data.
 *
 * @since TBD
 *
 * @returns {LocalizedData} The default localized data.
 */
export function getDefault(): LocalizedData {
	return {
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
}

export const localizedData: LocalizedData = window?.tec?.common?.classy?.data ?? getDefault();

/**
 * Gets the localized data.
 *
 * Extending plugins should use this function rather than accessing the localized
 * data directly.
 *
 * @since TBD
 *
 * @returns {LocalizedData} The localized data.
 */
export function getLocalizedData(): LocalizedData {
	return localizedData;
}

/**
 * Gets the settings from the localized data.
 *
 * Extending plugins should use this function rather than accessing the localized
 * data directly.
 *
 * @since TBD
 *
 * @returns {Settings} The settings from the localized data.
 *
 */
export function getSettings(): Settings {
	return localizedData.settings;
}
