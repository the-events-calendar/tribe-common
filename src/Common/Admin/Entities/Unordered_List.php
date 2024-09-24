<?php
/**
 * Unordered list element.
 *
 * @since 6.1.0
 *
 * phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;
use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class Unordered_List
 *
 * @since 6.1.0
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
	 * @since 6.1.0
	 *
	 * @param ?Classes    $classes    The classes for the unordered list.
	 * @param ?Attributes $attributes The attributes for the unordered list.
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
	 * @since 6.1.0
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
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		?>
		<ul
			class="<?php echo esc_attr( $this->get_classes() ); ?>"
			<?php echo $this->get_attributes(); // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput ?>
		>
			<?php $this->render_children(); ?>
		</ul>
		<?php
	}
}
