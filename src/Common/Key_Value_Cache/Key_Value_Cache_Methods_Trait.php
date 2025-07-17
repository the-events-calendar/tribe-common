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

	/**
	 * Stores a serialized value for a key.
	 *
	 * @since TBD
	 *
	 * @param string $key        The key to store the value for.
	 * @param mixed  $value      The value to serialize and store.
	 * @param int    $expiration The cache expiration, it cannot be below 300 seconds.
	 *
	 * @return bool Whether the value was correctly serialized and stored.
	 */
	public function set_serialized( string $key, $value, int $expiration = 300 ): bool {
		try {
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
			$serialized = serialize( $value );
		} catch ( \Exception $e ) {
			do_action(
				'tribe_log',
				'error',
				'Key Value Cache serialization error for key "' . $key . '": ' . $e->getMessage()
			);
			return false;
		}

		return $this->set( $key, $serialized, $expiration );
	}

	/**
	 * Stores a JSON-encoded value for a key.
	 *
	 * @since TBD
	 *
	 * @param string $key        The key to store the value for.
	 * @param mixed  $value      The value to encode as JSON and store.
	 * @param int    $expiration The cache expiration, it cannot be below 300 seconds.
	 *
	 * @return bool Whether the value was correctly JSON-encoded and stored.
	 */
	public function set_json( string $key, $value, int $expiration = 300 ): bool {
		$encoded = wp_json_encode( $value );

		if ( false === $encoded ) {
			do_action(
				'tribe_log',
				'error',
				'Key Value Cache JSON encoding error for key "' . $key . '": Failed to encode value'
			);
			return false;
		}

		return $this->set( $key, $encoded, $expiration );
	}

	/**
	 * Gets a cached value and attempts to unserialize it.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to return the value for.
	 *
	 * @return mixed The unserialized value if the key exists and can be unserialized, else null.
	 */
	public function get_serialized( string $key ) {
		$value = $this->get( $key );

		if ( empty( $value ) ) {
			return null;
		}

		try {
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
			$unserialized = unserialize( $value, [ 'allow_classes' => true ] );
		} catch ( \Exception $e ) {
			do_action(
				'tribe_log',
				'error',
				'Key Value Cache unserialization error for key "' . $key . '": ' . $e->getMessage()
			);
			return null;
		}

		// unserialize() returns false on failure, but false might also be the actual value.
		if ( false === $unserialized && 'b:0;' !== $value ) {
			do_action(
				'tribe_log',
				'error',
				'Key Value Cache unserialization failed for key "' . $key . '": invalid serialized data'
			);
			return null;
		}

		return $unserialized;
	}
}
