<?php
/**
 * Template for the Help Hub header section.
 *
 * @since 6.8.0
 *
 * @var Tribe__Main $main The main common object.
 */

// No direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Filter the logo source URL for the Help Hub header.
 *
 * @since 6.8.0
 *
 * @param string $src The default logo source URL.
 *
 * @return string The filtered logo source URL.
 */
$logo_src = apply_filters( 'tec_help_hub_header_logo_src', tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, $main ) );

/**
 * Filter the logo alt text for the Help Hub header.
 *
 * @since 6.8.0
 *
 * @param string $alt The default logo alt text.
 *
 * @return string The filtered logo alt text.
 */
$logo_alt = apply_filters( 'tec_help_hub_header_logo_alt', __( 'The Events Calendar logo', 'tribe-common' ) );

?>
<div class="tribe-notice-wrap">
	<?php
	/**
	 * Trigger the conditional content header notice.
	 *
	 * @since 6.8.2
	 */
	do_action( 'tec_conditional_content_header_notice' );
	?>
	<div class="wp-header-end"></div>
</div>
<div class="tec-settings-header-wrap">
<h1>
	<img
		class="tribe-events-admin-title__logo"
		src="<?php echo esc_url( $logo_src ); ?>"
		alt="<?php echo esc_attr( $logo_alt ); ?>"
		role="presentation"
		id="tec-settings-logo"
	/>
	Help
</h1>
</div>
