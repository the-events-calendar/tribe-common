<?php

namespace Tribe;

use Tribe__Feature_Detection as Feature_Detection;
use Tribe__Process__Tester as Tester;

class Feature_DetectionTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		parent::setUp();

		delete_option( 'tribe_feature_support_check_lock' );
		tec_timed_option()->delete( Tester::TRANSIENT_NAME );
	}

	public function tearDown() {
		remove_all_filters( 'tribe_supports_async_process' );

		delete_option( 'tribe_feature_support_check_lock' );
		tec_timed_option()->delete( Tester::TRANSIENT_NAME );

		parent::tearDown();
	}

	/**
	 * @test
	 * it should return the value set by the tribe_supports_async_process filter
	 */
	public function it_should_return_the_value_set_by_the_filter() {
		$sut = $this->make_instance();

		add_filter(
			'tribe_supports_async_process',
			static function () {
				return 'yes';
			}
		);

		$this->assertTrue( $sut->supports_async_process() );
	}

	/**
	 * @test
	 * it should honor a persisted negative result without re-running the loopback check
	 */
	public function it_should_honor_a_persisted_negative_result_without_re_running_the_check() {
		$sut = $this->make_instance();

		tec_timed_option()->set( Tester::TRANSIENT_NAME, 0, HOUR_IN_SECONDS );

		$this->assertFalse( $sut->supports_async_process() );
		$this->assertEmpty( get_option( 'tribe_feature_support_check_lock' ) );
	}

	/**
	 * @return Feature_Detection
	 */
	private function make_instance() {
		return new Feature_Detection();
	}
}
