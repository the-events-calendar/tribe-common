<?php

namespace TEC\Common\Telemetry;

/**
 * Class MigrationTest
 *
 * @since   TBD
 *
 * @package TEC\Common\Telemetry
 */
class MigrationTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * Removes all freemius options from the database.
	 * Only used if we were the only active plugin.
	 *
	 * @since TBD
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
				'plugins' =>
			   [
				'the-events-calendar/common/vendor/freemius' =>
				(object) [
				   'version' => '2.4.4',
				   'type' => 'plugin',
				   'timestamp' => 1682623989,
				   'plugin_path' => 'the-events-calendar/the-events-calendar.php',
				],
			  ],
				'abspath' => '/app/',
				'newest' =>
			   (object) [
				'plugin_path' => 'the-events-calendar/the-events-calendar.php',
				'sdk_path' => 'the-events-calendar/common/vendor/freemius',
				'version' => '2.4.4',
				'in_activation' => false,
				'timestamp' => 1682623989,
			 ],
			 ]
		);
	}

	/**
	 * @return Migration
	 */
	protected function make_instance() {
		return new Migration();
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
	 * Tests the negative case of is_opted_in
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
	public function it_should_detect_freemius() {
		$this->set_up_active_plugins();

		$sut = $this->make_instance();

		$this->assertTrue( $sut->is_opted_in() );
	}

	/**
	 * @test
	 * Tests the negative case of should_load
	 */
	public function it_should_not_load_if_no_freemius() {
		$this->remove_all_freemius_meta();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->should_load() );
	}

	/**
	 * @test
	 * Tests the positive case of should_load
	 */
	public function it_should_load_if_freemius() {
		$this->set_up_active_plugins();

		$sut = $this->make_instance();

		$this->assertTrue( $sut->should_load() );
	}
}
