<?php
/**
 * Heading element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;
use LogicException;
use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class Heading
 *
 * @since 6.1.0
 */
class Heading extends Base_Entity {

	/**
	 * The heading content.
	 *
	 * @var string
	 */
	protected string $content = '';

	/**
	 * The heading level.
	 *
	 * @var int
	 */
	protected int $level = 1;

	/**
	 * The maximum heading level.
	 *
	 * @var int
	 */
	protected int $max_level = 6;

	/**
	 * Heading constructor.
	 *
	 * @since 6.1.0
	 *
	 * @param string      $content    The content for the heading.
	 * @param int         $level      The level for the heading.
	 * @param ?Classes    $classes    The classes for the heading.
	 * @param ?Attributes $attributes The attributes for the heading.
	 */
	public function __construct( string $content, int $level = 1, ?Classes $classes = null, ?Attributes $attributes = null ) {
		$this->content = $content;

		$this->validate_level( $level );
		$this->level = $level;

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
		<h<?php echo absint( $this->level ); ?>
			<?php echo $this->get_class_attribute(); // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput ?>
			<?php echo $this->get_attributes(); // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput ?>
		>
			<?php echo esc_html( $this->content ); ?>
		</h<?php echo absint( $this->level ); ?>>
		<?php
	}

	/**
	 * Validate the heading level.
	 *
	 * @since 6.1.0
	 *
	 * @param int $level The heading level.
	 *
	 * @return void
	 * @throws InvalidArgumentException If the heading level is invalid.
	 * @throws LogicException If the maximum heading level is greater than 6.
	 */
	private function validate_level( int $level ) {
		if ( $this->max_level > 6 ) {
			throw new LogicException( esc_html__( 'The maximum heading level must be 6 or less', 'tribe-common' ) );
		}

		if ( $level < 1 || $level > $this->max_level ) {
			throw new InvalidArgumentException(
				sprintf(
					/* translators: %d: The maximum heading level. */
					esc_html__( 'Heading level must be between 1 and %d', 'tribe-common' ),
					$this->max_level
				)
			);
		}
	}
}
