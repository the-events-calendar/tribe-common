<?php

/**
 * Custom class for communicating with the Promoter Auth Connector
 *
 * @since 4.9
 */
class Tribe__Promoter__Connector {

	/**
	 * Whether the user request is currently authorized by Promoter.
	 *
	 * @since 4.9.4
	 *
	 * @var bool
	 */
	public $authorized = false;

	/**
	 * Get the base URL for interacting with the connector.
	 *
	 * @return string Base URL for interacting with the connector.
	 *
	 * @since 4.9
	 */
	public function base_url() {
		$url = 'https://us-central1-promoter-auth-connector.cloudfunctions.net/promoterConnector/';

		if ( defined( 'TRIBE_PROMOTER_AUTH_CONNECTOR_URL' ) ) {
			$url = TRIBE_PROMOTER_AUTH_CONNECTOR_URL;
		}

		return $url;
	}

	/**
	 * Authorize Promoter to communicate with this site.
	 *
	 * @param string $user_id      Promoter user ID.
	 * @param string $secret_key   Promoter secret key.
	 * @param string $promoter_key Promoter key (not license related).
	 * @param string $license_key  Promoter license key.
	 *
	 * @return bool Whether connector was authorized.
	 *
	 * @since 4.9
	 */
	public function authorize_with_connector( $user_id, $secret_key, $promoter_key, $license_key ) {
		$url = $this->base_url() . 'connect';

		$payload = array(
			'clientSecret' => $secret_key,
			'licenseKey'   => $license_key,
			'userId'       => $user_id,
		);

		tribe( 'logger' )->log( $url );

		$token = \Firebase\JWT\JWT::encode( $payload, $promoter_key );

		$args = array(
			'body'      => array( 'token' => $token ),
			'sslverify' => false,
		);

		$response = $this->make_call( $url, $args );

		return (bool) $response;
	}

	/**
	 * Authenticate the current request user with the Auth Connector
	 *
	 * @param string $user_id User ID.
	 *
	 * @return bool|string User ID or if promoter is authorized then it return true like a valid user.
	 *
	 * @since 4.9
	 */
	public function authenticate_user_with_connector( $user_id ) {
		$this->authorized = false;

		$token = tribe_get_request_var( 'tribe_promoter_auth_token' );

		if ( empty( $token ) ) {
			return $user_id;
		}

		$url = $this->base_url() . 'connect/auth';

		$args = array(
			'body'      => array( 'token' => $token ),
			'sslverify' => false,
		);

		$response = $this->make_call( $url, $args );

		if ( ! $response ) {
			return $user_id;
		}

		$this->authorized = true;

		return $response;
	}

	/**
	 * Notify the Promoter app of changes within this system.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @since 4.9
	 */
	public function notify_promoter_of_changes( $post_id ) {
		$post_type = get_post_type( $post_id );

		if ( ! in_array( $post_type, array( 'tribe_events', 'tribe_tickets' ), true ) ) {
			return;
		}

		/** @var Tribe__Promoter__PUE $promoter_pue */
		$promoter_pue = tribe( 'promoter.pue' );
		$license_info = $promoter_pue->get_license_info();

		if ( ! $license_info ) {
			return;
		}

		$license_key = $license_info['key'];
		$secret_key  = $this->get_secret_key();

		if ( empty( $secret_key ) ) {
			return;
		}

		$payload = array(
			'licenseKey' => $license_key,
			'sourceId'   => $post_id,
		);

		$token = \Firebase\JWT\JWT::encode( $payload, $secret_key );

		$url = $this->base_url() . 'connect/notify';

		$args = array(
			'body'      => array( 'token' => $token ),
			'sslverify' => false,
		);

		$this->make_call( $url, $args );
	}

	/**
	 * Get the value for the option `tribe_promoter_auth_key`
	 *
	 * @since 4.9.12
	 *
	 * @return mixed
	 */
	protected function get_secret_key() {
		$secret_key  = get_option( 'tribe_promoter_auth_key' );

		/**
		 * @since 4.9.12
		 *
		 * @param string $secret_key
		 */
		return apply_filters( 'tribe_promoter_secret_key', $secret_key );
	}

	/**
	 * Make the call to the remote endpoint.
	 *
	 * @param string $url  URL to send data to.
	 * @param array  $args Data to send.
	 *
	 * @return string|false The response body or false if not successful.
	 *
	 * @since 4.9
	 */
	private function make_call( $url, $args ) {
		$response = wp_remote_post( $url, $args );
		$code     = wp_remote_retrieve_response_code( $response );
		$body     = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $response ) ) {
			tribe( 'logger' )->log( $response->get_error_message() );

			return false;
		}

		if ( $code > 299 ) {
			tribe( 'logger' )->log( $body, 0 );

			return false;
		}

		return $body;
	}

	/**
	 * Check whether the user request is currently authorized by Promoter.
	 *
	 * @since 4.9.4
	 *
	 * @return bool Whether the user request is currently authorized by Promoter.
	 */
	public function is_user_authorized() {
		return $this->authorized;
	}

}
