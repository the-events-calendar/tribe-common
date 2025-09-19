<?php
/**
 * Helper for Query Var related tests.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Helper
 */

namespace TEC\Common\Tests\Helper;

use Codeception\Module;
use TEC\Common\Request\Abstract_Query_Var;

/**
 * Class QueryVarHelper
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Helper
 */
class QueryVarHelper extends Module {
	/**
	 * Registers a generic query var for testing.
	 *
	 * @param string $name                     The name of the query var.
	 * @param bool   $should_filter_superglobal Whether the query var should filter superglobals.
	 *
	 * @return void
	 */
	public function registerGenericQueryVar( string $name, bool $should_filter_superglobal = false ): void {
		$query_var = new class extends Abstract_Query_Var {
			protected string $name = '';
			protected bool $should_filter = true;
			protected bool $should_filter_superglobal = false;

			public function set_name( string $name ): void {
				$this->name = $name;
			}

			public function set_should_filter_superglobal( bool $should_filter_superglobal ): void {
				$this->should_filter_superglobal = $should_filter_superglobal;
			}

			public function filter_superglobal_allowed( bool $allowed, string $superglobal ) {
				return $this->should_filter_superglobal;
			}

			public function filter_query_var( $value, array $query_vars ) {
				// Mimic iCal's behavior for arrays: take the first element if it's an array.
				if ( is_array( $value ) ) {
					$value = reset( $value );
				}

				// Test for set but not null.
				if ( '' === $value ) {
					return 1;
				}

				if ( null === $value ) {
					return null;
				}

				return $value;
			}
		};

		$query_var->set_name( $name );
		$query_var->set_should_filter_superglobal( $should_filter_superglobal );
		$query_var->register();
	}
}
