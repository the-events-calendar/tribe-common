var tribe = tribe || {};
tribe.dialogs = tribe.dialogs || {};

( function( $, obj ) {
	'use strict';

	var $document = $( document );
	tribe.dialogs.dialogs = tribe.dialogs.dialogs || [];
	tribe.dialogs.events = tribe.dialogs.events || {};

	/**
	 * Get the dialog name.
	 *
	 * @since TBD
	 *
	 * @param {obj} dialog The dialog object
	 *
	 * @return {void}
	 */
	obj.getDialogName = function( dialog ) {
		return 'dialog_obj_' + dialog.id;
	};

	/**
	 * Initialize tribe dialogs.
	 *
	 * @since TBD
	 *
	 * @return {void}
	 */
	obj.init = function() {
		tribe.dialogs.dialogs.forEach( function( dialog ) {
			var objName       = obj.getDialogName( dialog );
			window[ objName ] = new window.A11yDialog( {
				appendTarget: dialog.appendTarget,
				bodyLock: dialog.bodyLock,
				closeButtonAriaLabel: dialog.closeButtonAriaLabel,
				closeButtonClasses: dialog.closeButtonClasses,
				contentClasses: dialog.contentClasses,
				effect: dialog.effect,
				effectEasing: dialog.effectEasing,
				effectSpeed: dialog.effectSpeed,
				overlayClasses: dialog.overlayClasses,
				overlayClickCloses: dialog.overlayClickCloses,
				trigger: dialog.trigger,
				wrapperClasses: dialog.wrapperClasses
			} );

			window[ objName ].on( 'show', function( dialogEl, event ) {
				if ( event ) {
					event.preventDefault();
					event.stopPropagation();
				}

				$( tribe.dialogs.events ).trigger( dialog.showEvent, [ dialogEl, event ] );
			} );

			window[ objName ].on( 'hide', function ( dialogEl, event ) {
				if ( event ) {
					event.preventDefault();
					event.stopPropagation();
				}

				$( tribe.dialogs.events ).trigger( dialog.closeEvent, [ dialogEl, event ] );
			} );
		} );

	};

	$document.ready( obj.init );

} )( jQuery, tribe.dialogs );
