<?php
/**
 * The API of the key-value cache.
 *
 * @since 6.9.1
 *
 * @package Common\Key_Value_Cache;
 */

namespace TEC\Common\Key_Value_Cache;

use Exception;
use TEC\Common\Key_Value_Cache\Table\Schema;
use TEC\Common\StellarWP\DB\DB;

/**
 * Class Key_Value_Cache_Table.
 *
 * @since 6.9.1
 *
 * @package Common\Key_Value_Cache;
 */
class Key_Value_Cache_Table implements Key_Value_Cache_Interface {
	use Key_Value_Cache_Methods_Trait;

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
		$current_time = time();

		try {
			$exists = DB::get_var(
				DB::prepare(
					'SELECT COUNT(*) FROM %i WHERE `cache_key` = %s AND (`expiration` = 0 OR `expiration` > %d)',
					Schema::table_name(),
					$key,
					$current_time
				)
			);
		} catch ( Exception $e ) {
			return false;
		}

		return $exists > 0;
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
		$current_time = time();

		try {
			$value = DB::get_var(
				DB::prepare(
					'SELECT `value` FROM %i WHERE `cache_key` = %s AND (`expiration` = 0 OR `expiration` > %d)',
					Schema::table_name(),
					$key,
					$current_time
				)
			);
		} catch ( Exception $e ) {
			return $fallback;
		}

		if ( null === $value ) {
			return $fallback;
		}

		return (string) $value;
	}

	/**
	 * Stores a value for a key.
	 *
	 * The maximum length of 191 chars aligns with the table definition and is the same length
	 * of the index used in the `wp_postmeta` table for meta_keys.
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
		// Bail if the key is longer than 191 characters.
		if ( strlen( $key ) > 191 ) {
			return false;
		}

		if ( $expiration < 300 ) {
			// To stick with WP VIP min requirements, we're not going to store values with an expiration under 5 minutes.
			return false;
		}

		$expiration_timestamp = $expiration + time();

		try {
			// Use INSERT ... ON DUPLICATE KEY UPDATE for upsert functionality.
			$result = DB::query(
				DB::prepare(
					'INSERT INTO %i (`cache_key`, `value`, `expiration`) VALUES (%s, %s, %d)
				ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `expiration` = VALUES(`expiration`)',
					Schema::table_name(),
					$key,
					$value,
					$expiration_timestamp
				)
			);
		} catch ( Exception $e ) {
			return false;
		}

		return false !== $result;
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
		$table_name = Schema::table_name();

		try {
			DB::delete(
				$table_name,
				[ 'cache_key' => $key ],
				[ '%s' ]
			);
		} catch ( Exception $e ) {
			do_action(
				'tribe_log',
				'error',
				'Key Value Cache delete error: ' . $e->getMessage()
			);
		}
	}

	/**
	 * Flushes the cache.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	public function flush(): void {
		$table_name = Schema::table_name();

		try {
			DB::query( "TRUNCATE TABLE {$table_name}" );
		} catch ( Exception $e ) {
			do_action(
				'tribe_log',
				'error',
				'Key Value Cache flush error: ' . $e->getMessage()
			);
		}
	}

	/**
	 * Clears the expired values from the table.
	 *
	 * This method is not part of the key-value interface as it applies only to the table version.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	public function clear_expired(): void {
		$table_name   = Schema::table_name();
		$current_time = time();

		try {
			DB::query(
				DB::prepare(
					'DELETE FROM %i WHERE `expiration` > 0 AND `expiration` < %d',
					$table_name,
					$current_time
				)
			);
		} catch ( Exception $e ) {
			do_action(
				'tribe_log',
				'error',
				'Key Value Cache clear expired error: ' . $e->getMessage()
			);
		}
	}
}
