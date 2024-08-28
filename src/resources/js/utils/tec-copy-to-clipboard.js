tribe.copyToClipboard = tribe.copyToClipboard || {};

( function ( $, obj ) {
	'use strict';

	obj.selectors = {
		copyButton: '.tec-copy-to-clipboard',
	};

	/**
	 * Initialize system info opt in copy
	 */
	obj.setupCopyButton = function () {
		const clipboard = new ClipboardJS( obj.selectors.copyButton );

		//Prevent Button From Doing Anything Else
		$( document ).on(
			'click',
			obj.selectors.copyButton,
			function ( e ) {
				e.preventDefault();
			}
		);

		clipboard.on( 'success', function ( event ) {
			event.clearSelection();
			const notice = $( $( event.trigger ).data( 'notice-target' ) );
			notice.html( '<span class="optin-success">' + tribeCopyToClipboard.clipboard_copied_text + '<span>' ); // eslint-disable-line max-len
			notice.show();

			window.setTimeout( function () {
				notice.html( '' );
				notice.hide();
			}, 2000 );
		} );

		clipboard.on( 'error', function ( event ) {
			const notice = $( $( event.trigger ).data( 'notice-target' ) );
			notice.html( '<span class="optin-fail">' + tribeCopyToClipboard.clipboard_fail_text + '<span>' ); // eslint-disable-line max-len
			notice.show();

			window.setTimeout( function () {
				notice.html( '' );
				notice.hide();
			}, 2000 );
		} );
	};

	obj.setupCopyButton();

} )( jQuery, tribe.copyToClipboard );
