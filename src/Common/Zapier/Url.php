<?php
/**
 * Manages the Zapier URLs for the plugin.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

use Tribe__Main as Common;

/**
 * Class Url
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */
class Url {

	/**
	 * The internal id of the API integration.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $api_id = 'zapier';

	/**
	 * The current API handler instance.
	 *
	 * @since TBD
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * The current Actions handler instance.
	 *
	 * @since TBD
	 *
	 * @var \TEC\Common\Zapier\Actions
	 */
	protected $actions;

	/**
	 * Url constructor.
	 *
	 * @since TBD
	 *
	 * @param Api     $api     An instance of the Zapier API handler.
	 * @param Actions $actions An instance of the Zapier Actions handler.
	 */
	public function __construct( Api $api, Actions $actions ) {
		$this->api     = $api;
		$this->actions = $actions;
	}

	/**
	 * Get the admin ajax url with parameters to enable an API action.
	 *
	 * @since TBD
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
			Common::$request_slug => $nonce,
			'_ajax_nonce'         => $nonce,
		];

		$query_args = array_merge( $args, $additional_arg );

		return add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );
	}

	/**
	 * Returns the URL that should be used to delete an API account in the settings.
	 *
	 * @since TBD
	 *
	 * @param string $account_id An API Account ID to change the status.
	 *
	 * @return string The URL to add an API Key.
	 */
	public function to_add_api_key_link() {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$add_aki_key_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_common_ev_{$api_id}_settings_add_api_key", $nonce, [] );
	}

	/**
	 * Returns the URL that should be used to generate a Zapier API Key pair.
	 *
	 * @since TBD
	 *
	 * @param string $account_id The internal account id for the key pair.
	 *
	 * @return string The URL used to generate a Zapier API Key pair.
	 */
	public function to_generate_api_key_pair( $account_id ) {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$generate_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_common_ev_{$api_id}_settings_generate_api_key_pair", $nonce, [
			'account_id' => $account_id
		] );
	}

	/**
	 * Returns the URL that should be used to revoke a Zapier API Key.
	 *
	 * @since TBD
	 *
	 * @param string $account_id An API Key to revoke.
	 *
	 * @return string The URL to revoke an API Key.
	 */
	public function to_revoke_api_key_link( $account_id ) {
		$api_id = static::$api_id;
		$nonce  = wp_create_nonce( $this->actions::$revoke_action );

		return $this->get_admin_ajax_url_with_parameters( "tec_common_ev_{$api_id}_settings_revoke_api_key_pair", $nonce, [
			'account_id' => $account_id
		] );
	}
}
