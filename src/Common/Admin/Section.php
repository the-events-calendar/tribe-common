<?php
/**
 * Section.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

use TEC\Common\Admin\Entities\Heading;
use TEC\Common\Admin\Entities\Validate_Elements;

/**
 * Class Section
 *
 * @since 6.1.0
 */
abstract class Section {

	use Validate_Elements;

	/**
	 * Title for the sidebar.
	 *
	 * @var ?Heading
	 */
	protected ?Heading $title = null;

	/**
	 * Set the title for the sidebar.
	 *
	 * @since 6.1.0
	 *
	 * @param Heading $heading The title for the sidebar.
	 *
	 * @return static
	 */
	public function set_title( Heading $heading ) {
		$this->title = $heading;

		return $this;
	}

	/**
	 * Render the title for the sidebar.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	protected function render_title() {
		if ( ! $this->title ) {
			return;
		}

		$this->title->render();
	}

	/**
	 * Render the section content.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	abstract public function render();
}
