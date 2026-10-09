<?php

namespace TEC\Common\Libraries;

use ReflectionProperty;
use TEC\Common\StellarWP\ContainerContract\ContainerInterface;
use TEC\Common\StellarWP\Shepherd\Config;
use TEC\Common\StellarWP\Shepherd\Contracts\Logger;
use TEC\Common\StellarWP\Shepherd\Loggers\ActionScheduler_DB_Logger;
use TEC\Common\StellarWP\Shepherd\Loggers\DB_Logger;
use TEC\Common\StellarWP\Shepherd\Loggers\Null_Logger;
use TEC\Common\StellarWP\Shepherd\Provider as Shepherd_Provider;
use TEC\Common\StellarWP\Shepherd\Tables\AS_Logs;
use TEC\Common\Tests\Provider\Controller_Test_Case;

class Shepherd_Test extends Controller_Test_Case {
	protected $controller_class = Shepherd::class;

	/**
	 * @var ContainerInterface
	 */
	private $original_container;

	/**
	 * @var Logger|null
	 */
	private $original_logger;

	/**
	 * @before
	 */
	public function reset_shepherd(): void {
		$this->original_container = Config::get_container();
		// Config::get_logger() would build a logger when none is set, running the lookup under test.
		$logger = new ReflectionProperty( Config::class, 'logger' );
		$logger->setAccessible( true );
		$this->original_logger = $logger->getValue();
		Config::set_logger( null );
		Shepherd_Provider::reset();
	}

	/**
	 * @after
	 */
	public function restore_shepherd(): void {
		Config::set_container( $this->original_container );
		Config::set_logger( $this->original_logger );
	}

	public function test_register_does_not_query_for_the_action_scheduler_logs_table(): void {
		$logs_table_queries = [];
		$logs_table         = AS_Logs::table_name( true );
		add_action(
			'stellarwp_db_pre_query',
			static function ( $query ) use ( &$logs_table_queries, $logs_table ) {
				if ( is_string( $query ) && false !== strpos( $query, $logs_table ) ) {
					$logs_table_queries[] = $query;
				}
			}
		);

		$this->make_controller()->register();

		/*
		 * Every query through stellarwp/db requires wp-admin/includes/upgrade.php, which on multisite loads ms.php
		 * on every request and lets a second ms.php load fatal on check_upload_size().
		 */
		$this->assertEmpty( $logs_table_queries );
	}

	public function test_uses_the_action_scheduler_logger_when_its_logs_table_is_installed(): void {
		$this->make_controller()->register();

		$this->assertInstanceOf( ActionScheduler_DB_Logger::class, Config::get_logger() );
	}

	public function test_uses_its_own_logger_when_the_action_scheduler_logs_table_is_missing(): void {
		global $wpdb;
		$table = AS_Logs::table_name( true );
		// Action Scheduler's schema option still says the table exists.
		$this->assertNotEmpty( get_option( 'schema-ActionScheduler_LoggerSchema' ) );

		// WordPress test cases turn DROP TABLE into DROP TEMPORARY TABLE, which cannot drop the real table.
		$wpdb->query( $wpdb->prepare( 'RENAME TABLE %i TO %i', $table, "{$table}_moved" ) );

		try {
			$this->make_controller()->register();
		} finally {
			$wpdb->query( $wpdb->prepare( 'RENAME TABLE %i TO %i', "{$table}_moved", $table ) );
		}

		$this->assertInstanceOf( DB_Logger::class, Config::get_logger() );
	}

	public function test_uses_the_null_logger_when_logging_is_filtered_off(): void {
		add_filter( 'shepherd_' . tribe( Provider::class )->get_hook_prefix() . '_should_log', '__return_false' );

		$this->make_controller()->register();

		$this->assertInstanceOf( Null_Logger::class, Config::get_logger() );
	}
}
