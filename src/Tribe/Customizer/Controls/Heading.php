<?php

namespace Tribe\Customizer\Controls;

use Tribe\Customizer\Control;

class Heading extends Control {
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