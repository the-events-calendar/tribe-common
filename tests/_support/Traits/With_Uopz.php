<?php

namespace Tribe\Tests\Traits;

use PHPUnit\Framework\Assert;

trait With_Uopz {
	private $uopz_set_returns = [];
	private $uopz_redefines = [];

	/**
	 * @after
	 */
	public function unset_uopz_returns() {
		if ( function_exists( 'uopz_set_return' ) ) {
			foreach ( $this->uopz_set_returns as $f ) {
				if ( is_array( $f ) ) {
					list( $class, $method ) = $f;
					uopz_unset_return( $class, $method );
				} else {
					uopz_unset_return( $f );
				}
			}
		}

		if ( function_exists( 'uopz_redefine' ) ) {
			foreach ( $this->uopz_redefines as $restore_callback ) { $restore_callback();
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

	private function set_const_value( $const, ...$args ) {
		if ( ! function_exists( 'uopz_redefine' ) ) {
			$this->markTestSkipped( 'uopz extension is not installed' );
		}

		if ( count( $args ) === 1 ) {
			// Normal const redefinition.
			$previous_value = defined( $const ) ? constant( $const ) : null;
			if ( null === $previous_value ) {
				$restore_callback = static function () use ( $const ) {
					uopz_undefine( $const );
					Assert::assertFalse( defined( $const ) );
				};
			} else {
				$restore_callback = static function () use ( $previous_value, $const ) {
					uopz_redefine( $const, $previous_value );
					Assert::assertEquals( $previous_value, constant( $const ) );
				};
			}
			uopz_redefine( $const, $args[0] );
			$this->uopz_redefines[] = $restore_callback;

			return;
		}

		// Static class const redefinition.
		$class = $const;
		list( $const, $value ) = $args;
		$previous_value = defined( $class . '::' . $const ) ?
			constant( $class . '::' . $const )
			: null;

		if ( null === $previous_value ) {
			$restore_callback = static function () use ( $const, $class ) {
				uopz_undefine( $class, $const );
				Assert::assertFalse( defined( $class . '::' . $const ) );
			};
		} else {
			$restore_callback = static function () use ( $class, $const, $previous_value ) {
				uopz_redefine( $class, $const, $previous_value );
				Assert::assertEquals( $previous_value, constant( $class . '::' . $const ) );
			};
		}
		uopz_redefine( $const, ...$args );
		$this->uopz_redefines[] = $restore_callback;
	}

	private function set_class_fn_return( $class, $method, $value, $execute = false ) {
		if ( ! function_exists( 'uopz_set_return' ) ) {
			$this->markTestSkipped( 'uopz extension is not installed' );
		}
		uopz_set_return( $class, $method, $value, $execute );
		$this->uopz_set_returns[] = [ $class, $method ];
	}

	private function set_class_property( $object, $field, $value ) {
		if ( ! function_exists( 'uopz_set_property' ) ) {
			$this->markTestSkipped( 'uopz extension is not installed' );
		}
		uopz_set_property( $object, $field, $value );
	}

	private function add_class_fn( $class, $function, $handler ) {
		if ( ! function_exists( 'uopz_add_function' ) ) {
			$this->markTestSkipped( 'uopz extension is not installed' );
		}
		uopz_add_function(
			$class,
			$function,
			$handler
		);
	}
}
