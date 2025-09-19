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
	protected string $name;

	/**
	 * Whether the query var should be filtered.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	protected bool $should_filter = false;

	/**
	 * Registers the query var filtering.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		add_filter( "tec_request_query_vars_{$this->name}", [ $this, 'filter_query_var' ], 10, 2 );
		add_filter( "tec_request_query_vars_should_filter_{$this->name}", [ $this, 'should_filter' ], 10, 1 );
		add_filter( "tec_request_superglobal_allowed_{$this->name}", [ $this, 'filter_superglobal_allowed' ], 10, 2 );
	}

	/**
	 * Unregisters the query var filtering.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_filter( "tec_request_query_vars_{$this->name}", [ $this, 'filter_query_var' ], 10 );
		remove_filter( "tec_request_query_vars_should_filter_{$this->name}", [ $this, 'should_filter' ], 10 );
		remove_filter( "tec_request_superglobal_allowed_{$this->name}", [ $this, 'filter_superglobal_allowed' ], 10 );
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
		return $value;
	}

	/**
	 * Filters if the superglobal is allowed to be filtered for this var.
	 *
	 * @since TBD
	 *
	 * @param bool   $allowed      Whether the superglobal is allowed to be filtered for this var.
	 * @param string $superglobal The superglobal key (GET, POST, REQUEST).
	 *
	 * @return bool|string Whether the superglobal is allowed to be filtered for this var. Returning a string "key" will limit the superglobal modification to that key.
	 */
	public function filter_superglobal_allowed( bool $allowed, string $superglobal ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return $allowed;
	}
}
