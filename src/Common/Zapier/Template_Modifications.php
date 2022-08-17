<?php
/**
 * Zapier template modifications class.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

use TEC\Common\Templates\Admin_Template;

/**
 * Class Template_Modifications
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */
class Template_Modifications {

	/**
	 * The internal id of the API integration.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $api_id = '';

	/**
	 *  The prefix, in the context of tribe options, of each setting for an API.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $option_prefix = '';

	/**
	 * An instance of the admin template handler.
	 *
	 * @since TBD
	 *
	 * @var Admin_Template
	 */
	protected $admin_template;

	/**
	 * An instance of the URl handler.
	 *
	 * @since TBD
	 *
	 * @var Url
	 */
	protected $url;

	/**
	 * Template_Modifications constructor.
	 *
	 * @since TBD
	 *
	 * @param Admin_Template $template An instance of the backend template handler.
	 * @param Url            $Url      An instance of the URl handler.
	 */
	public function __construct( Admin_Template $admin_template, Url $url ) {
		$this->admin_template = $admin_template;
		$this->url            = $url;
		self::$api_id         = 'zapier';
		self::$option_prefix  = Settings::$option_prefix;
	}

	/**
	 * Get intro text for an API Settings.
	 *
	 * @since TBD
	 *
	 * @return string HTML for the intro text.
	 */
	public function get_intro_text() {
		$args = [
			'allowed_html' => [
				'a' => [
					'href'   => [],
					'target' => [],
				],
			],
		];

		return $this->admin_template->template( static::$api_id . '/api/intro-text', $args, false );
	}

	/**
	 * Adds an API authorize fields to events->settings->api.
	 *
	 * @since TBD
	 *
	 * @param Api $api An instance of an API handler.
	 * @param Url $url The URLs handler for the integration.
	 *
	 * @return string HTML for the authorize fields.
	 */
	public function get_api_authorize_fields( Api $api, Url $url ) {
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
	 * The message template to display on user account changes for an API.
	 *
	 * @since TBD
	 *
	 * @param string $message The message to display.
	 * @param string $type    The type of message, either updated or error.
	 *
	 * @return string The message with html to display
	 */
	public function get_settings_message_template( $message, $type = 'updated' ) {
		return $this->admin_template->template( 'components/message', [
			'message' => $message,
			'type'    => $type,
		] );
	}

	/**
	 * Get the API Key fields.
	 *
	 * @since TBD
	 *
	 * @param int                 $local_id The unique id used to save the API Key data.
	 * @param array<string|mixed> $api_key  The API Key data.
	 * @param array<string|mixed> $users    An array of WordPress users to create an API Key for.
	 * @param string              $type     A string of the type of fields to load ( new and generated ).
	 *
	 * @return string The Zapier API Keys admin fields html.
	 */
	public function get_api_key_fields( $local_id, $api_key, $users, $type = 'new' ) {
		return $this->admin_template->template( 'zapier/api/components/fields-' . $type, [
			'local_id' => $local_id,
			'api_key'  => $api_key,
			'users'    => $users,
			'url'      => $this->url,
		] );
	}
}
