<?php

namespace Tribe\Log;

use Monolog\Handler\NullHandler;
use Tribe\Tests\Traits\With_Uopz;

class ProviderTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

	/**
	 * @before
	 */
	public function reset_env(): void {
		putenv( 'TEC_DISABLE_LOGGING=' );
		unset( $_ENV['TEC_DISABLE_LOGGING'] );
	}

	/**
	 * It should register the Action Logger among the available logging engines
	 *
	 * @test
	 */
	public function should_register_the_action_logger_among_the_available_logging_engines(): void {
		add_filter( 'tribe_log_use_action_logger', '__return_true' );
		tribe_register_provider( Service_Provider::class );
		$this->assertArrayHasKey( Action_Logger::class, apply_filters( 'tribe_common_logging_engines', [] ) );
	}

	public function test_setting_const_will_disable_logger(): void {
		$this->set_const_value( 'TEC_DISABLE_LOGGING', true );

		$logger = tribe(Service_Provider::class)->build_logger();

		$this->assertInstanceOf( Monolog_Logger::class, $logger );
		$this->assertCount( 1, $logger->getHandlers() );
		$this->assertInstanceOf( NullHandler::class, $logger->getHandlers()[0] );
	}

	public function test_setting_env_value_will_disable_logger(): void {
		$_ENV['TEC_DISABLE_LOGGING'] = true;

		$logger = tribe(Service_Provider::class)->build_logger();

		$this->assertInstanceOf( Monolog_Logger::class, $logger );
		$this->assertCount( 1, $logger->getHandlers() );
		$this->assertInstanceOf( NullHandler::class, $logger->getHandlers()[0] );
	}

	public function test_setting_putenv_value_will_disable_logger():void{
		putenv( 'TEC_DISABLE_LOGGING=1' );

		$logger = tribe(Service_Provider::class)->build_logger();

		$this->assertInstanceOf( Monolog_Logger::class, $logger );
		$this->assertCount( 1, $logger->getHandlers() );
		$this->assertInstanceOf( NullHandler::class, $logger->getHandlers()[0] );
	}

	public function test_setting_const_to_falsy_value_will_not_disable_logger():void{
		$this->set_const_value( 'TEC_DISABLE_LOGGING', false );

		$logger = tribe(Service_Provider::class)->build_logger();

		$this->assertInstanceOf( Monolog_Logger::class, $logger );
		$this->assertCount( 1, $logger->getHandlers() );
		$this->assertNotInstanceOf( NullHandler::class, $logger->getHandlers()[0] );
	}

	public function test_setting_env_to_falsy_value_will_not_disable_logger():void{
		$_ENV['TEC_DISABLE_LOGGING'] = false;

		$logger = tribe(Service_Provider::class)->build_logger();

		$this->assertInstanceOf( Monolog_Logger::class, $logger );
		$this->assertCount( 1, $logger->getHandlers() );
		$this->assertNotInstanceOf( NullHandler::class, $logger->getHandlers()[0] );
	}

	public function test_setting_putenv_to_falsy_value_will_not_disable_logger():void{
		putenv( 'TEC_DISABLE_LOGGING=0' );

		$logger = tribe(Service_Provider::class)->build_logger();

		$this->assertInstanceOf( Monolog_Logger::class, $logger );
		$this->assertCount( 1, $logger->getHandlers() );
		$this->assertNotInstanceOf( NullHandler::class, $logger->getHandlers()[0] );
	}

	public function test_filter_works_to_disable_the_logger():void{
		// Start from a context where the logger is not disabled by either env or const.
		$this->assertFalse( defined( 'TEC_DISABLE_LOGGING' ) );
		$this->assertEmpty( getenv( 'TEC_DISABLE_LOGGING' ) );
		// Filter `tec_disable_logging` to return true and disable the logger.
		add_filter( 'tec_disable_logging', '__return_true' );

		$logger = tribe(Service_Provider::class)->build_logger();

		$this->assertInstanceOf( Monolog_Logger::class, $logger );
		$this->assertCount( 1, $logger->getHandlers() );
		$this->assertInstanceOf( NullHandler::class, $logger->getHandlers()[0] );
	}

	public function test_filter_overrides_constant_value_to_disable_the_logger(): void {
		// Start from a context where the logger is disabled by means of the env var.
		putenv( 'TEC_DISABLE_LOGGING=1' );
		// Filter the `tec_disable_logging` to return false and enable the logger.
		add_filter( 'tec_disable_logging', '__return_false' );

		$logger = tribe( Service_Provider::class )->build_logger();

		$this->assertInstanceOf( Monolog_Logger::class, $logger );
		$this->assertCount( 1, $logger->getHandlers() );
		$this->assertNotInstanceOf( NullHandler::class, $logger->getHandlers()[0] );

	}
}