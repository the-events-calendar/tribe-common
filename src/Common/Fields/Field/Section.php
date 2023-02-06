<?php

namespace TEC\Common\Fields\Field;

use TEC\Common\Fields\Factory;

/**
 * Helper class that creates HTML sections for use in Settings.
 *
 * @since TBD
 */
class Section extends Abstract_Field  {
	/**
	 * The sections's contained fields.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $fields = [];

	/**
	 * The section's content.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $content = '';

	/**
	 * Generate a section "field".
	 *
	 * @return void
	 */
	public function render() {
		if ( empty( $this->fields ) ) {
			return;
		}

		ob_start();
		?>
			<section
				id="<?php echo esc_attr( self::$id ); ?>"
				class="tec-settings__section"
				<?php $this->do_attributes(); ?>
			><?php
				foreach( $this->fields as $id => $field) {
					$field->parent      = self::$id;
					$field->parent_type = self::$type;

					new Factory( $id, $field );
				}
			?></section>
		<?php

		$this->content = ob_end_clean();

		$content = apply_filters(
			'tec-field-section-content',
			$this->content,
			$this
		);

		echo $content;
	}
}
