<?php
/**
 * Zapier template modifications class.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier
 */

namespace TEC\Event_Automator\Zapier;

use TEC\Event_Automator\Integrations\Connections\Integration_Template_Modifications;
use TEC\Event_Automator\Templates\Admin_Template;

/**
 * Class Template_Modifications
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier
 */
class Template_Modifications extends Integration_Template_Modifications {

	/**
	 * @inerhitDoc
	 */
	public static string $api_id = 'zapier';

	/**
	 * Template_Modifications constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Admin_Template $template An instance of the backend template handler.
	 * @param Url            $Url      An instance of the URl handler.
	 */
	public function __construct( Admin_Template $admin_template, Url $url ) {
		$this->admin_template = $admin_template;
		$this->url            = $url;
		self::$option_prefix  = Settings::$option_prefix;
	}

	/**
	 * Adds an API authorize fields to events->settings->api.
	 * @deprecated 1.4.0 - Use get_all_connection_fields().
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api $api An instance of an API handler.
	 * @param Url $url The URLs handler for the integration.
	 *
	 * @return string HTML for the authorize fields.
	 */
	public function get_api_authorize_fields( Api $api, Url $url ) {
		_deprecated_function( __METHOD__, '1.4.0', 'get_all_connection_fields');

		/** @var \Tribe__Cache $cache */
		$cache   = tribe( 'cache' );
		$message = $cache->get_transient( static::$option_prefix . '_api_message' );
		if ( $message ) {
			$cache->delete_transient( static::$option_prefix . '_api_message' );
		}

		$args = [
			'api'     => $api,
			'url'     => $url,
			'message' => $message,
			'users'   => $api->get_users_dropdown(),
		];

		return $this->admin_template->template( static::$api_id . '/api/authorize-fields', $args, false );
	}

	/**
	 * Get the API Key fields.
	 * @deprecated 1.4.0 - Use get_connection_fields().
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api                 $api         An instance of an API handler.
	 * @param string              $consumer_id The unique id used to save the API Key data.
	 * @param array<string|mixed> $api_key     The API Key data.
	 * @param array<string|mixed> $users       An array of WordPress users to create an API Key for.
	 * @param string              $type        A string of the type of fields to load ( new and generated ).
	 *
	 * @return string The Zapier API Keys admin fields html.
	 */
	public function get_api_key_fields( Api $api, $consumer_id, $api_key, $users, $type = 'new' ) {
		_deprecated_function( __METHOD__, '1.4.0', 'get_connection_fields');

		return $this->admin_template->template( 'zapier/api/list/fields-' . $type, [
			'api'         => $api,
			'api_key'     => $api_key,
			'consumer_id' => $consumer_id,
			'users'       => $users,
			'url'         => $this->url,
		] );
	}

	/**
	 * Get intro text for an endpoint dashboard.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string HTML for the intro text.
	 */
	public function get_dashboard_intro_text() {
		return $this->admin_template->template( static::$api_id . '/dashboard/intro-text', [], false );
	}
}
