<?php
/**
 * View: Zapier Integration - Read only field.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/components/read-only.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,string> $classes_wrap  An array of classes for the toggle button.
 * @var string               $label         The label for the text input.
 * @var string               $screen_reader The screen reader instructions for the text input.
 * @var string               $id            ID of the hidden input.
 * @var string               $name          The name for the text input.
 * @var string               $value         The value of the text field.
 * @var boolean              $copy_button   Whether to display the copy button.
 */

?>
<div <?php tec_classes( $classes_wrap ); ?> >
	<fieldset class="tec-automator-settings-details__read-only-field">
		<legend class="tec-automator-settings-details__label tribe-field-label ">
			<?php echo esc_html( $label ); ?>
		</legend>
		<div class="tec-automator-settings-details__field-wrap tribe-field-wrap">
			<?php echo esc_html( $value ); ?>
			<input
				id="<?php echo esc_attr( $id ); ?>"
				type="hidden"
				name="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
			>
		</div>
		<div class="ec-automator-settings__copy-btn-wrap">
			<button class="tec-automator-settings__copy-btn" data-clipboard-text="<?php echo esc_attr( trim( $value ) ); ?>" >
				<span class="dashicons dashicons-admin-page"></span> <span class="tec-automator-settings__copy-btn-text"><?php echo esc_html_x( 'Copy', 'Button text for copying the consumer id or secret.', 'tribe-common' ); ?></span>
			</button>
		</div>
	</fieldset>
</div>
