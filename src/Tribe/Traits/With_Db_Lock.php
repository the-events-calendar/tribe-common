<?php
/**
 * Provides methods to acquire and release a database (SQL) lock.
 *
 * The MySQL functions used by this trait are `GET_LOCK`, `IS_FREE_LOCK` and `RELEASE_LOCK`.
 * The functions are part of MySQL 5.6 and in line with WordPress minimum requirement of MySQL version (5.6).
 *
 * @see     https://dev.mysql.com/doc/refman/5.6/en/locking-functions.html#function_get-lock
 *
 * @since   TBD
 *
 * @package Tribe\Traits
 */

namespace Tribe\Traits;

/**
 * Trait With_Db_Lock
 *
 * @since   TBD
 *
 * @package Tribe\Traits
 */
trait With_DB_Lock {

	/**
	 * Acquires a db lock.
	 *
	 * To ensure back-compatibility with MySQL 5.6, the lock will hash the lock key using SHA1.
	 *
	 * @since TBD
	 *
	 * @param string $lock_key The name of the db lock key to acquire.
	 *
	 * @return bool Whether the lock acquisition was successful or not.
	 */
	protected function acquire_db_lock( $lock_key ) {
		global $wpdb;

		/**
		 * Filters the timeout, in seconds, of the database lock acquisition attempts.
		 *
		 * @since TBD
		 *
		 * @param int    $timeout The timeout, in seconds, of the lock acquisition attempt.
		 * @param static $this    The object that's trying to acquire the lock by means of the trait.
		 */
		$timeout = apply_filters( 'tribe_db_lock_timeout', 3, $lock_key, $this );

		/*
		 * On MySQL 5.6 if a session (a db connection) fires two requests of `GET_LOCK`, the lock is
		 * implicitly released and re-acquired.
		 * While this will not cause issues in the context of different db sessions (e.g. two diff. PHP
		 * processes competing for a lock), it would cause issues when the lock acquisition is attempted
		 * in the context of the same PHP process.
		 * To avoid a read-what-you-write issue in the context of the same request, we check if the lock is
		 * free, using `IS_FREE_LOCK` first.
		 */
		// phpcs:ignore
		$free = $wpdb->get_var(
			$wpdb->prepare( "SELECT IS_FREE_LOCK( SHA1( %s ) )", $lock_key )

		);

		if ( ! $free ) {
			return false;
		}

		// phpcs:ignore
		$acquired = $wpdb->get_var(
			$wpdb->prepare( "SELECT GET_LOCK( SHA1( %s ),%d )", $lock_key, $timeout )

		);

		if ( false === $acquired ) {
			// Only log errors, a failure to acquire lock is not an error.
			$log_data = [
				'message' => 'Error while trying to acquire lock.',
				'key'     => $lock_key,
				'error'   => $wpdb->last_error
			];
			do_action( 'tribe_log', 'debug', __CLASS__, $log_data );

			return false;
		}

		return true;
	}

	/**
	 * Releases the database lock of the record.
	 *
	 * Release a not held db lock will return `null`, not `false`.
	 *
	 * @since TBD
	 *
	 * @param string $lock_key The name of the lock to release.
	 *
	 * @return bool Whether the lock was correctly released or not.
	 */
	protected function release_db_lock( $lock_key ) {
		global $wpdb;
		$released = $wpdb->query(
			$wpdb->prepare( "SELECT RELEASE_LOCK( SHA1( %s ) )", $lock_key )
		);

		if ( false === $released ) {
			$log_data = [
				'message' => 'Error while trying to release lock.',
				'key'     => $lock_key,
				'error'   => $wpdb->last_error
			];
			do_action( 'tribe_log', 'debug', __CLASS__, $log_data );

			return false;
		}

		return true;
	}
}
