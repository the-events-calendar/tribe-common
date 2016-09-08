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
			var $active_plugin_row = $( 'tr[data-plugin$="' + i + '.php"].active' );

			// We remove the 'update' class to prevent formatting issues (normally WP would insert an inline
			// message of its own, however this messaging replaces that and is styled differently)
			$active_plugin_row.removeClass( 'update' );

			// Insert our custom message
			$active_plugin_row.find( '.plugin-version-author-uri' ).after( $row );
		}
	};

	$( function() {
		my.init();
	});
})( jQuery, tribe_pue_notices );
