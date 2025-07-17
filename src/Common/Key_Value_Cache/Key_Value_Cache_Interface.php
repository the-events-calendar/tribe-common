<?php
/**
 * The interface exposed by a key-value cache implementation.
 *
 * @since TBD
 *
 * @package TEC\Common\Key_Value_Cache;
 */

namespace TEC\Common\Key_Value_Cache;

/**
 * Interface Key_Value_Cache_Interface.
 *
 * @since TBD
 *
 * @package TEC\Common\Key_Value_Cache;
 */
interface Key_Value_Cache_Interface {
	/**
	 * Whether a key exists in the cache.
	 *
	 * This method tells nothing about the nature of the value, it only checks whether the key exists in the cache.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to check.
	 *
	 * @return bool Whether the key exists in the cache.
	 */
	public function has( string $key ): bool;

	/**
	 * Returns a cached value if it exists.
	 *
	 * Note the method does not inquire into the nature of the stored value, it's returned as it is and no guarantee
	 * is made on the integrity or correctness of the value.
	 *
	 * @since TBD
	 *
	 * @param string      $key      The key to return the value for.
	 * @param string|null $fallback The fallback value to return if the key does not exist.
	 *
	 * @return string The cached value or the default value if not set.
	 */
	public function get( string $key, string $fallback = '' ): string;

	/**
	 * Stores a value for a key.
	 *
	 * @since TBD
	 *
	 * @param string $key   The key to store the value for.
	 * @param string $value The value to store for the key.
	 * @param int    $expiration The cache expiration, it cannot be below 300 seconds.
	 *
	 * @return bool Whether the value was correctly stored or not.
	 */
	public function set( string $key, string $value, int $expiration = 300 ): bool;

	/**
	 * Deletes a value from the cache, if present.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to delete.
	 *
	 * @return void The value is deleted if present, nothing is returned.
	 */
	public function delete( string $key ): void;

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
	public function get_json( string $key, bool $associative = false );

	/**
	 * Flushes the cache.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function flush(): void;
}
