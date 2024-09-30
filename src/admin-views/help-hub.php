<?php
/**
 * The template that displays the help page.
 *
 * @var Tribe__Main $main The main common object.
 */

?>
<div class="tribe_settings wrap tec-events-admin-settings">
	<h1>
		<img
			class="tribe-events-admin-title__logo"
			src="<?php echo esc_url( tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, $main ) ); ?>"
			alt="<?php esc_attr_e( 'The Events Calendar logo', 'the-events-calendar' ); ?>"
			role="presentation"
			id="tec-settings-logo"
		/>
		Help
	</h1>
	<nav class="tec-settings__nav-wrapper">
		<ul class="tec-nav">
			<li data-tab-target="tec-help-tab" class="tec-nav__tab tec-nav__tab--subnav-active">
				<a class="tec-nav__link">Support Hub</a>
			</li>
			<li data-tab-target="tec-resources-tab" class="tec-nav__tab">
				<a class="tec-nav__link">Resources</a>
			</li>
		</ul>
	</nav>

	<div id="tec-help-tab" class="tec-tab-container">
		<?php $this->template( 'help-hub/support-hub' ); ?>
	</div>

	<div id="tec-resources-tab" class="tec-tab-container">
		<?php $this->template( 'help-hub/resources' ); ?>
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

