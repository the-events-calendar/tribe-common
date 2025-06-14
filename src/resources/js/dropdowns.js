window.tribe_dropdowns = window.tribe_dropdowns || {};

( function ( $, obj, _ ) {
	'use strict';

	obj.selector = {
		dropdown: '.tribe-dropdown',
		created: '.tribe-dropdown-created',
		searchField: '.select2-search__field',
	};

	// Setup a Dependent
	$.fn.tribe_dropdowns = function () {
		obj.dropdown( this, {} );

		return this;
	};

	obj.freefrom_create_search_choice = function ( params ) {
		if ( 'string' !== typeof params.term ) {
			return null;
		}

		const term = params.term.trim();

		if ( '' === term ) {
			return null;
		}

		const args = this.options.options;
		const $select = args.$select;

		if (
			term.match( args.regexToken ) &&
			( ! $select.is( '[data-int]' ) || ( $select.is( '[data-int]' ) && term.match( /\d+/ ) ) )
		) {
			const choice = { id: term, text: term, new: true };

			if ( $select.is( '[data-create-choice-template]' ) ) {
				choice.text = _.template( $select.data( 'createChoiceTemplate' ) )( { term } );
			}

			return choice;
		}

		return null;
	};

	/**
	 * Better Search ID for Select2, compatible with WordPress ID from WP_Query
	 *
	 * @param {object|string} e Searched object or the actual ID
	 * @return {string}   ID of the object
	 */
	obj.search_id = function ( e ) {
		let id;

		if ( 'undefined' !== typeof e.id ) {
			id = e.id;
		} else if ( 'undefined' !== typeof e.ID ) {
			id = e.ID;
		} else if ( 'undefined' !== typeof e.value ) {
			id = e.value;
		}
		return undefined === e ? undefined : id;
	};

	/**
	 * Better way of matching results
	 *
	 * @param {string} term   Which term we are searching for
	 * @param {string} text   Search here
	 * @param         params
	 * @param         data
	 * @return {boolean}
	 */
	obj.matcher = function ( params, data ) {
		// If there are no search terms, return all of the data
		if ( 'string' !== typeof params.term || params.term.trim() === '' ) {
			return data;
		}

		// Do not display the item if there is no 'text' property
		if ( typeof data.text === 'undefined' ) {
			return null;
		}

		const term = params.term.trim();
		const text = data.text;
		const $select = $( data.element ).closest( 'select' );
		const args = $select.data( 'dropdown' );
		let result = text.toUpperCase().indexOf( term.toUpperCase() ) !== -1;

		if ( ! result && 'undefined' !== typeof args.tags ) {
			const possible = _.where( args.tags, { text } );
			if ( args.tags.length > 0 && _.isObject( possible ) ) {
				const test_value = obj.search_id( possible[ 0 ] );
				result = test_value.toUpperCase().indexOf( term.toUpperCase() ) !== -1;
			}
		}

		return result;
	};

	/**
	 * If the element used as the basis of a dropdown specifies one or more numeric/text
	 * identifiers in its val attribute, then use those to preselect the appropriate options.
	 *
	 * @param {Object}   $select
	 * @param {Function} make_selection
	 */
	obj.init_selection = function ( $select, make_selection ) {
		const isMultiple = $select.is( '[multiple]' );
		const options = $select.data( 'dropdown' );
		const currentValues = $select.val().split( options.regexSplit );
		const selectedItems = [];

		$( currentValues ).each( function ( index, value ) {
			// eslint-disable-line no-unused-vars
			const searchFor = { id: this, text: this };
			const data = options.ajax ? $select.data( 'options' ) : options.data;
			const locatedItem = find_item( searchFor, data );

			if ( locatedItem && locatedItem.selected ) {
				selectedItems.push( locatedItem );
			}
		} );

		if ( selectedItems.length && isMultiple ) {
			make_selection( selectedItems );
		} else if ( selectedItems.length ) {
			make_selection( selectedItems[ 0 ] );
		} else {
			make_selection( false );
		}
	};

	/**
	 * Searches array 'haystack' for objects that match 'description'.
	 *
	 * The 'description' object should take the form { id: number, text: string }. The first
	 * object within the haystack that matches one of those two properties will be returned.
	 *
	 * If objects contain an array named 'children', then that array will also be searched.
	 *
	 * @param {Object} description
	 * @param {Array}  haystack
	 *
	 * @return {Object|boolean}
	 */
	function find_item( description, haystack ) {
		if ( ! _.isArray( haystack ) ) {
			return false;
		}

		for ( const index in haystack ) {
			const possible_match = haystack[ index ];

			if ( possible_match.hasOwnProperty( 'id' ) && possible_match.id == description.id ) {
				// eslint-disable-line no-prototype-builtins,eqeqeq,max-len
				return possible_match;
			}

			if ( possible_match.hasOwnProperty( 'text' ) && possible_match.text == description.text ) {
				// eslint-disable-line no-prototype-builtins,eqeqeq,max-len
				return possible_match;
			}

			if ( possible_match.hasOwnProperty( 'children' ) && _.isArray( possible_match.children ) ) {
				// eslint-disable-line no-prototype-builtins,max-len
				const subsearch = find_item( description, possible_match.children );

				if ( subsearch ) {
					return subsearch;
				}
			}
		}

		return false;
	}

	obj.getSelectClasses = function ( $select ) {
		const classesToRemove = [ 'select2-hidden-accessible', 'hide-before-select2-init' ];
		const originalClasses = $select.attr( 'class' ).split( /\s+/ );
		return _.difference( originalClasses, classesToRemove );
	};

	obj.element = function ( field, args ) {
		const $select = $( field );
		var args = $.extend( {}, args ); // eslint-disable-line no-redeclare
		const carryOverData = [
			// eslint-disable-line no-unused-vars
			'depends',
			'condition',
			'conditionNot',
			'condition-not',
			'conditionNotEmpty',
			'condition-not-empty',
			'conditionEmpty',
			'condition-empty',
			'conditionIsNumeric',
			'condition-is-numeric',
			'conditionIsNotNumeric',
			'condition-is-not-numeric',
			'conditionChecked',
			'condition-is-checked',
		];

		let $container;

		// Add a class for dropdown created
		$select.addClass( obj.selector.created.className() );

		// args.debug = true;

		// For Reference we save the jQuery element as an Arg.
		args.$select = $select;

		// Auto define the Width of the Select2.
		args.dropdownAutoWidth = true;
		args.width = 'resolve';

		// CSS for the container
		args.containerCss = {};

		// Only apply visibility when it's a Visible Select2.
		if ( $select.is( ':visible' ) ) {
			args.containerCss.display = 'inline-block';
			args.containerCss.position = 'relative';
		}

		// CSS for the dropdown
		args.dropdownCss = {};
		args.dropdownCss.width = 'auto';

		// When we have this we replace the default with what's in the param.
		if ( $select.is( '[data-dropdown-css-width]' ) ) {
			args.dropdownCss.width = $select.data( 'dropdown-css-width' );

			if ( ! args.dropdownCss.width || 'false' === args.dropdownCss.width ) {
				delete args.dropdownCss.width;
				delete args.containerCss;
			}
		}

		// By default we allow The field to be cleared
		args.allowClear = true;
		if ( $select.is( '[data-prevent-clear]' ) ) {
			args.allowClear = false;
		}

		// Pass the "Searching..." placeholder if specified
		if ( $select.is( '[data-searching-placeholder]' ) ) {
			args.formatSearching = $select.data( 'searching-placeholder' );
		}

		// If we are dealing with a Input Hidden we need to set the Data for it to work
		if ( ! $select.is( '[data-placeholder]' ) && $select.is( '[placeholder]' ) ) {
			args.placeholder = $select.attr( 'placeholder' );
		}

		// If we are dealing with a Input Hidden we need to set the Data for it to work.
		if ( $select.is( '[data-options]' ) ) {
			args.data = $select.data( 'options' );
		}

		// With less then 10 args we wouldn't show the search.
		args.minimumResultsForSearch = 10;

		// Prevents the Search box to show
		if ( $select.is( '[data-hide-search]' ) ) {
			args.minimumResultsForSearch = Infinity;
		}

		// Makes sure search shows up.
		if ( $select.is( '[data-force-search]' ) ) {
			delete args.minimumResultsForSearch;
		}

		// Allows freeform entry
		if ( $select.is( '[data-freeform]' ) ) {
			args.createTag = obj.freefrom_create_search_choice;
			args.tags = true;
		}

		if ( $select.is( '[multiple]' ) ) {
			args.multiple = true;

			// Set the max select items, if defined
			if ( $select.is( '[data-maximum-selection-size]' ) ) {
				args.maximumSelectionSize = $select.data( 'maximum-selection-size' );
			}

			// If you don't have separator, add one (comma)
			if ( ! $select.is( 'data-separator' ) ) {
				$select.data( 'separator', ',' );
			}

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

		// Select also allows Tags, so we go with that too
		if ( $select.is( '[data-tags]' ) ) {
			const selectTags = $select.data( 'tags' );
			args.tags = 1 === selectTags || '1' === selectTags || 'true' === selectTags;

			// Don't force tags!
			if ( args.tags ) {
				args.createSearchChoice = function ( term, data ) {
					// eslint-disable-line no-unused-vars
					if ( term.match( args.regexToken ) ) {
						return { id: term, text: term };
					}
				};

				if ( 0 === args.tags.length ) {
					args.formatNoMatches = function () {
						return $select.attr( 'placeholder' );
					};
				}
			}
		}

		// When we have a source, we do an AJAX call
		if ( $select.is( '[data-source]' ) ) {
			const source = $select.data( 'source' );

			// For AJAX we reset the data
			args.data = { results: [] };

			// Format for Parents breadcrumbs
			args.formatResult = function ( item, container, query ) {
				// eslint-disable-line no-unused-vars,max-len
				if ( 'undefined' !== typeof item.breadcrumbs ) {
					return $.merge( item.breadcrumbs, [ item.text ] ).join( ' &#187; ' );
				}

				return item.text;
			};

			// instead of writing the function to execute the request we use Select2's convenient helper.
			args.ajax = {
				dataType: 'json',
				type: 'POST',
				url: obj.ajaxurl(),

				// parse the results into the format expected by Select2.
				processResults( response, params ) {
					// eslint-disable-line no-unused-vars
					if ( ! $.isPlainObject( response ) || 'undefined' === typeof response.success ) {
						console.error( 'We received a malformed Object, could not complete the Select2 Search.' ); // eslint-disable-line max-len
						return { results: [] };
					}

					if ( ! $.isPlainObject( response.data ) || 'undefined' === typeof response.data.results ) {
						console.error(
							'We received a malformed results array, could not complete the Select2 Search.'
						); // eslint-disable-line max-len
						return { results: [] };
					}

					if ( ! response.success ) {
						if ( 'string' === $.type( response.data.message ) ) {
							console.error( response.data.message );
						} else {
							console.error( 'The Select2 search failed in some way... Verify the source.' );
						}
						return { results: [] };
					}

					return response.data;
				},
			};

			if ( $select.is( '[data-ajax-delay]' ) ) {
				args.ajax.delay = $select.data( 'ajax-delay' );
			}

			if ( $select.is( '[data-ajax-cache]' ) ) {
				const ajaxCache = $select.data( 'ajax-cache' );
				args.ajax.cache = 1 === ajaxCache || '1' === ajaxCache || 'true' === ajaxCache;
			}

			if ( $select.is( '[data-minimum-input-length]' ) ) {
				args.minimumInputLength = parseInt( $select.data( 'minimum-input-length' ) );
			}

			// By default only send the source
			args.ajax.data = function ( search, page ) {
				return {
					action: 'tribe_dropdown',
					source,
					search,
					page,
					args: $select.data( 'source-args' ),
					nonce: $select.data( 'source-nonce' ),
				};
			};
		}

		// Attach dropdown to container in DOM.
		if ( $select.is( '[data-attach-container]' ) ) {
			// If multiple, attach container without search.
			if ( $select.is( '[multiple]' ) ) {
				$.fn.select2.amd.define(
					'AttachedDropdownAdapter',
					[ 'select2/utils', 'select2/dropdown', 'select2/dropdown/attachContainer' ],
					function ( utils, dropdown, attachContainer ) {
						return utils.Decorate( dropdown, attachContainer );
					}
				);

				args.dropdownAdapter = $.fn.select2.amd.require( 'AttachedDropdownAdapter' );

				// If not multiple, attach container with search.
			} else {
				$.fn.select2.amd.define(
					'AttachedWithSearchDropdownAdapter',
					[
						'select2/utils',
						'select2/dropdown',
						'select2/dropdown/search',
						'select2/dropdown/minimumResultsForSearch',
						'select2/dropdown/attachContainer',
					],
					function ( utils, dropdown, search, minimumResultsForSearch, attachContainer ) {
						let adapter = utils.Decorate( dropdown, attachContainer );
						adapter = utils.Decorate( adapter, search );
						adapter = utils.Decorate( adapter, minimumResultsForSearch );
						return adapter;
					}
				);

				args.dropdownAdapter = $.fn.select2.amd.require( 'AttachedWithSearchDropdownAdapter' );
			}
		}

		// Save data on Dropdown
		$select.data( 'dropdown', args );

		$container = $select.select2TEC( args );

		// If data-clear-to-value is set, on clear, set the value to the specified value.
		if ( $select.is( '[data-clear-to-value]' ) ) {
			/*
			 * Unlike setting `allowClear` to `false`, this will allow the user to clear the dropdown,
			 * but will then immediately set the value to the specified value.
			 */
			$container
				.on( 'select2:unselect', function () {
					/*
					 * Flag the dropdown to expect the opening event that would allow the user to pick
					 * a new value, or none, after clearing.
					 */
					const $select = $( this );
					$select.data( 'openingAfterUnselect', 1 );
				} )
				.on( 'select2:opening', function ( event ) {
					/*
					 * If the dropdown is expecting the opening event, then prevent it from opening
					 * and instead set the value to the specified value.
					 */
					const $select = $( this );

					if ( ! $select.data( 'openingAfterUnselect' ) ) {
						return;
					}

					$select.data( 'openingAfterUnselect', 0 );

					const value = $select.data( 'clear-to-value' );
					$select.val( value ).trigger( 'change' );

					// Do not open the dropdown.
					event.preventDefault();
				} );
		}

		// Propagating original input classes to the select2 container.
		const originalClasses = obj.getSelectClasses( $select ).join( ' ' );
		$container.data( 'select2' ).$container.addClass( originalClasses );

		// Propagating original input classes to the select2 container.
		$container.data( 'select2' ).$container.removeClass( 'hide-before-select2-init' );

		$container.on( 'select2:open', obj.action_select2_open );

		/**
		 * @todo @bordoni Investigate how and if we should be doing this.
		 *
		  if ( carryOverData.length > 0 ) {
		  carryOverData.map( function( dataKey ) {
		  var attr = 'data-' + dataKey;
		  var val = $select.attr( attr );

		  if ( ! val ) {
		  return;
		  }

		  this.attr( attr, val );
		  }, $container );
		  }
		 */
	};

	obj.ajaxurl = function () {
		if ( 'undefined' !== typeof window.ajaxurl ) {
			return window.ajaxurl;
		}

		if ( 'undefined' !== typeof TEC && 'undefined' !== typeof TEC.ajaxurl ) {
			return TEC.ajaxurl;
		}

		console.error(
			'Dropdowns framework cannot properly do an AJAX request without the WordPress `ajaxurl` variable setup.'
		); // eslint-disable-line max-len
	};

	obj.action_select2_open = function ( event ) {
		// eslint-disable-line no-unused-vars
		const $select = $( this );
		const select2Data = $select.data( 'select2' );
		const $search = select2Data.$dropdown.find( obj.selector.searchField ); // eslint-disable-line es5/no-es6-methods,max-len

		const originalClasses = obj.getSelectClasses( select2Data.$element ).reduce( function ( prev, curr ) {
			if ( 'hide-if-js' === curr ) {
				return prev;
			}

			if ( 'tribe-dropdown-created' === curr ) {
				return prev;
			}

			return prev + ' ' + curr;
		} );

		select2Data.$dropdown.addClass( originalClasses );

		// If we have a placeholder for search, apply it!
		if ( $select.is( '[data-search-placeholder]' ) ) {
			$search.attr( 'placeholder', $select.data( 'searchPlaceholder' ) );
		}
	};

	/**
	 * Configure the Drop Down Fields
	 *
	 * @param {jQuery} $fields All the fields from the page
	 * @param {Array}  args    Allow extending the arguments
	 *
	 * @return {jQuery}         Affected fields
	 */
	obj.dropdown = function ( $fields, args ) {
		const $elements = $fields.not( '.select2-offscreen, .select2-container, ' + obj.selector.created.className() ); // eslint-disable-line max-len

		if ( 0 === $elements.length ) {
			return $elements;
		}

		// Default args to avoid Undefined
		if ( ! args ) {
			args = {};
		}

		$elements.each( function ( index, element ) {
			// Apply element to all given items and pass args
			obj.element( element, args );
		} );

		// return to be able to chain jQuery calls
		return $elements;
	};

	$( function () {
		$( obj.selector.dropdown ).tribe_dropdowns();
	} );

	// Addresses some problems with Select2 inputs not being initialized when using a browser's "Back" button.
	$( window ).on( 'unload', function () {
		$( obj.selector.dropdown ).tribe_dropdowns();
	} );
} )( jQuery, window.tribe_dropdowns, window.underscore || window._ );
