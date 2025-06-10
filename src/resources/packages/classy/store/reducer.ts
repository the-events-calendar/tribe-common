import { StoreState } from '../types/StoreState';
import { Action, SetCountryOptionsAction } from '../types/Actions';
import { localizedData } from '../localizedData';
import { SET_COUNTRY_OPTIONS } from './actions';

// The store default state is read from the Classy application localized data.
const defaultState: StoreState = {
	settings: localizedData.settings,
	options: {
		country: [],
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
		default:
			return state;
	}
};
