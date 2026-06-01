<?php
/**
 * Auth URL decorator.
 *
 * @since 6.11.0
 *
 * @package TEC\Common\Integrations\Uplink
 */

namespace TEC\Common\Integrations\Uplink;

use TEC\Common\StellarWP\Uplink\API\V3\Auth\Auth_Url_Cache_Decorator;
use TEC\Common\StellarWP\Uplink\API\V3\Auth\Contracts\Auth_Url;

/**
 * Auth URL decorator.
 *
 * @since 6.11.0
 *
 * @package TEC\Common\Integrations\Uplink
 */
class Auth_URL_Decorator implements Auth_Url {

	/**
	 * @var Auth_Url_Cache_Decorator
	 */
	private Auth_Url_Cache_Decorator $auth_url_cache_decorator;

	/**
	 * The auth URL cache decorator constructor.
	 *
	 * @since 6.11.0
	 *
	 * @param Auth_Url_Cache_Decorator $auth_url_cache_decorator The auth URL cache decorator.
	 */
	public function __construct( Auth_Url_Cache_Decorator $auth_url_cache_decorator ) {
		$this->auth_url_cache_decorator = $auth_url_cache_decorator;
	}

	/**
	 * Get the auth URL.
	 *
	 * @since 6.11.0
	 *
	 * @param string $slug The slug.
	 *
	 * @return string The auth URL.
	 */
	public function get( string $slug ): string {
		$url = $this->auth_url_cache_decorator->get( $slug );

		/**
		 * Filter the auth URL.
		 *
		 * @since 6.11.0
		 *
		 * @param string $url The auth URL.
		 * @param string $slug The slug.
		 *
		 * @return string The auth URL.
		 */
		return apply_filters( 'tec_common_uplink_auth_url', $url, $slug );
	}
}
