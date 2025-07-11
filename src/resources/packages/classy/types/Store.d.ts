import { Currency } from './Currency';
import { Settings } from './LocalizedData';
import { CustomSelectOption } from '@wordpress/components/build-types/custom-select-control/types';

export type StoreState = {
	settings: Settings;
	options: {
		country: CustomSelectOption[];
		usStates: CustomSelectOption[];
		currencies: Currency[];
	};
};

/**
 * The type that should be assigned to the return value of the `select('tec/classy')` call.
 *
 * @example
 * ```
 * const classyStore: StoreSelect = select('tec/classy');
 * ```
 */
export type StoreSelect = {
	getSettings: () => Settings;
	getTimeInterval: () => number;
	getCountryOptions: () => CustomSelectOption[];
	getUsStatesOptions: () => CustomSelectOption[];
	getCurrencyOptions: () => Currency[];
	getDefaultCurrency: () => Currency;
};

/**
 * The type that should be assigned to the return value of the `dispatch('tec/classy')` call.
 *
 * @example
 * ```
 * const classyStore: StoreDispatch = dispatch('tec/classy');
 * ```
 */
export type StoreDispatch = {
	setCountryOptions: ( options: CustomSelectOption[] ) => void;
	setUsStateOptions: ( options: CustomSelectOption[] ) => void;
	setCurrencyOptions: ( options: Currency[] ) => void;
};
