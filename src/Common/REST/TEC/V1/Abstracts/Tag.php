<?php
/**
 * Abstract tag for the TEC REST API.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use TEC\Common\REST\TEC\V1\Contracts\Tag_Interface;

/**
 * Abstract tag for the TEC REST API.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */
abstract class Tag implements Tag_Interface {
	/**
	 * Returns the tag as a JSON serializable object.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return $this->get();
	}
}
