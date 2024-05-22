(function ( $, undefined ) {
    var format_json_string = function ( text ) {
        return JSON.stringify( JSON.parse( text ), undefined, 4 );
    };

    var format_json_content = function ( el ) {
        if ( undefined !== el.target ) {
            el = el.target;
        }
        var $this = $( el ), ugly, pretty, parsed;
        try {
            ugly = $this[ 0 ].value;
            pretty = format_json_string( ugly );
            $this[ 0 ].value = pretty;
        } catch ( e ) {
            if ( undefined !== console ) {
                console.log( 'Could not format JSON data in field: ' + e.message );
            }
        }
    };

    var insert_default = function ( ev ) {
        ev.preventDefault();
        var $this = $( this ),
            slug = $this.data( 'slug' ),
            $default = $this.siblings( '.default[data-slug="' + slug + '"]' ).first();
        if ( $default ) {
            var $textarea = $this.siblings( '.json' ).first();
            $textarea.val( $default.text() );
            format_json_content( $textarea[ 0 ] );
        }
    };

    var replace_import_id = function ( ev ) {
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
            $this.val( format_json_string( replaced ) );
        } );
    };

    var clean_target = function ( ev ) {
        var $this = $( ev.target ),
            $target = $( $this.data( 'target' ) );

        if ( !$target ) {
            return;
        }

        $target[ 0 ].value = '';
    };

    var start = function () {
        var $jsonFields = $( '#ea-mocker' ).find( '.json' );
        $jsonFields.each( function () {
            format_json_content( this );
            var $this = $( this );
            $this.on( 'change', format_json_content );
            var $insert = $this.siblings( '.insert-default' );
            if ( undefined !== $insert ) {
                $insert.on( 'click', insert_default );
            }
        } );

        $( '#ea_mocker-replace_import_id' ).on( 'click', replace_import_id );

        $( '#ea-mocker' ).find( 'button.clean' ).each( function () {
            $( this ).on( 'click', clean_target );
        } );
    };

    $( document ).ready( start );
})( jQuery );
