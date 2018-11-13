<?php

class Tribe__Promoter__Auth {

	/**
	 * @var Tribe__Promoter__Connector $connector
	 */
	private $connector;

	public function __construct( Tribe__Promoter__Connector $connector ) {
		$this->connector = $connector;
	}

	public function authorize_with_connector() {
		$secret_key   = $this->generate_secret_key();
		$promoter_key = tribe_get_request_var( 'promoter_key' );
		$license_key  = tribe_get_request_var( 'license_key' );

		// send request to auth connector
		return $this->connector->authorize_with_connector( get_current_user_id(), $secret_key, $promoter_key, $license_key );
	}

	private function generate_secret_key() {
		$key = bin2hex( openssl_random_pseudo_bytes( 16 ) );
		update_option( 'promoter_auth_key', $key );

		return $key;
	}

}
