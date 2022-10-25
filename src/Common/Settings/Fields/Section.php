<?php

namespace TEC\Common\Settings;

/**
 * Helper class that creates HTML sections for use in Settings.
 *
 * @since TBD
 */
class Section extends Abstract_Field  {
	/**
	 * Generate a section "field".
	 *
	 * @return void
	 */
	public function render() {
		ob_start();
		?>
			<section
				id="<?php echo esc_attr( $this->id ); ?>"
				class="tec-settings__section"
				<?php $this->do_attributes(); ?>
			><?php
				if ( ! empty( $this->fields ) ) {
					foreach( $this->fields as $id => $field) {
						$field['parent'] = $this->id;
						$field['parent_type'] = $this->type;

						new Field_Factory( $id, $field );
					}
				}
			?></section>
		<?php

		$this->content = ob_end_clean();

		$content = apply_filters(
			'tec-settings-field-section-content',
			$this->content,
			$this
		);

		echo $content;
	}
}
