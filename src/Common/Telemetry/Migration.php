<?php
/**
 * Handles Telemetry migration from Freemius.
 *
 * @since   TBD
 *
 * @package TEC\Common\Telemetry
 */
namespace TEC\Common\Telemetry;

use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Subscriber;

/**
 * Class Migration
 *
 * @since   TBD

 * @package TEC\Common\Telemetry
 */
final class Migration {
	public $fs_accounts_slug = 'tec_freemius_accounts_archive';
	public $fs_plugins_slug = 'tec_freemius_plugins_archive';
	public $our_plugins = [
		'the-events-calendar/common/vendor/freemius',
		'event-tickets/common/vendor/freemius'
	];

	public function should_load() {
		// If we're not opted in to Freemius, bail.
		if ( ! $this->is_opted_in() ) {
			return false;
		}

		// If we're opted in to Telemetry, bail.
		$status = Telemetry::get_status_object();
		if ( $status->get()  ) {
			return false;
		}
	}

	public function is_opted_in() {

	}

	/**
	 * Detect if the user has opted in to Freemius and auto-opt them in to Telemetry.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function migrate_existing_opt_in(): void {
		// Let's reduce the amount this triggers.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$fs_active_plugins = get_option( 'fs_active_plugins' );

		// Bail if empty.
		if ( empty( $fs_active_plugins ) ) {
			return;
		}

		// We're going to need these...
		$fs_accounts      = get_option( 'fs_accounts' );
		$freemius_plugins = $fs_active_plugins->plugins;

		foreach( $this->our_plugins as $plugin ) {
			if ( ! isset( $freemius_plugins[$plugin] ) ) {
				unset( $this->our_plugins[$plugin] );
			}
		}

		// Bail if none of our plugins are present.
		if ( ! count( $this->our_plugins ) ) {
			return;
		}

		// We opted in to Freemius, opt in to Telemetry.
		$this->auto_opt_in();
		// Allow for plugins to do something when automatically opting in. Like display a notice.
		// We use this to trigger the actual auto-opt-in.
		do_action( 'tec_telemetry_auto_opt_in' );

		// Store these for later.
		update_option( $this->fs_accounts_slug, $fs_accounts );
		update_option( $this->fs_plugins_slug, $fs_active_plugins );

		// If only our plugins are present, short-cut and delete everything.
		if ( count( $this->our_plugins ) === count( $freemius_plugins ) ) {
			$removed = true;
			delete_option( 'fs_active_plugins' );
			delete_option( 'fs_accounts' );

			return;
		}

		// Remove us from fs_active_plugins.
		if ( empty( $removed ) ) {
			foreach ( $this->our_plugins as $plugin ) {
				unset( $fs_active_plugins->plugins[$plugin] );

				if ( ! empty( $fs_active_plugins->newest->sdk_path ) && $fs_active_plugins->newest->sdk_path === $plugin ) {
					unset( $fs_active_plugins->newest );
				}
			}

			// Update the option in the database with our edits.
			update_option( 'fs_active_plugins', $fs_active_plugins );
		}

		// Remove us from fs_accounts.
		foreach ( $this->our_plugins as $plugin ) {
			$plugin = str_replace( '/common/vendor/freemius', '', $plugin );
			$fs_accounts = $this->strip_plugin_from_fs_accounts( $plugin, $fs_accounts );
		}

		// Update the option in the database with our edits.
		update_option( 'fs_accounts', $fs_accounts );
	}

	/**
	 * Contains all the logic for stripping our plugins from the fs_accounts option.
	 *
	 * @since TBD
	 *
	 * @param string $plugin
	 * @param array<string,mixed> $fs_accounts
	 * @return array<string,mixed>
	 */
	private function strip_plugin_from_fs_accounts( $plugin, $fs_accounts ): array {
		foreach( $fs_accounts[ 'id_slug_type_path_map' ] as $key => $data ) {
			if ( $data['slug'] === $plugin ) {
				unset( $fs_accounts[ 'id_slug_type_path_map' ][$key] );
			}
		}

		// these use the slug as the key.
		$straight_keys = [
			'plugins',
			'plugin_data',
			'plans',
			'admin_notices',
			'sites'
		];

		foreach( $straight_keys as $key ) {
			if ( isset( $fs_accounts[$key][$plugin] ) ) {
				unset( $fs_accounts[$key][$plugin] );
			}
		}

		// These use the path instead of the slug as the key.
		$plugin = $plugin . '/' . $plugin . '.php';
		if ( isset( $fs_accounts['file_slug_map'][$plugin] ) ) {
			unset( $fs_accounts['file_slug_map'][$plugin] );
		}

		if ( isset( $fs_accounts['active_plugins']->plugins[$plugin] ) ) {
			unset( $fs_accounts['active_plugins']->plugins[$plugin] );
		}

		// ...and return!
		return $fs_accounts;
	}

	private function auto_opt_in() {
		$Opt_In_Subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
		$Opt_In_Subscriber->opt_in( Telemetry::get_stellar_slug() );
	}
}
