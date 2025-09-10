import { __ } from '@wordpress/i18n';

/**
 * Hide the Zoom Out button in the editor toolbar.
 *
 * While not ideal, the hiding is based on the ARIA label.
 * Currently, the Block Editor does not define an `id` or `class` attribute that could help with this.
 *
 * @since TBD
 *
 * @param {Document|null} document The document to work on, or `null` to work on `window.document`.
 *
 * @return {number} The number of hidden elements.
 */
export function hideZoomOutButton( document: Document | null = null ): number {
	document = document || window.document;

	// Remove the Zoom Out button. The only way is by its aria label.
	const zoomOutAriaLabel = __( 'Zoom Out' );
	let hidden = 0;
	document
		.querySelectorAll( `.components-button[aria-label="${ zoomOutAriaLabel }"]` )
		.forEach( ( el: HTMLElement ) => {
			hidden++;
			return ( el.style.display = 'none' );
		} );

	return hidden;
}

/**
 * Hide the inserter toggle button of the Block Editor
 *
 * @since TBD
 *
 * @param {Document|null} document The document to work on, or `null` to work on `window.document`.
 *
 * @return {number} The number of buttons hidden
 */
export function hideInserterToggle( document: Document | null = null ): number {
	document = document || window.document;

	let hidden = 0;
	document.querySelectorAll( '.editor-document-tools__inserter-toggle' ).forEach( ( button: HTMLElement ) => {
		hidden++;
		return ( button.style.display = 'none' );
	} );

	return hidden;
}

/**
 * Hide the Block tab in the editor sidebar.
 *
 * @since TBD
 *
 * @param {Document|null} document The document to work on, or `null` to work on `window.document`.
 */
export function hideSidebarBlockTab( document: Document | null = null ): void {
	document = document || window.document;

	const hideBlockTab = () => {
		const blockTab = document.querySelector('[data-tab-id="edit-post/block"]' );
		if ( blockTab ) {
			blockTab.remove();
		}
	};

	hideBlockTab();

	// Set up observer to watch for tab list changes.
	const observer = new MutationObserver( () => hideBlockTab() );

	// Observe the body for updates.
	observer.observe( document.body, {
		childList: true,
		subtree: true
	} );

	// Clean up observer when page unloads.
	window.addEventListener( 'unload', () => observer.disconnect() );
}
