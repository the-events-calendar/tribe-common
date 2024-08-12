<?php
/**
 * Settings sidebar.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

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
	private array $sections = [];

	/**
	 * Header image for the sidebar.
	 *
	 * @var string
	 */
	private string $header_image_url;

	/**
	 * Alt text for the header image.
	 *
	 * @var string
	 */
	private string $header_image_alt_text = '';

	/**
	 * Render the sidebar.
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div class="tribe-settings-sidebar">
			<?php
			$this->render_header_image();
			$this->render_title();

			?>

			<?php foreach ( $this->sections as $section ) : ?>
				<div class="tribe-settings-sidebar__section">
					<?php $section->render(); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Set the header image for the sidebar.
	 *
	 * @param string $image_url The URL for the header image.
	 * @param string $alt_text  The alt text for the header image. If not provided, the
	 *                          title will be used instead.
	 *
	 * @return void
	 */
	public function set_header_image( string $image_url, string $alt_text = '' ) {
		$this->header_image_url      = $image_url;
		$this->header_image_alt_text = $alt_text;
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
	private function render_header_image() {
		if ( empty( $this->header_image_url ) ) {
			return;
		}

		printf(
			'<img src="%1$s" class="tribe-settings-sidebar__header-image" alt="%2$s">',
			esc_url( $this->header_image_url ),
			esc_attr( $this->header_image_alt_text ?: $this->title )
		);
	}
}
