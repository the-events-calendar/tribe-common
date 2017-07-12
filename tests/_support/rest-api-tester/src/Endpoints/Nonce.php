<?php


class Tribe__RAP__Endpoints__Nonce
	extends Tribe__RAP__Endpoints__Base
	implements Tribe__RAP__Endpoints__Interface {

	public function get_url() {
		return rest_url( $this->namespace . '/nonce/' );
	}

	public function register() {
		register_rest_route( $this->namespace, '/nonce/(?P<id>\\d+)', array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, 'generate_nonce' ),
			'args'     => array(
				'id' => array(
					'type'    => 'integer',
					'default' => 0,
				),
			),
		) );
	}

	public function generate_nonce( WP_REST_Request $request ) {
		add_action( 'set_logged_in_cookie', array( $this, 'grab_logged_in_cookie' ) );
		add_filter( 'send_auth_cookies', '__return_false' );

		wp_set_auth_cookie( $request['id'], false, is_ssl() );
		wp_set_current_user( $request['id'] );

		$nonce = wp_create_nonce( 'wp_rest' );

		return $nonce;
	}

	public function grab_logged_in_cookie( $logged_in_cookie ) {
		$_COOKIE[ LOGGED_IN_COOKIE ] = $logged_in_cookie;
	}

	public function set_current_user( $user_id ) {
		if ( isset( $_SERVER['HTTP_X_TEC_REST_API_USER'] ) && filter_var( $_SERVER['HTTP_X_TEC_REST_API_USER'], FILTER_VALIDATE_INT ) ) {
			return (int) $_SERVER['HTTP_X_TEC_REST_API_USER'];
		}

		return $user_id;
	}
}
