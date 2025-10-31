<?php
/**
 * Abstract class for query vars.
 *
 * @since TBD
 *
 * @package TEC\Common\Request
 */

namespace TEC\Common\Request;

use TEC\Common\Contracts\Provider\Controller;

/**
 * Abstract class for query vars.
 * Contains functions for filtering and sanitizing query vars.
 *
 * Extend this class to create a new query var object.
 *
 * @see Query_Vars::register()
 * @see Query_Vars::unregister()
 *
 * @since TBD
 *
 * @package TEC\Common\Request
 */
abstract class Abstract_Query_Var extends Controller {
	/**
	 * The query var name (key/slug).
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private string $name;

	/**
	 * Whether the query var should be filtered.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	private bool $should_filter = false;

	/**
	 * Whether the query var should be filtered for the superglobal.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	private bool $should_filter_superglobal = false;

	/**
	 * Whether the query var should accept valueless params.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	private bool $should_accept_valueless_params = false;

	/**
	 * Registers the query var filtering.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$name = $this->get_name();
		add_filter( "tec_request_query_vars_{$name}", [ $this, 'filter_query_var' ], 10, 2 );
		add_filter( "tec_request_query_vars_should_filter_{$name}", [ $this, 'should_filter' ], 10, 1 );

		// Self-register with the Query_Vars instance.
		$this->register_with_query_vars();
	}

	/**
	 * Unregisters the query var filtering.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		$name = $this->get_name();
		remove_filter( "tec_request_query_vars_{$name}", [ $this, 'filter_query_var' ], 10 );
		remove_filter( "tec_request_query_vars_should_filter_{$name}", [ $this, 'should_filter' ], 10 );

		// Self-unregister from the Query_Vars instance.
		$this->unregister_from_query_vars();
	}

	/**
	 * Get the query var name.
	 *
	 * @since TBD
	 *
	 * @return string The query var name.
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Set the query var name.
	 *
	 * @since TBD
	 *
	 * @param string $name The query var name.
	 */
	public function set_name( string $name ): void {
		$this->name = $name;
	}

	/**
	 * Filters if the query var should be filtered.
	 * Defaults to false to help short-circuit the query var filtering in most cases.
	 *
	 * @since TBD
	 *
	 * @param string $key The query var name.
	 *
	 * @return bool Whether the query var should be filtered.
	 */
	public function should_filter( string $key ): bool {
		return $this->should_filter;
	}

	/**
	 * Whether the query var should overwrite valueless params.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the query var should accept valueless params.
	 */
	public function should_accept_valueless_params(): bool {
		$name = $this->get_name();
		/**
		 * Filter whether the query var should accept valueless params.
		 *
		 * @since TBD
		 *
		 * @param bool               $should_accept_valueless_params Whether the query var should accept valueless params.
		 * @param Abstract_Query_Var $query_var                      The query var.
		 *
		 * @return bool Whether the query var should accept valueless params.
		 */
		return (bool) apply_filters( "tec_request_query_vars_should_accept_valueless_params_{$name}", $this->should_accept_valueless_params, $this );
	}

	/**
	 * Filters the query var.
	 *
	 * @since TBD
	 *
	 * @param mixed $value      The query var value.
	 * @param array $query_vars The query vars.
	 *
	 * @return mixed The filtered query var value. Null to unset it.
	 */
	public function filter_query_var( $value, array $query_vars ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return $this->maybe_replace_valueless_params( $value, $query_vars );
	}

	/**
	 * Replace valueless params.
	 *
	 * @since TBD
	 *
	 * @param mixed $value The query var value.
	 * @param array $query_vars The query vars.
	 *
	 * @return mixed The filtered query var value. Null to unset it.
	 */
	public function maybe_replace_valueless_params( $value, array $query_vars ) {
		// Only if we flagged it as accepting valueless params.
		if ( ! $this->should_accept_valueless_params() ) {
			return $value;
		}

		// Only if the query var key exists.
		if ( ! array_key_exists( $this->get_name(), $query_vars ) ) {
			return $value;
		}

		// Only if the value is an empty string or null. "false" is a valid value.
		if ( '' !== $value && null !== $value ) {
			return $value;
		}

		// Support presence-only query var (?ical) as truthy.
		return 1;
	}

	/**
	 * Filters if the superglobal is allowed to be filtered for this var.
	 *
	 * @since TBD
	 *
	 * @return bool|string Whether the superglobal is allowed to be filtered for this var.
	 *                     Returning a string "key" will limit the superglobal modification to that key only.
	 *                     Flexible formats supported: "get", "GET", "_GET", "_get" all resolve to "_GET".
	 *                     Same applies to "post"/"POST"/"_POST" and "request"/"REQUEST"/"_REQUEST".
	 */
	public function filter_superglobal_allowed() { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		// Add logic for individual $superglobal strings here in extending classes.
		return $this->should_filter_superglobal;
	}

	/**
	 * Registers this query var with the Query_Vars instance.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	private function register_with_query_vars(): void {
		if ( ! $this->container->isBound( Query_Vars::class ) ) {
			return;
		}

		$query_vars = $this->container->get( Query_Vars::class );
		$query_vars->register_query_var( $this );
	}

	/**
	 * Unregisters this query var from the Query_Vars instance.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	private function unregister_from_query_vars(): void {
		if ( ! $this->container->isBound( Query_Vars::class ) ) {
			return;
		}

		$query_vars = $this->container->get( Query_Vars::class );
		$query_vars->unregister_query_var( $this );
	}
}
