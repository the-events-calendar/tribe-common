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

/**
 * This type defines selectors for the core/editor store that we use in our application.
 *
 * Note that these selectors are not part of the Classy package, but are used in conjunction with it.
 *
 * @since TBD
 */
export type CoreEditorSelect = {
	getCurrentPostId: () => number | null;
	getEditedPostAttribute: ( attribute: string ) => any;
	getEditedPostContent: () => string;
	isSavingPost: () => boolean;
	isAutosavingPost: () => boolean;
	__unstableIsEditorReady: () => boolean;
	didPostSaveRequestSucceed: () => boolean;
};

/**
 * This type defines the dispatch actions for the core/editor store that we use in our application.
 *
 * Note that these actions are not part of the Classy package, but are used in conjunction with it.
 *
 * @since TBD
 */
export type CoreEditorDispatch = {
	editPost: ( attributes: Record< string, any > ) => void;
	trashPost: () => void;
	lockPostSaving: ( lockName: string ) => void;
	unlockPostSaving: ( lockName: string ) => void;
	savePost: ( attributes?: Record< string, any > ) => void;
};

/**
 * This type defines selectors for the core/edit-post store that we use in our application.
 *
 * Note that these selectors are not part of the Classy package, but are used in conjunction with it.
 *
 * @since TBD
 */
export type CoreEditPostSelect = {
	isEditorSidebarOpened: () => boolean;
};
