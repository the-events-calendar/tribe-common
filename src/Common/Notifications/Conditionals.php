<?php
/**
 * Handles In-App Notifications display logic.
 *
 * @since   TBD
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
 * @since   TBD

 * @package TEC\Common\Notifications
 */
class Conditionals {
	/**
	 * Get the current user status based on Telemetry and IAN opt-in.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public static function get_opt_in(): bool {
		// Trigger this before we try use the Telemetry value.
		tribe( Common_Telemetry::class )->normalize_optin_status();

		// We don't care what the value stored in tribe_options is - give us Telemetry's Opt_In\Status value.
		$status = Config::get_container()->get( Status::class );
		$value  = $status->get() === $status::STATUS_ACTIVE;

		// First check if the user has opted in to telemetry.
		if ( tribe_is_truthy( $value ) ) {
			return true;
		}

		// If Telemetry is off, return the IAN opt-in value.
		return tribe_is_truthy( tribe_get_option( 'ian-notifications-opt-in', false ) );
	}

	/**
	 * Check if the conditions are met for the notifications.
	 *
	 * @since TBD
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
				foreach ( $item['conditions'] as $k => $condition ) {
					switch ( $k ) {
						case 'php_version':
							$matches['php_version'] = self::check_php_version( $condition['php_version'] );
							break;
						case 'wp_version':
							$matches['wp_version'] = self::check_wp_version( $condition['wp_version'] );
							break;
						case 'plugin_version':
							$matches['plugin_version'] = self::check_plugin_version( $condition['plugin_version'] );
							break;
					}
				}

				return ! in_array( false, $matches, true );
			}
		);

		return array_values( $notifications );
	}

	/**
	 * Check if the PHP version is correct.
	 *
	 * @since TBD
	 *
	 * @param string $php_version The version to check against.
	 *
	 * @return bool
	 */
	public static function check_php_version( $php_version ): bool {
		if ( empty( $php_version ) ) {
			return true;
		}

		$version = preg_split( '/(?=\d)/', $php_version, 2 );
		if ( ! version_compare( PHP_VERSION, $version[1], $version[0] ?? '>=' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the WP version is correct.
	 *
	 * @since TBD
	 *
	 * @param string $wp_version The version to check against.
	 *
	 * @return bool
	 */
	public static function check_wp_version( $wp_version ): bool {
		if ( empty( $wp_version ) ) {
			return true;
		}

		$version = preg_split( '/(?=\d)/', $wp_version, 2 );
		if ( ! version_compare( get_bloginfo( 'version' ), $version[1], $version[0] ?? '>=' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the plugin version is correct.
	 *
	 * @since TBD
	 *
	 * @param array $plugins The plugins to check against.
	 *
	 * @return bool
	 */
	public static function check_plugin_version( array $plugins ): bool {
		if ( empty( $plugins ) ) {
			return true;
		}

		foreach ( $plugins as $plugin ) {
			$pieces = explode( '@', $plugin );

			if ( ! is_plugin_active( $pieces[0] . '/' . $pieces[0] . '.php' ) ) {
				return false;
			}

			$version = preg_split( '/(?=\d)/', $pieces[1], 2 );
			if ( ! version_compare( get_plugin_data( $pieces[0] . '/' . $pieces[0] . '.php' )['Version'], $version[1], $version[0] ?? '>=' ) ) {
				return false;
			}
		}

		return true;
	}
}
