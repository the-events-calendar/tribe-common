<?php
/**
 * Alert Dialog Script Template
 * The script template for tribe-dialog alerts.
 * Includes event handlers for default button.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/dialogs/alert-script.php
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

			document.addEventListener(
				'click',
				function (event) {
					if (event.target.matches('.tribe-confirm__continue')) {
						<?php echo esc_html( $template ); ?>.hide();
						return true;
					};
				},
				false
				);
		}
	);
} )(tribe.dialog);
</script>
