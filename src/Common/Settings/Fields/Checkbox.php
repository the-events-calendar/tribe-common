<?php

namespace TEC\Common\Settings;

/**
 * Helper class that creates checkbox fields for use in Settings.
 *
 * @since TBD
 */
class Checkbox extends Abstract_Field  {
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
					self::$id,
					self::$type,
				],
				'warning'
			);

			return;
		}

		ob_start();

		foreach ( $this->args['options'] as $option_id => $title ) {
			$field_id = sprintf(
				'%1$s-%2$s',
				sanitize_html_class( trim( self::$id ) ),
				sanitize_html_class( trim( $option_id ) )
			);

			$name = $this->name . '[]';
			?>
			<label title="<?php echo esc_attr( strip_tags( $title ) ); ?>" class="tec-field-label tec-field-label__checkbox">
				<input
					type="checkbox"
					id="tec-field-<?php echo esc_attr( $field_id ); ?>"
					name="<?php echo esc_attr( $name ) ?>"
					class="tec-field tec-field__checkbox"
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
			'tec-field-checkbox-content',
			$content,
			$this
		);

		echo $content;
	}
}
