<?php
/**
 * Paragraph element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class Paragraph
 *
 * @since 6.1.0
 */
class Paragraph extends Container {

	/**
	 * Paragraph constructor.
	 *
	 * @since 6.1.0
	 *
	 * @param ?Classes    $classes    The classes for the paragraph.
	 * @param ?Attributes $attributes The attributes for the paragraph.
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
	 * Render the paragraph content.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<p
			class="<?php echo esc_attr( $this->get_classes() ); ?>"
			<?php echo $this->get_attributes(); // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput ?>
		>
			<?php $this->render_children(); ?>
		</p>
		<?php
	}
}
