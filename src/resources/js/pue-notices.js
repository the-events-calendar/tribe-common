var tribe_pue_notices = tribe_pue_notices || {};

(function( $, my ) {
	'use strict';

	my.init = function() {
		if ( 'undefined' === typeof tribe_plugin_notices ) {
			return;
		}

		for ( var i in tribe_plugin_notices ) {
			if ( ! tribe_plugin_notices.hasOwnProperty( i ) ) {
				continue;
			}

			var $row = $( '<div class="update-message">' + tribe_plugin_notices[ i ].message + '</div>' );

			$( 'tr[data-plugin$="' + i + '.php"].active .plugin-version-author-uri' ).after( $row );
		}
	};

	$( function() {
		my.init();
	});
})( jQuery, tribe_pue_notices );
