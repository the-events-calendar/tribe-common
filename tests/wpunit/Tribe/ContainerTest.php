<?php

namespace Tribe;

class A {
}

class B {
	private $a;
	private $c;

	public function __construct( A $a, C $c ) {
		$this->a = $a;
		$this->c = $c;
	}

	public function get_a() {
		return $this->a;
	}

	public function get_c() {
		return $this->c;
	}
}

class C {
}

class D {
	private $e;

	public function __construct( E $e ) {
		$this->e = $e;
	}

	public function get_e() {
		return $this->e;
	}
}

class E {
}

class F {
	public static $setup_called_times = 0;

	public function setup() {
		static::$setup_called_times ++;
	}
}

class G {
	private $f;

	public function __construct( F $f ) {
		$this->f = $f;
	}

	public function get_f() {
		return $this->f;
	}
}

class ContainerTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should bind the class when binding a slug for the class
	 *
	 * Here, since we're not using singletons, we're really just testing how the container works.
	 *
	 * @test
	 */
	public function should_bind_the_class_when_binding_a_slug_for_the_class() {
		tribe_register( 'test-a', A::class );

		$this->assertInstanceOf( A::class, tribe( 'test-a' ) );
		$this->assertInstanceOf( A::class, tribe( A::class ) );
	}

	/**
	 * It should bind the singleton class when registering a singleton for the class
	 *
	 * The container should understand that, when we bind `test-a-singleton`, we're binding that to `A`.
	 * As such the instance should be the same when we ask the container for `test-a-singleton` or for
	 * an instance of `A`.
	 *
	 * @test
	 */
	public function should_bind_the_singleton_class_when_registering_a_singleton_for_the_class() {
		tribe_singleton( 'test-a-singleton', A::class );

		$this->assertSame( tribe( 'test-a-singleton' ), tribe( A::class ) );
		$this->assertSame( tribe( 'test-a-singleton' ), tribe( 'test-a-singleton' ) );
		$this->assertSame( tribe( A::class ), tribe( A::class ) );
	}

	/**
	 * It should use classes registered w/ slug in auto-wiring
	 *
	 * Since we've registered a class of `A` as the one to return when asking for the implementation of `some-a`,
	 * when auto-wiring kicks in to build the instance of `B`, then the the container should not build a new instance
	 * of `A`, but use the one implicitly registered in the `tribe_singleton( 'some-a', A::class );` call.
	 *
	 * @test
	 */
	public function should_use_classes_registered_w_slug_in_auto_wiring() {
		tribe_singleton( 'some-a', A::class );
		tribe_singleton( C::class, C::class );

		$b = tribe( B::class );

		$this->assertInstanceOf( B::class, $b );
		$this->assertSame( tribe( 'some-a' ), $b->get_a() );
		$this->assertSame( tribe( A::class ), $b->get_a() );
		$this->assertSame( tribe( C::class ), $b->get_c() );
	}

	/**
	 * It should not auto-wire the class when implementation is closure
	 *
	 * There's a limit to magic: we cannot know what `some-e` will resolve to before resolving it.
	 * Registering the class of what `some-e` resolves to the first time it's resolved is a side-effect
	 * that would be overriding what's, maybe, set-up to resolve for `E`.
	 *
	 * @test
	 */
	public function should_not_auto_wire_the_class_when_implementation_is_closure() {
		tribe_singleton( 'some-e', static function () {
			return new E();
		} );

		$d = tribe( D::class );

		$this->assertInstanceOf( D::class, $d );
		$this->assertNotSame( tribe( E::class ), tribe( 'some-e' ) );
		$this->assertNotSame( tribe( 'some-e' ), $d->get_e() );
	}

	/**
	 * It should auto-wire implicitly with setup methods
	 *
	 * @test
	 */
	public function should_auto_wire_implicitly_with_setup_methods() {
		tribe_singleton( 'some-f', F::class, [ 'setup' ] );

		$g = tribe( G::class );

		$this->assertSame( $g->get_f(), tribe( 'some-f' ) );
		$this->assertSame( tribe( 'some-f' ), tribe( F::class ) );
		$this->assertEquals( 1, F::$setup_called_times );
	}
}
