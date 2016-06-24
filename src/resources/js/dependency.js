(function( $, _ ) {
	'use strict';
	var $document = $( document ),
		selectors = {
			dependent: '.tribe-dependent',
			active: '.tribe-active',
			dependency: '.tribe-dependency',
		};

	// Setup a Dependent
	$.fn.dependency = function () {
		this.each( function(){
			var selector = $( this ).data( 'depends' );
			$( selector ).addClass( selectors.dependency.replace( '.', '' ) );
		} );
	};

	$document
		// Prevents double global actions
		.off( 'change.dependency' )
		.on( {
			'change.dependency': function( e ) {
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
						is_not_empty = $dependent.data( 'conditionNotEmpty' ) || $dependent.is( '[data-condition-not-empty]' ),
						is_empty = $dependent.data( 'conditionEmpty' ) || $dependent.is( '[data-condition-empty]' );

					if ( is_empty && '' == value ) {
						$dependent.addClass( selectors.active.replace( '.', '' ) );
					} else if ( is_not_empty && '' != value ) {
						$dependent.addClass( selectors.active.replace( '.', '' ) );
					} else if ( _.isArray( condition ) && -1 !== _.findIndex( condition, value ) ) {
						$dependent.addClass( selectors.active.replace( '.', '' ) );
					} else if ( value == condition ) {
						$dependent.addClass( selectors.active.replace( '.', '' ) );
					} else {
						$dependent.removeClass( selectors.active.replace( '.', '' ) );
					}
				} );
			}
		}, selectors.dependency )

	// Configure on Document ready for the default trigger
	$document.ready( function() {
		$( selectors.dependency ).trigger( 'change.dependency' );
		$( selectors.dependent ).dependency();
	} );
}( jQuery, _ ) );