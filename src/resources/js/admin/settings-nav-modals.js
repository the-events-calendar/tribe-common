( function ($) {
	document.addEventListener("DOMContentLoaded", function() {
		const $dialog = $('#tec-settings-nav-modal');
		const $buttonOpen = $('#tec-settings-nav-modal-open');
		const $buttonClose = $('#tec-settings-nav-modal-close');
		const $modalNav  = $('#tec-settings-modal-nav');
		const $subnavLinks = $modalNav.find('.tec-nav__tab--has-subnav > .tec-nav__link');
		const $sidebarOpen = $( '#tec-settings-sidebar-modal-open' );
		const $sidebarClose = $( '#tec-settings-sidebar-modal-close' );
		const $modalSidebar = $( '#tec-settings-form__sidebar-modal' );

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
			$sidebarOpen.on('click', sidebarHandlers.open );
			$sidebarClose.on('click', sidebarHandlers.close );
		}

		const sidebarHandlers = {
			open: function( event ) {
				event.preventDefault();
				event.stopPropagation();
				$modalSidebar[0].showModal();
			},
			close: function() {
				$modalSidebar[0].close();
			}
		};

		$( document ).ready( init )
	});
} )(jQuery);
