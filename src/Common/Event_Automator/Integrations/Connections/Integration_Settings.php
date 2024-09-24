<?php
/**
 * Abstract Class to manage integration settings.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */

namespace TEC\Event_Automator\Integrations\Connections;

use Tribe\Events\Admin\Settings as TEC_Settings;
use Tribe\Tickets\Admin\Settings as ET_Settings;

/**
 * Class Settings
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */
abstract class Integration_Settings {

	/**
	 * The prefix, in the context of tribe options, of each setting for this extension.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public static string $option_prefix;

	/**
	 * The internal id of the integration.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public static string $api_id;

	/**
	 * An instance of the Integration API handler.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * An instance of the Integration Template_Modifications.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Template_Modifications
	 */
	protected $template_modifications;

	/**
	 * The Integration URL handler instance.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Url
	 */
	protected $url;

	/**
	 * Settings constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api                    $api                    An instance of the API handler.
	 * @param Template_Modifications $template_modifications An instance of the Template_Modifications handler.
	 * @param Url                    $url                    An instance of the URL handler.
	 */
	public function __construct( Api $api, Template_Modifications $template_modifications, Url $url ) {
		$this->api                    = $api;
		$this->template_modifications = $template_modifications;
		$this->url                    = $url;
	}

	/**
	 * Returns the URL of the TEC Settings URL page.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The URL of the TEC Integration settings page.
	 */
	public static function tec_admin_url() {
		$admin_page_url = tribe( TEC_Settings::class )->get_url( [ 'tab' => 'addons' ] );

		return $admin_page_url;
	}

	/**
	 * Returns the URL of the ET Settings URL page.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The URL of the ET Integration settings page.
	 */
	public static function et_admin_url() {
		$admin_page_url = tribe( ET_Settings::class )->get_url( [ 'tab' => 'integrations' ] );

		return $admin_page_url;
	}

	/**
	 * Get the integration connection fields to the ones in the Integrations tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function get_fields() {
		$api_id =  static::$api_id;

		$wrapper_classes = tribe_get_classes( [
			'tec-automator-settings' => true,
			'tec-events-settings-' . static::$api_id . '-application' => true,
		] );

		$api_fields = [
			static::$option_prefix . 'wrapper_open'  => [
				'type' => 'html',
				'html' => '<div id="tribe-settings-' . static::$api_id . '-application" class="' . implode( ' ', $wrapper_classes ) . '">'
			],
			static::$option_prefix . 'header'        => [
				'type' => 'html',
				'html' => $this->get_intro_text(),
			],
			static::$option_prefix . 'authorize'     => [
				'type' => 'html',
				'html' => $this->get_all_connection_fields(),
			],
			static::$option_prefix . 'wrapper_close' => [
				'type' => 'html',
				'html' => '</div>',
			],
		];

		/**
		 * Filters the integrations settings shown to the user in the Events > Settings > Integrations tab.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string,array> A map of the API fields that will be printed on the page.
		 * @param Settings $this A Settings instance.
		 */
		$api_fields = apply_filters( "tec_event_automator_{$api_id}_settings_fields", $api_fields, $this );

		return $api_fields;
	}

	/**
	 * Adds the integration connections to The Events Calendar Integration Tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function add_fields_tec( array $fields = [] ) {
		$api_fields = $this->get_fields();

		return $fields + $api_fields;
	}

	/**
	 * Get the key to place the integration fields.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The key to place the API integration fields.
	 */
	protected function get_integrations_fields_key() {
		$api_id =  static::$api_id;

		/**
		 * Filters the array key to place the API integration settings.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param string The default array key to place the API integration fields.
		 * @param Settings $this This Settings instance.
		 */
		return apply_filters( "tec_event_automator_{$api_id}_settings_field_placement_key", 'gmaps-js-api-start', $this );
	}

	/**
	 * Adds the integration connections to Event Tickets Integration Tab.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function add_fields_et( array $fields = [] ) {
		$api_fields = $this->get_fields( $fields );

		// Insert the link after the other APIs and after the WooCommerce ones.
		$woo_fields = array_splice( $fields, array_search( $this->get_et_integrations_fields_key(), array_keys( $fields ) ) );

		$fields = array_merge( $fields, $woo_fields, $api_fields );

		return $fields;
	}

	/**
	 * Get the key to place the integration fields in Event Tickets.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The key to place the API integration fields.
	 */
	protected function get_et_integrations_fields_key() {
		$api_id =  static::$api_id;

		/**
		 * Filters the array key to place the API integration settings.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param string The default array key to place the API integration fields.
		 * @param Settings $this Settings instance.
		 */
		return apply_filters( "tec_event_automator_{$api_id}_settings_field_placement_key", 'tickets-woo-dispatch-status', $this );
	}

	/**
	 * Provides the introductory text to the set up and configuration an integration.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The introductory text to the the set up and configuration of an integration.
	 */
	protected function get_intro_text() {
		return $this->template_modifications->get_intro_text();
	}

	/**
	 * Get the all the integration connection fields.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The HTML for all integration connections.
	 */
	protected function get_all_connection_fields() {
		return $this->template_modifications->get_all_connection_fields( $this->api, $this->url );
	}
}
