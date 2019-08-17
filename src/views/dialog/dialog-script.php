<?php
/**
 * Dialog Script Template
 * The script template for Tribe Dialogs.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/dialogs/dialog-script.php
 *
 * @since TBD
 *
 * @package Tribe
 * @version TBD
 */
?>
<script>
	var tribe = tribe || {};
	tribe.dialog = tribe.dialog || {};

	( function ( obj ) {
	'use strict';

	document.addEventListener(
		'DOMContentLoaded',
		function () {
			<?php tribe( 'dialog.view' )->template( 'script', get_defined_vars(), true ); ?>

			<?php echo esc_html( $template ); ?>.on('show', function (dialogEl, event) {
				event.preventDefault();
				event.stopPropagation();
			});
		}
	);
} )(tribe.dialog);
</script>
