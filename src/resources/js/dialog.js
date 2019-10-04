var tribe = tribe || {};
var tribe_ev = tribe_ev || {};
tribe.dialogs = tribe.dialogs || {};

	( function ( obj ) {
		'use strict';

		document.addEventListener(
			'DOMContentLoaded',
			function () {
				tribe.dialogs.forEach(function(dialog) {
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
						event.preventDefault();
						event.stopPropagation();

						jQuery( tribe_ev.events ).trigger( dialog.showEvent, [dialogEl, event] );
					});

					window[objName].on('hide', function (dialogEl, event) {
						event.preventDefault();
						event.stopPropagation();

						jQuery( tribe_ev.events ).trigger( dialog.closeEvent, [dialogEl, event] );
					});
				});
			}
		)
	})(tribe.dialog);
