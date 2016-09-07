var tribe_plugin_notices = tribe_plugin_notices || {};

(function( $, my ) {
	'use strict';

	my.init = function() {
		for ( var plugin_slug in tribe_plugin_notices ) {
			if ( ! tribe_plugin_notices.hasOwnProperty( plugin_slug ) ) {
				continue;
			}

			var $row = $( tribe_plugin_notices[ plugin_slug ].message_row_html );
			var $active_plugin_row = $( 'tr[data-plugin$="' + plugin_slug + '.php"].active' );

			// Insert our custom message
			$active_plugin_row.after( $row );
		}
	};

	$( function() {
		if ( 'object' === typeof tribe_plugin_notices ) {
			my.init();
		}
	});
})( jQuery, tribe_plugin_notices );