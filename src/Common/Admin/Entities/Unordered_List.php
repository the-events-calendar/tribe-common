<?php
/**
 * Unordered list element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;
use Tribe\Utils\Element_Classes;

/**
 * Class Unordered_List
 *
 * @since TBD
 */
class Unordered_List extends Container {

	use Validate_Elements;

	/**
	 * The type of child elements that the container can contain.
	 *
	 * @var string
	 */
	protected string $child_type = List_Item::class;

	/**
	 * Unordered_List constructor.
	 *
	 * @param ?Element_Classes $classes The classes for the unordered list.
	 */
	public function __construct( ?Element_Classes $classes = null ) {
		if ( $classes ) {
			$this->set_classes( $classes );
		}
	}

	/**
	 * Add a child to the container.
	 *
	 * @param List_Item $child The child to add.
	 *
	 * @return static
	 * @throws InvalidArgumentException If the child is not an instance of the child type.
	 */
	public function add_child( $child ) {
		return parent::add_child( $child );
	}

	/**
	 * Add multiple children to the container.
	 *
	 * @param List_Item[] $children The children to add.
	 *
	 * @return static
	 * @throws InvalidArgumentException If any of the children are not an instance of the child type.
	 */
	public function add_children( array $children ) {
		return parent::add_children( $children );
	}

	/**
	 * Render the unordered list content.
	 *
	 * @return void
	 */
	public function render() {
		?>
		<ul class="<?php echo esc_attr( $this->get_classes() ); ?>">
			<?php $this->render_children(); ?>
		</ul>
		<?php
	}
}