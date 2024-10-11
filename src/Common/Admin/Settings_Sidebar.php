<?php
/**
 * Settings sidebar.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

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
	 * @return void
	 */
	public function add_section( Section $section ): self {
		return $this->append_section( $section );
	}

	/**
	 * Add a section to the end of the sidebar array of sections
	 *
	 * @since TBD
	 *
	 * @param Section $section The section to add.
	 *
	 * @return void
	 */
	public function append_section( Section $section ): self {
		$this->sections[] = $section;

		return $this;
	}

	/**
	 * Add a section to the start of the sidebar array of sections
	 *
	 * @since TBD
	 *
	 * @param Section $section The section to add.
	 *
	 * @return void
	 */
	public function prepend_section( Section $section ): self {
		array_unshift( $this->sections, $section );

		return $this;
	}

	/**
	 * Get the sidebar sections.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_sections(): array {
		/**
		 * Filter the sidebar sections.
		 *
		 * @since TBD
		 *
		 * @param Section[]        $sections The sidebar sections.
		 * @param Settings_Sidebar $sidebar  The sidebar object.
		 */
		return apply_filters( 'tec_settings_sidebar_sections', $this->sections, $this );
	}

}
