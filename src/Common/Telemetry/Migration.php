<?php
/**
 * Handles Telemetry migration from Freemius.
 *
 * @since   5.1.0
 *
 * @package TEC\Common\Telemetry
 */
namespace TEC\Common\Telemetry;

use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Subscriber;
use Tribe__Utils__Array as Arr;

/**
 * Class Migration
 *
 * @since   5.1.0

 * @package TEC\Common\Telemetry
 */
class Migration {
	/**
	 * The key we back up original fs_accounts data to.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	public static $fs_accounts_slug = 'tec_freemius_accounts_archive';

	/**
	 * The key we back up modified fs_accounts data to.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	public static $fs_accounts_data = 'tec_freemius_accounts_data_archive';

	/**
	 * The key we back up fs_active_plugins data to.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	public static $fs_plugins_slug = 'tec_freemius_plugins_archive';

	/**
	 * Where Freemius stores the active plugins.
	 *
	 * @since 5.1.4
	 *
	 * @var string
	 */
	protected static string $key_fs_active_plugins = 'fs_active_plugins';

	/**
	 * List of our plugins to check for.
	 *
	 * @since 5.1.0
	 *
	 * @var array
	 */
	public $our_plugins = [
		'the-events-calendar',
		'event-tickets'
	];

	/**
	 * Placeholder for if the user has opted in via Freemius.
	 *
	 * @since 5.1.0
	 *
	 * @var boolean
	 */
	public static $is_opted_in;

	/**
	 * Get and massage the fs_accounts
	 *
	 * @since 5.1.0
	 *
	 * @return array
	 */
	private function get_fs_accounts() {
		// If we've already been here for some reason, don't do it all again.
		$data = get_option( self::$fs_accounts_data );
		if ( ! empty( $data ) ) {
			return $data;
		}

		global $wpdb;
		$fs_accounts = $wpdb->get_var( "SELECT `option_value` FROM $wpdb->options WHERE `option_name` = 'fs_accounts' LIMIT 1" );


		if ( empty( $fs_accounts ) || $fs_accounts instanceof \WP_Error ) {
			return [];
		}

		// Store original here as backup.
		update_option( static::$fs_accounts_slug, $fs_accounts );

		// Prevent issues with incomplete classes
		$fs_accounts = preg_replace_callback(
			'/O:(\d+):"([^"]+)":([^:]+):\{/m',
			static function( $matches ) {
				if ( $matches[2] === 'stdClass' ) {
					return $matches[0];
				}

				$key_slug = "tec_fs_key";
				$key_slug_count = strlen( $key_slug );
				$new_size = $matches[3] + 1;

				return "a:{$new_size}:{s:{$key_slug_count}:\"{$key_slug}\";s:{$matches[1]}:\"{$matches[2]}\";";
			},
			$fs_accounts
		);

		$fs_accounts = maybe_unserialize( $fs_accounts );

		// Store the modified data here.
		update_option( static::$fs_accounts_data, $fs_accounts );

		// return the modified data.
		return $fs_accounts;
	}

	/**
	 * Determine if we are opted-in to Freemius
	 *
	 * @since 5.1.0
	 *
	 * @return boolean
	 */
	public function is_opted_in(): bool {
		if ( ! is_null( self::$is_opted_in ) ) {
			return self::$is_opted_in;
		}

		$fs_accounts = $this->get_fs_accounts();

		$sites = Arr::get( $fs_accounts, 'sites', [] );

		if ( empty( $sites ) ) {
			self::$is_opted_in = false;
			return false;
		}

		$disconnected = [];

		foreach ( $this->our_plugins as $plugin ) {
			if ( ! isset( $sites[ $plugin ] ) ) {
				continue;
			}

			$disconnected[] = (bool) Arr::get( $sites, [ $plugin, 'is_disconnected' ] );
		}

		if ( 1 > count( $disconnected ) ) {
			self::$is_opted_in = false;
			return false;
		}

		self::$is_opted_in = in_array( false, $disconnected, true );
		return self::$is_opted_in;
	}

