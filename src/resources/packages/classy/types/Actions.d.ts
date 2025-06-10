import { CustomSelectOption } from '@wordpress/components/build-types/custom-select-control/types';
import { SET_COUNTRY_OPTIONS } from '../store/actions.ts';

export type Action = {
	type: string;
};

export type SetCountryOptionsAction = {
	type: typeof SET_COUNTRY_OPTIONS;
	options: CustomSelectOption[];
};
