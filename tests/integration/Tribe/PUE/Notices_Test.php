<?php

namespace Tribe\PUE;

use Closure;
use Codeception\TestCase\WPTestCase;
use Generator;
use Tribe__PUE__Notices;

class Notices_Test extends WPTestCase {

	/**
	 * Runs before each test.
	 *
	 * @before
	 */
	public function initialize_notices(): void {
		$notices = tribe( 'pue.notices' );
		$notices->clear_all_notices();
		$notices->save_notices();
	}

	/**
	 * Runs after each test.
	 *
	 * @after
	 */
	public function cleanup_notices(): void {
		// Use the class method to clear all notices
		$notices = tribe( 'pue.notices' );
		$notices->clear_all_notices();
		$notices->save_notices();
	}

	/**
	 * Data provider for test_notice_with_all_statuses.
	 *
	 * @return Generator
	 */
	public function notice_data_provider(): Generator {
		$statuses = [
			Tribe__PUE__Notices::INVALID_KEY,
			Tribe__PUE__Notices::UPGRADE_KEY,
			Tribe__PUE__Notices::EXPIRED_KEY,
		];

		// Individual statuses with multiple plugins
		foreach ( $statuses as $status ) {
			yield "Multiple plugins, single status ($status)" => [
				function () use ( $status ) {
					$pue_notices      = tribe( 'pue.notices' );
					$expected_options = [ $status => [] ];

					foreach ( range( 1, 5 ) as $index ) {
						$plugin_name = "{$status}-plugin-{$index}";
						$pue_notices->add_notice( $status, $plugin_name );
						$expected_options[ $status ][ $plugin_name ] = true;
					}

					return [
						'expected_options' => $expected_options,
						'status'           => $status,
						'plugin_names'     => array_keys( $expected_options[ $status ] ),
					];
				},
				"Multiple plugins should be added with status $status.",
			];
		}

		// Multiple plugins with multiple statuses
		yield 'Multiple plugins, multiple statuses' => [
			function () use ( $statuses ) {
				$expected_options = [];
				$plugin_names     = [];

				$pue_notices = tribe( 'pue.notices' );
				foreach ( $statuses as $status ) {
					$expected_options[ $status ] = [];

					foreach ( range( 1, 3 ) as $index ) {
						$plugin_name = "{$status}-plugin-{$index}";
						$pue_notices->add_notice( $status, $plugin_name );
						$expected_options[ $status ][ $plugin_name ] = true;
						$plugin_names[]                              = $plugin_name;
					}
				}

				return [
					'expected_options' => $expected_options,
					'status'           => $statuses,
					'plugin_names'     => $plugin_names,
				];
			},
			'Multiple plugins should be added with multiple statuses.',
		];
	}

	/**
	 * Test using the data provider for all status scenarios.
	 *
	 * @dataProvider notice_data_provider
	 */
	public function test_notice_with_all_statuses(
		Closure $setup_closure,
		string $scenario
	): void {
		// Execute the setup closure to prepare the test case
		$data = $setup_closure();

		// Retrieve the options after setup
		$options = get_option( Tribe__PUE__Notices::STORE_KEY );

		// Iterate through each status in the expected options
		foreach ( $data['expected_options'] as $status => $expected_plugins ) {
			$this->assertArrayHasKey(
				$status,
				$options,
				"The status $status should exist in the options array."
			);

			$actual_plugins = $options[ $status ];

			foreach ( $expected_plugins as $plugin_name => $expected_value ) {
				$this->assertArrayHasKey(
					$plugin_name,
					$actual_plugins,
					"The plugin {$plugin_name} should exist under $status."
				);

				$this->assertSame(
					$expected_value,
					$actual_plugins[ $plugin_name ],
					"The plugin {$plugin_name} should have the value {$expected_value} under $status."
				);
			}

			// Ensure the count matches
			$this->assertCount(
				count( $expected_plugins ),
				$actual_plugins,
				"The number of plugins under $status should match the expected count."
			);
		}

		// Ensure the final options match the expected output
		$this->assertEquals(
			$data['expected_options'],
			$options,
			$scenario
		);
	}

