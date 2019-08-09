<?php
/**
 * Tooltip Basic View Template
 * The base template for Tribe Tooltips.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/tooltips/tooltip.php
 *
 * @package Tribe
 * @version TBD
 */

?>
<button data-js="trigger-newsletter-signup" data-content="newsletter-signup-content" class="tribe_dialog_trigger">Open the dialog window</button>
<script data-js="newsletter-signup-content" type="text/template" >
	<?php echo $content; ?>
</script>
