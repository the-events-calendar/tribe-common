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

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Query_Vars
 *
 * @since TBD
 *
 * @package TEC\Common\Request
 */
class Query_Vars extends Controller_Contract {
	/**
	 * The action registration action for the query vars controller.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static string $registration_action = 'tec_request_query_vars_registered';

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
	 * The registered query vars.
	 *
	 * @since TBD
	 *
	 * @var array<string,Abstract_Query_Var>
	 */
	private array $registered_query_vars = [];

	/**
	 * Register hooks.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
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
	 * Register a query var.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Query_Var $query_var The query var to register.
	 */
	public function register_query_var( Abstract_Query_Var $query_var ): void {
		$this->registered_query_vars[ $query_var->get_name() ] = $query_var;
	}

	/**
	 * Unregister a query var.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Query_Var $query_var The query var to unregister.
	 */
	public function unregister_query_var( Abstract_Query_Var $query_var ): void {
		unset( $this->registered_query_vars[ $query_var->get_name() ] );
	}

	/**
	 * Get a registered query var.
	 *
	 * @since TBD
	 *
	 * @param string $name The name of the query var.
	 *
	 * @return ?Abstract_Query_Var The query var. Null if not registered..
	 */
	public function get_query_var( string $name ): ?Abstract_Query_Var {
		return $this->registered_query_vars[ $name ] ?? null;
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
		/**
		 * Filter the query vars.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $query_vars Parsed query variables.
		 *
		 * @return array<string,mixed> The filtered query vars.
		 */
		$query_vars = apply_filters( 'tec_request_query_vars', $query_vars );

		foreach ( $query_vars as $key => $value ) {
			$query_var = $this->get_query_var( $key );
			// Not one of ours - don't touch it!
			if ( ! $query_var ) {
				continue;
			}

			/**
			 * Filter whether the query var should be filtered.
			 * Defaults to false so we skip over most vars.
			 *
			 * @since TBD
			 *
			 * @param bool $should_filter Whether the query var should be filtered.
			 */
			$should_filter = apply_filters( "tec_request_query_vars_should_filter_{$key}", $query_var->should_filter( $key ) );

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
		// Filter the query vars first. This should include sanitization based on the expected query var value type(s).
		$vars = $this->filter_query_vars( $vars );

		// Handle final cleanup and superglobal synchronization.
		foreach ( $vars as $key => $value ) {
			$query_var = $this->get_query_var( $key );
			// Not one of ours - don't touch it!
			if ( ! $query_var ) {
				continue;
			}

			// If the query var doesn't accept valueless params, and we still have no value after filtering, unset it.
			if ( ( null === $value || '' === $value ) && ! $query_var->should_accept_valueless_params() ) {
				unset( $vars[ $key ] );
				$value = null; // Set to null so superglobals get unset.
			}

			// Make superglobals match the filtered vars.
			$this->filter_superglobals( $query_var, $value );
		}

		return $vars;
	}

	/**
	 * Filter the superglobals.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Query_Var $query_var The query var to filter.
	 * @param mixed $value The value to filter.
	 *
	 * @return void
	 */
	private function filter_superglobals( Abstract_Query_Var $query_var, mixed $value ): void {
		// If the query var doesn't allow filtering superglobals at all, skip it.
		if ( false === $query_var->filter_superglobal_allowed() ) {
			return;
		}

		foreach ( self::ALLOWED_SUPERGLOBALS as $superglobal ) {
			$this->filter_superglobal_value( $superglobal, $query_var, $value );
		}
	}

	/**
	 * Sanitize a specific key in a superglobal-like array reference.
	 * Allows individual vars to indicate if they should get filtered here.
	 * Only modifies existing superglobal keys, doesn't create new ones.
	 * Unset the value if it is null.
	 *
	 * @since TBD
	 *
	 * @param string             $superglobal The superglobal key (GET, POST, REQUEST).
	 * @param Abstract_Query_Var $query_var   The query var to sanitize.
	 * @param mixed              $value       The value to sanitize.
	 *
	 * @return void
	 */
	private function filter_superglobal_value( string $superglobal, Abstract_Query_Var $query_var, $value ): void {
		// Check if the query var allows filtering the superglobal.
		$allowed = $query_var->filter_superglobal_allowed();

		// If the allowed superglobal is a *string*, it means we only want to filter a *specific* superglobal (i.e. only $_GET).
		// This is useful for query vars that only want to filter one of the superglobals.
		// Normalize and use case-insensitive comparison for string matching.
		// Allow flexible formats like "get" → "_GET", "GET" → "_GET", "_get" → "_GET".
		if ( is_string( $allowed ) && 0 !== strcasecmp( $this->normalize_superglobal_name( $allowed ), $superglobal ) ) {
			return;
		}

		$key = $query_var->get_name();

		// Only modify existing superglobal keys, don't create new ones.
		if ( ! array_key_exists( $key, $GLOBALS[ $superglobal ] ) ) {
			return;
		}

		if ( null === $value ) {
			unset( $GLOBALS[ $superglobal ][ $key ] );
		} else {
			$GLOBALS[ $superglobal ][ $key ] = $value;
		}
	}

	/**
	 * Normalize a superglobal name to the expected format.
	 *
	 * Converts flexible input formats to standard superglobal names:
	 * - "get" → "_GET"
	 * - "GET" → "_GET"
	 * - "_get" → "_GET"
	 * - "_GET" → "_GET"
	 *
	 * @since TBD
	 *
	 * @param string $name The superglobal name to normalize.
	 *
	 * @return string The normalized superglobal name, or original if invalid.
	 */
	private function normalize_superglobal_name( string $name ): string {
		// Remove any existing underscore prefix and convert to uppercase.
		$normalized = '_' . strtoupper( ltrim( $name, '_' ) );

		// Only return normalized name if it's in our allowed list.
		if ( in_array( $normalized, self::ALLOWED_SUPERGLOBALS, true ) ) {
			return $normalized;
		}

		// Return original name if it doesn't match any allowed superglobal.
		return $name;
	}
}
