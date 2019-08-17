<?php
/**
 * Dialog Modal View Template
 * The base template for Tribe Dialog Modals.
 *  All event handling is in `modal-script.php`
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/dialogs/modal.php
 *
 * @since TBD
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
	</div>
</script>
