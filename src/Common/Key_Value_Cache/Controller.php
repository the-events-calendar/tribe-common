<?php
/**
 * Handles the key-value cache API actions and filters.
 *
 * @since 6.9.1
 *
 * @package Common\Key_Value_Cache;
 */

namespace TEC\Common\Key_Value_Cache;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Key_Value_Cache\Table\Schema;
use TEC\Common\StellarWP\Schema\Register;
use TEC\Common\StellarWP\Schema\Tables\Contracts\Table as Schema_Interface;

/**
 * Class Controller.
 *
 * @since 6.9.1
 *
 * @package Common\Key_Value_Cache;
 */
class Controller extends Controller_Contract {
	/**
	 * The action name for the clear expired entries action.
	 *
	 * @since 6.9.1
	 *
	 * @var string
	 */
	const CLEAR_EXPIRED_ACTION = 'tec_clear_expired_key_value_cache';

	/**
	 * A reference to the key-value cache table schema. Null if not using the table.
	 *
	 * @since 6.9.1
	 *
	 * @var Schema_Interface|null
	 */
	private ?Schema_Interface $table_schema;

	/**
	 * Registers the actions and filters required by the key-value cache functionality.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$wp_using_ext_object_cache = wp_using_ext_object_cache();

		/**
		 * Whether to force the use of the table cache when real object caching is present or not.
		 * By default, the table cache is not used when real object caching is present.
		 *
		 * @since 6.9.1
		 *
		 * @param bool $force_use_of_table_cache Whether to force the use of the table cache when real object caching is present or not.
		 */
		$force_use_of_table_cache = apply_filters( 'tec_key_value_cache_force_use_of_table_cache', false );

		if ( $wp_using_ext_object_cache && ! $force_use_of_table_cache ) {
			$this->container->singleton( Key_Value_Cache_Interface::class, Object_Cache::class );

			// If we were using the table cache, we need to unschedule the cron event.
			add_action( 'init', [ $this, 'unschedule_table_clean_action' ] );
		} else {
			$this->register_table_schema();
			$this->container->singleton( Key_Value_Cache_Interface::class, Key_Value_Cache_Table::class );

			// Schedule an action to clear the table of expired entries.
			add_action( 'init', [ $this, 'schedule_table_clean_action' ] );

			add_action( self::CLEAR_EXPIRED_ACTION, [ $this, 'clear_expired' ] );
		}
	}

	/**
	 * Register the custom table schema.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	public function register_table_schema(): void {
		$this->table_schema = Register::table( Schema::class );
	}

	/**
	 * Unregisters the actions and filters required by the key-value cache functionality.
	 *
	 * The method is unscheduling the cron event as well. While the purpose of the unregister method
	 * is to control the controller filters and actions in the context of the current request, this
	 * will not prevent a following request from scheduling it again.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_action( self::CLEAR_EXPIRED_ACTION, [ $this, 'clear_expired' ] );
		remove_action( 'plugins_loaded', [ $this, 'register_table_schema' ] );
		remove_action( 'init', [ $this, 'schedule_table_clean_action' ] );

		$this->unschedule_table_clean_action();
	}

	/**
	 * Clears the expired entries from the table if using the table and it exists.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	public function clear_expired(): void {
		if ( $this->table_schema === null || ! $this->table_schema->exists() ) {
			// Not using the table or the table does not exist.
			return;
		}

		$this->container->make( Key_Value_Cache_Table::class )->clear_expired();
	}

	/**
	 * Unschedules the key-value table clean action.
	 *
	 * The method will unschedule the action from both Action Scheduler and WP Cron.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	public function unschedule_table_clean_action(): void {
		if (
			function_exists( 'as_has_scheduled_action' )
			&& as_has_scheduled_action( self::CLEAR_EXPIRED_ACTION )
		) {
			// Unschedule from Action Scheduler, if possible.
			as_unschedule_action( self::CLEAR_EXPIRED_ACTION );
		}

		// Unschedule from WP Cron if present.
		$next_scheduled = wp_next_scheduled( self::CLEAR_EXPIRED_ACTION );
		if ( $next_scheduled ) {
			wp_unschedule_event( $next_scheduled, self::CLEAR_EXPIRED_ACTION );
		}
	}

	/**
	 * Schedules the key-value table clean action.
	 *
	 * @since 6.9.1
	 *
	 * @return void
	 */
	public function schedule_table_clean_action(): void {
		if (
			function_exists( 'as_has_scheduled_action' )
			&& function_exists( 'as_schedule_single_action' )
		) {
			// Prefer using Action Scheduler if available.
			if ( ! as_has_scheduled_action( self::CLEAR_EXPIRED_ACTION ) ) {
				as_schedule_single_action( time() + 12 * HOUR_IN_SECONDS, self::CLEAR_EXPIRED_ACTION );
			}

			return;
		}

		if ( ! wp_next_scheduled( self::CLEAR_EXPIRED_ACTION ) ) {
			wp_schedule_event( time(), 'twicedaily', self::CLEAR_EXPIRED_ACTION );
		}
	}
}
