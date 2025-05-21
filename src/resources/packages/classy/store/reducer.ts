import { StoreState } from '../types/StoreState';
import { Action } from '../types/Actions';
import { localizedData } from '../localizedData';

// The store default state is read from the Classy application localized data.
const defaultState = {
	settings: localizedData.settings,
};

/**
 * Store reducer; returns the new store date following an action.
 *
 * @param {StoreState|null} state The current store state, or the defaul state if the state is not set.
 * @param {Action}    action The dispatched action.
 *
 * @return {StoreState} The new store state.
 */
export const reducer = (
	state: StoreState = defaultState,
	action: Action
): StoreState => {
	return state;
};
