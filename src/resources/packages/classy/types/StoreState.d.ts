import { Settings } from './LocalizedData';
import { CustomSelectOption } from '@wordpress/components/build-types/custom-select-control/types';

export type StoreState = {
	settings: Settings;
	options: {
		country: CustomSelectOption[];
	};
};
