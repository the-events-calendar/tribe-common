import { whenEditorIsReady } from './functions/whenEditorIsReady';
import { hideInserterToggle, hideZoomOutButton } from './functions/editorModifications';
import { initApp as initClassyApp, insertElement as insertClassyElement } from './functions/classy';
import { getLocalizedData, getSettings } from './localizedData';
import { registerMiddlewares } from './api';
import './style.pcss';

whenEditorIsReady().then( () => {
	registerMiddlewares();
	hideZoomOutButton();
	hideInserterToggle();
	initClassyApp();
	insertClassyElement();
} );

// Re-exports that will appear under `window.tec.common.classy.<re-export>`.
export * as components from './components';
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
