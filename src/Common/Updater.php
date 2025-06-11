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
 */
class Updater extends Tribe__Updater {
	/**
	 * The key used to hold the TCMN version in the database.
	 *
	 * @since 5.6.1.1
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
	protected $reset_version = '3.9';

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
	 * @param ?string $current_version The current version of the plugin. Passed or provided from the Main class.
	 */
	public function __construct( $current_version = null ) {
		$this->current_version = ! empty( $current_version ) ? $current_version : Tribe__Main::VERSION;
	}

	/**
	 * Allows setting the version we are using for running tasks.
	 *
	 * @since 6.5.2
	 *
	 * @param string $version The version we want to use.
	 */
	public function set_version( string $version ) {
		$this->current_version = $version;
	}

	/**
	 * Gets the current version we are using for running tasks.
	 *
	 * @since 6.5.2
	 *
	 * @return string The current version we are using.
	 */
	public function get_version(): string {
		return $this->current_version;
	}

	/**
	 * Hook into admin init and run the update process.
	 *
	 * @since 5.6.1.1
	 */
	public function hook(): void {
		// Only run once.
		if ( did_action( 'tec_did_updates' ) ) {
			return;
		}

		if ( ! is_admin() ) {
			return;
		}

		// Dom't run on AJAX requests.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Don't run on autosaves.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! $this->update_required() ) {
			return;
		}

		$this->do_updates();
	}

	/**
	 * Run Updates for a Plugin.
	 * This is slighty modified from the parent to simplify a bit and to run a custom hook at the end.
	 *
	 * @since 5.6.1.1
	 */
	public function do_updates(): void {
		$this->clear_option_caches();

		$updates = $this->get_update_callbacks();

		uksort( $updates, 'version_compare' );

		// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedCatchEmpty
		try {
			foreach ( $updates as $version => $callback ) {
				if (
					version_compare( $version, $this->current_version, '<=' )
					&& $this->is_version_in_db_less_than( $version )
				) {
					call_user_func( $callback );
				}
			}

			foreach ( $this->get_constant_update_callbacks() as $callback ) {
				call_user_func( $callback );
			}

			$this->update_version_option( $this->current_version );
		} catch ( \Exception $e ) {
			// We want fail silently, but it should try again next time.
		}
		// phpcs:enable Generic.CodeAnalysis.EmptyStatement.DetectedCatchEmpty

		do_action( 'tec_did_updates' );
	}

	/**
	 * Returns an array of callbacks with version strings as keys.
	 * Any key higher than the version recorded in the DB
	 * and lower than $this->current_version will have its callback called.
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
