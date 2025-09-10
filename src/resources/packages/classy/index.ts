import { whenEditorIsReady } from './functions/whenEditorIsReady';
import { hideInserterToggle, hideZoomOutButton, hideSidebarBlockTab } from './functions/editorModifications';
import {
	initApp as initClassyApp,
	insertElement as insertClassyElement,
} from './functions/classy';
import { getLocalizedData, getSettings } from './localizedData';
import './style.pcss';

whenEditorIsReady().then( () => {
	hideZoomOutButton();
	hideInserterToggle();
	hideSidebarBlockTab();
	initClassyApp();
	insertClassyElement();
} );

// Re-exports that will appear under `window.tec.common.classy.<re-export>`.
export * as components from './components';
export * as fields from './fields';
export * as functions from './functions';
export * as store from './store';

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
