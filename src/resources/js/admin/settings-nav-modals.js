( function ($) {
	document.addEventListener("DOMContentLoaded", function() {
		const $dialog = $('#tec-settings__nav-modal');
		const $buttonOpen = $('.tec-modal__control--open');
		const $buttonClose = $('.tec-modal__control--close');
		const $modalNav  = $('#tec-settings-modal-nav');
		const $subnavLinks = $modalNav.find('.tec-nav__tab--has-subnav > .tec-nav__link');
		const $sidebarToggle = $( '#tec-settings__sidebar-toggle' );
		const $sidebar = $( '#tec-settings__sidebar-modal' );

		const init = () => {
			addNavHandlers();
			addSidebarHandlers();
		}

		const addNavHandlers = () => {
			$buttonOpen.on('click', modalHandlers.open );
			$buttonClose.on('click', modalHandlers.close );
		};

		const modalHandlers = {
			open: function() {
				$dialog[0].showModal();
				$subnavLinks.on('click', toggleSubnav );
			},
			close: function() {
				$subnavLinks.off('click', toggleSubnav );
				$dialog[0].close();
			}
		};

		const toggleSubnav = ( event ) => {
			event.preventDefault();

			const $target = $( event.target );
			$subnavLinks.not( $target ).parent().removeClass('tec-nav__tab--subnav-active');

			$target.parent().toggleClass('tec-nav__tab--subnav-active');
		}

		const addSidebarHandlers = () => {
			$sidebarToggle.on('click', sidebarHandlers.open );
		}

		const sidebarHandlers = {
			open: function(event) {
				event.preventDefault();
				$sidebar[0].showModal();
			},
			close: function(event) {
				event.preventDefault();
				$sidebar[0].close();
			}
		};

		$( document ).ready( init )
	});
} )(jQuery);
