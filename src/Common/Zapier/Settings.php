<?php
/**
 * Class to manage Zapier settings.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

use \Tribe\Events\Admin\Settings as TEC_Settings;

/**
 * Class Settings
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */
class Settings {

	/**
	 * The prefix, in the context of tribe options, of each setting for this extension.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $option_prefix = 'tec_zapier_';

	/**
	 * The internal id of the Zapier API integration.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $api_id = 'zapier';

	/**
	 * An instance of the Zapier API handler.
	 *
	 * @since TBD
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * An instance of the Zapier Template_Modifications.
	 *
	 * @since TBD
	 *
	 * @var Template_Modifications
	 */
	protected $template_modifications;

	/**
	 * The Zapier URL handler instance.
	 *
	 * @since TBD
	 *
	 * @var Url
	 */
	protected $url;

	/**
	 * Settings constructor.
	 *
	 * @since TBD
	 *
	 * @param Api                    $api                    An instance of the Zapier API handler.
	 * @param Template_Modifications $template_modifications An instance of the Template_Modifications handler.
	 * @param Url                    $url                    An instance of the URL handler.
	 */
	public function __construct( Api $api, Template_Modifications $template_modifications, Url $url ) {
		$this->api                    = $api;
		$this->template_modifications = $template_modifications;
		$this->url                    = $url;
	}

	/**
	 * Returns the URL of the Settings URL page.
	 *
	 * @since TBD
	 *
	 * @return string The URL of the TEC Integration settings page.
	 */
	public static function admin_url() {
		$admin_page_url = tribe( TEC_Settings::class )->get_url( [ 'tab' => 'addons' ] );

		return $admin_page_url;
	}

	/**
	 * Adds the Zapier API fields to the ones in the Events > Settings > APIs tab.
	 *
	 * @since TBD
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function add_fields( array $fields = [] ) {
		$wrapper_classes = tribe_get_classes( [
			'tec-settings-integrations'                               => true,
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
				'html' => $this->get_authorize_fields(),
			],
			static::$option_prefix . 'wrapper_close' => [
				'type' => 'html',
				'html' => '<div class="clear"></div></div>',
			],
		];

		/**
		 * Filters the Zapier settings shown to the user in the Events > Settings > Integrations tab.
		 *
		 * @since TBD
		 *
		 * @param array<string,array> A map of the API fields that will be printed on the page.
		 * @param Settings $this A Settings instance.
		 */
		$api_fields = apply_filters( 'tec_common_zapier_settings_fields', $api_fields, $this );

		// Insert the link after the other APIs and before the Google Maps API ones.
		$gmaps_fields = array_splice( $fields, array_search( $this->get_integrations_fields_key(), array_keys( $fields ) ) );

		$fields = array_merge( $fields, $api_fields, $gmaps_fields );

		return $fields;
	}

	/**
	 * Get the key to place the Zapier API integration fields.
	 *
	 * @since TBD
	 *
	 * @return string The key to place the API integration fields.
	 */
	protected function get_integrations_fields_key() {
		/**
		 * Filters the array key to place the API integration settings.
		 *
		 * @since TBD
		 *
		 * @param string The default array key to place the API integration fields.
		 * @param Settings $this This Settings instance.
		 */
		return apply_filters( 'tec_common_zapier_settings_field_placement_key', 'gmaps-js-api-start', $this );
	}

	/**
	 * Provides the introductory text to the set up and configuration of the Zapier API integration.
	 *
	 * @since TBD
	 *
	 * @return string The introductory text to the the set up and configuration of the API integration.
	 */
	protected function get_intro_text() {
		return $this->template_modifications->get_intro_text();
	}

	/**
	 * Get the Zapier API authorization fields.
	 *
	 * @since TBD
	 *
	 * @return string The HTML fields.
	 */
	protected function get_authorize_fields() {
		return $this->template_modifications->get_api_authorize_fields( $this->api, $this->url );
	}
}
