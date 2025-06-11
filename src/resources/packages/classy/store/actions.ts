import {
	SetCountryOptionsAction,
	SetCurrencyOptionsAction,
} from '../types/Actions';

export const SET_COUNTRY_OPTIONS = 'SET_COUNTRY_OPTIONS';
export const SET_CURRENCY_OPTIONS = 'SET_CURRENCY_OPTIONS';

export default {
	setCountryOptions: ( options ): SetCountryOptionsAction => ( { type: SET_COUNTRY_OPTIONS, options } ),
	setCurrencyOptions: ( options: any ): SetCurrencyOptionsAction => ( { type: SET_CURRENCY_OPTIONS, options } ),
};
