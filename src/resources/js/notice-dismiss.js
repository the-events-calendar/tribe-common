/**
 * Notice Dismiss structure
 */
( function( $ ) {
	$( document ).on(
		'click',
		'.tribe-dismiss-notice .notice-dismiss',
		( event ) => {
			const $this = $( event.target );
			const $notice = $this.parents( '.tribe-dismiss-notice' ).eq( 0 );

			$.ajax( ajaxurl, {
				dataType: 'json',
				method: 'POST',
				data: {
					action: 'tribe_notice_dismiss',
					'tribe-dismiss-notice': $notice.data( 'ref' ),
					'tec-dismiss-notice-nonce': $notice.data( 'dismiss-nonce' ),
				}
			} );
		}
	);
}( jQuery ) );
