<?php
/**
 * Div element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class Div
 *
 * @since 6.1.0
 */
class Div extends Container {

	/**
	 * Div constructor.
	 *
	 * @since 6.1.0
	 *
	 * @param ?Classes    $classes    The classes for the div.
	 * @param ?Attributes $attributes The attributes for the div.
	 */
	public function __construct( ?Classes $classes = null, ?Attributes $attributes = null ) {
		if ( $classes ) {
			$this->set_classes( $classes );
		}

		if ( $attributes ) {
			$this->set_attributes( $attributes );
		}
	}

	/**
	 * Render the element.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div
			class="<?php echo esc_attr( $this->get_classes() ); ?>"
			<?php echo $this->get_attributes(); // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput ?>
		>
			<?php $this->render_children(); ?>
		</div>
		<?php
	}
}
