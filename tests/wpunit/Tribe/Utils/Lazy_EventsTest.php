<?php

namespace Tribe\Utils;

class Lazy_EventsTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should call the resolve callback if set and resolved
	 *
	 * @test
	 */
	public function should_call_the_resolve_callback_if_set_and_resolved() {
		$lazy = $this->make_lazy_object();

		$called = false;
		$lazy->on_resolve( static function () use ( &$called ) {
			$called = true;
		} );

		$this->assertEquals( 'test', $lazy->resolve() );

		do_action( 'test_action' );

		$this->assertTrue( $called );
	}

	protected function make_lazy_object() {
		$lazy = new class {
			use Lazy_Events;

			protected $lazy_resolve_action = 'test_action';
			public $lazy_resolve_priority = 23;

			public function resolve() {
				$this->resolved();

				return 'test';
			}
		};

		return $lazy;
	}

	/**
	 * It should only call the resolve callback once even if hooked multiple times.
	 *
	 * @test
	 */
	public function should_only_call_the_resolve_callback_once_even_if_hooked_multiple_times_() {
		$lazy_1 = $this->make_lazy_object();
		$lazy_2 = $this->make_lazy_object();
		$lazy_3 = $this->make_lazy_object();

		$on_resolve = function () {
			yield true;
			$this->fail( 'The on_resolve callback should be called once.' );
		};

		$lazy_1->on_resolve( $on_resolve );
		$lazy_2->on_resolve( $on_resolve );
		$lazy_3->on_resolve( $on_resolve );

		$this->assertEquals( 'test', $lazy_1->resolve() );
		$this->assertEquals( 'test', $lazy_2->resolve() );
		$this->assertEquals( 'test', $lazy_3->resolve() );

		$this->assertEquals( 23, has_action( 'test_action', $on_resolve ) );

		do_action( 'test_action' );
	}

	/**
	 * It should hook the on_resolve callback at most once for different objects
	 *
	 * @test
	 */
	public function should_hook_the_on_resolve_callback_at_most_once_for_different_objects() {
		$lazy_1 = $this->make_lazy_object();
		$lazy_2 = $this->make_lazy_object();
		$lazy_2->lazy_resolve_priority = 89;

		$on_resolve = function () {
			yield true;
			$this->fail( 'The on_resolve callback should be called once.' );
		};

		$lazy_1->on_resolve( $on_resolve );
		$lazy_2->on_resolve( $on_resolve );

		$this->assertEquals( 'test', $lazy_1->resolve() );
		$this->assertEquals( 'test', $lazy_2->resolve() );

		$this->assertEquals( 89, has_action( 'test_action', $on_resolve ) );

		do_action( 'test_action' );
	}
}
