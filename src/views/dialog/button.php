<?php
/**
 * Dialog Button Template
 * The button template for Tribe Dialog trigger.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/dialogs/buton.php
 *
 * @package Tribe
 * @version TBD
 */

?>
<button
	data-js="<?php echo esc_attr( 'trigger-dialog-' . $id ); ?>"
	data-content="<?php echo esc_attr( 'dialog-content-' . $id ); ?>"
	class="tribe-button"
><?php echo esc_html( $button_text ); ?></button>
