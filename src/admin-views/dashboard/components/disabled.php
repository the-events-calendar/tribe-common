<?php
/**
 * View: Integration Endpoint - Disabled field.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/dashboard/components/disabled.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,string> $classes_wrap  An array of classes for the toggle button.
 * @var string               $label         The label for the text input.
 * @var string               $screen_reader The screen reader instructions for the text input.
 */

?>
<div <?php tec_classes( $classes_wrap ); ?> >
	<fieldset class="tec-automator-settings-details__read-only-field">
		<legend class="tec-automator-settings-details__label tribe-field-label screen-reader-text">
			<?php echo esc_html( $label ); ?>
		</legend>
		<div class="tec-automator-settings-details__field-wrap tribe-field-wrap">
			-
			<?php if ( $screen_reader ) { ?>
				<label class="screen-reader-text">
					<?php echo esc_html( $screen_reader ); ?>
				</label>
			<?php } ?>
		</div>
	</fieldset>
</div>
