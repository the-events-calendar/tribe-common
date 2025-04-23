<?php
/**
 * Template for the Help Hub header section.
 *
 * @since TBD
 *
 * @var Tribe__Main $main The main common object.
 */

?>
<div class="tribe-notice-wrap">
	<div class="wp-header-end"></div>
</div>
<h1>
	<img
		class="tribe-events-admin-title__logo"
		src="<?php echo esc_url( tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, $main ) ); ?>"
		alt="<?php esc_attr_e( 'The Events Calendar logo', 'tribe-common' ); ?>"
		role="presentation"
		id="tec-settings-logo"
	/>
	Help
</h1>
