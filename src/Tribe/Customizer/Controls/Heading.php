<?php
/**
 * Models a Customizer heading, a Control just in name, it does not control any setting.
 *
 * @since   TBD
 *
 * @package Tribe\Customizer\Controls
 */

namespace Tribe\Customizer\Controls;

use Tribe\Customizer\Control;

/**
 * Class Heading
 *
 * @since   TBD
 *
 * @package Tribe\Customizer\Controls
 */
class Heading extends Control {
	/**
	 * Anyone able to set theme options will be able to see the header.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $capability = 'edit_theme_options';

	/**
	 * The heading does not control any setting.
	 *
	 * @since TBD
	 *
	 * @var array<string,mixed>
	 */
	public $settings = [];

	/**
	 * Render the control's content
	 *
	 * @since TBD
	 */
	public function render_content() {
		?>
		<h4><?php echo esc_html( $this->label ); ?></h4>
		<?php
	}
}
