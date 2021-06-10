<?php
/**
 * The template that displays the help page.
 */

$main = Tribe__Main::instance();

// Fetch the Help page Instance
$help = tribe(Tribe__Admin__Help_Page::class);

// get the products list
$products = tribe('plugins.api')->get_products();

//echo '<pre>' . print_r( $products, true ) . '</pre>';
?>

<div class="tribe-events-admin-header">
	<?php do_action('tec-admin-notice-area', 'help'); ?>
	<div class="tribe-events-admin-header__content-wrapper">

		<img
			class="tribe-events-admin-header__logo-word-mark"
			src="<?php echo esc_url(tribe_resource_url('images/logo/tec-brand.svg', false, null, $main)); ?>"
			alt="<?php esc_attr_e('The Events Calendar brand logo', 'tribe-common'); ?>"
		/>

		<img
			class="tribe-events-admin-header__right-image"
			src="<?php echo esc_url(tribe_resource_url('images/help/help-hero-header.png', false, null, $main)); ?>"
		/>

		<h2 class="tribe-events-admin-header__title"><?php esc_html_e('Help', 'tribe-common'); ?></h2>
		<p class="tribe-events-admin-header__description"><?php esc_html_e('We\'re committed to helping make your calendar spectacular and have a wealth of resources available.', 'tribe-common'); ?></p>

		<ul class="tribe-events-admin-tab-nav">
			<li class="tribe-events-admin-tab-nav__link-item tribe-events-admin-tab-nav__link-item--selected" data-tab="tec-help-calendar"><?php esc_html_e('Calendar', 'tribe-common'); ?></li>
			<li class="tribe-events-admin-tab-nav__link-item" data-tab="tec-help-ticketing"><?php esc_html_e('Ticketing & RSVP', 'tribe-common'); ?></li>
			<li class="tribe-events-admin-tab-nav__link-item" data-tab="tec-help-community"><?php esc_html_e('Community', 'tribe-common'); ?></li>
			<li class="tribe-events-admin-tab-nav__link-item" data-tab="tec-help-troubleshooting"><?php esc_html_e('Troubleshooting', 'tribe-common'); ?></li>
		</ul>
	</div>
</div>


<div class="tribe-events-admin-content-wrapper">

	<?php

    // Calendar Tab
    include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/help-calendar.php';

    // Ticketing & RSVP Tab
    include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/help-ticketing.php';

    // Ticketing & RSVP Tab
    include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/help-community.php';
    ?>

	<?php // Shared footer area?>
	<div class="tribe-events-admin-cta">
		<img
			class="tribe-events-admin-cta__image"
			src="<?php echo esc_url(tribe_resource_url('images/help/troubleshooting.png', false, null, $main)); ?>"
			alt="<?php esc_attr_e('Graphic with an electrical plug and gears', 'tribe-common'); ?>"
		/>

		<div class="tribe-events-admin-cta__content">
			<div class="tribe-events-admin-cta__content-title">
				<?php esc_html_e('Need additional support?', 'tribe-common'); ?>
			</div>

			<div class="tribe-events-admin-cta__content-description">
				<a href="#troubleshooting">
					<?php esc_html_e('Visit Troubleshooting next', 'tribe-common'); ?>
				</a>
			</div>
		</div>
	</div>

	<img
		class="tribe-events-admin-footer-logo"
		src="<?php echo esc_url(tribe_resource_url('images/logo/tec-brand.svg', false, null, $main)); ?>"
		alt="<?php esc_attr_e('The Events Calendar brand logo', 'tribe-common'); ?>"
	/>
</div>

<?php // this is inline jQuery / javascript for extra simplicity */?>
<script type="text/javascript">
	jQuery( document ).ready( function($) {
		var current_tab = "#tribe-calendar";
		$( 'body' ).on( "click", ".tribe-events-admin-tab-nav__link-item", function() {
			var tab = "#" + $( this ).data( "tab" );
			$( current_tab ).hide();
			$( '.tribe-events-admin-tab-nav__link-item' ).removeClass( "tribe-events-admin-tab-nav__link-item--selected" );
			$( this ).addClass( "tribe-events-admin-tab-nav__link-item--selected" );

			$( tab ).show();
			current_tab = tab;
		} );
	} );
</script>