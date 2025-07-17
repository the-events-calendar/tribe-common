<?php
/**
 * Handles the key-value cache API actions and filters.
 *
 * @since TBD
 *
 * @package Common\Key_Value_Cache;
 */

namespace TEC\Common\Key_Value_Cache;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Key_Value_Cache\Table\Schema;
use TEC\Common\StellarWP\Schema\Register;
use TEC\Common\StellarWP\Schema\Tables\Contracts\Schema_Interface;

/**
 * Class Controller.
 *
 * @since TBD
 *
 * @package Common\Key_Value_Cache;
 */
class Controller extends Controller_Contract {
	/**
	 * The action name for the clear expired entries action.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	const CLEAR_EXPIRED_ACTION = 'tec_clear_expired_key_value_cache';

	/**
	 * A reference to the key-value cache table schema. Null if not using the table.
	 *
	 * @since TBD
	 *
	 * @var Schema_Interface|null
	 */
	private ?Schema_Interface $table_schema;

	/**
	 * Registers the actions and filters required by the key-value cache functionality.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$wp_using_ext_object_cache = wp_using_ext_object_cache();

		/**
		 * Whether to force the use of the table cache when real object caching is present or not.
		 * By default, the table cache is not used when real object caching is present.
		 *
		 * @since TBD
		 *
		 * @param bool $force_use_of_table_cache Whether to force the use of the table cache when real object caching is present or not.
		 */
		$force_use_of_table_cache = apply_filters( 'tec_key_value_cache_force_use_of_table_cache', false );

		if ( $wp_using_ext_object_cache && ! $force_use_of_table_cache ) {
			$this->container->singleton( Key_Value_Cache_Interface::class, Object_Cache::class );

			// If we were using the table cache, we need to unschedule the cron event.
			if ( ! wp_next_scheduled( self::CLEAR_EXPIRED_ACTION ) ) {
				wp_unschedule_event( time(), self::CLEAR_EXPIRED_ACTION );
			}
		} else {
			if ( doing_action( 'plugins_loaded' ) || did_action( 'plugins_loaded' ) ) {
				$this->table_schema = Register::table( Schema::class );
			} else {
				add_action(
					'plugins_loaded',
					function () {
						$this->table_schema = Register::table( Schema::class );
					} 
				);
			}

			$this->container->singleton( Key_Value_Cache_Interface::class, Key_Value_Cache_Table::class );

			// Schedule an action to clear the table of expired entries.
			if ( ! wp_next_scheduled( self::CLEAR_EXPIRED_ACTION ) ) {
				wp_schedule_event( time(), 'twicedaily', self::CLEAR_EXPIRED_ACTION );
			}

			add_action( self::CLEAR_EXPIRED_ACTION, [ $this, 'clear_expired' ] );
		}
	}

	/**
	 * Unregisters the actions and filters required by the key-value cache functionality.
	 *
	 * The method is unscheduling the cron event as well. While the purpose of the unregister method
	 * is to control the controller filters and actions in the context of the current request, this
	 * will not prevent a following request from scheduling it again.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_action( self::CLEAR_EXPIRED_ACTION, [ $this, 'clear_expired' ] );

		if ( ! wp_next_scheduled( self::CLEAR_EXPIRED_ACTION ) ) {
			wp_unschedule_event( time(), self::CLEAR_EXPIRED_ACTION );
		}
	}

	/**
	 * Clears the expired entries from the table if using the table and it exists.
	 *
	 * @since TBD
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
}
