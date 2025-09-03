<?php
/**
 * A key-value cache implementation based on the WordPress cache layer.
 *
 * @since 6.9.1
 *
 * @package TEC\Common\Key_Value_Cache;
 */

namespace TEC\Common\Key_Value_Cache;

/**
 * Class Object_Cache.
 *
 * @since 6.9.1
 *
 * @package TEC\Common\Key_Value_Cache;
 */
class Object_Cache implements Key_Value_Cache_Interface {
	use Key_Value_Cache_Methods_Trait;

	/**
	 * The cache group to use for the object cache.
	 *
	 * @since 6.9.1
	 *
	 * @var string
	 */
	public const CACHE_GROUP = 'tec_kv_cache';

	/**
	 * Whether a key exists in the cache.
	 *
	 * This method tells nothing about the nature of the value, it only checks whether the key exists in the cache.
	 *
	 * @since 6.9.1
	 *
	 * @param string $key The key to check.
	 *
	 * @return bool Whether the key exists in the cache.
	 */
	public function has( string $key ): bool {
		$value = wp_cache_get( $key, static::CACHE_GROUP );

		return false !== $value;
	}

	/**
	 * Returns a cached value if it exists.
	 *
	 * Note the method does not inquire into the nature of the stored value, it's returned as it is and no guarantee
	 * is made on the integrity or correctness of the value.
	 *
	 * @since 6.9.1
	 *
	 * @param string      $key      The key to return the value for.
	 * @param string|null $fallback The fallback value to return if the key does not exist.
	 *
	 * @return string The cached value or the default value if not set.
	 */
	public function get( string $key, string $fallback = '' ): string {
		$value = wp_cache_get( $key, static::CACHE_GROUP );

		if ( false === $value ) {
			return $fallback;
		}

		return (string) $value;
	}

	/**
	 * Stores a value for a key.
	 *
	 * @since 6.9.1
	 *
	 * @param string $key   The key to store the value for.
	 * @param string $value The value to store for the key.
	 * @param int    $expiration The cache expiration, it cannot be below 300 seconds.
	 *
	 * @return bool Whether the value was correctly stored or not.
	 */
	public function set( string $key, string $value, int $expiration = 300 ): bool {
		if ( $expiration < 300 ) {
			// To stick with WP VIP min requirements, we're not going to store values with an expiration under 5 minutes.
			return false;
		}

		// We're dealing with this above.
		// phpcs:ignore WordPressVIPMinimum.Performance.LowExpiryCacheTime.CacheTimeUndetermined
		return wp_cache_set( $key, $value, static::CACHE_GROUP, $expiration );
	}

	/**
	 * Deletes a value from the cache, if present.
	 *
	 * @since 6.9.1
	 *
	 * @param string $key The key to delete.
	 *
	 * @return void The value is deleted if present, nothing is returned.
	 */
	public function delete( string $key ): void {
		wp_cache_delete( $key, static::CACHE_GROUP );
	}

	/**
	 * Flushes the cache.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	public function flush(): void {
		wp_cache_flush_group( static::CACHE_GROUP );
	}
}
