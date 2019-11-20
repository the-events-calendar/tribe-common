<?php
/**
 * Dialog View Template
 * The base template for Tribe Dialogs.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/dialog.php
 *
 * @since TBD
 *
 * @package Tribe
 * @version TBD
 */

/** @var \Tribe\Dialog\View $dialog_view */
$dialog_view = tribe( 'dialog.view' );
// grab allthevars!
$vars        = get_defined_vars();
?>
<?php $dialog_view->template( 'button', $vars, true ); ?>
<script data-js="<?php echo esc_attr( 'dialog-content-' . $id ); ?>" type="text/template">
	<div <?php tribe_classes( $content_classes ) ?>>
		<?php if ( ! empty( $title ) ) : ?>
			<h2 <?php tribe_classes( $title_classes ) ?>><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php echo $content; ?>
	</div>
</script>
