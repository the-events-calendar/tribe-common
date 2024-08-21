<?php
/**
 * Settings sidebar.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

use TEC\Common\Admin\Entities\Image;

/**
 * Class Settings_Sidebar
 *
 * @since TBD
 */
class Settings_Sidebar extends Section {

	/**
	 * Sections for the sidebar.
	 *
	 * @var Section[]
	 */
	protected array $sections = [];

	/**
	 * Header image for the sidebar.
	 *
	 * @var ?Image
	 */
	protected ?Image $header_image = null;

	/**
	 * Render the sidebar.
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div class="tec-settings__sidebar">
			<?php
			$this->render_header_image();
			$this->render_title();

			?>

			<?php foreach ( $this->sections as $section ) : ?>
				<div class="tec-settings__sidebar-section">
					<?php $section->render(); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Set the header image for the sidebar.
	 *
	 * @param Image $image The image to set.
	 *
	 * @return void
	 */
	public function set_header_image( Image $image ) {
		$this->header_image = $image;
	}

	/**
	 * Add a section to the sidebar.
	 *
	 * @param Section $section The section to add.
	 *
	 * @return void
	 */
	public function add_section( Section $section ) {
		$this->sections[] = $section;
	}

	/**
	 * Render the header image for the sidebar.
	 *
	 * @return void
	 */
	protected function render_header_image() {
		if ( ! $this->header_image ) {
			return;
		}

		$this->header_image->render();
	}
}
