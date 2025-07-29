<?php
/**
 * Common tag for the TEC REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Tags
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Tags;

use TEC\Common\REST\TEC\V1\Abstracts\Tag;

/**
 * Common tag for the TEC REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Tags
 */
class Common_Tag extends Tag {
	/**
	 * Returns the name of the tag.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'Common';
	}

	/**
	 * Returns the tag.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get(): array {
		return [
			'name'        => $this->get_name(),
			'description' => __( 'These operations are introduced by the Common library.', 'tribe-common' ),
		];
	}

	/**
	 * Returns the priority of the tag.
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 1000;
	}
}