	/**
	 * @test
	 */
	public function recursive_bug_with_same_plugin(): void {
		$plugin_name = 'plugin-merge-test';

		$pue_notices_initial = tribe( 'pue.notices' );

		// Add initial notices to different keys
		$pue_notices_initial->add_notice( Tribe__PUE__Notices::UPGRADE_KEY, 'initial_plugin1' );
		$pue_notices_initial->add_notice( Tribe__PUE__Notices::EXPIRED_KEY, 'initial_plugin2' );

		// Simulate repeated usage of the same plugin name with different instances
		for ( $j = 0; $j < 5; $j++ ) {
			for ( $i = 0; $i < 5; $i++ ) {
				$temp_plugin_name = $plugin_name . $j;
				tribe( 'pue.notices' )->register_name( $temp_plugin_name );

				// Recreate the tribe instance to simulate typical usage
				$pue_notices = tribe( 'pue.notices' );

				// Add the same notice repeatedly
				$pue_notices->add_notice( Tribe__PUE__Notices::INVALID_KEY, $temp_plugin_name );

				unset( $pue_notices ); // Clear instance to simulate separate requests
			}
		}

		$pue_notices_initial->save_notices();

		// Retrieve the final notices from the database
		$options = get_option( Tribe__PUE__Notices::STORE_KEY );


		// Check UPGRADE_KEY contains only the initial plugin
		$this->assertArrayHasKey(
			Tribe__PUE__Notices::UPGRADE_KEY,
			$options,
			'The "upgrade_key" key should exist in the options array.'
		);

		$this->assertArrayHasKey(
			'initial_plugin1',
			$options[ Tribe__PUE__Notices::UPGRADE_KEY ],
			'initial_plugin1 should exist under "upgrade_key".'
		);

		$this->assertTrue(
			$options[ Tribe__PUE__Notices::UPGRADE_KEY ]['initial_plugin1'],
			'initial_plugin1 should have a value of true under "upgrade_key".'
		);

		// Check EXPIRED_KEY contains only the initial plugin
		$this->assertArrayHasKey(
			Tribe__PUE__Notices::EXPIRED_KEY,
			$options,
			'The "expired_key" key should exist in the options array.'
		);

		$this->assertArrayHasKey(
			'initial_plugin2',
			$options[ Tribe__PUE__Notices::EXPIRED_KEY ],
			'initial_plugin2 should exist under "expired_key".'
		);

		$this->assertTrue(
			$options[ Tribe__PUE__Notices::EXPIRED_KEY ]['initial_plugin2'],
			'initial_plugin2 should have a value of true under "expired_key".'
		);

		// Check INVALID_KEY contains all plugins from the loop
		$this->assertArrayHasKey(
			Tribe__PUE__Notices::INVALID_KEY,
			$options,
			'The "invalid_key" key should exist in the options array.'
		);

		$invalid_key_plugins = $options[ Tribe__PUE__Notices::INVALID_KEY ];

		for ( $j = 0; $j < 5; $j++ ) {
			$temp_plugin_name = $plugin_name . $j;

			$this->assertArrayHasKey(
				$temp_plugin_name,
				$invalid_key_plugins,
				"{$temp_plugin_name} should exist under 'invalid_key'."
			);

			$this->assertTrue(
				$invalid_key_plugins[ $temp_plugin_name ],
				"{$temp_plugin_name} should have a value of true under 'invalid_key'."
			);
		}

		// Ensure there are no unexpected nesting or duplicates
		foreach ( $invalid_key_plugins as $plugin => $value ) {
			$this->assertNotIsArray(
				$value,
				"The value for {$plugin} under 'invalid_key' should not be an array."
			);
		}

		// Ensure the counts match expectations
		$this->assertCount(
			5,
			$invalid_key_plugins,
			'There should be exactly 5 plugins under "invalid_key".'
		);
	}

	/**
	 * @test
	 */
	public function option_has_partial_serialization_corruption(): void {
		// Insert corrupted serialized data directly
		$corrupted_data = 'a:1:{s:11:"invalid_key";a:1:{s:17:"plugin-early-init1";a:8388608:{i:0;b:1;i:1;b:1;i:2;b:1;}}';
		update_option( Tribe__PUE__Notices::STORE_KEY, $corrupted_data );

		// Attempt to load notices
		$pue_notices = new Tribe__PUE__Notices();

		$pue_notices->save_notices();

		// Retrieve and debug the final notices
		$options = get_option( Tribe__PUE__Notices::STORE_KEY );

		$this->assertEmpty( $options, 'Corrupted notices should of been cleared.' );
	}

	/**
	 * @test
	 */
	public function option_has_serialization_corruption(): void {
		// Insert corrupted serialized data directly into the option
		$corrupted_data = 'a:1:{s:11:"invalid_key";a:1:{s:17:"plugin-early-init1";a:8388608:{i:0;b:1;i:1;b:1;i:2;b:1;}}';
		update_option( Tribe__PUE__Notices::STORE_KEY, $corrupted_data );

		// Initialize the PUE Notices class
		$pue_notices = new Tribe__PUE__Notices();

		// Add three plugins to the INVALID_KEY notice
		$pue_notices->add_notice( Tribe__PUE__Notices::INVALID_KEY, 'plugin-early-init1' );
		$pue_notices->add_notice( Tribe__PUE__Notices::INVALID_KEY, 'plugin-early-init2' );
		$pue_notices->add_notice( Tribe__PUE__Notices::INVALID_KEY, 'plugin-early-init3' );

		// Save the notices to persist them
		$pue_notices->save_notices();

		// Retrieve the final notices from the database
		$options = get_option( Tribe__PUE__Notices::STORE_KEY );

		// Assertions
		$this->assertArrayHasKey(
			Tribe__PUE__Notices::INVALID_KEY,
			$options,
			'The "invalid_key" notice should exist in the options.'
		);

		$invalid_key_notices = $options[ Tribe__PUE__Notices::INVALID_KEY ];

		// Ensure all three plugins are correctly stored
		$this->assertArrayHasKey( 'plugin-early-init1', $invalid_key_notices, 'plugin-early-init1 should exist in invalid_key.' );
		$this->assertArrayHasKey( 'plugin-early-init2', $invalid_key_notices, 'plugin-early-init2 should exist in invalid_key.' );
		$this->assertArrayHasKey( 'plugin-early-init3', $invalid_key_notices, 'plugin-early-init3 should exist in invalid_key.' );

		// Ensure their values are correctly set to true
		$this->assertTrue( $invalid_key_notices['plugin-early-init1'], 'plugin-early-init1 should have a value of true.' );
		$this->assertTrue( $invalid_key_notices['plugin-early-init2'], 'plugin-early-init2 should have a value of true.' );
		$this->assertTrue( $invalid_key_notices['plugin-early-init3'], 'plugin-early-init3 should have a value of true.' );

		// Ensure no unexpected nesting or corruption
		foreach ( $invalid_key_notices as $plugin => $value ) {
			$this->assertIsNotArray( $value, "The value for {$plugin} under invalid_key should not be an array." );
		}
	}
}

