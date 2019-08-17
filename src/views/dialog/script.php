<?php
/**
 * Dialog Script View Template
 * The base script template for tribe-dialog.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/dialogs/script.php
 *
 * @since TBD
 *
 * @package Tribe
 * @version TBD
 */
?>
var <?php echo esc_html( $template ); ?> = new window.A11yDialog({
	appendTarget: '<?php echo esc_html( $append_target ); ?>',
	bodyLock: '<?php echo esc_html( $body_lock ); ?>',
	closeButtonAriaLabel: '<?php echo esc_html( $close_button_aria_label ); ?>',
	closeButtonClasses: '<?php echo esc_html( $close_button_classes ); ?>',
	contentClasses: '<?php echo esc_html( $content_wrapper_classes ); ?>',
	effect: '<?php echo esc_html( $effect ); ?>',
	effectEasing: '<?php echo esc_html( $effect_easing ); ?>',
	effectSpeed: '<?php echo esc_html( $effect_speed ); ?>',
	overlayClasses: '<?php echo esc_html( $overlay_classes ); ?>',
	overlayClickCloses: '<?php echo esc_html( $overlay_click_closes ); ?>',
	trigger: '[data-js="<?php echo esc_attr( 'trigger-dialog-' . $id ); ?>"]',
	wrapperClasses: '<?php echo esc_attr($wrapper_classes ); ?>',
});
