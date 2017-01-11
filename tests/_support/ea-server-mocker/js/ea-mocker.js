(function ( $, undefined ) {
	var formatJSONString = function ( text ) {
		return JSON.stringify( JSON.parse( text ), undefined, 4 );
	};

	var formatJSONContent = function ( el ) {
		if ( undefined !== el.target ) {
			el = el.target;
		}
		var $this = $( el ), ugly, pretty, parsed;
		try {
			ugly = $this[0].value;
			pretty = formatJSONString( ugly );
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
			formatJSONContent( $textarea[0] );
		}
	};

	var replaceImportId = function ( ev ) {
		var $this = $( ev.target ), placeholder, importId;
		placeholder = $this.data( 'placeholder' );
		importId = $( '#ea_mocker-import_id' ).val();

		if ( !importId ) {
			return;
		}

		$( '#ea-mocker' ).find( '.json' ).each( function () {
			var $this = $( this ), replaced;

			if ( !$this.text() ) {
				return;
			}

			replaced = $this.val().replace( placeholder, importId );
			$this.val( formatJSONString( replaced ) );
		} );
	};

	var start = function () {
		var $jsonFields = $( '#ea-mocker' ).find( '.json' );
		$jsonFields.each( function () {
			formatJSONContent( this );
			var $this = $( this );
			$this.on( 'change', formatJSONContent );
			var $insert = $this.siblings( '.insert-default' );
			if ( undefined !== $insert ) {
				$insert.on( 'click', insertDefault );
			}
		} );

		$( '#ea_mocker-replace_import_id' ).on( 'click', replaceImportId );
	};

	$( document ).ready( start );
})( jQuery );
