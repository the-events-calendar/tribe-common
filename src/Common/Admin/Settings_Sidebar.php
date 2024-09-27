<?php
/**
 * Settings sidebar.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

use TEC\Common\Admin\Entities\Image;

/**
 * Class Settings_Sidebar
 *
 * @since 6.1.0
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
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div class="tec-settings-form__sidebar">
		<?php do_action( 'tec_settings_sidebar_start' ); ?>
			<div class="tec-settings-form__sidebar-section tec-settings-form__sidebar-header">
				<?php do_action( 'tec_settings_sidebar_header_start' ); ?>
				<?php
				$this->render_header_image();
				$this->render_title();
				?>
				<?php do_action( 'tec_settings_sidebar_header_end' ); ?>
			</div>

			<?php foreach ( $this->sections as $section ) : ?>
				<div class="tec-settings-form__sidebar-section">
					<?php $section->render(); ?>
				</div>
			<?php endforeach; ?>
			<?php do_action( 'tec_settings_sidebar_end' ); ?>
		</div>
		<?php
	}

	/**
	 * Set the header image for the sidebar.
	 *
	 * @since 6.1.0
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
	 * @since 6.1.0
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
	 * @since 6.1.0
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
