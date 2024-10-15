<?php
/**
 * Settings_Section.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

use TEC\Common\Admin\Entities\Image;

/**
 * Class Settings_Sidebar_Section
 *
 * @since TBD
 */
class Settings_Sidebar_Section extends Settings_Section {

	/**
	 * Sections for the sidebar.
	 *
	 * @since TBD
	 *
	 * @var Section[]
	 */
	protected array $sections = [];

	/**
	 * Header image for the sidebar.
	 *
	 * @since TBD
	 *
	 * @var ?Image
	 */
	protected ?Image $header_image = null;

	/**
	 * Set the header image for the sidebar.
	 *
	 * @since TBD
	 *
	 * @param Image $image The image to set.
	 *
	 * @return self
	 */
	public function set_header_image( Image $image ): self {
		$this->header_image = $image;

		return $this;
	}

	/**
	 * Alias to prepending a sub-section to the sidebar.
	 *
	 * @since 6.1.0
	 *
	 * @param Section $section The section to add.
	 *
	 * @return self
	 */
	public function add_section( Section $section ): self {
		$this->sections[] = $section;

		return $this;
	}

	/**
	 * Add a sub-section to the start of the sidebar array of sections
	 *
	 * @since TBD
	 *
	 * @param Section $section The section to add.
	 *
	 * @return self
	 */
	public function prepend_section( Section $section ): self {
		array_unshift( $this->sections, $section );

		return $this;
	}

	/**
	 * Render the header image for the sidebar.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function render_header_image(): void {
		if ( ! $this->header_image ) {
			return;
		}

		$this->header_image->render();
	}

	/**
	 * Render the sidebar Section.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render(): void {
		?>
		<div class="tec-settings-form__sidebar-section tec-settings-form__sidebar-header">
			<?php
			/**
			 * Fires before the sidebar header is rendered.
			 *
			 * @since TBD
			 */
			do_action( 'tec_settings_sidebar_header_start' );

			$this->render_header_image();
			$this->render_title();

			/**
			 * Fires after the sidebar header is rendered.
			 *
			 * @since TBD
			 */
			do_action( 'tec_settings_sidebar_header_end' );
			?>
		</div>
		<?php foreach ( $this->sections as $section ) : ?>
			<div class="tec-settings-form__sidebar-section">
				<?php $section->render(); ?>
			</div>
		<?php endforeach; ?>
		<?php
	}
}
