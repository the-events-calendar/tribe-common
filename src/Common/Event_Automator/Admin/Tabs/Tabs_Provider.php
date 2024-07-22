<?php

namespace TEC\Event_Automator\Admin\Tabs;

use TEC\Common\Contracts\Service_Provider;

/**
 * Class Service_Provider
 *
 * @package TEC\Event_Automator\Admin\Tabs
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 */
class Tabs_Provider extends Service_Provider {

	/**
	 * Register the provider.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function register() {

		// If Plugin Settings does not exist, return as there is no settings tab to add.
		if ( ! class_exists('Tribe\Tickets\Admin\Settings', false ) ) {
			return;
		}

		// If Event Tickets Plus is Active, do not add the Integrations tab as it will do it.
		if ( class_exists('TEC\Tickets_Plus\Admin\Tabs\Provider', false ) ) {
			return;
		}

		// Hook actions and filters.
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Add the action hooks.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function add_actions() {
		add_action( 'tribe_settings_do_tabs', [ $this, 'add_tabs' ] );
	}

	/**
	 * Add fhe filter hooks.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function add_filters() {
		add_filter( 'tec_tickets_settings_tabs_ids', [ $this, 'filter_include_integrations_tab_id' ] );
	}

	/**
	 * Register the Attendee Registration tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string Admin page id.
	 *
	 * @return void
	 */
	public function add_tabs( $admin_page ) {
		$this->container->make( Integrations::class )->register_tab( $admin_page );
	}

	/**
	 * Register the Integrations tab id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string> $tabs Array of tabs IDs for the Events settings page.
	 *
	 * @return array<string> The filtered list of tab ids.
	 */
	public function filter_include_integrations_tab_id( array $tabs ): array {
		return $this->container->make( Integrations::class )->register_tab_id( $tabs );
	}
}
