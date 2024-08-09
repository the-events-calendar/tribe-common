tribe.copyToClipboard = tribe.copyToClipboard || {};

( function ( $, obj ) {
	'use strict';

	obj.selectors = {
		copyButton: '.tribe-copy-to-clipboard',
	};

	/**
	 * Initialize system info opt in copy
	 */
	obj.setupCopyButton = function () {
		const clipboard = new ClipboardJS( obj.selectors.copyButton );
		const notice     = $( $( obj.selectors.copyButton ).data( 'notice-target' ) );

		//Prevent Button From Doing Anything Else
		$( obj.selectors.copyButton ).on(
			'click',
			function ( e ) {
				e.preventDefault();
			}
		);

		clipboard.on( 'success', function ( event ) {
			event.clearSelection();
			notice.html( '<span class="optin-success">' + tribeCopyToClipboard.clipboard_copied_text + '<span>' ); // eslint-disable-line max-len
			notice.show();
			window.setTimeout( function () {
				notice.html( '' );
				notice.hide();
			}, 2000 );
		} );

		clipboard.on( 'error', function ( event ) {
			notice.html( '<span class="optin-fail">' + tribeCopyToClipboard.clipboard_fail_text + '<span>' ); // eslint-disable-line max-len
			notice.show();
			window.setTimeout( function () {
				notice.html();
				notice.hide();
			}, 2000 );
		} );
	};

	obj.setupCopyButton();

} )( jQuery, tribe.copyToClipboard );
