<?php

namespace Tribe\Log;

class Monolog_LoggerTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * It should allow changing the global channel to the specified one.
	 *
	 * @test
	 */
	public function should_allow_changing_the_global_channel_to_the_specified_one_() {
		/** @var Monolog_Logger $monolog_logger */
		$monolog_logger = tribe( 'monolog' );

		$monolog_logger->set_global_channel( 'test' );

		$this->assertEquals( 'test', tribe( 'monolog' )->getName() );

		$monolog_logger->reset_global_channel();

		$this->assertEquals( Monolog_Logger::DEFAULT_CHANNEL, tribe( 'monolog' )->getName() );
	}

	public function tearDown(): void {
		tribe( 'monolog' )->reset_global_channel();
		parent::tearDown();
	}
}