import { CustomSelectOption } from '@wordpress/components/build-types/custom-select-control/types';
import { Currency } from './Currency.ts';
import { SET_COUNTRY_OPTIONS, SET_US_STATE_OPTIONS, SET_CURRENCY_OPTIONS } from '../store/actions';

export type Action = {
	type: string;
};

export type SetCountryOptionsAction = {
	type: typeof SET_COUNTRY_OPTIONS;
	options: CustomSelectOption[];
};

export type SetUsStateOptionsAction = {
	type: typeof SET_US_STATE_OPTIONS;
	options: CustomSelectOption[];
};

export type SetCurrencyOptionsAction = {
	type: typeof SET_CURRENCY_OPTIONS;
	options: Currency[];
};
