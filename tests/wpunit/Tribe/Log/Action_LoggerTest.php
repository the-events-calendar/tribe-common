<?php

namespace Tribe\Log;

class Action_LoggerTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should always be available
	 *
	 * @test
	 */
	public function should_always_be_available() {
		$action_logger = new Action_Logger();
		$this->assertTrue( $action_logger->is_available() );
	}

	/**
	 * It should have a name.
	 *
	 * @test
	 */
	public function should_have_a_name_() {
		$action_logger = new Action_Logger();
		$this->assertNotEmpty( $action_logger->get_name() );
		$this->assertInternalType( 'string', $action_logger->get_name() );
	}

	public function log_levels_data_set() {
		foreach (
			[
				\Tribe__Log::DEBUG,
				\Tribe__Log::WARNING,
				\Tribe__Log::ERROR,
				\Tribe__Log::SUCCESS,
			] as $level
		) {
			yield $level => [ $level ];
		}
	}

	/**
	 * It should forward log calls to the tribe_log action
	 *
	 * @test
	 * @dataProvider log_levels_data_set
	 */
	public function should_forward_log_calls_to_the_tribe_log_action( $type ) {
		add_action( 'tribe_log',
			function () {
				$this->assertCount( 2, func_get_args() );
			},
			10,
			10
		);

		$action_logger = new Action_Logger();

		$action_logger->log( 'test', $type, 'Log source' );
	}

	/**
	 * It should provide a message when retrievng logs.
	 *
	 * @test
	 */
	public function should_provide_a_message_when_retrievng_logs_() {
		$action_logger = new Action_Logger();
		$logs          = $action_logger->retrieve();
		$this->assertNotEmpty( $logs );
	}

	/**
	 * It should not have available logs
	 *
	 * @test
	 */
	public function should_not_have_available_logs() {
		$action_logger = new Action_Logger();
		$this->assertEmpty( $action_logger->list_available_logs() );
	}

	/**
	 * It should allow changing the log channel with use_log
	 *
	 * @test
	 */
	public function should_allow_changing_the_log_channel_with_use_log() {
		$action_logger = new Action_Logger();
		$action_logger->use_log( 'my_channel' );

		$this->assertEquals( 'my_channel', tribe( 'monolog' )->getName() );
	}

	/**
	 * It should do nothing on cleanup
	 *
	 * @test
	 */
	public function should_do_nothing_on_cleanup() {
		$action_logger = new Action_Logger();
		$this->assertTrue( $action_logger->cleanup() );
	}
}
