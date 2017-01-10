(function ( $, undefined ) {
	var prettyPrint = function ( el ) {
		if ( undefined !== el.target ) {
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

	var insertDefault = function ( ev ) {
		ev.preventDefault();
		var $this = $( this ),
			slug = $this.data( 'slug' ),
			$default = $this.siblings( '.default[data-slug="' + slug + '"]' ).first();
		if ( $default ) {
			var $textarea = $this.siblings( '.json' ).first();
			$textarea.val( $default.text() );
			prettyPrint( $textarea[0] );
		}
	};

	var start = function () {
		var $jsonFields = $( '#ea-mocker' ).find( '.json' );
		$jsonFields.each( function () {
			prettyPrint( this );
			var $this = $( this );
			$this.on( 'change', prettyPrint );
			var $insert = $this.siblings( '.insert-default' );
			if ( undefined !== $insert ) {
				$insert.on( 'click', insertDefault );
			}
		} )
	};

	$( document ).ready( start );
})( jQuery );
