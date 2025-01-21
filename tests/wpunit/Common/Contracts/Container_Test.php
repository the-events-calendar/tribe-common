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
		$provider_class_name = Fake_Test_Controller::class;

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( Fake_Test_Controller::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 1, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 1, did_action( Fake_Test_Controller::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 1, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 1, did_action( Fake_Test_Controller::$registration_action ) );
	}

	/**
	 * @test
	 */
	public function it_should_not_fire_registration_actions_for_inactive() {
		$provider_class_name = Fake_Test_Inactive_Controller::class;

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( Fake_Test_Inactive_Controller::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( Fake_Test_Inactive_Controller::$registration_action ) );

		$this->cloned_container->register( $provider_class_name );

		$this->assertEquals( 0, did_action( 'tec_container_registered_provider_' . $provider_class_name ) );
		$this->assertEquals( 0, did_action( Fake_Test_Inactive_Controller::$registration_action ) );
	}
}

class Fake_Test_Controller extends Controller_Contract {
	public static string $registration_action = 'foo_test_action';

	protected function do_register(): void {}

	public function unregister(): void {}

	public function is_active(): bool {
		return true;
	}
}

class Fake_Test_Inactive_Controller extends Fake_Test_Controller {
	public function is_active(): bool {
		return false;
	}
}
