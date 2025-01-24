<?php

namespace TEC\Common\Contracts;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Contracts\Provider\AlreadyRegisteredException;
use TEC\Common\Contracts\Provider\ControllerInactiveException;
use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use Tribe\Tests\Traits\With_Uopz;

class Container_Test extends WPTestCase {
	use With_Uopz;

	/**
	 * The cloned container.
	 *
	 * @return ?Container
	 */
	protected $cloned_container = null;

	/**
	 * @before
	 */
	public function prepare() {
		$this->cloned_container = clone tribe();
		$this->assertNotSame( $this->cloned_container, tribe() );

		$localized = $this->cloned_container;
		$this->set_fn_return( 'tribe', fn( $service = null ) => $service ? $localized->get( $service ) : $localized, true );

		$this->assertSame( $this->cloned_container, tribe() );
	}

	/**
	 * @test
	 */
	public function it_should_fire_registration_actions_only_once() {
		$provider = new class( $this->cloned_container ) extends Controller_Contract {
			public static string $registration_action = 'foo_test_action';

			protected function do_register(): void {}

			public function unregister(): void {}

			public function is_active(): bool {
				return true;
			}
		};

		$provider_class_name = get_class( $provider );

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( $provider::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 1, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 1, did_action( $provider::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 1, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 1, did_action( $provider::$registration_action ) );
	}

	/**
	 * @test
	 */
	public function it_should_fire_registration_actions_only_once_even_after_unregistration() {
		$provider = new class( $this->cloned_container ) extends Controller_Contract {
			public static string $registration_action = 'foo_test_action';

			protected function do_register(): void {}

			public function unregister(): void {}

			public function is_active(): bool {
				return true;
			}
		};

		$provider_class_name = get_class( $provider );

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( $provider::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 1, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 1, did_action( $provider::$registration_action ) );

		$provider->unregister();
		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 1, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 1, did_action( $provider::$registration_action ) );
	}

	/**
	 * @test
	 */
	public function it_should_not_fire_registration_actions_for_inactive() {
		$provider = new class( $this->cloned_container ) extends Controller_Contract {
			public static string $registration_action = 'foo_test_action';

			protected function do_register(): void {}

			public function unregister(): void {}

			public function is_active(): bool {
				return false;
			}
		};

		$provider_class_name = get_class( $provider );

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( $provider::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( $provider::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( $provider::$registration_action ) );
	}
}
