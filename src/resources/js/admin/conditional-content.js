/**
 * Notice Dismiss structure
 */
( function( $, wp, obj ) {
	const { tec } = window;

	/**
	 * Selectors used for configuration and setup.
	 *
	 * @since TBD
	 *
	 * @type {{}}
	 */
	obj.selectors = {
		dismissButton: '[data-tec-conditional-content-dismiss-button]',
		dismissedContainer: '[data-tec-conditional-content-dismiss-container]',
	};

	$( document ).on(
		'click',
		obj.selectors.dismissButton,
		( event ) => {
			const $button = $( event.target );
			const $container = $button.parents( obj.selectors.dismissedContainer ).eq( 0 );

			if ( ! $container.length ) {
				return;
			}

			let id = $button.data( 'tecConditionalContentDismissId' );

			if ( ! id ) {
				id = $container.data( 'tecConditionalContentDismissId' );
			}

			let nonce = $button.data( 'tecConditionalContentDismissNonce' );

			if ( ! nonce ) {
				nonce = $container.data( 'tecConditionalContentDismissNonce' );
			}

			if ( ! id || ! nonce ) {
				return;
			}

			wp.data
				.dispatch( 'core/preferences' )
				.set( 'tec/conditional-content-dismissed', id, 1 );

			$.ajax( ajaxurl, {
				dataType: 'json',
				method: 'POST',
				data: {
					action: 'tec_conditional_content_dismiss',
					id: id,
					nonce: nonce,
				},
				complete: () => {
					$container.remove();
				},
			} );
		}
	).ready(() => {
		const preferences = wp.data.select( 'core/preferences' );
		const dismissed = preferences.get( 'tec/conditional-content-dismissed' );

		if ( ! dismissed ) {
			return;
		}

		dismissed.forEach( ( id ) => {
			$( `[data-tec-conditional-content-dismiss-id="${id}"]` ).remove();
		} );
	});


	// Expose the object to the global scope.
	tec.conditionalContent = obj;
}( jQuery, window.wp, {} ) );
