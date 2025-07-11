import { localizedData } from '../localizedData';
import { Settings } from '../types/LocalizedData';
import { StoreState } from '../types/Store';
import { CustomSelectOption } from '@wordpress/components/build-types/custom-select-control/types';
import { Currency } from '../types/Currency';

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
	return getSettings().timeInterval;
}

/**
 * Returns the country options available for selection.
 *
 * @since TBD
 *
 * @param {StoreState} state The current store state.
 *
 * @returns {CustomSelectOption[]} An array of country options with
 */
export function getCountryOptions( state: StoreState ): CustomSelectOption[] {
	return state?.options?.country || [];
}

/**
 * Returns the US states options available for selection.
 *
 * @since TBD
 *
 * @param state
 */
export function getUsStatesOptions( state: StoreState ): CustomSelectOption[] {
	return state?.options?.usStates || [];
}

/**
 * Returns the currency options available for selection.
 *
 * @since TBD
 *
 * @param {StoreState} state The current store state.
 * @returns {Currency[]} An array of currency options with code, symbol, and position.
 */
export function getCurrencyOptions( state: StoreState ): Currency[] {
	return state?.options?.currencies || [];
}

/**
 * Returns the default currency from the settings.
 *
 * @since TBD
 *
 * @return {Currency} The default currency.
 */
export function getDefaultCurrency(): Currency {
	const settings: Settings = localizedData.settings;
	return settings.defaultCurrency;
}
