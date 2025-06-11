import { SetCountryOptionsAction, SetCurrencyOptionsAction, SetUsStateOptionsAction } from '../types/Actions';

export const SET_COUNTRY_OPTIONS = 'SET_COUNTRY_OPTIONS';
export const SET_CURRENCY_OPTIONS = 'SET_CURRENCY_OPTIONS';
export const SET_US_STATE_OPTIONS = 'SET_US_STATE_OPTIONS';

export default {
	setCountryOptions: ( options ): SetCountryOptionsAction => ( { type: SET_COUNTRY_OPTIONS, options } ),
	setUsStateOptions: ( options ): SetUsStateOptionsAction => ( { type: SET_US_STATE_OPTIONS, options } ),
	setCurrencyOptions: ( options: any ): SetCurrencyOptionsAction => ( { type: SET_CURRENCY_OPTIONS, options } ),
};
