var tribe_logger_admin = tribe_logger_admin || {};
var tribe_logger_data  = tribe_logger_data || {};

( function( $, obj ) {
	var working        = false;
	var current_view   = '';
	var current_engine = '';
	var view_changed   = false;
	var $controls      = $( '#tribe-log-controls' );
	var $options       = $controls.find( 'select' );
	var $spinner       = $controls.find( '.working' );
	var $viewer        = $( '#tribe-log-viewer' );
	var $download_link = $( 'a.download_log' );

	function update() {
		// If an update is already in progress let's wait until that job completes
		if ( working ) {
			return;
		}

		detect_view_change();
		freeze();
		request();
	}

	function request() {
		var data = {
			'action':     'tribe_logging_controls',
			'check':      tribe_logger_data.check,
			'log-level':  $( '#log-level' ).find( ':selected' ).attr( 'name' ),
			'log-engine': $( '#log-engine' ).find( ':selected' ).attr( 'name' )
		};

		if ( view_changed ) {
			data['log-view'] = current_view;
		}

		$.ajax( ajaxurl, {
			'method':   'POST',
			'success':  on_success,
			'error':    on_error,
			'dataType': 'json',
			'data':     data
		} );
	}

	function on_success( data ) {
		unfreeze();

		if ( $.isArray( data.data.entries ) ) {
			$viewer.html( to_table( data.data.entries ) );
			update_download_link();
		}
	}

	/**
	 * Converts data_array, which is expected to be an array of
	 * arrays, into an HTML table.
	 */
	function to_table( data_array ) {
		var html = '<table>';

		for ( var row in data_array ) {
			html += '<tr>';

			for ( var cell in data_array[row] ) {
				html += '<td>' + data_array[row][cell] + '</td>';
			}

			html += '</tr>';
		}

		return html + '</table>';
	}

	function update_download_link() {
		var url = $download_link.attr( 'href' );
		var log = encodeURI( get_current_view() );
		var matches = url.match(/&log=([a-z0-9\-]+)/i);

		// Update or add the log parameter
		if ( $.isArray( matches ) && 2 === matches.length ) {
			url = url.replace( matches[0], '&log=' + log );
		} else if ( url.indexOf( '?' ) ) {
			url = url + '&log=' + log;
		} else {
			url = url + '?log=' + log;
		}

		$download_link.attr( 'href', url );
	}

	function on_error() {
		unfreeze();
	}

	function freeze() {
		working = true;
		$options.prop( 'disabled', true );
		$spinner.removeClass( 'hidden' );
	}

	function unfreeze() {
		working = false;
		$options.prop( 'disabled', false );
		$spinner.addClass( 'hidden' );
	}

	function detect_view_change() {
		var new_view = get_current_view();
		var new_engine = get_current_engine();

		if ( new_view !== current_view || new_engine !== current_engine ) {
			view_changed = true;
			current_view = new_view;
			current_engine = new_engine;
		} else {
			view_changed = false;
		}
	}

	function get_current_view() {
		return $( '#log-selector' ).find( ':selected' ).attr( 'name' );
	}

	function get_current_engine() {
		return $( '#log-engine' ).find( ':selected' ).attr( 'name' );
	}

	// Setup
	current_view = get_current_view();
	current_engine = get_current_engine();

	update_download_link();
	$options.change( update );
} )( jQuery, tribe_logger_admin );