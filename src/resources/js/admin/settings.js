/* eslint-disable es5/no-arrow-functions */
/* eslint-disable linebreak-style */
/**
 * Makes sure we have all the required levels on the Tribe Object.
 *
 * @since 5.0.0
 *
 * @type {PlainObject}
 */
 tribe.settings = tribe.settings || {};

 ( function ( obj ) {
	'use strict';

	obj.init = () => {
		obj.tablistNode = document.getElementById( 'tribe-settings-tabs' );

		if ( ! obj.tablistNode ) {
			return;
		}

		obj.tabs = [];

		obj.firstTab = null;
		obj.lastTab = null;

		obj.tabs = obj.tablistNode.querySelectorAll('[role=tab]');

		obj.tabs.forEach((tab) => {
			tab.tabIndex = -1;
			tab.setAttribute('aria-selected', 'false');

			// Add event listeners.
			tab.addEventListener('keydown', obj.onKeydown.bind(obj));
			tab.addEventListener('click', obj.onClick.bind(obj));

			if (! obj.firstTab) {
				obj.firstTab = tab;
			}

			obj.lastTab = tab;
		});

		const liveTab = obj.getSelectedTab();

		obj.setSelectedTab( liveTab );

		// When clicking on the header save button, just "transfer" the click to the bottom one.
		// Then we don't have to alter/duplicate event listeners, etc.
		document.querySelector('.tec-save-settings').addEventListener(
			'click',
			() => { document.getElementById('tribeSaveSettings').click(); },
			false
		);
	};

	obj.getSelectedTab = () => {
		const urlParams = new URLSearchParams(window.location.search);
		let liveTab = urlParams.get('tab');

		if (! liveTab) {
			obj.tabs.forEach ((tab) => {
				if ( tab.classList.contains( 'nav-tab-active' ) ) {
					liveTab = tab;
				}
			});
		}

		return liveTab;
	};

	/**
	 * Since we are not loading all tab content initially,
	 * and not using links for tabs, we need to do a redirect on tab selection.
	 */
	obj.setSelectedTab = (currentTab) => {
		const liveTab = obj.getSelectedTab();

		obj.tabs.forEach ((tab) => {
			if (currentTab === tab.value ) {
				tab.setAttribute('aria-selected', 'true');
				tab.removeAttribute('tabindex');
			} else {
				tab.setAttribute('aria-selected', 'false');
				tab.tabIndex = -1;
			}
		});

		// If we are not on the selected tab, redirect.
		if ( liveTab && liveTab.id !== currentTab.value ) {
			let url = new URL( window.location.toString() );

			if ( ! new URLSearchParams(window.location.search).get('tab') ) {
				// 'tab' is not set - add it.
				url.searchParams.append('tab', currentTab.value);
			} else {
				// 'tab' is set - change it.
				url.searchParams.set('tab', currentTab.value);
			}

			// ...and redirect.
			window.location = url;
		}
	};

	obj.moveFocusToPreviousTab = (currentTab) => {
		if (currentTab === obj.firstTab) {
			obj.lastTab.focus();
		} else {
			currentTab.previousElementSibling.focus();
		}
	};

	obj.moveFocusToNextTab = (currentTab) => {
		if (currentTab === obj.lastTab) {
			obj.firstTab.focus();
		} else {
			currentTab.nextElementSibling.focus();
		}
	};

	/* EVENT HANDLERS */

	obj.onKeydown = (event) => {
		var tgt = event.currentTarget,
			flag = false;

		switch (event.key) {
			case 'ArrowLeft':
				obj.moveFocusToPreviousTab(tgt);
				flag = true;
				break;

			case 'ArrowRight':
				obj.moveFocusToNextTab(tgt);
				flag = true;
				break;

			case 'Home':
				obj.firstTab.focus();
				flag = true;
				break;

			case 'End':
				obj.lastTab.focus();
				flag = true;
				break;

			default:
				break;
		}

		if (flag) {
			event.stopPropagation();
			event.preventDefault();
		}
	};

	// Since we're using buttons for the tabs, the click is also activated with the space and enter keys.
	obj.onClick = (event) => {
		obj.setSelectedTab(event.currentTarget);
	};

	window.onload = () => {
		obj.init();
	};

} ) ( tribe.settings );
