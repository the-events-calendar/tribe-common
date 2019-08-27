<?php

namespace Tribe\Log;

class ProviderTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should register the Action Logger among the available logging engines
	 *
	 * @test
	 */
	public function should_register_the_action_logger_among_the_available_logging_engines() {
		add_filter( 'tribe_log_use_action_logger', '__return_true' );
		tribe_register_provider( Service_Provider::class );
		$this->assertArrayHasKey( Action_Logger::class, apply_filters( 'tribe_common_logging_engines', [] ) );
	}
}