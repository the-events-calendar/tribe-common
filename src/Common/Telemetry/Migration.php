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

	/**
	 * Determine if we are opted-in to Freemius
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function is_opted_in(): bool {
		$fs_active_plugins = get_option( 'fs_active_plugins' );

		if ( empty( $fs_active_plugins ) ) {
			return false;
		}

		foreach( $this->our_plugins as $plugin ) {
			if ( isset( $fs_active_plugins->plugin[$plugin] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Whether the class should load/run.
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function should_load(): bool {
		// If we're not opted in to Freemius, bail.
		if ( ! $this->is_opted_in() ) {
			return false;
		}

		/**
		 * Allows filtering of whether the class should load/run.
		 */
		return apply_filters( 'tec_telemetry_migration_should_load', true );
	}

	/**
	 * Detect if the user has opted in to Freemius and auto-opt them in to Telemetry.
	 *
	 * @since TBD
	 */
	public function migrate_existing_opt_in(): void {
		// Let's reduce the amount this triggers.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! $this->should_load() ) {
			return;
		}

		$fs_active_plugins = get_option( 'fs_active_plugins' );

		// Bail if empty.
		if ( empty( $fs_active_plugins ) ) {
			return;
		}

		// Clean up our list.
		$this->remove_inactive_plugins( $fs_active_plugins );

		// Bail if none of our plugins are present.
		if ( ! count( $this->our_plugins ) ) {
			return;
		}

		/**
		 * Allows plugins to hook in and perform actions (like display a notice) when
		 * the user is automatically opted in to Telemetry.
		 *
		 * We also use this to trigger the actual auto-opt-in at the default priority.
		 *
		 * @since TBD
		 */
		do_action( 'tec_telemetry_auto_opt_in' );

		$fs_accounts = get_option( 'fs_accounts' );

		// Store these for later.
		update_option( $this->fs_accounts_slug, $fs_accounts );

		// If only our plugins are present, short-cut and delete everything.
		if ( count( $this->our_plugins ) === count( $fs_active_plugins->plugins ) ) {
			$this->remove_all_freemius();

			return;
		}

		// Remove us from fs_active_plugins.
		$this->handle_fs_active_plugins( $fs_active_plugins );

		// Remove us from fs_accounts.
		$this->handle_fs_accounts( $fs_accounts );

	}

	/**
	 * Filters our list of plugins to only the ones Freemius shows as active
	 *
	 * @since TBD
	 *
	 * @param Object $fs_active_plugins The stored list of active plugins from Freemius.
	 */
	private function remove_inactive_plugins( $fs_active_plugins ): void {
		$freemius_plugins = $fs_active_plugins->plugins;

		foreach( $this->our_plugins as $plugin ) {
			if ( ! isset( $freemius_plugins[$plugin] ) ) {
				unset( $this->our_plugins[$plugin] );
			}
		}
	}

	private function handle_fs_active_plugins( $fs_active_plugins ): void {
		update_option( $this->fs_plugins_slug, $fs_active_plugins );

		foreach ( $this->our_plugins as $plugin ) {
			unset( $fs_active_plugins->plugins[$plugin] );

			if ( ! empty( $fs_active_plugins->newest->sdk_path ) && $fs_active_plugins->newest->sdk_path === $plugin ) {
				unset( $fs_active_plugins->newest );
			}
		}

		// Update the option in the database with our edits.
		update_option( 'fs_active_plugins', $fs_active_plugins );
	}

	/**
	 * Removes our plugins from the fs_accounts option.
	 *
	 * @since TBD
	 *
	 * @param [type] $fs_accounts
	 */
	private function handle_fs_accounts( $fs_accounts ): void {
		foreach ( $this->our_plugins as $plugin ) {
			$fs_accounts = $this->strip_plugin_from_fs_accounts( $plugin, $fs_accounts );
		}

		// Update the option in the database with our edits.
		update_option( 'fs_accounts', $fs_accounts );
	}

	/**
	 * Removes all freemius options from the database.
	 * Only used if we were the only active plugin.
	 *
	 * @since TBD
	 */
	private function remove_all_freemius(): void {
		delete_option( 'fs_active_plugins' );
		delete_option( 'fs_accounts' );
		delete_option( 'fs_api_cache' );
		delete_option( 'fs_debug_mode' );
		delete_option( 'fs_gdpr' );
	}

	/**
	 * Contains all the logic for stripping our plugins from the fs_accounts option.
	 *
	 * @since TBD
	 *
	 * @param string $plugin
	 * @param array<string,mixed> $fs_accounts
	 *
	 * @return array<string,mixed>
	 */
	private function strip_plugin_from_fs_accounts( $plugin, $fs_accounts ): array {
		$plugin = str_replace( '/common/vendor/freemius', '', $plugin );

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

	/**
	 * Opts the user in to Telemetry.
	 *
	 * @since TBD
	 */
	public function auto_opt_in() {
		$Opt_In_Subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
		$Opt_In_Subscriber->opt_in( Telemetry::get_stellar_slug() );
	}
}
