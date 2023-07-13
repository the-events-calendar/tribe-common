<?php

namespace TEC\Common\Telemetry;

use Tribe\Tests\Traits\With_Uopz;

class Migration_No_Auto_Opt_In extends \TEC\Common\Telemetry\Migration {
	public $counter = 0;

	public function auto_opt_in() {
		$this->counter++;

		return;
	}
}

/**
 * Class MigrationTest
 *
 * @since	TBD
 *
 * @package TEC\Common\Telemetry
 */
class MigrationTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

	/**
	 * Removes all freemius options from the database.
	 * Only used if we were the only active plugin.
	 *
	 * @since 5.1.3
	 */
	protected function remove_all_freemius_meta(): void {
		delete_option( 'fs_active_plugins' );
		delete_option( 'fs_accounts' );
		delete_option( 'fs_api_cache' );
		delete_option( 'fs_debug_mode' );
		delete_option( 'fs_gdpr' );
	}

	protected function set_up_active_plugins() {
		update_option(
			'fs_active_plugins',
			(object) [
				'plugins' => [
					'the-events-calendar/common/vendor/freemius' => (object) [
						'version' => '2.4.4',
						'type' => 'plugin',
						'timestamp' => 1682623989,
						'plugin_path' => 'the-events-calendar/the-events-calendar.php',
					],
				],
				'abspath' => '/app/',
				'newest' => (object) [
					'plugin_path' => 'the-events-calendar/the-events-calendar.php',
					'sdk_path' => 'the-events-calendar/common/vendor/freemius',
					'version' => '2.4.4',
					'in_activation' => false,
					'timestamp' => 1682623989,
				],
			]
		);
	}

	protected function setup_fs_accounts_disconnected() {
		update_option(
			'fs_accounts',
			[
				'sites' => [
					'the-events-calendar' => [
						'is_disconnected' => true,
					],
				],
			 ]
		);
	}

	protected function setup_fs_accounts_connected() {
		update_option(
			'fs_accounts',
			[
				'sites' => [
					'the-events-calendar' => [
						'is_disconnected' => false,
					],
				],
			 ]
		);
	}

	protected function setup_fs_accounts_no_tec() {
		update_option(
			'fs_accounts',
			[
				'sites' => [
					'some-plugin' => [
						'is_disconnected' => false,
					],
				],
			 ]
		);
	}

	protected function setup_fs_accounts_bad_data() {
		update_option(
			'fs_accounts',
			[
				'sites' => [
					'the-events-calendar' => [
						'is_disconnected' => 'luca',
					],
				],
			 ]
		);
	}

	protected function setup_fs_accounts_mixed() {
		update_option(
			'fs_accounts',
			[
				'sites' => [
					'the-events-calendar' => [
						'is_disconnected' => false,
					],
					'event-tickets' => [
						'is_disconnected' => true,
					],
				],
			]
		);
	}

	/**
	 * @return Migration
	 */
	protected function make_instance() {
		$this->set_up_active_plugins();

		return new Migration();
	}

	/**
	 * @return Migration
	 */
	protected function make_no_auto_opt_in_instance() {
		$this->set_up_active_plugins();

		return new Migration_No_Auto_Opt_In();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Migration::class, $sut );
	}

	/**
	 * @test
	 * Tests the positive case of is_opted_in
	 */
	public function it_should_detect_no_freemius() {
		$this->remove_all_freemius_meta();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_opted_in() );
	}

	/**
	 * @test
	 * Tests the positive case of is_opted_in
	 */
	public function it_should_detect_no_tec() {
		$this->remove_all_freemius_meta();

		$sut = $this->make_instance();

		$this->setup_fs_accounts_no_tec();

		$this->assertFalse( $sut->is_opted_in() );
	}

	/**
	 * @test
	 * Tests the positive case of is_opted_in
	 */
	public function it_should_detect_bad_data() {
		$this->remove_all_freemius_meta();

		$sut = $this->make_instance();

		$this->setup_fs_accounts_bad_data();

		$this->assertFalse( $sut->is_opted_in() );
	}

	/**
	 * @test
	 * Tests the negative case of is_opted_in
	 */
	public function it_should_detect_opted_out_freemius() {
		$sut = $this->make_instance();

		$this->setup_fs_accounts_disconnected();

		$this->assertFalse( $sut->is_opted_in() );
	}

	/**
	 * @test
	 * Tests the positive case of should_load
	 */
	public function it_should_load_if_freemius() {
		$sut = $this->make_instance();

		$this->setup_fs_accounts_connected();

		$this->assertTrue( $sut->should_load() );
	}

	/**
	 * @test
	 * Tests the positive case of should_load
	 */
	public function it_should_load_if_freemius_mixed() {
		$sut = $this->make_instance();

		$this->setup_fs_accounts_mixed();

		$this->assertTrue( $sut->should_load() );
	}

	/**
	 * @test
	 */
	public function it_should_not_migrate_when_ajax() {
		$this->set_const_value( 'DOING_AJAX', true );
		$this->set_const_value( 'DOING_AUTOSAVE', false );

		$sut = $this->make_no_auto_opt_in_instance();
		$this->setup_fs_accounts_connected();

		$sut->migrate_existing_opt_in();

		$this->assertEquals( 0, $sut->counter );
	}

	/**
	 * @test
	 */
	public function it_should_not_migrate_when_autosaving() {
		$this->set_const_value( 'DOING_AJAX', false );
		$this->set_const_value( 'DOING_AUTOSAVE', true );

		$sut = $this->make_no_auto_opt_in_instance();
		$this->setup_fs_accounts_connected();

		$sut->migrate_existing_opt_in();

		$this->assertEquals( 0, $sut->counter );
	}

	/**
	 * @test
	 */
	public function it_should_not_call_auto_optin_more_than_once() {
		$this->set_const_value( 'DOING_AJAX', false );
		$this->set_const_value( 'DOING_AUTOSAVE', false );

		$sut = $this->make_no_auto_opt_in_instance();

		$this->setup_fs_accounts_connected();

		$sut->migrate_existing_opt_in();
		$sut->migrate_existing_opt_in();

		$this->assertEquals( 1, $sut->counter );
	}
}
