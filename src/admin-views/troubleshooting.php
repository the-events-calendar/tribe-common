<?php
/**
 * The template that displays the help page.
 */

$main = Tribe__Main::instance();

// Fetch the Help page Instance
$help = tribe( Tribe__Admin__Help_Page::class );

// get the products list
$products = $this->get_all_products();

?>

<div class="tribe-events-admin-content-wrapper">

	<?php do_action( 'tec-admin-notice-area', 'troubleshooting' ); ?>

	<img
		class="tribe-events-admin-graphic tribe-events-admin-graphic--desktop-only"
		src="<?php echo esc_url( tribe_resource_url( 'images/header/troubleshooting-desktop.jpg', false, null, $main ) ); ?>"
		alt="<?php esc_attr_e( 'Shapes and lines for visual interest', 'tribe-common' ); ?>"
	/>

	<div class="tribe-events-admin-title">
		<img
			src="<?php echo esc_url( tribe_resource_url( 'images/logo/tec-brand.svg', false, null, $main ) ); ?>"
			alt="<?php esc_attr_e( 'The Events Calendar brand logo', 'tribe-common' ); ?>"
		/>
	</div>
	<h2 class="tribe-events-admin-heading"><?php esc_html_e( 'Troubleshooting', 'tribe-common' ); ?></h2>
	<p class="tribe-events-admin-description"><?php esc_html_e( 'We\'re committed to helping make your calendar spectacular and have a wealth of resources available', 'tribe-common' ); ?></p>

	<div class="tribe-events-admin-tab-nav">
		<ul class="tribe-events-admin-tab-nav__links">
			<li class="tribe-events-admin-tab-nav__link-item" data-tab="tec-help-calendar"><?php esc_html_e( 'Calendar', 'tribe-common' ); ?></li>
			<li class="tribe-events-admin-tab-nav__link-item" data-tab="tec-help-ticketing"><?php esc_html_e( 'Ticketing & RSVP', 'tribe-common' ); ?></li>
			<li class="tribe-events-admin-tab-nav__link-item" data-tab="tec-help-community"><?php esc_html_e( 'Community', 'tribe-common' ); ?></li>
			<li class="tribe-events-admin-tab-nav__link-item tribe-events-admin-tab-nav__link-item--selected" data-tab="tec-help-troubleshooting"><?php esc_html_e( 'troubleshooting', 'tribe-common' ); ?></li>
		</ul>
	</div>

	<?php // Troubleshooting Tab ?>
	<div id="tec-help-troubleshooting">

	<?php // Shared footer area ?>
	<div class="tribe-events-admin-card tribe-events-admin-card--1up">
			<div class="tribe-events-admin-card__title"><?php esc_html_e( 'Need additional support?', 'tribe-common' ); ?></div>
			<div class="tribe-events-admin-card__link" href="#troubleshooting"><?php esc_html_e( 'Visit Troubleshooting next', 'tribe-common' ); ?></div>
	</div>

	<img
		class="tribe-events-admin-footer-logo"
		src="<?php echo esc_url( tribe_resource_url( 'images/logo/tec-brand.svg', false, null, $main ) ); ?>"
		alt="<?php esc_attr_e( 'The Events Calendar brand logo', 'tribe-common' ); ?>"
	/>

</div>


<?php

/*
// Creates the System Info section
$help->add_section( 'system-info', __( 'System Information', 'tribe-common' ), 30 );
$help->add_section_content( 'system-info', __( 'The details of your calendar plugin and settings is often needed for you or our staff to help troubleshoot an issue. Please opt-in below to automatically share your system information with our support team. This will allow us to assist you faster if you post in our help desk.', 'tribe-common' ), 0 );

$help->add_section_content(
	'system-info',
	Tribe__Support::opt_in(),
	10
);

$help->add_section_content( 'system-info', '<div class="system-info-copy"><button data-clipboard-action="copy" class="system-info-copy-btn" data-clipboard-target=".support-stats" ><span class="dashicons dashicons-clipboard license-btn"></span>' .  __( 'Copy to clipboard', 'tribe-common' ) . '</button></div>', 10 );

$help->add_section( 'template-changes', __( 'Recent Template Changes', 'tribe-common' ), 40 );
$help->add_section_content( 'template-changes', Tribe__Support__Template_Checker_Report::generate() );

$help->add_section( 'event-log', __( 'Event Log', 'tribe-common' ), 50 );
$help->add_section_content( 'event-log', tribe( 'logger' )->admin()->display_log() );
*/
?>


<?php // this is inline jQuery / javascript for extra simplicity */ ?>
<script type="text/javascript">
	jQuery( document ).ready( function($) {
		var current_tab = "#tec-help-calendar";
		$( 'body' ).on( "click", ".tribe-events-admin-tab-nav__link-item", function() {
			var tab = "#" + $( this ).data( "tab" );
			document.location = 'somewhere else!';
			/*
			$( current_tab ).hide();
			$( '.tribe-events-admin-tab-nav__link-item' ).removeClass( "tribe-events-admin-tab-nav__link-item--selected" );
			$( this ).addClass( "tribe-events-admin-tab-nav__link-item--selected" );

			$( tab ).show();
			current_tab = tab;
			*/
		} );
	} );
</script>