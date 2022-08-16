<?php
/**
 * View: Zapier Integration - Read only field.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/components/read-only.php
 *
 * See more documentation about our views templating system.
 *
 * @since   TBD
 *
 * @version TBD
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,string> $classes_wrap  An array of classes for the toggle button.
 * @var string               $label         The label for the text input.
 * @var string               $screen_reader The screen reader instructions for the text input.
 * @var string               $id            ID of the hidden input.
 * @var string               $name          The name for the text input.
 * @var string               $value         The value of the text field.
 */
?>
<div <?php tribe_classes( $classes_wrap ); ?> >
	<fieldset class="tec-settings-details__read-only-field">
		<legend class="tec-settings-details__label tribe-field-label">
			<?php echo esc_html( $label ); ?>
		</legend>
		<div class="tec-settings-details__field-wrap tribe-field-wrap">
			<?php echo esc_html( $value ); ?>
			<input
				id="<?php echo esc_attr( $id ); ?>"
				type="hidden"
				name="<?php echo esc_html( $name ); ?>"
				value="<?php echo esc_html( $value ); ?>"
			>
			<label class="screen-reader-text">
				<?php echo esc_html( $screen_reader ); ?>
			</label>
		</div>
	</fieldset>
</div>