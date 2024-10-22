<?php
/**
 * Button element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class Button
 *
 * @since TBD
 */
class Button extends Container {

	/**
	 * Button constructor.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return void
	 */
	public function render() {
		?>
		<button
			class="<?php echo esc_attr( $this->get_classes() ); ?>"
			<?php echo $this->get_attributes(); // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput ?>
		>
			<?php $this->render_children(); ?>
		</button>
		<?php
	}
}
