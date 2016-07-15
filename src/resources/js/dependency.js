(function( $, _ ) {
	'use strict';
	var $document = $( document ),
		selectors = {
			dependent: '.tribe-dependent',
			active: '.tribe-active',
			dependency: '.tribe-dependency',
			fields: 'input, select, textarea',
			advanced_fields: '.select2-container'
		};

	// Setup a Dependent
	$.fn.dependency = function () {
		this.each( function(){
			var selector = $( this ).data( 'depends' );
			$( selector ).addClass( selectors.dependency.replace( '.', '' ) ).data( 'dependent', $( this ) );
		} );
	};

	$document
		// Prevents double global actions
		.off( 'change.dependency verify.dependency', selectors.dependency )
		.on( {
			'verify.dependency': function( e ) {
				var $field = $( this ),
					selector = '#' + $field.attr( 'id' ),
					value = $field.val();

				// We need an ID to make something depend on this
				if ( ! selector ) {
					return;
				}

				// Fetch dependent elements
				var $dependents = $document.find( '[data-depends="' + selector + '"]' );

				$dependents.each( function( k, dependent ) {
					var $dependent = $( dependent ),
						condition = $dependent.data( 'condition' ),
						not_condition = $dependent.data( 'conditionNot' ),
						is_not_empty = $dependent.data( 'conditionNotEmpty' ) || $dependent.is( '[data-condition-not-empty]' ),
						is_empty = $dependent.data( 'conditionEmpty' ) || $dependent.is( '[data-condition-empty]' ),
						is_disabled = $field.is( ':disabled' ),
						active_class = selectors.active.replace( '.', '' );

					if (
						(
							( is_empty && '' == value )
							|| ( is_not_empty && '' != value )
							|| ( _.isArray( condition ) && -1 !== _.findIndex( condition, value ) )
							|| ( 'undefined' !== typeof condition && value == condition )
							|| ( 'undefined' !== typeof not_condition && value != not_condition )
						) && ! is_disabled
					) {
						$dependent
							.addClass( active_class )
							.find( selectors.fields ).prop( 'disabled', false )
							.end().find( '.select2-container' ).select2( 'enable', false );

						if ( $( '#s2id_' + $dependent.attr( 'id' ) ).length ) {
							$( '#s2id_' + $dependent.attr( 'id' ) ).addClass( active_class );
						}
					} else {
						$dependent
							.removeClass( active_class )
							.find( selectors.fields ).prop( 'disabled', true )
							.end().find( '.select2-container' ).select2( 'enable', true );

						if ( $( '#s2id_' + $dependent.attr( 'id' ) ).length ) {
							$( '#s2id_' + $dependent.attr( 'id' ) ).removeClass( active_class );
						}
					}

					// Checks if any child elements have dependencies
					$dependent.find( selectors.dependency ).trigger( 'change' );
				} );
			},
			'change.dependency': function( e ) {
				$( this ).trigger( 'verify.dependency' );
			}
		}, selectors.dependency )

	// Configure on Document ready for the default trigger
	$document.ready( function() {
		$( selectors.dependent ).dependency();
		$( selectors.dependency ).trigger( 'verify.dependency' );
	} );
}( jQuery, _ ) );
