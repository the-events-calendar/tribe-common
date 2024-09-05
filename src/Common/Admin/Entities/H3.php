<?php
/**
 * H3 Entity
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class H3
 *
 * @since TBD
 */
class H3 extends Heading {

	/**
	 * H3 constructor.
	 *
	 * @param string      $content    The content for the heading.
	 * @param ?Classes    $classes    The classes for the heading.
	 * @param ?Attributes $attributes The attributes for the heading.
	 */
	public function __construct( string $content, ?Classes $classes = null, ?Attributes $attributes = null ) {
		parent::__construct( $content, 3, $classes, $attributes );
	}
}