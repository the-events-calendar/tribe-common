<?php
/**
 * Installed Plugins Conditional Trait to check plugin activation and licensing status.
 *
 * Currently, this trait is used to check if a plugin is active and licensed.
 * Then modifies the content to display from the content matrix to show an ad for an uninstalled plugin.
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
	 * This method is required by Plugin_Suite_Conditional_Trait to determine ad creatives.
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

	/**
	 * Register the installed plugins content hook.
	 *
	 * @since TBD
	 *
	 * @param string $hook_name     The name of the filter to register.
	 * @param int    $priority      The priority of the filter.
	 * @param int    $accepted_args The number of arguments the filter accepts.
	 */
	protected function register_installed_plugins_content_hook( $hook_name, $priority = 10, $accepted_args = 2 ): void {
		/**
		 * Filters the content creative based on installed and licensed plugins.
		 *
		 * @since TBD
		 *
		 * @param array  $creative_rules_for_suite An associative array of creative rules for the current suite context,
		 * where keys are plugin slugs or 'default'.
		 * @param object $instance                 The promotional content instance.
		 */
		add_filter( $hook_name, [ $this, 'filter_installed_plugins_content_condition' ], $priority, $accepted_args );
	}

	/**
	 * Filter the content creative based on installed and licensed plugins.
	 *
	 * This filter expects an array of creative rules for the current suite context
	 * and selects the specific creative from them.
	 *
	 * @since TBD
	 *
	 * @param array  $creative_rules_for_suite An associative array of creative rules for the current suite context,
	 * where keys are plugin slugs or 'default'.
	 * @param object $instance                 The promotional content instance.
	 *
	 * @return array The selected creative content configuration.
	 */
	public function filter_installed_plugins_content_condition( array $creative_rules_for_suite, $instance ): array {
		if ( empty( $creative_rules_for_suite ) ) {
			return [];
		}

		$placeholder = 'placeholder'; // 1

		foreach ( $creative_rules_for_suite as $plugin_slug_or_default_key => $creative_details ) {
			if ( 'default' === $plugin_slug_or_default_key ) {
				continue;
			}

			// If plugin is NOT active and licensed, this is the upsell opportunity.
			if ( ! $this->is_plugin_active_and_licensed( $plugin_slug_or_default_key ) ) {
				// Defensive check: Ensure we always return an array.
				if ( ! is_array( $creative_details ) ) {
					return []; // Return empty array to prevent fatal error.
				}
				return $creative_details;
			}
		}

		// If no specific plugin condition was met, return the default creative for this suite.
		if ( isset( $creative_rules_for_suite['default'] ) ) {
			$default_creative = $creative_rules_for_suite['default'];
			// Defensive check for default creative as well.
			if ( ! is_array( $default_creative ) ) {
				return []; // Return empty array to prevent fatal error.
			}
			return $default_creative;
		}

		return []; // Fallback if no rules or default found for the current suite.
		// phpcs:enable
	}
}
