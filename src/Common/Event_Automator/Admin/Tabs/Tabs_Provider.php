<?php

namespace TEC\Event_Automator\Admin\Tabs;

use TEC\Common\Contracts\Service_Provider;

/**
 * Class Tabs_Provider
 *
 * @package TEC\Event_Automator\Admin\Tabs
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @deprecated 6.4.1
 */
class Tabs_Provider extends Service_Provider {

	/**
	 * Register the provider.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated 6.4.1
	 */
	public function register() {
		_deprecated_function( __METHOD__, '6.4.1' );
	}

	/**
	 * Add the action hooks.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated 6.4.1
	 */
	public function add_actions() {
		_deprecated_function( __METHOD__, '6.4.1' );
	}

	/**
	 * Add fhe filter hooks.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated 6.4.1
	 */
	public function add_filters() {
		_deprecated_function( __METHOD__, '6.4.1' );
	}

	/**
	 * Register the Attendee Registration tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated 6.4.1
	 *
	 * @param string Admin page id.
	 *
	 * @return void
	 */
	public function add_tabs( $admin_page ) {
		_deprecated_function( __METHOD__, '6.4.1' );
	}

	/**
	 * Register the Integrations tab id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @deprecated 6.4.1
	 *
	 * @param array<string> $tabs Array of tabs IDs for the Events settings page.
	 *
	 * @return array<string> The filtered list of tab ids.
	 */
	public function filter_include_integrations_tab_id( array $tabs ): array {
		_deprecated_function( __METHOD__, '6.4.1' );
		return [];
	}
}
