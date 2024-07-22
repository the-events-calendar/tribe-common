<?php
/**
 * Integrations Template Modifications abstract class.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */

namespace TEC\Event_Automator\Integrations\Connections;

use TEC\Event_Automator\Templates\Admin_Template;

/**
 * Class Template_Modifications
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */
abstract class Integration_Template_Modifications {

	/**
	 * The internal id of the API integration.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public static string $api_id = '';

	/**
	 * The prefix, in the context of tribe options, of each setting for an API.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public static string $option_prefix = '';

	/**
	 * An instance of the admin template handler.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Admin_Template
	 */
	protected $admin_template;

	/**
	 * An instance of the URl handler.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Url
	 */
	protected $url;

	/**
	 * Get intro text for an API Settings.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
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
	 * Gets all the integration connections.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Integration_Connections $api An instance of an API handler.
	 * @param Integration_Url         $url The URLs handler for the integration.
	 *
	 * @return string HTML for all integration connections.
	 */
	public function get_all_connection_fields( Integration_Connections $api, Integration_Url $url ) {
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

		return $this->admin_template->template( static::$api_id . '/api/connections', $args, false );
	}

	/**
	 * Get the message template.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $message The message to display.
	 * @param string $type    The type of message, either updated or error.
	 * @param boolean $echo    Whether to echo the template.
	 *
	 * @return string The message with html to display
	 */
	public function get_settings_message_template( $message, $type = 'updated', $echo = false ) {
		return $this->admin_template->template( 'components/message', [
			'message' => $message,
			'type'    => $type,
			$echo
		] );
	}

	/**
	 * Print the message template to display.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string  $message The message to display.
	 * @param string  $type    The type of message, either updated or error.
	 * @param boolean $echo    Whether to echo the template.
	 *
	 * @return string The message with html to display
	 */
	public function print_settings_message_template( $message, $type = 'updated', $echo = true ) {
		return $this->get_settings_message_template( $message, $type, $echo );
	}

	/**
	 * Get fields for a connection.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Integration_Connections $api         An instance of an API handler.
	 * @param string                  $consumer_id The unique id used to save the API Key data.
	 * @param array<string|mixed>     $connection_data     The API Key data.
	 * @param array<string|mixed>     $users       An array of WordPress users to create an API Key for.
	 * @param string                  $type        A string of the type of fields to load ( new and generated ).
	 *
	 * @return string HTML fields for a connection.
	 */
	public function get_connection_fields( Integration_Connections $api, $consumer_id, $connection_data, $users, $type = 'new' ) {
		return $this->admin_template->template( static::$api_id .  '/api/list/connection-' . $type, [
			'api'             => $api,
			'connection_data' => $connection_data,
			'consumer_id'     => $consumer_id,
			'users'           => $users,
			'url'             => $this->url,
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

	/**
	 * Adds the Endpoint Dashboard.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string,array> $endpoints An array of the Zapier endpoints.
	 * @param Endpoints_Manager   $manager   The Endpoint Manager instance.
	 * @param Url                 $url       The URLs handler for the integration.
	 *
	 * @return string HTML for the dashboard.
	 */
	public function get_dashboard( array $endpoints, $manager, $url ) {
		$args = [
			'endpoints' => $endpoints,
			'manager'   => $manager,
			'url'       => $url,
		];

		return $this->admin_template->template( 'dashboard/table', $args, false );
	}

	/**
	 * Get the HTML for an Integration Endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string,array> $endpoint An array of details for a Zapier endpoint.
	 * @param Endpoints_Manager   $manager  The Endpoint Manager instance.
	 * @param boolean             $echo     Whether to echo the template.
	 *
	 * @return string HTML for the endpoint row.
	 */
	public function get_endpoint_row( array $endpoint_details, $manager, $echo = false ): string {
		$args = [
			'endpoint' => $endpoint_details,
			'manager'  => $manager,
			'url'      => $this->url,
		];

		return $this->admin_template->template( 'dashboard/endpoints/endpoint', $args, $echo );
	}

	/**
	 * Print the HTML for a Integration Endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string,array> $endpoint An array of details for a Zapier endpoint.
	 * @param Endpoints_Manager   $manager  The Endpoint Manager instance.
	 * @param boolean             $echo     Whether to echo the template.
	 *
	 * @return string HTML for the endpoint row.
	 */
	public function print_endpoint_row( array $endpoint_details, $manager, $echo = true ): string {
		return $this->get_endpoint_row( $endpoint_details, $manager, $echo );
	}
}
