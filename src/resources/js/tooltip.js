window.tribe = window.tribe || {}; // eslint-disable-line no-redeclare
window.tribe.tooltip = window.tribe.tooltip || {};

( function ( $, obj ) {
	'use strict';

	const $document = $( document );

	/**
	 * Object containing the relevant selectors
	 *
	 * @since 4.9.12
	 *
	 * @return {Object}
	 */
	obj.selectors = {
		tooltip: '.tribe-tooltip',
		active: 'active',
	};

	/**
	 * Setup the live listener to anything that lives inside of the document
	 * that matches the tooltip selector for a click action.
	 *
	 * @since 4.9.12
	 *
	 * @return {void}
	 */
	obj.setup = function () {
		$document.on( 'click', obj.selectors.tooltip, obj.onClick );

		$document.on( 'click', function ( event ) {
			const tooltip = event.target.closest( obj.selectors.tooltip );
			if ( ! tooltip ) {
				$( obj.selectors.tooltip ).each( function () {
					$( this ).removeClass( obj.selectors.active ).attr( 'aria-expanded', false );
				} );
			}
		} );
	};

	/**
	 * When a tooltip is clicked we setup A11y for the element
	 *
	 * @since 4.9.12
	 *
	 * @return {void}
	 */
	obj.onClick = function () {
		const $tooltip = $( this ).closest( obj.selectors.tooltip );
		const add = ! $tooltip.hasClass( obj.selectors.active );

		$( obj.selectors.tooltip ).each( function () {
			$( this ).removeClass( obj.selectors.active ).attr( 'aria-expanded', false );
		} );

		if ( add ) {
			$( $tooltip ).addClass( obj.selectors.active ).attr( 'aria-expanded', true );
		}
	};

	$( obj.setup );
} )( jQuery, window.tribe.tooltip );
