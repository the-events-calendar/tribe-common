import { whenEditorIsReady } from './functions/whenEditorIsReady';
import { hideInserterToggle, hideZoomOutButton } from './functions/editorModifications';
import { addEditorTools } from './functions/addEditorTools';
import {
	initApp as initClassyApp,
	insertElement as insertClassyElement,
	toggleElementVisibility as toggleClassyElementVisibility,
} from './functions/classy';
import { hasQueryParam } from './functions/url';
import { getLocalizedData, getSettings } from './localizedData';
import { registerMiddlewares } from '@tec/common/tecApi';
import './style.pcss';

whenEditorIsReady().then( () => {
	registerMiddlewares();
	hideZoomOutButton();
	hideInserterToggle();
	initClassyApp();
	insertClassyElement();

	// Only add the Visual editor tools button when explicitly enabled via query parameter
	if ( hasQueryParam( 'classy_enable_visual', '1' ) ) {
		addEditorTools( () => toggleClassyElementVisibility() );
	}
} );

// Re-exports that will appear under `window.tec.common.classy.<re-export>`.
export * as components from './components';
export * as constants from './constants';
export * as fields from './fields';
export * as functions from './functions';
export * as store from './store';
export * as api from './api';

/*
 * Re-export localized data accessors and not the localized data object directly.
 * Packages outside of this will be able to access the localized data in one of two ways:
 *
 * Recommended:
 * - import {getLocalizedData, getSettings} from '@tec/common/classy/localizedData';
 *
 * Not ideal but still possible:
 * - const {getLocalizedData, getSettings} = window.tec.common.classy.localizedData;
 */
export const localizedData = {
	getLocalizedData,
	getSettings,
};
