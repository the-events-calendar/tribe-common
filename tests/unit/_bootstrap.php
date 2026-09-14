<?php
// This suite runs without WordPress; the files it loads exit when ABSPATH is undefined.
defined( 'ABSPATH' ) || define( 'ABSPATH', dirname( __DIR__, 2 ) . '/' );

if ( ! function_exists( 'wp_parse_url' ) ) {
	/**
	 * Stand-in for the WordPress function in this WordPress-less suite, mirroring wp_parse_url().
	 *
	 * @param string $url       The URL to parse.
	 * @param int    $component A PHP_URL_* constant, or -1 for all components.
	 *
	 * @return mixed
	 */
	function wp_parse_url( $url, $component = -1 ) {
		$to_unset = [];
		$url      = (string) $url;

		if ( '//' === substr( $url, 0, 2 ) ) {
			$to_unset[] = 'scheme';
			$url        = 'placeholder:' . $url;
		} elseif ( '/' === substr( $url, 0, 1 ) ) {
			$to_unset[] = 'scheme';
			$to_unset[] = 'host';
			$url        = 'placeholder://placeholder' . $url;
		}

		$parts = parse_url( $url ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url -- This is the stand-in for wp_parse_url().

		if ( false === $parts ) {
			return $parts;
		}

		foreach ( $to_unset as $key ) {
			unset( $parts[ $key ] );
		}

		if ( -1 === $component ) {
			return $parts;
		}

		$key = [ PHP_URL_SCHEME => 'scheme', PHP_URL_HOST => 'host', PHP_URL_PORT => 'port', PHP_URL_USER => 'user', PHP_URL_PASS => 'pass', PHP_URL_PATH => 'path', PHP_URL_QUERY => 'query', PHP_URL_FRAGMENT => 'fragment' ][ $component ] ?? null;

		return $key && isset( $parts[ $key ] ) ? $parts[ $key ] : null;
	}
}

require_once __DIR__ . '/../../tribe-autoload.php';

$functions = __DIR__ . '/../../src/functions';
foreach ( glob( $functions . '/*.php', GLOB_NOSORT ) as $file ) {
	require_once $file;
}
