<?php

namespace Tribe;

use Tribe__Log as Log;

class LogTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @return Log
	 */
	private function make_instance() {
		return new Log();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Log::class, $sut );
	}

	/**
	 * It should cast success and colorize types to the debug type
	 *
	 * @test
	 */
	public function should_cast_success_and_colorize_types_to_the_debug_type() {
		$sut             = $this->make_instance();
		$logger_prophecy = $this->prophesize( \Tribe__Log__File_Logger::class );
		$logger_prophecy->log( 'success', Log::DEBUG, 'source' )->shouldBeCalled();
		$logger_prophecy->log( 'colorize', Log::DEBUG, 'source' )->shouldBeCalled();
		$logger = $logger_prophecy->reveal();
		add_filter( 'tribe_common_logging_engines', function ( array $engines ) use ( $logger ) {
			$engines[ get_class( $logger ) ] = $logger;

			return $engines;
		} );

		$sut->set_current_logger( get_class( $logger ) );
		$sut->set_level( Log::DEBUG );
		$sut->log( 'success', Log::SUCCESS, 'source' );
		$sut->log( 'colorize', Log::COLORIZE, 'source' );
	}
}