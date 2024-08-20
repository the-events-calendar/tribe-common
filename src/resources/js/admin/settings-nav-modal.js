( function ($) {
	document.addEventListener("DOMContentLoaded", function() {
		const $dialog = $('#tec-settings__nav-modal');
		const $buttonOpen = $('.tec-modal__control--open');
		const $buttonClose = $('.tec-modal__control--close');
		const $modalNav  = $('#tec-settings-modal-nav');
		const $subnavLinks = $modalNav.find('.tec-nav__tab--has-subnav > .tec-nav__link');

		$buttonOpen.on('click', () => {
			$dialog[0].showModal();

			$subnavLinks.on('click', toggleSubnav );
		});

		$buttonClose.on('click', () => {
			$subnavLinks.off('click', toggleSubnav );

			$dialog[0].close();
		});

		function toggleSubnav( event ) {
			event.preventDefault();

			const $target = $( event.target );
			$subnavLinks.not( $target ).parent().removeClass('tec-nav__tab--subnav-active');

			$target.parent().toggleClass('tec-nav__tab--subnav-active');
		}
	});
} )(jQuery);
