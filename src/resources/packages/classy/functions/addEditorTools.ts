import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';

/**
 * CSS class names for the editor tools.
 *
 * @since TBD
 */
const EDITOR_TOOL_CLASSES = {
	EDITOR_DOCUMENT_TOOLS: 'editor-document-tools',
	EDITOR_DOCUMENT_TOOLS_LEFT: 'editor-document-tools__left',
	TEC_EDITOR_TOOL: 'tec-editor-tool',
	TEC_EDITOR_TOOL_PREVIEW: 'tec-editor-tool--preview',
	BUTTON: 'button',
	DASHICONS_VISIBILITY: 'dashicons dashicons-visibility',
} as const;

/**
 * Data attributes for the editor tools.
 *
 * @since TBD
 */
const DATA_ATTRIBUTES = {
	TOOLBAR_ITEM: 'data-toolbar-item',
} as const;

/**
 * Plugin registration name.
 *
 * @since TBD
 */
const PLUGIN_NAME = 'tec-editor-tools';

/**
 * Adds the Editor tools inserting them among the Block Editor tools.
 *
 * This function is weird-looking to work around the lack of an official API to add/remove Editor tools.
 * The function registers a plugin to be called when the toolbar is rendered; then calls a function that looks like
 * a React Component (EditorTools) that will not actually render a component, but will use the call to insert a button
 * in the Editor tools.
 *
 * @since TBD
 *
 * @param {(this: GlobalEventHandlers, ev: MouseEvent) => void} onClick The click event to handle on press of the added
 *     button.
 * @param {Document|null} document The document to work on, or `null` to work on `window.document`.
 *
 * @return {void} The Editor tools are added to the toolbar.
 */
export function addEditorTools(
	onClick: ( this: GlobalEventHandlers, ev: MouseEvent ) => void,
	document: Document | null = null
): void {
	document = document || window.document;

	// 2. The function _should_ render a React component, but won't. It will insert a button.
	function EditorTools() {
		const editorDocumentTools = document.querySelector(
			`.${ EDITOR_TOOL_CLASSES.EDITOR_DOCUMENT_TOOLS } .${ EDITOR_TOOL_CLASSES.EDITOR_DOCUMENT_TOOLS_LEFT }`
		);
		const previewButton = document.querySelector( `.${ EDITOR_TOOL_CLASSES.TEC_EDITOR_TOOL_PREVIEW }` );

		if ( editorDocumentTools && previewButton === null ) {
			const previewButton = document.createElement( 'button' );
			previewButton.classList.add(
				EDITOR_TOOL_CLASSES.TEC_EDITOR_TOOL,
				EDITOR_TOOL_CLASSES.TEC_EDITOR_TOOL_PREVIEW,
				EDITOR_TOOL_CLASSES.BUTTON
			);
			previewButton.type = 'button';
			previewButton.setAttribute( DATA_ATTRIBUTES.TOOLBAR_ITEM, 'true' );
			previewButton.innerHTML = `<span class="${ EDITOR_TOOL_CLASSES.DASHICONS_VISIBILITY }"></span>${ __(
				'Visual',
				'tribe-common'
			) }`;
			editorDocumentTools.append( previewButton );
			previewButton.onclick = onClick;
		}

		// 3. Do not actually render any component.
		return null;
	}

	// 1. When the time comes for plugin registration, it's the moment to insert the button.
	registerPlugin( PLUGIN_NAME, {
		render: EditorTools,
	} );
}
