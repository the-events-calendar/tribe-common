( function( $, obj ) {
	'use strict';

	obj.selector = {
		dropdown: 'tribe-dropdown'
	};

	// Setup a Dependent
	$.fn.tribe_dropdowns = function () {
		obj.dropdown( this );

		return this;
	};

	/**
	 * Configure the Drop Down Fields
	 *
	 * @param  {jQuery} $fields All the fields from the page
	 *
	 * @return {jQuery}         Affected fields
	 */
	obj.dropdown = function( $fields ) {
		var $elements = $fields.not( '.select2-offscreen, .select2-container' );

		$elements.each(function(){
			var $select = $(this),
				args = {
					dropdownAutoWidth: true,
					dropdownCss: {
						'min-width': '5rem'
					}
				};

			if ( ! $select.is( 'select' ) ) {
				// Better Method for finding the ID
				args.id = obj.search_id;
			}

			// By default we allow The field to be cleared
			args.allowClear = true;
			if ( $select.is( '[data-prevent-clear]' ) ) {
				args.allowClear = false;
			}

			// If we are dealing with a Input Hidden we need to set the Data for it to work
			if ( $select.is( '[data-options]' ) ) {
				args.data = $select.data( 'options' );

				if ( ! $select.is( 'select' ) ) {
					args.initSelection = obj.setup_initSelection( args, $select );
				}
			}

			// Prevents the Search box to show
			if ( $select.is( '[data-hide-search]' ) ) {
				args.minimumResultsForSearch = Infinity;
			}

			// Allows freeform entry
			if ( $select.is( '[data-freeform]' ) ) {
				args.createSearchChoice = function( term, data ) {
					if (
						term.match( args.regexToken )
						&& (
							! $select.is( '[data-int]' )
							|| (
								$select.is( '[data-int]' )
								&& term.match( /\d+/ )
							)
						)
					) {
						return { id: term, text: term };
					}
				};
			}

			if ( 'tribe-ea-field-origin' === $select.attr( 'id' ) ) {
				args.formatResult = args.upsellFormatter,
					args.formatSelection = args.upsellFormatter,
					args.escapeMarkup = function(m) { return m; };
			}

			if ( $select.is( '[multiple]' ) ) {
				args.multiple = true;

				if ( ! _.isArray( $select.data( 'separator' ) ) ) {
					args.tokenSeparators = [ $select.data( 'separator' ) ];
				} else {
					args.tokenSeparators = $select.data( 'separator' );
				}
				args.separator = $select.data( 'separator' );

				// Define the regular Exp based on
				args.regexSeparatorElements = [ '^(' ];
				args.regexSplitElements = [ '(?:' ];
				$.each( args.tokenSeparators, function ( i, token ) {
					args.regexSeparatorElements.push( '[^' + token + ']+' );
					args.regexSplitElements.push( '[' + token + ']' );
				} );
				args.regexSeparatorElements.push( ')$' );
				args.regexSplitElements.push( ')' );

				args.regexSeparatorString = args.regexSeparatorElements.join( '' );
				args.regexSplitString = args.regexSplitElements.join( '' );

				args.regexToken = new RegExp( args.regexSeparatorString, 'ig' );
				args.regexSplit = new RegExp( args.regexSplitString, 'ig' );
			}

			/**
			 * Better way of matching results
			 *
			 * @param  {string} term Which term we are searching for
			 * @param  {string} text Search here
			 * @return {boolean}
			 */
			args.matcher = function( term, text ) {
				var result = text.toUpperCase().indexOf( term.toUpperCase() ) == 0;

				if ( ! result && 'undefined' !== typeof args.tags ){
					var possible = _.where( args.tags, { text: text } );
					if ( args.tags.length > 0  && _.isObject( possible ) ){
						var test_value = obj.search_id( possible[0] );
						result = test_value.toUpperCase().indexOf( term.toUpperCase() ) == 0;
					}
				}

				return result;
			};

			// Select also allows Tags, so we go with that too
			if ( $select.is( '[data-tags]' ) ){
				args.tags = $select.data( 'tags' );

				args.initSelection = obj.setup_initSelection( args, $select );

				args.createSearchChoice = function(term, data) {
					if ( term.match( args.regexToken ) ) {
						return { id: term, text: term };
					}
				};

				if ( 0 === args.tags.length ){
					args.formatNoMatches = function(){
						return $select.attr( 'placeholder' );
					};
				}
			}

			// When we have a source, we do an AJAX call
			if ( $select.is( '[data-source]' ) ) {
				var source = $select.data( 'source' );

				// For AJAX we reset the data
				args.data = { results: [] };

				// Allows HTML from Select2 AJAX calls
				args.escapeMarkup = function (m) {
					return m;
				};

				args.ajax = { // instead of writing the function to execute the request we use Select2's convenient helper
					dataType: 'json',
					type: 'POST',
					url: window.ajaxurl,
					results: function ( data ) { // parse the results into the format expected by Select2.
						return data.data;
					}
				};

				// By default only send the source
				args.ajax.data = function( search, page ) {
					return {
						action: 'tribe_aggregator_dropdown_' + source,
					};
				};
			}

			$select.select2( args );
		})
		.on( 'change', function( event ) {
			var $select = $(this),
				data = $( this ).data( 'value' );

			if ( ! $select.is( '[multiple]' ) ){
				return;
			}
			if ( ! $select.is( '[data-source]' ) ){
				return;
			}

			if ( event.added ){
				if ( _.isArray( data ) ) {
					data.push( event.added );
				} else {
					data = [ event.added ];
				}
			} else {
				if ( _.isArray( data ) ) {
					data = _.without( data, event.removed );
				} else {
					data = [];
				}
			}
			$select.data( 'value', data ).attr( 'data-value', JSON.stringify( data ) );
		} );

		// return to be able to chain jQuery calls
		return $elements;
	};

	/**
	 * Better Search ID for Select2, compatible with WordPress ID from WP_Query
	 *
	 * @param  {object|string} e Searched object or the actual ID
	 * @return {string}   ID of the object
	 */
	obj.search_id = function ( e ) {
		var id = undefined;

		if ( 'undefined' !== typeof e.id ){
			id = e.id;
		} else if ( 'undefined' !== typeof e.ID ){
			id = e.ID;
		} else if ( 'undefined' !== typeof e.value ){
			id = e.value;
		}
		return undefined === e ? undefined : id;
	};

	/**
	 */
	obj.setup_initSelection = function( args, $select ) {
		var is_multiple = $select.is( '[data-multiple]' );

		return function ( element, callback ) {
			var data = [];
			$( element.val().split( args.regexSplit ) ).each( function () {
				var item = { id: this, text: this };
				if ( args.data.length > 0  && _.isObject( args.data[0] ) ) {
					var _item = _.where( args.data, { text: this.valueOf() } );
					if ( _item.length > 0 ){
						item = _item[0];
					}
				}

				if ( is_multiple ) {
					data.push( item );
				} else {
					callback( item );
				}
			} );

			if ( is_multiple ) {
				callback( data );
			}
		};
	};

	$( function() {
		$( obj.selector.dropdown ).tribe_dropdowns();
	} );
} )( jQuery, {} );
