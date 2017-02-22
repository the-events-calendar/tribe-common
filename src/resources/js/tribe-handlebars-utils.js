(function ( $, my ) {
	'use strict';

	window.Handlebars.registerHelper( {
		tribe_select_option: function( value, options ) {
			var $el = $( '<select />' ).html( options.fn( this ) );

			// if a value is passed in, get rid of the defaults
			if ( value ) {
				$el.find( 'option:selected' ).attr( 'selected', false );
			}

			$el.find( '[value="' + value + '"]' ).attr( 'selected', 'selected' );
			return $el.html();
		},

		tribe_if_in: function( value, collection, text ) {
			if ( typeof collection === 'undefined' ) {
				collection = [];
			}

			if ( typeof text === 'undefined' ) {
				text = '';
			}

			return -1 !== $.inArray( value, collection ) ? text : '';
		},

		tribe_if_not_in: function( value, collection, text ) {
			if ( typeof collection === 'undefined' ) {
				collection = [];
			}

			if ( typeof text === 'undefined' ) {
				text = '';
			}

			return -1 === $.inArray( value, collection ) ? text : '';
		},

		tribe_checked_if_is: function( value, goal ) {
			return value === goal ? 'checked' : '';
		},

		tribe_checked_if_is_not: function( value, goal ) {
			return value !== goal ? 'checked' : '';
		},

		tribe_checked_if_in: function( value, collection ) {
			return -1 !== $.inArray( value, collection ) ? 'checked' : '';
		}
	} );

})( jQuery, {} );
