<?php
/**
 * Upsell Template
 * The base template for TEC Upsell notices.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/upsell/upsell.php
 *
 * @since TBD
 *
 * @var string        $icon_url  URL to icon.
 * 
 * @version TBD
 */

?>
<img
	class="tec-admin__upsell-icon-image"
	src="<?php echo esc_url( $icon_url ); ?>"
	alt="<?php esc_attr_e( 'The Events Calendar important notice icon', 'tribe-common' ); ?>"
/>