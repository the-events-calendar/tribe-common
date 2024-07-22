<?php

namespace TEC\Event_Automator\Admin\Tabs;

use Tribe\Tickets\Admin\Settings as Plugin_Settings;

/**
 * Class Integrations
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Admin\Tabs
 *
 */
class Integrations {

	/**
	 * Slug for the tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public static $slug = 'integrations';

	/**
	 * Register the Tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string Admin page id.
	 */
	public function register_tab( $admin_page ) {
		if ( ! empty( $admin_page ) && Plugin_Settings::$settings_page_id !== $admin_page ) {
			return;
		}

		$tab_settings = [
			'priority'  => 35,
			'fields'    => $this->get_fields(),
			'show_save' => true,
		];

		/**
		 * Filter the tab settings options.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string,mixed> Key value pairs of setting options.
		 */
		$tab_settings = apply_filters( 'tec_event_automator_integrations_tab_settings', $tab_settings );

		new \Tribe__Settings_Tab( static::$slug, esc_html__( 'Integrations', 'tribe-common' ), $tab_settings );
	}

	/**
	 * Register tab ID for network mode support.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string> $tabs Array of tabs IDs for the Events settings page.
	 *
	 * @return array<string> The filtered list of tab ids.
	 */
	public function register_tab_id( array $tabs ): array {
		$tabs[] = static::$slug;

		return $tabs;
	}

	/**
	 * Gets the settings.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string,mixed> Key value pair for setting options.
	 */
	public function get_fields(): array {
		$settings_start = [
			'info-start' => [
				'type' => 'html',
				'html' => '<div class="tribe-settings-form-wrap">',
			]
		];

		$settings_end = [
			'info-end' => [
				'type' => 'html',
				'html' => '</div>',
			]
		];

		/**
		 * Filters the fields in the Tickets > Settings > Integrations tab.
		 * Utilizes the name from Event Tickets Plus as this is a replacement if that plugin is deactivated.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string,array> $fields The current fields.
		 *
		 * @return array<string,array> The fields, as updated by the settings.
		 */
		$fields = apply_filters( 'tec_tickets_plus_integrations_tab_fields', [] );

		return array_merge( $settings_start, $fields, $settings_end );
	}
}
