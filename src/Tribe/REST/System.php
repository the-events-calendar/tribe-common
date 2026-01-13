<?php


class Tribe__REST__System {

	/**
	 * Whether the WP installation supports WP REST API or not.
	 *
	 * @return bool
	 */
	public function supports_wp_rest_api() {
		return function_exists( 'get_rest_url' );
	}

	/**
	 * Determines if we are coming from a REST API request.
	 *
	 * @since 5.0.0
	 * @since 6.10.1 Introduced the `tec_common_is_rest_api` filter and added some escaping and sanitization.
	 *
	 * @return bool
	 */
	public static function is_rest_api(): bool {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			// Probably a CLI request
			return false;
		}

		$rest_prefix = trailingslashit( rest_get_url_prefix() );
		$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		/**
		 * Filters if the current request is a REST API request.
		 *
		 * @since 6.10.1
		 *
		 * @param bool $is_rest_api True if the current request is a REST API request, false otherwise.
		 */
		return (bool) apply_filters( 'tec_common_is_rest_api', strpos( $request_uri, $rest_prefix ) !== false );
	}
}
