<?php

namespace TEC\Common\Settings;

/**
 * Helper class that creates fieldsets for use in Settings.
 *
 * @since TBD
 */
class Fieldset extends Abstract_Field  {

	public $default_class = [ 'tec-settings__fieldset' ];
	/**
	 * Generate a fieldset "field".
	 *
	 * @return void
	 */
	public function render() {
		ob_start();
		?>
			<fieldset
				id="<?php echo esc_attr( $this->id ); ?>"
				class="<?php echo esc_attr( $this->class ); ?>"
				<?php echo empty( $this->error ) ? 'tribe-error' : ''; ?>
				<?php $this->do_attributes(); ?>
			><?php
				if ( ! empty( $this->fields ) ) {
					foreach( $this->fields as $id => $field ) {
						if ( $field['type'] !== 'fieldset' ) {
							$field['parent'] = $this->id;
							$field['parent_type'] = $this->type;

							new Field_Factory( $id, $field );
						}

						// Don't allow nested fieldsets - they are really bad for screen readers.
						// Fail, log the error.
						\Tribe__Debug::debug(
							esc_html__( 'Nested fieldsets are bad for accessibility! Fieldset will not display.', 'tribe-common' ),
							[
								$id,
								$field,
								$this
							],
							'warning'
						);
					}
				}
			?></fieldset>
		<?php

		$this->content = ob_end_clean();

		$content = apply_filters(
			'tec-settings-field-fieldset-content',
			$this->content,
			$this
		);

		echo $content;
	}
}
