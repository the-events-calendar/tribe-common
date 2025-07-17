<?php
/**
 * A trait of common methods for kye-value cache implementations.
 *
 * @since TBD
 *
 * @package Common\Key_Value_Cache;
 */

namespace TEC\Common\Key_Value_Cache;

/**
 * Trait Key_Value_Cache_Methods_Trait.
 *
 * @since TBD
 *
 * @package Common\Key_Value_Cache;
 */
trait Key_Value_Cache_Methods_Trait {
	/**
	 * Gets a cache value and attempts to decode it to a JSON object.
	 *
	 * @since TBD
	 *
	 * @param string $key         The key to return the value for.
	 * @param bool   $associative Whether to return an associative array or an object.
	 *
	 * @return object|array<string|int,mixed>|null The decoded JSON object or array if the key exists and can be
	 *                                             decoded, else `null`.
	 */
	public function get_json( string $key, bool $associative = false ) {
		$value = $this->get( $key );

		if ( empty( $value ) ) {
			return null;
		}

		$decoded = json_decode( $value, $associative );

		// On error return null.
		if ( null === $decoded || json_last_error() !== JSON_ERROR_NONE ) {
			return null;
		}

		return $decoded;
	}
}