	/**
	 * Whether the class should load/run.
	 *
	 * @since 5.1.0
	 *
	 * @return boolean
	 */
	public function should_load(): bool {
		// If we've already checked, bail.
		if ( get_option( self::$fs_accounts_data ) ) {
			return false;
		}

		// When we have an archived plugin list we can bail.
		if ( get_option( self::$fs_plugins_slug ) ) {
			return false;
		}

		$fs_active_plugins = get_option( self::$key_fs_active_plugins );

		// Bail if empty.
		if ( empty( $fs_active_plugins ) ) {
			return false;
		}

		/**
		 * Allows filtering of whether the class should load/run.
		 *
		 * @since 5.1.0
		 *
		 * @param bool $should_load Whether the class should load/run.
		 */
		return apply_filters( 'tec_telemetry_migration_should_load', true );
	}

	/**
	 * Detect if the user has opted in to Freemius and auto-opt them in to Telemetry.
	 *
	 * @since 5.1.0
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

		$fs_active_plugins = get_option( self::$key_fs_active_plugins );

		// Clean up our list.
		$this->remove_inactive_plugins( $fs_active_plugins );

		// Bail if none of our plugins are present.
		if ( ! count( $this->our_plugins ) ) {
			return;
		}

		$this->auto_opt_in();

		// Remove us from fs_active_plugins.
		$this->handle_fs_active_plugins( $fs_active_plugins );
	}

	/**
	 * Filters our list of plugins to only the ones Freemius shows as active
	 *
	 * @since 5.1.0
	 *
	 * @param Object $fs_active_plugins The stored list of active plugins from Freemius.
	 */
	private function remove_inactive_plugins( $fs_active_plugins ): void {
		$freemius_plugins = ! empty( $fs_active_plugins->plugins ) ? (array) $fs_active_plugins->plugins : [];

		foreach ( $this->our_plugins as $plugin ) {
			if ( ! isset( $freemius_plugins[ $plugin ] ) ) {
				unset( $this->our_plugins[ $plugin ] );
			}
		}
	}

	/**
	 * Handles our entries in the fs_active_plugins option.
	 * Removes them from the Freemius option and stores a backup of the original.
	 *
	 * @since 5.1.0
	 *
	 * @param Object $fs_active_plugins
	 * @return void
	 */
	private function handle_fs_active_plugins( $fs_active_plugins ): void {
		// Store a backup of the original option.
		update_option( self::$fs_plugins_slug, $fs_active_plugins );

		foreach ( $this->our_plugins as $plugin ) {
			$plugin .= '/common/vendor/freemius';

			unset( $fs_active_plugins->plugins[ $plugin ] );

			if ( ! empty( $fs_active_plugins->newest->sdk_path ) && $fs_active_plugins->newest->sdk_path === $plugin ) {
				unset( $fs_active_plugins->newest );
			}
		}

		// Update the Freemius option in the database with our edits.
		update_option( self::$key_fs_active_plugins, $fs_active_plugins );
	}

	/**
	 * Opts the user in to Telemetry.
	 *
	 * @since 5.1.0
	 *
	 */
	public function auto_opt_in() {
		$opt_in = $this->is_opted_in();

		$opt_in_subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
		$telemetry         = tribe( Telemetry::class );
		$slug              = Telemetry::get_stellar_slug();

		$opt_in_subscriber->opt_in( $slug );
		$telemetry->register_tec_telemetry_plugins( $opt_in );

		/**
		 * Allows plugins to hook in and perform actions (like display a notice) when
		 * the user is automatically opted in to Telemetry.
		 *
		 * We also use this to trigger the actual auto-opt-in at the default priority.
		 *
		 * @since 5.1.0
		 */
		do_action( 'tec_telemetry_auto_opt_in' );

		// Disable the modal on all migrations.
		$telemetry::disable_modal( $slug, 0 );
	}
}
