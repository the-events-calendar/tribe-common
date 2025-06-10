import { SetCountryOptionsAction } from '../types/Actions';

export const SET_COUNTRY_OPTIONS = 'SET_COUNTRY_OPTIONS';

export default {
	setCountryOptions: ( options ): SetCountryOptionsAction => ( { type: SET_COUNTRY_OPTIONS, options } ),
};
