<?php
/**
 * Dialog View Template
 * The base template for Tribe Dialogs.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/dialog.php
 *
 * @since 4.10.0
 *
 * @package Tribe
 * @version 4.10.0
 */

/** @var \Tribe\Dialog\View $dialog_view */
$dialog_view = tribe( 'dialog.view' );
// grab allthevars!
$vars        = get_defined_vars();
?>
<?php $dialog_view->template( 'button', $vars, true ); ?>
<script data-js="<?php echo esc_attr( 'dialog-content-' . $id ); ?>" type="text/template">
	<div <?php tec_classes( $content_classes ); ?>>
		<?php if ( ! empty( $title ) ) : ?>
			<h2 <?php tec_classes( $title_classes ); ?>><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php echo $content; ?>
	</div>
</script>
