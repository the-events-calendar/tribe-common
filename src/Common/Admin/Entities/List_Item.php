<?php
/**
 * List item element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;
use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class List_Item
 *
 * @since 6.1.0
 */
class List_Item extends Container {

	/**
	 * List_Item constructor.
	 *
	 * @since 6.1.0
	 *
	 * @param ?Classes    $classes    The classes for the list item.
	 * @param ?Attributes $attributes The attributes for the list item.
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
	 * Add a child to the container.
	 *
	 * @since 6.1.0
	 *
	 * @param Element $child The child to add. Cannot be another List Item.
	 *
	 * @return static
	 * @throws InvalidArgumentException If the child is another List Item.
	 */
	public function add_child( $child ) {
		if ( $child instanceof static ) {
			throw new InvalidArgumentException( esc_html__( 'List items cannot contain other list items.', 'tribe-common' ) );
		}

		return parent::add_child( $child );
	}

	/**
	 * Render the list item content.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<li
			class="<?php echo esc_attr( $this->get_classes() ); ?>"
			<?php echo $this->get_attributes(); // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput ?>
		>
			<?php $this->render_children(); ?>
		</li>
		<?php
	}
}
