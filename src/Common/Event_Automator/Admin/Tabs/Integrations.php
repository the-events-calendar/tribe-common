<?php

namespace TEC\Event_Automator\Admin\Tabs;

/**
 * Class Integrations
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Admin\Tabs
 *
 * @deprecated TBD
 *
 */
class Integrations {

	/**
	 * Slug for the tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated TBD
	 *
	 * @var string
	 */
	public static $slug = 'integrations';

	/**
	 * Register the Tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated TBD
	 *
	 * @param string Admin page id.
	 */
	public function register_tab( $admin_page ) {
		_deprecated_function( __METHOD__, 'TBD' );
	}

	/**
	 * Register tab ID for network mode support.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated TBD
	 *
	 * @param array<string> $tabs Array of tabs IDs for the Events settings page.
	 *
	 * @return array<string> The filtered list of tab ids.
	 */
	public function register_tab_id( array $tabs ): array {
		_deprecated_function( __METHOD__, 'TBD' );

		return $tabs;
	}

	/**
	 * Gets the settings.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated TBD
	 *
	 * @return array<string,mixed> Key value pair for setting options.
	 */
	public function get_fields(): array {
		_deprecated_function( __METHOD__, 'TBD' );

		return [];
	}
}
