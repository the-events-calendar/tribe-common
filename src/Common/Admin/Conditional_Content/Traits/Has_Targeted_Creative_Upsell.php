<?php
/**
 * Trait for targeted upsell opportunity checking with creative map.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits;
 */

namespace TEC\Common\Admin\Conditional_Content\Traits;

/**
 * Trait Has_Targeted_Creative_Upsell
 *
 * Provides targeted upsell opportunity checking: "Which specific plugins are missing?"
 * Use this trait for promotions that show plugin-specific ads based on what's not installed.
 *
 * This trait is mutually exclusive with Has_Generic_Upsell_Opportunity. Both traits
 * define has_upsell_opportunity() so a class cannot use both simultaneously.
 *
 * Classes using this trait MUST implement:
 * - protected function get_suite_creative_map(): array - Return the creative map
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */
trait Has_Targeted_Creative_Upsell {
	/**
	 * Check if there's an upsell opportunity based on targeted creatives.
	 *
	 * @since TBD
	 *
	 * @return bool True if a valid creative to show was found.
	 */
	protected function has_upsell_opportunity(): bool {
		// If we have a valid creative to show, we have an upsell opportunity.
		return $this->get_selected_creative() !== null;
	}

	/**
	 * Get the suite creative map.
	 *
	 * The creative map should be structured as follows:
	 *
	 * [
	 *   'context' => [
	 *     'plugin/path.php' => [
	 *       'image_url' => '...',
	 *       'narrow_image_url' => '...',
	 *       'link_url' => '...',
	 *       'alt_text' => '...',
	 *     ],
	 *     'feature-check' => [
	 *       'callback' => [ 'Class', 'method' ], // Callback to determine if feature is active.
	 *       'image_url' => '...',
	 *       'narrow_image_url' => '...',
	 *       'link_url' => '...',
	 *       'alt_text' => '...',
	 *     ],
	 *     'default' => [ ... ] // Fallback creative.
	 *   ],
	 * ]
	 *
	 * @since TBD
	 *
	 * @return array The suite creative map.
	 */
	abstract protected function get_suite_creative_map(): array;

	/**
	 * Determine the admin page context.
	 *
	 * @since TBD
	 *
	 * @return string The admin page context ('tickets', 'events', or 'default').
	 */
	protected function get_admin_page_context(): string {
		$admin_pages = tribe( 'admin.pages' );
		$admin_page  = $admin_pages->get_current_page();

		// If no admin page is detected, use default context.
		if ( empty( $admin_page ) ) {
			return 'default';
		}

		// Check if we're on a tickets admin page.
		if ( strpos( $admin_page, 'tec-tickets' ) !== false || strpos( $admin_page, 'tickets_page_' ) !== false ) {
			return 'tickets';
		}

		// Check if we're on an events admin page.
		if ( strpos( $admin_page, 'tribe_events' ) !== false || strpos( $admin_page, 'events_page_' ) !== false ) {
			return 'events';
		}

		// Check if we're on a general TEC admin page.
		if ( strpos( $admin_page, 'tec-' ) !== false ) {
			return 'events';
		}

		return 'default';
	}

	/**
	 * Get the selected creative based on admin page context and installed plugins.
	 *
	 * @since TBD
	 *
	 * @return array|null The selected creative array or null if none found.
	 */
	protected function get_selected_creative(): ?array {
		$creative_map = $this->get_suite_creative_map();
		$context      = $this->get_admin_page_context();

		// If no creative map is available, return null.
		if ( empty( $creative_map ) ) {
			return null;
		}

		// Check if the context exists in the creative map.
		if ( ! isset( $creative_map[ $context ] ) ) {
			// Fall back to default if context not found.
			$context = 'default';
			if ( ! isset( $creative_map[ $context ] ) ) {
				return null;
			}
		}

		$context_creatives = $creative_map[ $context ];

		// Iterate through the creatives and find the first plugin that is not installed or where the callback returns false.
		foreach ( $context_creatives as $plugin_path => $creative ) {
			// Skip the default entry for now.
			if ( 'default' === $plugin_path ) {
				continue;
			}

			// Check if we have a callback for plugin detection.
			if ( isset( $creative['callback'] ) && is_callable( $creative['callback'] ) ) {
				// Execute the callback to determine if the plugin or feature is active.
				$is_active = call_user_func( $creative['callback'] );

				// If the callback returns false (feature not active), use this creative.
				if ( ! $is_active ) {
					return $creative;
				}
			} elseif ( ! is_plugin_active( $plugin_path ) ) {
				return $creative;
			}
		}

		// If all plugins are installed, use the default creative.
		if ( isset( $context_creatives['default'] ) ) {
			return $context_creatives['default'];
		}

		return null;
	}

	/**
	 * Get the wide banner image URL.
	 *
	 * @since TBD
	 *
	 * @return string The wide banner image URL.
	 */
	protected function get_wide_banner_image_url(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['image_url'] ) ) {
			return $creative['image_url'];
		}

		// Fallback to default behavior.
		return tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the narrow banner image URL.
	 *
	 * @since TBD
	 *
	 * @return string The narrow banner image URL.
	 */
	protected function get_narrow_banner_image_url(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['narrow_image_url'] ) ) {
			return $creative['narrow_image_url'];
		}

		// Fallback to default behavior.
		return tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the sidebar image URL.
	 *
	 * @since TBD
	 *
	 * @return string The sidebar image URL.
	 */
	protected function get_sidebar_image_url(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['sidebar_image_url'] ) ) {
			return $creative['sidebar_image_url'];
		}

		// Fallback to default behavior.
		return tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the link URL for the creative.
	 *
	 * @since TBD
	 *
	 * @return string The link URL.
	 */
	protected function get_creative_link_url(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['link_url'] ) ) {
			return $creative['link_url'];
		}

		// Fallback to default behavior.
		return $this->get_link_url();
	}

	/**
	 * Get the alt text for the creative.
	 *
	 * @since TBD
	 *
	 * @return string The alt text.
	 */
	protected function get_creative_alt_text(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['alt_text'] ) ) {
			return $creative['alt_text'];
		}

		// Fallback to default behavior.
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		return sprintf(
			/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
			_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
			$year,
			$sale_name
		);
	}

	/**
	 * Get the wide banner image filename.
	 *
	 * Required for fallback behavior.
	 *
	 * @since TBD
	 *
	 * @return string The wide banner image filename.
	 */
	abstract protected function get_wide_banner_image();

	/**
	 * Get the narrow banner image filename.
	 *
	 * Required for fallback behavior.
	 *
	 * @since TBD
	 *
	 * @return string The narrow banner image filename.
	 */
	abstract protected function get_narrow_banner_image();

	/**
	 * Get the sidebar image filename.
	 *
	 * Required for fallback behavior.
	 *
	 * @since TBD
	 *
	 * @return string The sidebar image filename.
	 */
	abstract protected function get_sidebar_image();

	/**
	 * Get the link URL.
	 *
	 * Required for fallback behavior.
	 *
	 * @since TBD
	 *
	 * @return string The link URL.
	 */
	abstract protected function get_link_url(): string;

	/**
	 * Get the sale name.
	 *
	 * Required for fallback alt text.
	 *
	 * @since TBD
	 *
	 * @return string The sale name.
	 */
	abstract protected function get_sale_name(): string;
}
