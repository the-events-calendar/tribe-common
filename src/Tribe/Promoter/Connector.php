<?php

/**
 * Custom class for communicating with the Promoter Auth Connector
 *
 * @since TBD
 */
class Tribe__Promoter__Connector {

	/**
	 * Get the base URL for interacting with the connector.
	 *
	 * @return string
	 *
	 * @since TBD
	 */
	public static function base_url() {
		$url = 'https://promoter-connector.tribe/promoter-auth-connector/us-central1/promoterConnector/';

		if ( defined( 'PROMOTER_AUTH_CONNECTOR_URL' ) ) {
			$url = PROMOTER_AUTH_CONNECTOR_URL;
		}

		return $url;
	}

	/**
	 * Authorize Promoter to communicate with this site.
	 *
	 * @param string $user_id
	 * @param string $secret_key
	 * @param string $promoter_key
	 * @param string $license_key
	 *
	 * @return bool
	 *
	 * @since TBD
	 */
	public function authorize_with_connector( $user_id, $secret_key, $promoter_key, $license_key ) {
		$url = self::base_url() . 'connect';

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

		$response = self::make_call( $url, $args );

		return (bool) $response;
	}

	/**
	 * Authenticate the current request user with the Auth Connector
	 *
	 * @param string $user_id
	 *
	 * @return bool|string
	 *
	 * @since TBD
	 */
	public static function authenticate_user_with_connector( $user_id ) {
		$token = tribe_get_request_var( 'promoter_auth_token' );

		if ( empty( $token ) ) {
			return $user_id;
		}

		$url = self::base_url() . 'connect/auth';

		$args = array(
			'body'      => array( 'token' => $token ),
			'sslverify' => false,
		);

		$response = self::make_call( $url, $args );

		if ( ! $response ) {
			return $user_id;
		}

		return $response;
	}

	/**
	 * Notify the Promoter app of changes within this system.
	 *
	 * @param int $post_id
	 *
	 * @since TBD
	 */
	public function notify_promoter_of_changes( $post_id ) {
		$post_type = get_post_type( $post_id );

		if ( ! in_array( $post_type, array( 'tribe_events', 'tribe_tickets' ), true ) ) {
			return;
		}

		$license_key = get_option( 'pue_install_key_promoter' );
		$secret_key  = get_option( 'promoter_auth_key' );

		if ( empty( $license_key ) || empty( $secret_key ) ) {
			return;
		}

		$payload = array(
			'licenseKey' => $license_key,
		);

		$token = \Firebase\JWT\JWT::encode( $payload, $secret_key );

		$url = self::base_url() . 'connect/notify';

		$args = array(
			'body'      => array( 'token' => $token ),
			'sslverify' => false,
		);

		self::make_call( $url, $args );
	}

	/**
	 * Make the call to the remote endpoint.
	 *
	 * @param string $url
	 * @param array $args
	 *
	 * @return bool|string
	 *
	 * @since TBD
	 */
	private static function make_call( $url, $args ) {
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

}
