<?php
/**
 * Handles In-App Notifications display logic.
 *
 * @since   6.4.0
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Status;
use TEC\Common\Telemetry\Telemetry as Common_Telemetry;

/**
 * Class Conditionals
 *
 * @since   6.4.0

 * @package TEC\Common\Notifications
 */
class Conditionals {
	/**
	 * Get the current user status based on Telemetry and IAN opt-in.
	 *
	 * @since 6.4.0
	 *
	 * @return bool
	 */
	public static function get_opt_in(): bool {
		// Trigger this before we try use the Telemetry value.
		tribe( Common_Telemetry::class )->normalize_optin_status();

		// We don't care what the value stored in tribe_options is - give us Telemetry's Opt_In\Status value.
		$status    = Config::get_container()->get( Status::class );
		$telemetry = $status->get() === $status::STATUS_ACTIVE;

		// Check if the user has opted in to telemetry, then If Telemetry is off, return the IAN opt-in value.
		return apply_filters( 'tec_common_ian_opt_in', tribe_is_truthy( $telemetry ) || tribe_is_truthy( tribe_get_option( 'ian-notifications-opt-in', false ) ) );
	}

	/**
	 * Check if the conditions are met for the notifications.
	 *
	 * @since 6.4.0
	 *
	 * @param array $feed The feed of notifications from the server.
	 *
	 * @return array The notifications that meet the conditions.
	 */
	public static function filter_feed( $feed ): array {
		$notifications = array_filter(
			$feed,
			function ( $item ) {
				if ( empty( $item['conditions'] ) || ! is_array( $item['conditions'] ) ) {
					return true;
				}

				$matches = [];
				foreach ( $item['conditions'] as $condition ) {
					if ( 0 === strpos( $condition, 'wp_version' ) ) {
						$version = substr( $condition, strlen( 'wp_version' ) );

						$matches['wp_version'] = self::check_wp_version( $version );
					} elseif ( 0 === strpos( $condition, 'php_version' ) ) {
						$version = substr( $condition, strlen( 'php_version' ) );

						$matches['php_version'] = self::check_php_version( $version );
					} elseif ( 0 === strpos( $condition, 'plugin_version' ) ) {
						$split   = explode( ':', $condition );
						$plugins = explode( ',', $split[1] );

						$matches['plugin_version'] = self::check_plugin_version( (array) $plugins );
					}
				}

				return ! in_array( false, $matches, true );
			}
		);

		// Ensure slugs are always unique.
		$notifications = array_map(
			function ( $item ) {
				$item['slug'] = $item['id'] . '_' . $item['slug'];

				return $item;
			},
			$notifications
		);

		return array_values( $notifications );
	}

	/**
	 * Check if the PHP version is correct.
	 *
	 * @since 6.4.0
	 *
	 * @param string $version The version to check against.
	 *
	 * @return bool
	 */
	public static function check_php_version( $version ): bool {
		if ( empty( $version ) ) {
			return true;
		}

		$version = preg_split( '/(?=\d)/', $version, 2 );

		return (bool) apply_filters( 'tec_common_ian_conditional_php', version_compare( PHP_VERSION, $version[1], $version[0] ?? '>=' ) );
	}

	/**
	 * Check if the WP version is correct.
	 *
	 * @since 6.4.0
	 *
	 * @param string $version The version to check against.
	 *
	 * @return bool
	 */
	public static function check_wp_version( $version ): bool {
		if ( empty( $version ) ) {
			return true;
		}

		global $wp_version;
		$version = preg_split( '/(?=\d)/', $version, 2 );

		return (bool) apply_filters( 'tec_common_ian_conditional_wp', version_compare( $wp_version, $version[1], $version[0] ?? '>=' ) );
	}

	/**
	 * Check if the plugin version matches requirements.
	 *
	 * @since 6.4.0
	 *
	 * @param array $plugins The required plugins to check.
	 *
	 * @return bool
	 */
	public static function check_plugin_version( array $plugins ): bool {
		// If no plugins are specified as a condition, we can assume the condition is met.
		if ( empty( $plugins ) ) {
			return true;
		}

		// Get all installed plugins data, keyed by plugin file name.
		$all_plugins = get_plugins();

		foreach ( $plugins as $plugin ) {
			$pieces = explode( '@', $plugin );

			// Find the actual plugin directory/file from the list.
			$plugin_file = '';
			foreach ( $all_plugins as $k => $data ) {
				// If the plugin directory/file_name contains the required slug.
				if ( strpos( $k, $pieces[0] ) !== false ) {
					$plugin_file = $k;
					$installed   = $data['Version'];
					break;
				}
			}

			// We didn't find the plugin in the list of installed plugins.
			if ( empty( $plugin_file ) ) {
				return false;
			}

			// If the plugin is not active, the condition is not met.
			if ( ! is_plugin_active( $plugin_file ) ) {
				return false;
			}

			// Plugin is installed and active so compare its version to the required.
			$version = preg_split( '/(?=\d)/', $pieces[1], 2 );
			if ( ! version_compare( $installed, $version[1], $version[0] ?: '>=' ) ) {
				return false;
			}
		}

		// All plugins met the conditions.
		return true;
	}
}
