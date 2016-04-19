var tribe_logger_admin = tribe_logger_admin || {};
var tribe_logger_data  = tribe_logger_data || {};

( function( $, obj ) {
	var working      = false;
	var current_view = '';
	var view_changed = false;
	var $controls    = $( '#tribe-log-controls' );
	var $options     = $controls.find( 'select' );
	var $spinner     = $controls.find( '.working' );
	var $viewer      = $( '#tribe-log-viewer' );

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

		if ( new_view !== current_view ) {
			view_changed = true;
			current_view = new_view;
		} else {
			view_changed = false;
		}
	}

	function get_current_view() {
		return $( '#log-selector' ).find( ':selected' ).attr( 'name' );
	}

	// Setup
	current_view = get_current_view();
	$options.change( update );
} )( jQuery, tribe_logger_admin );