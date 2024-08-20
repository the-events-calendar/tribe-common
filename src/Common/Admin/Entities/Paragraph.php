<?php
/**
 * Paragraph element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Classes;

/**
 * Class Paragraph
 *
 * @since TBD
 */
class Paragraph extends Container {

	/**
	 * Paragraph constructor.
	 *
	 * @param ?Element_Classes $classes The classes for the paragraph.
	 */
	public function __construct( ?Element_Classes $classes = null ) {
		if ( $classes ) {
			$this->set_classes( $classes );
		}
	}

	/**
	 * Render the paragraph content.
	 *
	 * @return void
	 */
	public function render() {
		?>
		<p class="<?php echo esc_attr( $this->get_classes() ); ?>">
			<?php $this->render_children(); ?>
		</p>
		<?php
	}
}
