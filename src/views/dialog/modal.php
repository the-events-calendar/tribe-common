<?php
/**
 * Dialog Modal View Template
 * The base template for Tribe Dialog Modals.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/modal.php
 *
 * @since TBD
 *
 * @package Tribe
 * @version TBD
 */

/** @var \Tribe\Dialog\View $dialog_view */
$dialog_view = tribe( 'dialog.view' );
?>
<?php $dialog_view->template( 'button', get_defined_vars(), true ); ?>
<script data-js="<?php echo esc_attr( 'dialog-content-' . $id ); ?>" type="text/template" >
	<div <?php tribe_classes( $content_classes ) ?>>
		<?php if ( ! empty( $title ) ) : ?>
			<h2><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php echo $content; ?>
	</div>
</script>
<?php $dialog_view->template( 'script', get_defined_vars(), true ); ?>
