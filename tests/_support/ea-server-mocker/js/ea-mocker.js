(function ( $, undefined ) {
	var prettyPrint = function ( el ) {
		if(undefined !== el.target ) {
			el = el.target;
		}
		var $this = $( el ), ugly, pretty, parsed;
		try {
			ugly = $this[0].value;
			parsed = JSON.parse( ugly );
			pretty = JSON.stringify( parsed, undefined, 4 );
			$this[0].value = pretty;
		} catch ( e ) {
			if ( undefined !== console ) {
				console.log( 'Could not format JSON data in field: ' + e.message );
			}
		}
	};

	var start = function () {
		var $jsonFields = $( '#ea-mocker' ).find( '.json' );
		$jsonFields.each( function () {
			prettyPrint( this );
			$( this ).on( 'change', prettyPrint );
		} )
	};

	$( document ).ready( start );
})( jQuery );
