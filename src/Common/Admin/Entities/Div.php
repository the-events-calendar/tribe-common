<?php
/**
 * Div element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Classes;

/**
 * Class Div
 *
 * @since TBD
 */
class Div extends Container {

	/**
	 * Div constructor.
	 *
	 * @param ?Element_Classes $classes The classes for the div.
	 */
	public function __construct( ?Element_Classes $classes = null ) {
		if ( $classes ) {
			$this->set_classes( $classes );
		}
	}

	/**
	 * Render the element.
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div class="<?php echo esc_attr( $this->get_classes() ); ?>">
			<?php $this->render_children(); ?>
		</div>
		<?php
	}
}
