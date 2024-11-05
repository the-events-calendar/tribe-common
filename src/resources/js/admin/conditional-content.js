/**
 * Notice Dismiss structure
 */
( function( $, wp, obj ) {
	const { tec } = window;

	/**
	 * Selectors used for configuration and setup.
	 *
	 * @since 6.3.0
	 *
	 * @type {{}}
	 */
	obj.selectors = {
		dismissButton: '[data-tec-conditional-content-dismiss-button]',
		dismissedContainer: '[data-tec-conditional-content-dismiss-container]',
	};


	/**
	 * Handles the click event on the dismiss button.
	 *
	 * @since 6.3.0
	 *
	 * @param {Event} event
	 */
	obj.onDismissClick = ( event ) => {
		event.preventDefault();

		let $button = $( event.target );
		if ( ! $button.is( obj.selectors.dismissButton ) ) {
			$button = $button.parents( obj.selectors.dismissButton ).eq( 0 );
		}

		const $container = $button.parents( obj.selectors.dismissedContainer ).eq( 0 );

		if ( ! $container.length ) {
			return;
		}

		let slug = $button.data( 'tecConditionalContentDismissSlug' );

		if ( ! slug ) {
			slug = $container.data( 'tecConditionalContentDismissSlug' );
		}

		let nonce = $button.data( 'tecConditionalContentDismissNonce' );

		if ( ! nonce ) {
			nonce = $container.data( 'tecConditionalContentDismissNonce' );
		}

		if ( ! slug || ! nonce ) {
			return;
		}

		$.ajax( ajaxurl, {
			dataType: 'json',
			method: 'POST',
			data: {
				action: 'tec_conditional_content_dismiss',
				slug: slug,
				nonce: nonce,
			},
			complete: () => {
				$container.remove();
			},
		} );
	};

	$( document ).on(
		'click',
		obj.selectors.dismissButton,
		obj.onDismissClick
	);

	// Expose the object to the global scope.
	tec.conditionalContent = obj;
}( jQuery, window.wp, {} ) );
