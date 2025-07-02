<?php
/**
 * Installed Plugins Conditional Trait to check plugin activation and licensing status.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Conditional_Content\Traits;

/**
 * Trait for installed plugin-related conditional functionality.
 *
 * @since TBD
 */
trait Installed_Plugins_Conditional_Trait {

	/**
	 * Checks if a specific plugin is both active and considered "licensed" by the system.
	 *
	 * @since TBD
	 *
	 * @param string $plugin_slug The plugin slug to check.
	 *
	 * @return bool True if the plugin is active and licensed, false otherwise.
	 */
	public function is_plugin_active_and_licensed( string $plugin_slug ): bool {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! is_plugin_active( $plugin_slug ) ) {
			return false;
		}

		// If active, now check if it's licensed using the PUE system.
		// Extract plugin slug from path (removing file extension).
		$slug_parts = explode( '/', $plugin_slug );
		$base_slug  = reset( $slug_parts );

		// Check if the Tribe__PUE__Checker class is available.
		if ( ! class_exists( 'Tribe__PUE__Checker' ) ) {
			return false;
		}

		// Get the PUE Checker for this plugin.
		$pue_instance = null;
		if ( class_exists( 'Tribe__Plugins' ) ) {
			$plugins     = tribe( 'plugins' );
			$plugin_list = $plugins->get_list();

			// Find the matching plugin in the list.
			foreach ( $plugin_list as $plugin ) {
				if ( ! empty( $plugin['path'] ) && strpos( $plugin['path'], $base_slug ) !== false ) {
					// Found the plugin, now check if it has a PUE checker.
					if ( ! empty( $plugin['class'] ) && class_exists( $plugin['class'] ) ) {
						if ( property_exists( $plugin['class'], 'pue_checker' ) ) {
							$plugin_instance = call_user_func( [ $plugin['class'], 'instance' ] );
							$pue_instance    = $plugin_instance->pue_checker;
							break;
						}
					}
				}
			}
		}

		// If we couldn't find the PUE instance, try a fallback method.
		if ( empty( $pue_instance ) ) {
			// Try to get the PUE instance from the global tribe plugins.
			global $tribe_pue_checkers;

			if ( ! empty( $tribe_pue_checkers ) && is_array( $tribe_pue_checkers ) ) {
				foreach ( $tribe_pue_checkers as $pue_checker ) {
					if ( $pue_checker instanceof \Tribe__PUE__Checker && strpos( $pue_checker->get_slug(), $base_slug ) !== false ) {
						$pue_instance = $pue_checker;
						break;
					}
				}
			}
		}

		// If we have a PUE instance, check the key status.
		if ( $pue_instance instanceof \Tribe__PUE__Checker ) {
			// Check if the plugin has a valid license key.
			$key = $pue_instance->get_key();

			if ( empty( $key ) ) {
				return false;
			}

			// Option name structure is 'pue_key_status_PLUGIN-SLUG_example.org'.
			$option_name = 'pue_key_status_' . $pue_instance->get_slug() . '_' . $pue_instance->get_site_domain();
			$key_status  = get_option( $option_name );

			// Valid status means the key is active.
			return 'valid' === $key_status;
		}

		/**
		 * Filters whether a plugin should be considered licensed when the license status cannot be determined.
		 *
		 * @since TBD
		 *
		 * @param bool   $is_licensed Default is false when status can't be determined.
		 * @param string $plugin_slug The plugin slug being checked.
		 */
		return apply_filters( 'tec_admin_conditional_content_is_plugin_licensed', false, $plugin_slug );
	}
}
