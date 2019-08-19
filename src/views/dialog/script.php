<?php
/**
 * Dialog Script View Template
 * The base script template for tribe-dialog.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/script.php
 *
 * @since TBD
 *
 * @package Tribe
 * @version TBD
 */
?>
<script>
	var tribe = tribe || {};
		tribe.dialogs = tribe.dialogs || [];

	tribe.dialogs.push({
		'appendTarget'         : '<?php echo esc_html( $append_target ); ?>',
		'bodyLock'             : '<?php echo esc_html( $body_lock ); ?>',
		'closeButtonAriaLabel' : '<?php echo esc_html( $close_button_aria_label ); ?>',
		'closeButtonClasses'   : '<?php echo esc_html( $close_button_classes ); ?>',
		'contentClasses'       : '<?php echo esc_html( $content_wrapper_classes ); ?>',
		'effect'               : '<?php echo esc_html( $effect ); ?>',
		'effectEasing'         : '<?php echo esc_html( $effect_easing ); ?>',
		'effectSpeed'          : '<?php echo esc_html( $effect_speed ); ?>',
		'id'                   : '<?php echo esc_html( $id ); ?>',
		'overlayClasses'       : '<?php echo esc_html( $overlay_classes ); ?>',
		'overlayClickCloses'   : '<?php echo esc_html( $overlay_click_closes ); ?>',
		'template'             : '<?php echo esc_html( $template ); ?>',
		'trigger'              : '[data-js="' + '<?php echo  esc_attr( 'trigger-dialog-' . $id ); ?>' + '"]',
		'wrapperClasses'       : '<?php echo esc_attr($wrapper_classes ); ?>'
	});
</script>
