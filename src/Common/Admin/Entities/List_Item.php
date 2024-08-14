<?php
/**
 * List item element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;
use Tribe\Utils\Element_Classes;

/**
 * Class List_Item
 *
 * @since TBD
 */
class List_Item extends Container {

	/**
	 * List_Item constructor.
	 *
	 * @param ?Element_Classes $classes The classes for the list item.
	 */
	public function __construct( ?Element_Classes $classes = null ) {
		if ( $classes ) {
			$this->set_classes( $classes );
		}
	}

	/**
	 * Add a child to the container.
	 *
	 * @param Element $child The child to add. Cannot be another List Item.
	 *
	 * @return static
	 * @throws InvalidArgumentException If the child is another List Item.
	 */
	public function add_child( $child ) {
		if ( $child instanceof static::class ) {
			throw new InvalidArgumentException( esc_html__( 'List items cannot contain other list items.', 'tribe-common' ) );
		}

		return parent::add_child( $child );
	}

	/**
	 * Render the list item content.
	 *
	 * @return void
	 */
	public function render() {
		?>
		<li class="<?php echo esc_attr( $this->get_classes() ); ?>">
			<?php $this->render_children(); ?>
		</li>
		<?php
	}
}
