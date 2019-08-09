var tribe = tribe || {};
tribe.dialog = tribe.dialog || {};

( function ( obj ) {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var dialog = new window.A11yDialog({
			appendTarget: 'body',
			bodyLock: true, // lock the body while dialog open?
			closeButtonAriaLabel: 'Close this dialog window', // aria label for close button
			closeButtonClasses: 'tribe-dialog__close-button', // classes for close button
			contentClasses: 'tribe-dialog__content', // dialog content classes
			effect: 'none',
			effectEasing: 'ease-in-out', // a css easing string
			effectSpeed: 300,
			overlayClasses: 'tribe-dialog__overlay', // overlay classes
			overlayClickCloses: true, // clicking overlay closes dialog
			trigger: '[data-js="trigger-newsletter-signup"]', // can be node or selector string
			wrapperClasses: 'tribe-dialog', // the wrapper class for the dialog
		});

		dialog.on('show', function (dialogEl, triggerEl) {
			console.log(dialogEl);
			console.log(triggerEl);
		});

		dialog.on('render', function (dialogEl, triggerEl) {
			console.log('rendered');
			console.log(dialogEl);
			console.log(triggerEl);
		});
	});

} )( tribe.dialog );
