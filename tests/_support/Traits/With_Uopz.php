<?php

namespace Tribe\Tests\Traits;

trait With_Uopz {
	private $uopz_set_returns = [];

	/**
	 * @after
	 */
	public function unset_uopz_returns() {
		if ( function_exists( 'uopz_set_return' ) ) {
			foreach ( $this->uopz_set_returns as $f ) {
				uopz_unset_return( $f );
			}
		}
	}

	/**
	 * Wrapper for uopz_set_return
	 *
	 * @since 4.15.1
	 *
	 * @param [type] $fn       The name of an existing function
	 * @param [type] $value    The value the function should return.
	 *                         If a Closure is provided and the execute flag is set,
	 *                         the Closure will be executed in place of the original function.
	 * @param boolean $execute If true, and a Closure was provided as the value,
	 *                         the Closure will be executed in place of the original function.
	 * @return void
	 */
	private function set_fn_return( $fn, $value, $execute = false ) {
		if ( ! function_exists( 'uopz_set_return' ) ) {
			$this->markTestSkipped( 'uopz extension is not installed' );
		}
		uopz_set_return( $fn, $value, $execute );
		$this->uopz_set_returns[] = $fn;
	}
}
