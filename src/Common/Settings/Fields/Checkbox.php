<?php

namespace TEC\Common\Settings;

/**
 * Helper class that creates checkbox fields for use in Settings.
 *
 * @since TBD
 */
class Checkbox extends Abstract_Field  {
	/**
	 * Class constructor.
	 *
	 * @since TBD
	 *
	 * @param string     $id    The field id.
	 * @param array      $args  The field settings.
	 * @param null|mixed $value The field's current value.
	 *
	 * @return void
	 */
	public function __construct( $id, $args, $value = null ) {
		parent::__construct( $id, $args, $value );

		// Are we using a checkbox as a boolean "on/off"?
		if ( 'checkbox_bool' === $args['type'] || 1 === count( $this->args['options'] )) {
			$this->value = '1';
		}

	}

	/**
	 * Generate a checkbox field and label.
	 *
	 */
	public function render() {
		if ( ! is_array( $this->args['options'] ) ) {
			// Fail, log the error.
			\Tribe__Debug::debug(
				esc_html__( 'No checkbox options specified! Field will not display.', 'tribe-common' ),
				[
					$this->id,
					$this->type,
				],
				'warning'
			);

			return;
		}

		ob_start();

		foreach ( $this->args['options'] as $option_id => $title ) {
			$field_id = sprintf(
				'%1$s-%2$s',
				sanitize_html_class( trim( $this->id ) ),
				sanitize_html_class( trim( $option_id ) )
			);

			$name = $this->name . '[]';
			?>
			<label title="<?php echo esc_attr( strip_tags( $title ) ); ?>" class="tec-field-label tec-field-label__checkbox">
				<input
					type="checkbox"
					id="tec-settings-field-<?php echo esc_attr( $field_id ); ?>"
					name="<?php echo esc_attr( $name ) ?>"
					class="tec-settings__field tec-settings__field--checkbox"
					value="<?php echo esc_attr( $option_id ); ?>"
					<?php $this->do_attributes(); ?>
					<?php checked( $this->value, $option_id, false ); ?>
				/>
				<?php echo esc_html( $title ); ?>
			</label>
			<?php
		}

		$content = ob_end_clean();

		$content =  apply_filters(
			'tec-settings-field-checkbox-content',
			$content,
			$this
		);

		echo $content;
	}
}
