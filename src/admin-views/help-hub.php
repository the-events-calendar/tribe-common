<?php
/**
 * The template that displays the help page.
 */


?>
<div class="tribe_settings wrap tec-events-admin-settings">

	<h1>
		<img src="/wp-content/plugins/the-events-calendar/common/src/resources/images/logo/the-events-calendar.svg"
			 alt="" role="presentation" id="tec-settings-logo">
		Help 			</h1>
	<nav class="tec-nav__wrapper tec-settings__nav-wrapper tec-nav__wrapper--subnav-active">
		<ul class="tec-nav">

			<li data-tab-target="tec-help-tab" class="tec-nav__tab tec-nav__tab--general tec-nav__tab--has-subnav tec-nav__tab--subnav-active">
				<a class="tec-nav__link">Support Hub</a>

			</li>
			<li data-tab-target="tec-resources-tab" class="tec-nav__tab tec-nav__tab--general tec-nav__tab--has-subnav ">
				<a class="tec-nav__link" >Resources</a>

			</li>
		</ul>
	</nav>



	<div id="tec-help-tab" class="tribe-settings-form form tec-tab-container">
		<form id="tec-settings-form" class="tec-settings-form__display-calendar-tab-tab--active tec-settings-form__subnav-active" method="post">
			<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal">
				<h2 class="tec-settings-form__section-header">Resources</h2>
				<p class="tec-settings-form__section-description">
					todo 1
				</p>
			</div>
			<div class="tec-settings-form__content-section">
				<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">todo</h3>
			</div>
		</form>
	</div>

	<div id="tec-resources-tab" class="tribe-settings-form form tec-tab-container">
		<form id="tec-settings-form" class="tec-settings-form__display-calendar-tab-tab--active tec-settings-form__subnav-active" method="post">
			<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal">
				<h2 class="tec-settings-form__section-header">Support Hub</h2>
				<p class="tec-settings-form__section-description">
					todo 2
				</p>
			</div>
			<div class="tec-settings-form__content-section">
				<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">todo</h3>
			</div>
		</form>
	</div>
</div>


<script>
	jQuery( document ).ready( function($) {
		let currentTab = $( 'li.tec-nav__tab.tec-nav__tab--subnav-active' );
		let tabContainer = $( '#' + currentTab.data( 'tab-target' ) );
		$( '.tec-tab-container' ).hide();
		tabContainer.show();

		$( 'li.tec-nav__tab' ).on( 'click', function() {
			let tab = $( this );
			let tabTarget = $( '#' + tab.data( 'tab-target' ) );

			$( 'li.tec-nav__tab' ).removeClass( 'tec-nav__tab--subnav-active' );
			tab.addClass( 'tec-nav__tab--subnav-active' );

			tabContainer.hide();
			tabTarget.show();
			tabContainer = tabTarget;
		} );
	} );
</script>

