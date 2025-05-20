import { localizedData } from '../localizedData';
import { Settings } from '../types/LocalizedData';

/**
 * Returns the current Classy settings, including the ones added using the PHP filter.
 *
 * @see TEC\Common\Classy\Controller::get_data for more information.
 *
 * @since TBD
 *
 * @returns {Settings} The current Classy settings.
 */
export function getSettings(): Settings {
	return localizedData.settings;
}

/**
 * Returns the time interval to use in time-pickers in minutes.
 *
 * @since TBD
 *
 * @returns {number} The time interval in minutes to use in time-pickers.
 */
export function getTimeInterval(): number {
	const settings: Settings = localizedData.settings;
	return settings.timeInterval;
}
