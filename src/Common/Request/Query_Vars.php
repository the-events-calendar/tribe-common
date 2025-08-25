<?php
/**
 * Request query vars filter and sanitization.
 *
 * @since TBD
 *
 * @package TEC\Common\Request
 */

declare(strict_types=1);

namespace TEC\Common\Request;

/**
 * Class Query_Vars
 *
 * @since TBD
 *
 * @package TEC\Common\Request
 */
class Query_Vars {
	/**
	 * The GET superglobal.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const SUPERGLOBAL_GET = '_GET';

	/**
	 * The POST superglobal.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const SUPERGLOBAL_POST = '_POST';

	/**
	 * The REQUEST superglobal.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const SUPERGLOBAL_REQUEST = '_REQUEST';

	/**
	 * The allowed superglobals check list.
	 *
	 * @since TBD
	 *
	 * @var array<string>
	 */
	private const ALLOWED_SUPERGLOBALS = [
		self::SUPERGLOBAL_GET,
		self::SUPERGLOBAL_POST,
		self::SUPERGLOBAL_REQUEST,
	];

	/**
	 * Register hooks.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register(): void {
		do_action( 'tec_request_query_vars_registered' );

		add_filter( 'request', [ $this, 'clean_query_vars' ], 0 );
	}

	/**
	 * Unregister hooks.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_filter( 'request', [ $this, 'clean_query_vars' ], 0 );
	}

	/**
	 * Filter the query vars to allow for modifications.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $query_vars Parsed query variables.
	 *
	 * @return array<string,mixed> The filtered query vars.
	 */
	public function filter_query_vars( array $query_vars ): array {
		$query_vars = apply_filters( 'tec_request_query_vars', $query_vars );

		foreach ( $query_vars as $key => $value ) {
			/**
			 * Filter whether the query var should be filtered.
			 * Defaults to false so we skip over most vars.
			 *
			 * @since TBD
			 *
			 * @param bool $should_filter Whether the query var should be filtered.
			 */
			$should_filter = apply_filters( "tec_request_query_vars_should_filter_{$key}", false );

			// Only filter vars that have been explicitly allowed.
			if ( ! $should_filter ) {
				continue;
			}

			/**
			 * Filter the query var value.
			 *
			 * @since TBD
			 *
			 * @param mixed $value The query var value.
			 * @param array<string,mixed> $query_vars The query vars.
			 *
			 * @return mixed The filtered query var value. Null to unset it.
			 */
			$filtered_value = apply_filters( "tec_request_query_vars_{$key}", $value, $query_vars );

			$query_vars[ $key ] = $filtered_value;
		}

		return $query_vars;
	}

	/**
	 * Sanitize relevant query vars as early as possible.
	 *
	 * Ensures vars are normalized and sanitized.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $vars Parsed query variables.
	 *
	 * @return array<string,mixed> The sanitized query vars.
	 */
	public function clean_query_vars( array $vars ): array {
		// Filter the query vars first. This should include sanitization based on expected the query var value type(s).
		$vars = $this->filter_query_vars( $vars );

		// Remove null values.
		foreach ( $vars as $key => $value ) {
			if ( null === $value ) {
				unset( $vars[ $key ] );
			}

			// Make superglobals match the filtered vars.
			$this->filter_superglobals( $key, $value );
		}

		return $vars;
	}

	/**
	 * Filter the superglobals.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to filter.
	 * @param mixed  $value The value to filter.
	 *
	 * @return void
	 */
	protected function filter_superglobals( string $key, $value ): void {
		foreach ( self::ALLOWED_SUPERGLOBALS as $superglobal ) {
			$this->filter_superglobal_value( $superglobal, $key, $value );
		}
	}

	/**
	 * Sanitize a specific key in a superglobal-like array reference.
	 * Allows individual vars to indicate if they should get filtered here.
	 * Unset the value if it is null.
	 *
	 * @since TBD
	 *
	 * @param string $superglobal The superglobal key (GET, POST, REQUEST).
	 * @param string $key         The key to sanitize.
	 * @param mixed  $value       The value to sanitize.
	 *
	 * @return void
	 */
	protected function filter_superglobal_value( string $superglobal, string $key, $value ): void {
		if ( ! $this->should_filter_superglobal_value( $superglobal, $key ) ) {
			return;
		}

		if ( null === $value ) {
			unset( $GLOBALS[ $superglobal ][ $key ] );
			return;
		}

		$GLOBALS[ $superglobal ][ $key ] = $value;
	}

	/**
	 * Checks if the value should be filtered.
	 *
	 * @since TBD
	 *
	 * @param string $superglobal The superglobal key (GET, POST, REQUEST).
	 * @param string $key         The key to sanitize.
	 *
	 * @return bool Whether the value should be filtered.
	 */
	protected function should_filter_superglobal_value( string $superglobal, string $key ): bool {
		// Only allow whitelisted superglobals.
		// This shouldn't be necessary - but you can never be too careful.
		if ( ! in_array( $superglobal, self::ALLOWED_SUPERGLOBALS, true ) ) {
			return false;
		}

		/**
		 * Filter whether a var allows its superglobal value to be filtered.
		 *
		 * @since TBD
		 *
		 * @param bool   $allowed Whether the value is allowed.
		 * @param string $key The key to sanitize.
		 *
		 * @return bool|string Whether the value is allowed. Returning a string "key" will limit the superglobal modification to that key.
		 */
		$var_allowed = apply_filters( "tec_request_superglobal_allowed_{$key}", true, $superglobal );

		// If the var is not allowed, skip.
		if ( ! $var_allowed ) {
			return false;
		}

		// If the var is allowed, but not for this superglobal, skip.
		if ( is_string( $var_allowed ) && $var_allowed !== $superglobal ) {
			return false;
		}

		// If the superglobal is not set, skip.
		if ( ! isset( $GLOBALS[ $superglobal ] ) || ! is_array( $GLOBALS[ $superglobal ] ) ) {
			return false;
		}

		// If the key is not set, skip.
		if ( ! array_key_exists( $key, $GLOBALS[ $superglobal ] ) ) {
			return false;
		}

		return true;
	}
}
