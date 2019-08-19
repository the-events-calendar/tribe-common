<?php
/**
 * Confirmation Dialog View Template
 * The confirmation template for tribe-dialog.
 *
 * Includes "cancel" and "continue" buttons. All event handling is in `confirm-script.php`
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/confirm.php
 *
 * @package Tribe
 * @version TBD
 */

?>
<?php tribe( 'dialog.view' )->template( 'button', get_defined_vars(), true ); ?>
<script data-js="<?php echo esc_attr( 'dialog-content-' . $id ); ?>" type="text/template" >
	<div class="<?php echo esc_attr( $content_classes ); ?>">
		<?php if ( ! empty( $title ) ) : ?>
			<h2><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php echo $content; ?>
		<div class="tribe-dialog__button_wrap">
			<button class="tribe-button tribe-confirm__cancel"><?php echo esc_html( $cancel_button_text ); ?></button>
			<button class="tribe-button tribe-confirm__continue"><?php echo esc_html( $continue_button_text ); ?></button>
		</div>
	</div>
</script>
<?php tribe( 'dialog.view' )->template( 'script', get_defined_vars(), true ); ?>
