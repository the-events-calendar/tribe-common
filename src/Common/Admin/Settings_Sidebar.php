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
	 * @since 6.1.0
	 *
	 * @var Section[]
	 */
	protected array $sections = [];

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
			<?php do_action( 'tec_settings_sidebar_start', $this ); ?>
			<?php foreach ( $this->get_sections() as $section ) : ?>
				<div class="tec-settings-form__sidebar-section">
					<?php $section->render(); ?>
				</div>
			<?php endforeach; ?>
			<?php do_action( 'tec_settings_sidebar_end', $this ); ?>
		</div>
		<?php
	}

	/**
	 * Alias to prepending a section to the sidebar.
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
	 * Add a section to the start of the sidebar array of sections
	 *
	 * @since 6.3.0
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
	 * Get the sidebar sections.
	 *
	 * @since 6.3.0
	 *
	 * @return array
	 */
	public function get_sections(): array {
		/**
		 * Filter the sidebar sections.
		 *
		 * @since 6.3.0
		 *
		 * @param Section[]        $sections The sidebar sections.
		 * @param Settings_Sidebar $sidebar  The sidebar object.
		 */
		return apply_filters( 'tec_settings_sidebar_sections', $this->sections, $this );
	}

	/**
	 * Set the header image for the sidebar.
	 *
	 * @since      6.1.0
	 *
	 * @deprecated 6.3.0
	 *
	 * @param Image $deprecated Deprecated.
	 */
	public function set_header_image( Image $deprecated ) {
		_deprecated_function( __METHOD__, '6.3.0', 'Sidebar no longer has headers, they can be added to individual sections.' );
	}
}
