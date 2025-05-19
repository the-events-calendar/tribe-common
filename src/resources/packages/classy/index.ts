import { whenEditorIsReady } from './functions/whenEditorIsReady';
import {
	hideInserterToggle,
	hideZoomOutButton,
} from './functions/editorModifications';
import { addEditorTools } from './functions/addEditorTools';
import {
	initApp as initClassyApp,
	insertElement as insertClassyElement,
	toggleElementVisibility as toggleClassyElementVisibility,
} from './functions/classy';
import './style.pcss';

whenEditorIsReady().then( () => {
	hideZoomOutButton();
	hideInserterToggle();
	initClassyApp().then( () => {
		insertClassyElement();
		addEditorTools( () => toggleClassyElementVisibility() );
	} );
} );

// Re-exports that will appear under `window.tec.common.classy.<re-export>`.
export * as functions from './functions';
export * as constants from './constants';
export * as components from './components';
export * as fields from './fields';
export * as store from './store';
