import { StoreState } from '../types/StoreState';
import { Action, SetCountryOptionsAction, SetCurrencyOptionsAction, SetUsStateOptionsAction } from '../types/Actions';
import { localizedData } from '../localizedData';
import { SET_COUNTRY_OPTIONS, SET_CURRENCY_OPTIONS, SET_US_STATE_OPTIONS } from './actions';

// The store default state is read from the Classy application localized data.
const defaultState: StoreState = {
	settings: localizedData.settings,
	options: {
		country: [],
		currencies: [],
		usStates: [],
	},
};

/**
 * Store reducer; returns the new store date following an action.
 *
 * @param {StoreState|null} state The current store state, or the defaul state if the state is not set.
 * @param {Action}    action The dispatched action.
 *
 * @return {StoreState} The new store state.
 */
export const reducer = ( state: StoreState = defaultState, action: Action ): StoreState => {
	switch ( action.type ) {
		case SET_COUNTRY_OPTIONS:
			return {
				...state,
				options: {
					...state.options,
					country: ( action as SetCountryOptionsAction ).options,
				},
			};
		case SET_US_STATE_OPTIONS:
			return {
				...state,
				options: {
					...state.options,
					usStates: ( action as SetUsStateOptionsAction ).options,
				},
			};
		case SET_CURRENCY_OPTIONS:
			return {
				...state,
				options: {
					...state.options,
					currencies: ( action as SetCurrencyOptionsAction ).options,
				},
			};
		default:
			return state;
	}
};
