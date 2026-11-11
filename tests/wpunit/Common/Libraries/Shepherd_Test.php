<?php

namespace TEC\Common\Libraries;

use ReflectionProperty;
use TEC\Common\StellarWP\AdminNotices\AdminNotices;
use TEC\Common\StellarWP\ContainerContract\ContainerInterface;
use TEC\Common\StellarWP\DB\Database\Exceptions\DatabaseQueryException;
use TEC\Common\StellarWP\DB\DB;
use TEC\Common\StellarWP\Shepherd\Config;
use TEC\Common\StellarWP\Shepherd\Contracts\Logger;
use TEC\Common\StellarWP\Shepherd\Loggers\ActionScheduler_DB_Logger;
use TEC\Common\StellarWP\Shepherd\Loggers\DB_Logger;
use TEC\Common\StellarWP\Shepherd\Loggers\Null_Logger;
use TEC\Common\StellarWP\Shepherd\Provider as Shepherd_Provider;
use TEC\Common\StellarWP\Shepherd\Tables\AS_Logs;
use TEC\Common\Tests\Provider\Controller_Test_Case;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class Shepherd_Test extends Controller_Test_Case {
	use SnapshotAssertions;

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
		AdminNotices::removeNotice( 'tec_common_shepherd_tables_error' );
	}

	public function test_front_end_request_does_not_load_upgrade_php(): void {
		global $table_prefix;

		/*
		 * On multisite, upgrade.php loads wp-admin/includes/ms.php, and a later plain require of ms.php
		 * fatals on check_upload_size().
		 */
		$env     = [
			'WORDPRESS_DB_HOST'      => DB_HOST,
			'WORDPRESS_DB_NAME'      => DB_NAME,
			'WORDPRESS_DB_USER'      => DB_USER,
			'WORDPRESS_DB_PASSWORD'  => DB_PASSWORD,
			'WORDPRESS_TABLE_PREFIX' => $table_prefix,
		];
		$command = '';
		foreach ( $env as $name => $value ) {
			$command .= $name . '=' . escapeshellarg( $value ) . ' ';
		}
		$command .= implode(
			' ',
			array_map( 'escapeshellarg', [ PHP_BINARY, codecept_data_dir( 'front-end-request-loads-upgrade.php' ), ABSPATH ] )
		);

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec -- The check needs a process the bootstrap has not touched.
		exec( $command . ' 2>&1', $output, $exit_code );
		$output = implode( "\n", $output );

		$this->assertSame( 0, $exit_code, $output );

		$result = json_decode( substr( $output, strrpos( $output, '{' ) ), true );

		$this->assertTrue( $result['shepherd_registered'] ?? null, $output );
		$this->assertFalse( $result['upgrade_loaded'], $output );
	}

	public function test_uses_the_action_scheduler_logger_when_its_logs_table_is_installed(): void {
		$this->make_controller()->register();

		$this->assertInstanceOf( ActionScheduler_DB_Logger::class, Config::get_logger() );
	}

	public function test_uses_its_own_logger_when_the_action_scheduler_logs_table_is_missing(): void {
		$table         = AS_Logs::table_name( true );
		$missing_query = DB::prepare( 'SHOW TABLES LIKE %s', DB::esc_like( "{$table}_missing" ) );
		// Action Scheduler's schema option still says the table exists.
		$this->assertNotEmpty( get_option( 'schema-ActionScheduler_LoggerSchema' ) );

		/*
		 * Dropping or renaming the real table is DDL, which commits the test transaction; pointing the existence
		 * check at a table that does not exist leaves the database untouched.
		 */
		add_filter(
			'query',
			static function ( $query ) use ( $table, $missing_query ) {
				// The table name arrives LIKE-escaped, so compare it with the backslashes removed.
				if (
					is_string( $query )
					&& 0 === strpos( $query, 'SHOW TABLES LIKE' )
					&& false !== strpos( str_replace( '\\', '', $query ), $table )
				) {
					return $missing_query;
				}

				return $query;
			}
		);

		$this->make_controller()->register();

		$this->assertInstanceOf( DB_Logger::class, Config::get_logger() );
	}

	public function test_uses_the_null_logger_when_logging_is_filtered_off(): void {
		add_filter( 'shepherd_' . tribe( Provider::class )->get_hook_prefix() . '_should_log', '__return_false' );

		$this->make_controller()->register();

		$this->assertInstanceOf( Null_Logger::class, Config::get_logger() );
	}

	public function test_the_tables_error_notice_shows_the_failed_query_and_its_errors(): void {
		$controller = $this->make_controller();
		$controller->register();

		$controller->handle_tables_error(
			new DatabaseQueryException(
				'CREATE TABLE wp_shepherd_tec_tasks (id bigint(20))',
				[ 'db_delta_error' => [ 'First error.', 'Second error.' ] ]
			)
		);

		$notices = array_filter(
			AdminNotices::getNotices(),
			static fn( $notice ) => 'tec_common_shepherd_tables_error' === $notice->getId()
		);
		$this->assertCount( 1, $notices );
		$this->assertMatchesHtmlSnapshot( AdminNotices::render( reset( $notices ), false ) );
	}
}
