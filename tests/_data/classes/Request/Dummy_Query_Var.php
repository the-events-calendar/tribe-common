<?php
/**
 * Dummy query var.
 *
 * @since TBD
 *
 * @package TEC\Events\Request
 */

namespace Tribe\Common\Tests\Request;

use TEC\Common\Request\Abstract_Query_Var;

/**
 * Class Dummy_Query_Var
 *
 * @since TBD
 *
 * @package TEC\Common\Request
 */
class Dummy_Query_Var extends Abstract_Query_Var {
	/**
	 * The query var name.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $name = 'dummy_query_var';

	/**
	 * Whether the query var should be filtered.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	protected bool $should_filter = true;

	/**
	 * Whether the query var should accept valueless params.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	protected bool $should_accept_valueless_params = true;

	/**
	 * Whether the query var should filter superglobals.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	protected bool $should_filter_superglobal = true;

	/**
	 * Filters the value to either `1` or `null` (to unset).
	 *
	 * @since TBD
	 *
	 * @param mixed $value      The raw value to normalize.
	 * @param array $query_vars The query vars.
	 *
	 * @return int|null `1` when truthy, `null` when not.
	 */
	public function filter_query_var( $value, array $query_vars ) {
		if ( is_array( $value ) ) {
			$value = reset( $value );
		}

		// Support presence-only query var (?ical) as truthy.
		if ( array_key_exists( $this->get_name(), $query_vars ) && ( '' === $value || null === $value ) ) {
			return 1;
		}

		return tribe_is_truthy( $value ) ? 1 : null;
	}
}
