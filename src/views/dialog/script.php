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

$args = [
	'appendTarget'         => esc_html( $append_target ),
	'bodyLock'             => esc_html( $body_lock ),
	'closeButtonAriaLabel' => esc_html( $close_button_aria_label ),
	'closeButtonClasses'   => esc_html( $close_button_classes ),
	'contentClasses'       => esc_html( $content_wrapper_classes ),
	'effect'               => esc_html( $effect ),
	'effectEasing'         => esc_html( $effect_easing ),
	'effectSpeed'          => esc_html( $effect_speed ),
	'id'                   => esc_html( $id ),
	'overlayClasses'       => esc_html( $overlay_classes ),
	'overlayClickCloses'   => esc_html( $overlay_click_closes ),
	'template'             => esc_html( $template ),
	'trigger'              => "[data-js='" .  esc_attr( 'trigger-dialog-' . $id ) . "']",
	'wrapperClasses'       => esc_attr( $wrapper_classes ),
];

/**
 * Allows for modifying the arguments before they are passed to the dialog script

 * @since TBD
 *
 * @param array $args List of arguments to override dialog script. See \Tribe\Dialog\View->build_dialog().
 */
$args = apply_fiters( 'tribe_dialog_script_args', $args );
?>
<script>
	var tribe = tribe || {};
	tribe.dialogs = tribe.dialogs || [];

	tribe.dialogs.push( <?php echo json_encode( $args ); ?> );
</script>
