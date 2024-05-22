<?php

/**
 * Class Tribe__RAP__Nonce
 *
 * Handles the spoofing (hacking) of the user for the purpose of passing the WP REST API nonce checks.
 */
class Tribe__RAP__Nonce {

	/**
	 * Stores the just generated logged in cookie for the user in the the COOKIE superglobal.
	 *
	 * @param string $logged_in_cookie
	 */
	public function grab_logged_in_cookie( $logged_in_cookie ) {
		$_COOKIE[ LOGGED_IN_COOKIE ] = $logged_in_cookie;
	}

	/**
	 * Stores the just generated auth, or secure auth, cookie for the user in the the COOKIE superglobal.
	 *
	 * @param string $auth_cookie
	 */
	public function grab_auth_cookie( $auth_cookie ) {
		$key             = is_ssl() ? SECURE_AUTH_COOKIE : AUTH_COOKIE;
		$_COOKIE[ $key ] = $auth_cookie;
	}

	/**
	 * Logs in the user specified in the REST API testing plugin request and sets up the show to pass nonce checks.
	 */
	public function maybe_spoof_user() {
		if (
			! isset( $_SERVER['HTTP_X_TEC_REST_API_USER'] )
			|| ! filter_var( $_SERVER['HTTP_X_TEC_REST_API_USER'], FILTER_VALIDATE_INT )
		) {
			return;
		}

		$user_id = (int) $_SERVER['HTTP_X_TEC_REST_API_USER'];
		$this->change_user_to( $user_id );
		$_SERVER['HTTP_X_WP_NONCE'] = wp_create_nonce( 'wp_rest' );
	}

	/**
	 * Changes the current user into the one requested by the testing plugin.
	 *
	 * @param \WP_REST_Request $request
	 */
	protected function change_user_to( $user_id ) {
		// save and store the cookie values generated during the `wp_set_auth_cookie` call below
		add_action( 'set_logged_in_cookie', array( $this, 'grab_logged_in_cookie' ) );
		add_action( 'set_auth_cookie', array( $this, 'grab_auth_cookie' ) );

		// we do not **really** want to change the user cookies, just for the time of the request...
		add_filter( 'send_auth_cookies', '__return_false' );

		// log-in spoof
		wp_set_auth_cookie( $user_id, false, is_ssl() );
		wp_set_current_user( $user_id );
	}
}
