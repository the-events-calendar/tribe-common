<?php
namespace Tribe\Common;

use Codeception\TestCase\WPTestCase,
    Tribe__Log,
    Tribe__Log__Logger,
    Tribe__Main;

class Logging_Test extends WPTestCase {
	/**
	 * @var Tribe__Log
	 */
	protected $log_manager;

	/**
	 * @var Tribe__Log__Logger
	 */
	protected $logger;

	/**
	 * Dates for which test logs are available.
	 *
	 * @var array
	 */
	protected $test_dates = array();

	public function setUp() {
		parent::setUp();

		$this->use_test_prefix();
		$this->configure_logging();
	}

	/**
	 * We switch away from the default log file prefix to prevent file ownership
	 * issues during testing.
	 */
	protected function use_test_prefix() {
		add_filter( 'tribe_file_logger_file_prefix', function() {
			return 'tribe_common_logging_test_';
		} );
	}

	/**
	 * Each test run normally starts with a clean installation, so we need to set
	 * up the default file logger for the first time.
	 */
	protected function configure_logging() {
		$this->log_manager = Tribe__Main::instance()->log();
		$this->log_manager->set_current_logger( 'Tribe__Log__File_Logger' );
		$this->logger = $this->log_manager->get_current_logger();
	}

	/**
	 * (Re-)creates a set of test logs staggered over the default cleanup threshold.
	 */
	protected function create_test_logs() {
		$this->assertNotNull( $this->logger );
		$test_data_dir = dirname( __DIR__ ) . '/_data/log_file_data';

		$this->test_dates = array(
			// Subject to cleanup by default (at least 7 days old):
			date_i18n( 'Y-m-d', current_time( 'timestamp' ) - WEEK_IN_SECONDS - DAY_IN_SECONDS ),
			date_i18n( 'Y-m-d', current_time( 'timestamp' ) - WEEK_IN_SECONDS ),

			// Safe from cleanup by default:
			date_i18n( 'Y-m-d', current_time( 'timestamp' ) - WEEK_IN_SECONDS + DAY_IN_SECONDS ),
			date_i18n( 'Y-m-d', current_time( 'timestamp' ) - WEEK_IN_SECONDS + ( 2 * DAY_IN_SECONDS ) ),
		);

		$existing_logs = $this->logger->list_available_logs();

		foreach ( $this->test_dates as $log_date ) {
			// Only (re-)create the test logs when required
			if ( in_array( $log_date, $existing_logs ) ) {
				continue;
			}

			$this->logger->use_log( $log_date, true );
			$test_entries = rand( 10, 20 );

			for ( $i = 0; $i <= $test_entries; $i++ ) {
				$this->logger->log(
					$this->get_gibberish_text(),
					$this->get_gibberish_text(),
					$this->get_gibberish_text()
				);
			}
		}
	}

	/**
	 * Returns a random 'sentence' of words with randomish characters.
	 *
	 * Example: '91cf5305fb 6de72e032d1 00ad3ee86a76 1c0d 0f087 e3a2c5d16482 a6bede4'
	 *
	 * @return string
	 */
	protected function get_gibberish_text() {
		$text = '';
		$count = rand( 2, 12 );

		for ( $i = 0; $i <= $count; $i++ ) {
			$text .= substr( hash( 'md5', uniqid() ), 0, rand( 4, 12 ) ) . ' ';
		}

		return trim( $text );
	}

	/**
	 * The default file logger should be listed as available and indeed ought to be
	 * set as the current logger.
	 */
	public function test_logger_is_available() {
		$this->assertArrayHasKey( 'Tribe__Log__File_Logger', $this->log_manager->get_logging_engines() );
		$this->assertInstanceOf( 'Tribe__Log__Logger', $this->logger );
	}

	/**
	 * Ensure logs can be created for specified dates.
	 */
	public function test_logs_can_be_created() {
		$this->create_test_logs();
		$available_logs = $this->logger->list_available_logs();
		$this->assertNotEmpty( $available_logs );
	}

	/**
	 * Ensure that when we have written to logs we can later retrieve the
	 * content.
	 */
	public function test_logs_can_be_read() {
		$last_log_signature = '';

		foreach ( $this->test_dates as $log_date ) {
			$this->assertTrue( $this->logger->use_log( $log_date, 'Can switch to specified log' ) );

			// Get the log entries and get a unique hash: by comparing with the hash from the last set
			// of logs we can ensure we aren't repeatedly fetching the same entries from the same log
			$log_entries = $this->logger->retrieve();
			$log_signature = hash( 'md5', join( $log_entries ) );

			$this->assertNotEmpty( $log_entries, 'Entries were retrieved from the log' );
			$this->assertNotEquals( $log_signature, $last_log_signature, 'Entries were retrieved from the desired log' );

			$last_log_signature = $log_signature;
		}
	}

	/**
	 * Run the cleanup function and ensure it removes expired logs as
	 * expected.
	 */
	public function test_log_cleanup() {
		// Our test log setup creates 2 expired logs and 2 that are still valid
		// ...another valid log is also created automatically for today's date
		$original_count = $this->count_available_logs();
		$this->logger->cleanup();

		// We expect at least 3 logs to survive cleanup and at least 2 to have
		// been purged
		$new_count = $this->count_available_logs();
		$difference = $original_count - $new_count;

		$this->assertGreaterThanOrEqual( 2, $difference, 'At least 2 logs should have been removed' );
		$this->assertGreaterThanOrEqual( 3, $new_count, 'At least 3 logs should have survived cleanup' );
	}

	/**
	 * @return int
	 */
	protected function count_available_logs() {
		$available = $this->logger->list_available_logs();
		return count( $available );
	}
}