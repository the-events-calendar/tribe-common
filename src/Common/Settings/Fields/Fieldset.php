<?php

namespace TEC\Common\Settings;

/**
 * Helper class that creates fieldsets for use in Settings.
 *
 * @since TBD
 */
class Fieldset extends Abstract_Field  {

	public $default_class = [ 'tec-fieldset' ];
	/**
	 * Generate a fieldset "field".
	 *
	 * @return void
	 */
	public function render() {
		ob_start();
		?>
			<fieldset
				id="<?php echo esc_attr( self::$id ); ?>"
				class="<?php echo esc_attr( $this->class ); ?>"
				<?php echo empty( $this->error ) ? 'tribe-error' : ''; ?>
				<?php $this->do_attributes(); ?>
			><?php
				if ( ! empty( $this->fields ) ) {
					foreach( $this->fields as $id => $field ) {
						if ( $field['type'] !== 'fieldset' ) {
							// Don't allow nested fieldsets - they are really bad for screen readers. Fail and log the error.
							\Tribe__Debug::debug(
								esc_html__(
									'Nested fieldsets are bad for accessibility! Fieldset will not display.',
									'tribe-common'
								),
								[
									$id,
									$field,
									$this
								],
								'warning'
							);
						}

						$field['parent']      = self::$id;
						$field['parent_type'] = self::$type;

						new Field_Factory( $id, $field );
					}
				}
			?></fieldset>
		<?php

		$this->content = ob_end_clean();

		$content = apply_filters(
			'tec-field-fieldset-content',
			$this->content,
			$this
		);

		echo $content;
	}
}
