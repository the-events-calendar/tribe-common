<?php
/**
 * Settings_Section.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

use TEC\Common\Admin\Entities\Element;

/**
 * Class Settings_Section
 *
 * @since 6.1.0
 */
class Settings_Section extends Section {

	/**
	 * Elements for the section.
	 *
	 * @var Element[]
	 */
	protected array $elements = [];

	/**
	 * Add an element to the section.
	 *
	 * @since 6.1.0
	 *
	 * @param Element $element The element to add.
	 *
	 * @return static
	 */
	public function add_element( Element $element ) {
		$this->elements[] = $element;

		return $this;
	}

	/**
	 * Add multiple elements to the section.
	 *
	 * @since 6.1.0
	 *
	 * @param Element[] $elements The elements to add.
	 *
	 * @return static
	 */
	public function add_elements( array $elements ) {
		foreach ( $elements as $element ) {
			$this->add_element( $element );
		}

		return $this;
	}

	/**
	 * Render the section content.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div class="tribe-settings-section">
			<?php $this->render_title(); ?>
			<?php $this->render_elements(); ?>
		</div>
		<?php
	}

	/**
	 * Render the elements for the section.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	protected function render_elements() {
		foreach ( $this->elements as $element ) {
			$element->render();
		}
	}
}
