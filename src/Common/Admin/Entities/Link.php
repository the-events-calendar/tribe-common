<?php
/**
 * Link element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;
use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class Link
 *
 * @since 6.1.0
 */
class Link extends Base_Entity {

	/**
	 * The URL for the link.
	 *
	 * @var string
	 */
	private string $url = '';

	/**
	 * Content for the link if not a string.
	 *
	 * @var ?Base_Entity
	 */
	private ?Base_Entity $content = null;

	/**
	 * Link constructor.
	 *
	 * @since 6.1.0
	 *
	 * @param string             $url        The URL for the link.
	 * @param string|Base_Entity $content    The text or entity for the link.
	 * @param ?Classes           $classes    The classes for the link.
	 * @param ?Attributes        $attributes The attributes for the link.
	 */
	public function __construct( string $url, $content, ?Classes $classes = null, ?Attributes $attributes = null ) {
		$this->url = $url;

		if ( is_string( $content ) ) {
			$this->content = new Plain_Text( $content );
		} elseif ( $content instanceof Base_Entity ) {
			$this->content = $content;
		} else {
			throw new InvalidArgumentException( 'Content must be a string or an instance of Base_Entity' );
		}

		if ( $classes ) {
			$this->set_classes( $classes );
		}

		if ( $attributes ) {
			$this->set_attributes( $attributes );
		}
	}

	/**
	 * Render the link.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		?>
	<a
		href="<?php echo esc_url( $this->url ); ?>"
		class="<?php echo esc_attr( $this->get_classes() ); ?>"
		<?php echo $this->get_attributes(); // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput ?>
	>
		<?php $this->content->render(); ?>
	</a>
		<?php
	}
}
