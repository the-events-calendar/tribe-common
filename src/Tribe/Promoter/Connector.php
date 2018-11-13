<?php

class Tribe__Promoter__Connector {

	public static function base_url() {
		$url = 'https://connector.tri.be/promoter-auth-connector/us-central1/promoterConnector/';

		if ( defined( 'PROMOTER_AUTH_CONNECTOR_URL' ) ) {
			$url = PROMOTER_AUTH_CONNECTOR_URL;
		}

		return $url;
	}

	public function authorize_with_connector( $user_id, $secret_key, $promoter_key, $license_key ) {
		$url = self::base_url() . 'connect';

		$payload = array(
			'clientSecret' => $secret_key,
			'licenseKey'   => $license_key,
			'userId'       => $user_id,
		);

		error_log( $url );

		$token = \Firebase\JWT\JWT::encode( $payload, $promoter_key );

		$args = array(
			'body'      => array( 'token' => $token ),
			'sslverify' => false,
		);

		$response = wp_remote_post( $url, $args );

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $response ) ) {
			error_log( $response->get_error_message() );
			return false;
		}

		if ( $code > 299 ) {
			error_log( $body, 0 );
			return false;
		}

		return true;
	}

	public static function authenticate_user_with_connector( $user_id ) {
		if ( ! isset( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
			return $user_id;
		}

		$token = $_SERVER['HTTP_AUTHORIZATION'];

		if ( empty( $token ) ) {
			return $user_id;
		}

		$url = self::base_url() . 'connect/auth';

		$args = array(
			'body'      => array( 'token' => $token ),
			'sslverify' => false,
		);

		$response = wp_remote_post( $url, $args );
		$code     = wp_remote_retrieve_response_code( $response );
		$body     = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $response ) ) {
			error_log( $response->get_error_message() );
			return $user_id;
		}

		if ( $code > 299 ) {
			error_log( $body, 0 );
			return $user_id;
		}

		return $body;
	}

	public function notify_promoter_of_changes( $post_id ) {
		if ( get_post_type( $post_id ) !== 'tribe_events' && get_post_type( $post_id ) !== 'tribe_tickets' ) {
			return;
		}

		// @TODO figure out where to get License key here
		$license_key = 'foobar';
		$secret_key  = get_option( 'promoter_auth_key' );

		$payload = array(
			'licenseKey' => $license_key,
		);

		$token = \Firebase\JWT\JWT::encode( $payload, $secret_key );

		$url = self::base_url() . 'connect/notify';

		$args = array(
			'body'      => array( 'token' => $token ),
			'sslverify' => false,
		);

		$response = wp_remote_post( $url, $args );
		$code     = wp_remote_retrieve_response_code( $response );
		$body     = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $response ) ) {
			error_log( $response->get_error_message() );
			return;
		}

		if ( $code > 299 ) {
			error_log( $body, 0 );
			return;
		}
	}

}
