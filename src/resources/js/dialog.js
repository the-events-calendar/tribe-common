var tribe = tribe || {};
tribe.dialogs = tribe.dialogs || {};
tribe.dialogs.dialogs = tribe.dialogs.dialogs || {};
tribe.dialogs.events = tribe.dialogs.events || {};

	( function ( obj ) {
		'use strict';

		document.addEventListener(
			'DOMContentLoaded',
			function () {
				tribe.dialogs.dialogs.forEach(function(dialog) {
					var objName     = 'dialog_obj_' + dialog.id;
					window[objName] = new window.A11yDialog({
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
					});

					window[objName].on('show', function (dialogEl, event) {
						if ( event ) {
							event.preventDefault();
							event.stopPropagation();
						}

						jQuery( tribe.dialogs.events ).trigger( dialog.showEvent, [dialogEl, event] );
					});

					window[objName].on('hide', function (dialogEl, event) {
						if ( event ) {
							event.preventDefault();
							event.stopPropagation();
						}

						jQuery( tribe.dialogs.events ).trigger( dialog.closeEvent, [dialogEl, event] );
					});
				});
			}
		)
	})(tribe.dialog);
