<?php
/**
 * Common Updater.
 *
 * @since 5.6.1.1
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use Tribe__Updater;
use Tribe__Main;

/**
 * Class Updater
 *
 * @since 5.6.1.1
 *
 */
class Updater extends Tribe__Updater {
	/**
	 * The key used to hold the TCMN version in the database.
	 * 
	 * @var string
	 */
	protected $version_option = 'tec-schema-version';

	/**
	 * The version to reset to when a reset() is called.
	 * 
	 * @since 5.6.1.1
	 * 
	 * @var string
	 */
	protected $reset_version   = '3.9';

	/**
	 * The current version of the plugin. As recorded in the database.
	 * 
	 * @since 5.6.1.1
	 * 
	 * @var string
	 */
	protected $current_version = 0;

	/**
	 * Instantiates the class and set the current version to the passed version 
	 * or the one found in the Tribe__Main class.
	 * 
	 * @since 5.6.1.1
	 * 
	 * @param ?string $current_version
	 */
	public function __construct( $current_version = null) {
		$this->current_version = ! empty( $current_version ) ? $current_version : Tribe__Main::VERSION;
	}

	/**
	 * Run Updates for a Plugin
	 *
	 * @since 5.6.1.1
	 *
	 */
	public function do_updates() {
		$this->clear_option_caches();
		$updates = $this->get_update_callbacks();
		uksort( $updates, 'version_compare' );

		try {
			foreach ( $updates as $version => $callback ) {
				if (  ! $this->is_new_install() && $this->is_version_in_db_less_than( $version ) ) {
					call_user_func( $callback );
				}
			}

			foreach ( $this->get_constant_update_callbacks() as $callback ) {
				call_user_func( $callback );
			}

			$this->update_version_option( $this->current_version );
		} catch ( \Exception $e ) {
			// fail silently, but it should try again next time.
		}
	}

	/**
	 * Returns an array of callbacks with version strings as keys.
	 * Any key higher than the version recorded in the DB
	 * and lower than $this->current_version will have its
	 * callback called.
	 *
	 * @since 5.6.1.1
	 *
	 * @return array
	 */
	public function get_update_callbacks(): array {
		return [
			'6.5.1.1' => [ $this, 'reset_pue_notices' ],
		];
	}

	/**
	 * Resets the `tribe_pue_key_notices` option.
	 * 
	 * @since 6.5.1.1
	 */
	public function reset_pue_notices(): void {
		delete_option( 'tribe_pue_key_notices' );
	}
}