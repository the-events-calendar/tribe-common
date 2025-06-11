import { CustomSelectOption } from '@wordpress/components/build-types/custom-select-control/types';
import { Currency } from './Currency.ts';
import { SET_COUNTRY_OPTIONS, SET_CURRENCY_OPTIONS } from '../store/actions.ts';

export type Action = {
	type: string;
};

export type SetCountryOptionsAction = {
	type: typeof SET_COUNTRY_OPTIONS;
	options: CustomSelectOption[];
};

export type SetCurrencyOptionsAction = {
	type: typeof SET_CURRENCY_OPTIONS;
	options: Currency[];
};
