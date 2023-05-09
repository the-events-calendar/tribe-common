<?php
/**
 * Handles Telemetry migration from Freemius.
 *
 * @since   5.0.17
 *
 * @package TEC\Common\Telemetry
 */
namespace TEC\Common\Telemetry;

use Automattic\WooCommerce\Utilities\ArrayUtil;
use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Subscriber;

/**
 * Class Migration
 *
 * @since   5.0.17

 * @package TEC\Common\Telemetry
 */
final class Migration {
	/**
	 * The key we back up fs_accounts data to.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $fs_accounts_slug = 'tec_freemius_accounts_archive';

	/**
	 * The key we back up fs_active_plugins data to.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $fs_plugins_slug = 'tec_freemius_plugins_archive';

	/**
	 * Placeholder for fs_accounts data
	 *
	 * @since TBD
	 *
	 * @var [type]
	 */
	private $fs_accounts;

	/**
	 * List of our plugins to check for.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $our_plugins = [
		'the-events-calendar',
		'event-tickets'
	];

	/**
	 * Determine if we are opted-in to Freemius
	 *
	 * @since 5.0.17
	 *
	 * @return boolean
	 */
	public function is_opted_in(): bool {
		global $wpdb;
		$fs_accounts = $wpdb->get_results( "SELECT `option_value` FROM $wpdb->options WHERE `option_name` = 'fs_accounts'", ARRAY_A );

		if ( empty( $fs_accounts ) || $fs_accounts instanceof \WP_Error ) {
			return false;
		}

		$fs_accounts = array_pop( $fs_accounts );

		// Prevent issues with incomplete classes
		$result = preg_replace(
			'/O:(\d+):"(?:[^:]+)":/m',
			'a:',
			$fs_accounts['option_value']
		);
		$result = substr( $result, stripos( $result, 'sites' ) );

		foreach ( $this->our_plugins as $plugin ) {
			$pos = stripos( $result, $plugin );
			if ( false !== $pos ) {
				$pos = stripos( $result, 'is_disconnected', $pos );
				if ( false !== $pos ) {
					$pos = stripos( $result, 'b:', $pos );
					if ( false !== $pos ) {
						$pos = stripos( $result, '0', $pos );
						if ( false !== $pos ) {
							return true;
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * Whether the class should load/run.
	 *
	 * @since 5.0.17
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
	 * @since 5.0.17
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
		 * @since 5.0.17
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
	 * @since 5.0.17
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

	/**
	 * Handles our entries in the fs_active_plugins option.
	 *
	 * @since 5.0.17
	 *
	 * @param Object $fs_active_plugins
	 * @return void
	 */
	private function handle_fs_active_plugins( $fs_active_plugins ): void {
		// Store a backup of the original option.
		update_option( $this->fs_plugins_slug, $fs_active_plugins );

		foreach ( $this->our_plugins as $plugin ) {
			$plugin .= '/common/vendor/freemius';

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
	 * @since 5.0.17
	 *
	 * @param array<string,mixed> $fs_accounts
	 */
	private function handle_fs_accounts( $fs_accounts ): void {
		// Store a backup of the original option.
		update_option( $this->fs_accounts_slug, $fs_accounts );

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
	 * @since 5.0.17
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
	 * @since 5.0.17
	 *
	 * @param string $plugin
	 * @param array<string,mixed> $fs_accounts
	 *
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

	/**
	 * Opts the user in to Telemetry.
	 *
	 * @since 5.0.17
	 */
	public function auto_opt_in() {
		$Opt_In_Subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
		$Opt_In_Subscriber->opt_in( Telemetry::get_stellar_slug() );
	}
}
