<?php
/**
 * Provides method to mock the services registered in the `tribe()` service locator in tests.
 *
 * @package Traits;
 */

namespace Tribe\Tests\Traits;

use Closure;

/**
 * Class Service_Locator_Mocks.
 *
 * @package Traits;
 */
trait Service_Locator_Mocks {
	/**
	 * A set of Closures that will unset the mocks when called.
	 *
	 * @var array<Closure>
	 */
	private array $service_locator_mock_unsets = [];

	/**
	 * Mocks a singleton service annd replaces it with a mock.
	 *
	 * @param string $slug    The service slug.
	 * @param array  $methods An array of methods and their return values.
	 *                        If a closure is provided, it will be called when the method is called.
	 *
	 * @return Closure A closure that will restore the original service when called.
	 */
	protected function mock_singleton_service( $slug, array $methods ): Closure {
		$original_service    = tribe( $slug );
		$original_class_name = get_class( $original_service );
		$mock                = $this->createPartialMock( $original_class_name, array_keys( $methods ) );
		$mock_class_name     = get_class( $mock );
		tribe()->singleton( $slug, $mock_class_name );
		$built_by_service_locator = tribe()->get( $slug );
		tribe()->singleton( $slug, function () use ( $built_by_service_locator, $methods ) {
			foreach ( $methods as $method => $return ) {
				if ( $return instanceof Closure ) {
					$built_by_service_locator->method( $method )->willreturncallback( $return );
					continue;
				}
				$built_by_service_locator->method( $method )->willreturn( $return );
			}

			return $built_by_service_locator;
		} );

		$unsets = $this->service_locator_mock_unsets;
		$unset  = static function () use ( $slug, $original_service, &$unsets, &$unset ) {
			tribe()->singleton( $slug, $original_service );
			$unsets = array_values( array_diff( $unsets, [ $unset ] ) );
		};

		$this->service_locator_mock_unsets[] = $unset;

		return $unset;
	}

	/**
	 * Mocks a prototype service. i.e. a service that is not bound as a singleton, and replaces it with a mock.
	 *
	 * @param string $slug    The service slug.
	 * @param array  $methods An array of methods and their return values.
	 *                        If a closure is provided, it will be called when the method is called.
	 *
	 * @return Closure A closure that will restore the original service when called.
	 */
	protected function mock_prototype_service( $slug, array $methods ): Closure {
		$original_service    = tribe( $slug );
		$original_class_name = get_class( $original_service );
		$mock                = $this->createPartialMock( $original_class_name, array_keys( $methods ) );
		$mock_class_name     = get_class( $mock );
		tribe()->bind( $slug, $mock_class_name );
		$built_by_service_locator = tribe()->get( $slug );
		tribe()->bind( $slug, function () use ( $built_by_service_locator, $methods ) {
			$built_by_service_locator_clone = clone $built_by_service_locator;
			foreach ( $methods as $method => $return ) {
				if ( $return instanceof Closure ) {
					$built_by_service_locator_clone->method( $method )->willreturncallback( $return );
					continue;
				}
				$built_by_service_locator_clone->method( $method )->willreturn( $return );
			}

			return $built_by_service_locator_clone;
		} );

		$unsets = $this->service_locator_mock_unsets;
		$unset  = static function () use ( $slug, $original_service, &$unsets, &$unset ) {
			tribe()->bind( $slug, $original_service );
			$unsets = array_values( array_diff( $unsets, [ $unset ] ) );
		};

		$this->service_locator_mock_unsets[] = $unset;

		return $unset;
	}

	/**
	 * @after
	 */
	public function unset_all_service_locator_mocks(): void {
		foreach ( $this->service_locator_mock_unsets as $unset ) {
			$unset();
		}

		$this->service_locator_mock_unsets = [];
	}
}
