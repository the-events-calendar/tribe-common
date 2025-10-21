<?php
/**
 * Trait for generic upsell opportunity checking.
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits;
 */

namespace TEC\Common\Admin\Conditional_Content\Traits;

/**
 * Trait Has_Generic_Upsell_Opportunity
 *
 * Provides simple upsell opportunity checking: "Is ANY paid plugin not installed?"
 * Use this trait for promotions that show a single generic ad regardless of which
 * plugins are missing.
 *
 * This trait is mutually exclusive with Has_Targeted_Creative_Upsell. Both traits
 * define has_upsell_opportunity() so a class cannot use both simultaneously.
 *
 * To always show content regardless of plugin installation, override:
 * protected function should_ignore_plugin_checks(): bool { return true; }
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */
trait Has_Generic_Upsell_Opportunity {
	/**
	 * Determine whether to ignore plugin checks and always show content.
	 *
	 * Override this method in your class to return true if you want to always
	 * show promotional content regardless of which plugins are installed.
	 *
	 * @since 6.9.8
	 *
	 * @return bool Whether to ignore plugin installation checks.
	 */
	protected function should_ignore_plugin_checks(): bool {
		return false;
	}

	/**
	 * Check if there's an upsell opportunity (at least one paid plugin is not installed).
	 *
	 * @since 6.9.8
	 *
	 * @return bool True if at least one paid plugin is not installed, or always true if ignoring plugin checks.
	 */
	protected function has_upsell_opportunity(): bool {
		$should_display = false;

		// If we're ignoring plugin checks, always show content.
		if ( $this->should_ignore_plugin_checks() ) {

			/**
			 * Filters the result of the upsell opportunity check.
			 *
			 * @since 6.9.8
			 *
			 * @param bool   $result     The result of the upsell opportunity check. Defaults to true for ignoring plugin checks.
			 * @param object $instance   The conditional content object.
			 */
			return (bool) apply_filters( "tec_admin_conditional_content_{$this->slug}_generic_upsell_opportunity_should_display", true, $this );
		}

		// Get all products from the API.
		$products = tribe( 'plugins.api' )->get_products();

		// Check if any paid plugins are not installed.
		foreach ( $products as $product ) {
			// Skip free plugins.
			if ( ! empty( $product['free'] ) ) {
				continue;
			}

			// If this paid plugin is not installed, we have an upsell opportunity.
			if ( empty( $product['is_installed'] ) ) {
				$should_display = true;
				break;
			}
		}

		/**
		 * Filters the result of the upsell opportunity check.
		 *
		 * @since 6.9.8
		 *
		 * @param bool   $result     The result of the upsell opportunity check.
		 * @param object $instance   The conditional content object.
		 */
		return (bool) apply_filters( "tec_admin_conditional_content_{$this->slug}_generic_upsell_opportunity_should_display", $should_display, $this );
	}
}
