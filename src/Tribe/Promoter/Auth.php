<?php

/**
 * Custom class for authenticating with the Promoter Connector.
 *
 * @since 4.9
 */
class Tribe__Promoter__Auth {

	/**
	 * @var Tribe__Promoter__Connector $connector
	 */
	private $connector;

	/**
	 * Tribe__Promoter__Auth constructor.
	 *
	 * @param Tribe__Promoter__Connector $connector Connector object.
	 *
	 * @since 4.9
	 */
	public function __construct( Tribe__Promoter__Connector $connector ) {
		$this->connector = $connector;
	}

	/**
	 * Authorize the request with the Promoter Connector.
	 *
	 * @return bool Whether the request was authorized successfully.
	 *
	 * @since 4.9
	 */
	public function authorize_with_connector() {
		$secret_key   = $this->generate_secret_key();
		$promoter_key = tribe_get_request_var( 'promoter_key' );
		$license_key  = tribe_get_request_var( 'license_key' );

		// send request to auth connector
		return $this->connector->authorize_with_connector( get_current_user_id(), $secret_key, $promoter_key, $license_key );
	}

	/**
	 * Grab the WP constant and store it as the auth key.
	 *
	 * @return string The secret key.
	 *
	 * @since 4.9
	 */
	private function generate_secret_key() {
		$key = AUTH_KEY;

		update_option( 'tribe_promoter_auth_key', $key );

		return $key;
	}

}
