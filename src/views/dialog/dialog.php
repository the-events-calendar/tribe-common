<?php
/**
 * Dialog View Template
 * The base template for Tribe Dialogs.
 *  All event handling is in `dialog-script.php`
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/dialog.php
 *
 * @package Tribe
 * @version TBD
 */

?>
<?php tribe( 'dialog.view' )->template( 'button', get_defined_vars(), true ); ?>
<script data-js="<?php echo esc_attr( 'dialog-content-' . $id ); ?>" type="text/template" >
	<div <?php tribe_classes( $content_classes ) ?>>
		<?php if ( ! empty( $title ) ) : ?>
			<h2><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php echo $content; ?>
	</div>
</script>
<?php tribe( 'dialog.view' )->template( 'script', get_defined_vars(), true ); ?>
