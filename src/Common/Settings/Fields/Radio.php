<?php

namespace TEC\Common\Settings;

use TEC\Common\Settings\Abstract_Field;

/**
 * Helper class that creates radio fields for use in Settings.
 *
 * @since TBD
 */
class Radio extends Abstract_Field {
		/**
		 * Generate a radio button field and label.
		 *
		 */
		public function render() {
			if ( ! is_array( $this->args['options'] ) ) {
				// Fail, log the error.
				\Tribe__Debug::debug(
					esc_html__( 'No radio options specified! Field will not display.', 'tribe-common' ),
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
				<label title="<?php echo esc_attr( strip_tags( $title ) ); ?>" class="tec-field-label tec-field-label__radio">
					<input
						type="radio"
						id="tec-settings-field-<?php echo esc_attr( $field_id ); ?>"
						name="<?php echo esc_attr( $name ) ?>"
						class="tec-settings__field tec-settings__field--radio"
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
				'tec-settings-field-radio-content',
				$content,
				$this
			);

			echo $content;
		}
}
