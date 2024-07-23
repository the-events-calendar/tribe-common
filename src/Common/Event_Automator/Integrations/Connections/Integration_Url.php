<?php
/**
 * Abstract Class to Manage the Url for Connections to an Integration.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */

namespace TEC\Event_Automator\Integrations\Connections;

use TEC\Event_Automator\Plugin;

/**
 * Class Url
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */
abstract class Integration_Url {

	/**
	 * The internal id of the API integration.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public static string $api_id;

	/**
	 * The current Actions handler instance.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Actions
	 */
	protected $actions;

	/**
	 * Get the admin ajax url with parameters to enable an API action.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string               $action         The name of the action to add to the url.
	 * @param string               $nonce          The nonce to verify for the action.
	 * @param array<string|string> $additional_arg An array of arugments to add to the query string of the admin ajax url.
	 *
	 * @return string
	 */
	public function get_admin_ajax_url_with_parameters( string $action, string $nonce, array $additional_arg ) {
		$args = [
			'action'              => $action,
			Plugin::$request_slug => $nonce,
			'_ajax_nonce'         => $nonce,
		];

		$query_args = array_merge( $args, $additional_arg );

		return add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );
	}

	/**
	 * Returns the URL that should be used to add integration connection fields in the settings.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The URL to add integration connection fields.
	 */
	public function to_add_connection_link() {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$add_connection );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_settings_add_connection", $nonce, [] );
	}

	/**
	 * Returns the URL that should be used to create access token for an integration connection.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The consumer id to use to create the access token.
	 *
	 * @return string The URL used to create access token for an integration connection.
	 */
	public function to_create_access_link( $consumer_id ) {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$create_access );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_settings_create_access_token", $nonce, [
			'consumer_id' => $consumer_id
		] );
	}

	/**
	 * Returns the URL that should be used to delete an integration connection.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The id of the connection to delete.
	 *
	 * @return string The URL to delete an integration connection.
	 */
	public function to_delete_connection_link( $consumer_id ) {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$delete_connection );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_settings_delete_connection", $nonce, [
			'consumer_id' => $consumer_id
		] );
	}

	/**
	 * Returns the URL that should be used clear a Zapier endpoint queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $endpoint_id An endpoint id to clear.
	 *
	 * @return string The URL to clear a Zapier endpoint queue.
	 */
	public function to_clear_endpoint_queue( $endpoint_id ) {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$clear_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_dashboard_clear_endpoint_queue", $nonce, [
			'endpoint_id' => $endpoint_id
		] );
	}

	/**
	 * Returns the URL that should be used disable a Zapier endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $endpoint_id An endpoint id to disable.
	 *
	 * @return string The URL to disable a Zapier endpoint.
	 */
	public function to_disable_endpoint_queue( $endpoint_id ) {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$disable_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_dashboard_disable_endpoint", $nonce, [
			'endpoint_id' => $endpoint_id
		] );
	}

	/**
	 * Returns the URL that should be used enable a Zapier endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $endpoint_id An endpoint id to enable.
	 *
	 * @return string The URL to enable a Zapier endpoint.
	 */
	public function to_enable_endpoint_queue( $endpoint_id ) {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$enable_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_dashboard_enable_endpoint", $nonce, [
			'endpoint_id' => $endpoint_id
		] );
	}
}
