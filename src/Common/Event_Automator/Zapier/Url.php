<?php
/**
 * Manages the Zapier URLs for the plugin.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier
 */

namespace TEC\Event_Automator\Zapier;

use TEC\Event_Automator\Integrations\Connections\Integration_Url;

/**
 * Class Url
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @since 6.0.0 Migrated to Common from Event Automator - Utilizes common class.
 *
 * @package TEC\Event_Automator\Zapier
 */
class Url extends Integration_Url {

	/**
	 * @inheritdoc
	 */
	public static string $api_id = 'zapier';

	/**
	 * Url constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Actions $actions An instance of the Zapier Actions handler.
	 */
	public function __construct( Actions $actions ) {
		$this->actions = $actions;
	}

	/**
	 * Returns the URL that should be used to delete an API Key pair in the settings.
	 * @deprecated 1.4.0 - use to_add_connection_link() instead.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The URL to add an API Key.
	 */
	public function to_add_api_key_link() {
		_deprecated_function( __METHOD__, '1.4.0', 'to_add_connection_link');

		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$add_aki_key_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_settings_add_api_key", $nonce, [] );
	}

	/**
	 * Returns the URL that should be used to generate a Zapier API Key pair.
	 * @deprecated 1.4.0 - use to_create_access_link() instead.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The consumer id for the key pair.
	 *
	 * @return string The URL used to generate a Zapier API Key pair.
	 */
	public function to_generate_api_key_pair( $consumer_id ) {
		_deprecated_function( __METHOD__, '1.4.0', 'to_create_access_link');

		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$generate_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_settings_generate_api_key_pair", $nonce, [
			'consumer_id' => $consumer_id
		] );
	}

	/**
	 * Returns the URL that should be used to revoke a Zapier API Key.
	 * @deprecated 1.4.0 - use to_delete_connection_link() instead.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id An API Key to revoke.
	 *
	 * @return string The URL to revoke an API Key.
	 */
	public function to_revoke_api_key_link( $consumer_id ) {
		_deprecated_function( __METHOD__, '1.4.0', 'to_delete_connection_link');

		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$revoke_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_automator_ev_{$api_id}_settings_revoke_api_key_pair", $nonce, [
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
