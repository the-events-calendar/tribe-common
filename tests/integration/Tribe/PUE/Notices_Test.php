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
			$this->assertIsNotArray(
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
	public function handles_large_notice_data(): void {
		$large_data = [];
		for ( $i = 0; $i < 10000; $i++ ) {
			$large_data[ Tribe__PUE__Notices::INVALID_KEY ]["plugin-$i"] = true;
		}

		// Save the large dataset as an option
		update_option( Tribe__PUE__Notices::STORE_KEY, $large_data );

		// Instantiate the class to trigger `populate()`
		$pue_notices = new Tribe__PUE__Notices();

		// Retrieve notices after `populate()` runs
		$options = get_option( Tribe__PUE__Notices::STORE_KEY );

		// Assert the data remains consistent
		$this->assertArrayHasKey( Tribe__PUE__Notices::INVALID_KEY, $options );
		$this->assertCount( 10000, $options[ Tribe__PUE__Notices::INVALID_KEY ], 'The large dataset should have 10,000 entries.' );
	}

	/**
	 * @test
	 */
	public function handles_invalid_option_values(): void {
		// Save invalid data as an option
		$invalid_data = 'corrupted_string_instead_of_array';
		update_option( Tribe__PUE__Notices::STORE_KEY, $invalid_data );

		// Instantiate the class to trigger `populate()`
		$pue_notices = new Tribe__PUE__Notices();

		// The cleaning in the DB should be done just prior saving.
		$pue_notices->save_notices();

		// Retrieve notices after `populate()` runs
		$options = get_option( Tribe__PUE__Notices::STORE_KEY );

		// Assert the option was reset to an empty array
		$this->assertEmpty( $options, 'Invalid data should be cleared from the option.' );
	}

	/**
	 * @test
	 */
	public function fixes_corrupted_notices_data(): void {
		// Simulate corrupted data that our clients had.
		$corrupted_data = [
			Tribe__PUE__Notices::INVALID_KEY => [
				'Promoter' => array_fill( 0, 100, [ true ] ),
			],
		];

		// Save the corrupted data directly into the database.
		update_option( Tribe__PUE__Notices::STORE_KEY, $corrupted_data );

		// Instantiate the class to trigger `populate()`.
		$pue_notices = new Tribe__PUE__Notices();

		// The cleaning in the DB should be done just prior saving.
		$pue_notices->save_notices();

		// Retrieve notices after `populate()` runs.
		$options = get_option( Tribe__PUE__Notices::STORE_KEY );

		// Assertions
		$this->assertIsArray( $options, 'The notices option should be an array.' );

		// Ensure `INVALID_KEY` exists.
		$this->assertArrayHasKey(
			Tribe__PUE__Notices::INVALID_KEY,
			$options,
			'The "invalid_key" should exist in the notices.'
		);

		// Ensure `Promoter` exists under `invalid_key`.
		$this->assertArrayHasKey(
			'Promoter',
			$options[ Tribe__PUE__Notices::INVALID_KEY ],
			'The "Promoter" key should exist under "invalid_key".'
		);

		// Ensure `Promoter` value is true.
		$this->assertTrue(
			$options[ Tribe__PUE__Notices::INVALID_KEY ]['Promoter'],
			'The "Promoter" key should have a value of true.'
		);

		// Ensure no unexpected keys exist in the root array.
		$this->assertCount(
			1,
			$options,
			'The root notices array should only contain "invalid_key".'
		);

		// Ensure no unexpected keys exist under `invalid_key`.
		$this->assertCount(
			1,
			$options[ Tribe__PUE__Notices::INVALID_KEY ],
			'The "invalid_key" array should only contain "Promoter".'
		);
	}

	/**
	 * @test
	 */
	public function handles_mixed_valid_and_invalid_data(): void {
		$mixed_data = [
			Tribe__PUE__Notices::INVALID_KEY => [
				'ValidPlugin'     => true,
				'CorruptedPlugin' => array_fill( 0, 10, [ true ] ),
			],
			'CustomKey'                      => 'not_an_array',
			'CustomKeyButArray'              => [
				'key1' => 'value1',
				'key2' => 'value2',
			],
		];

		update_option( Tribe__PUE__Notices::STORE_KEY, $mixed_data );

		$pue_notices = new Tribe__PUE__Notices();

		// The cleaning in the DB should be done just prior saving.
		$pue_notices->save_notices();

		$options = get_option( Tribe__PUE__Notices::STORE_KEY );

		// Ensure `INVALID_KEY` exists.
		$this->assertArrayHasKey( Tribe__PUE__Notices::INVALID_KEY, $options );

		// Ensure valid data is retained.
		$this->assertArrayHasKey(
			'ValidPlugin',
			$options[ Tribe__PUE__Notices::INVALID_KEY ],
			'ValidPlugin should be retained under INVALID_KEY.'
		);

		$this->assertTrue(
			$options[ Tribe__PUE__Notices::INVALID_KEY ]['ValidPlugin'],
			'ValidPlugin should have a value of true.'
		);

		// Make sure `CorruptedPlugin` is no longer corrupted.

		$this->assertArrayHasKey(
			'CorruptedPlugin',
			$options[ Tribe__PUE__Notices::INVALID_KEY ],
			'CorruptedPlugin should not be removed from INVALID_KEY.'
		);

		$this->assertTrue(
			$options[ Tribe__PUE__Notices::INVALID_KEY ]['CorruptedPlugin'],
			'CorruptedPlugin should have a value of true under INVALID_KEY.'
		);

		$this->assertIsNotArray(
			$options[ Tribe__PUE__Notices::INVALID_KEY ]['CorruptedPlugin'],
			'CorruptedPlugin should not be an array under INVALID_KEY.'
		);

		$this->assertArrayNotHasKey(
			'CustomKey',
			$options,
			'CustomKey should not be removed from the root array.'
		);

		$this->assertArrayHasKey(
			'CustomKeyButArray',
			$options,
			'CustomKeyButArray should not be removed from the root array.'
		);

		$this->assertIsArray(
			$options['CustomKeyButArray'],
			'CustomKeyButArray should be an array in the root array.'
		);

		$this->assertCount(
			2,
			$options,
			'The root array should only contain INVALID_KEY and CustomKeyButArray.'
		);

		$this->assertEquals(
			[
				'key1' => true,
				'key2' => true,
			],
			$options['CustomKeyButArray'],
			'CustomKeyButArray should have the expected values.'
		);
	}

	/**
	 * @test
	 */
	public function prevent_duplicates_for_same_plugin(): void {
		$plugin_name = 'DuplicatePlugin';
		$status = Tribe__PUE__Notices::INVALID_KEY;

		$pue_notices = tribe( 'pue.notices' );

		// Add the same notice 50 times.
		for ( $i = 0; $i < 50; $i++ ) {
			$pue_notices->add_notice( $status, $plugin_name );
		}

		// Save notices to persist the data.
		$pue_notices->save_notices();

		// Retrieve the final notices from the database.
		$options = get_option( Tribe__PUE__Notices::STORE_KEY );

		// Assert that the status exists.
		$this->assertArrayHasKey(
			$status,
			$options,
			"The status $status should exist in the notices."
		);

		// Assert that the plugin exists under the status.
		$this->assertArrayHasKey(
			$plugin_name,
			$options[ $status ],
			"The plugin $plugin_name should exist under $status."
		);

		// Assert that the plugin's value is true.
		$this->assertTrue(
			$options[ $status ][ $plugin_name ],
			"The plugin $plugin_name should have a value of true under $status."
		);

		// Ensure no duplicates exist.
		$this->assertCount(
			1,
			$options[ $status ],
			"There should be exactly 1 entry for $status."
		);
	}

	/**
	 * @test
	 */
	public function it_should_not_exhaust_memory() {
		// For the sake of testing we assume memory limit 2M => so in bytes 2 * 1024 * 1024
		$test_memory_limit = 2 * 1024 * 1024;

		$initial_memory_usage = memory_get_usage();

		// We'll increase the memory limit by the initial used memory.
		$test_memory_limit += $initial_memory_usage;

		// we'll create a large array about 3/4 of the memory limit.
		$data = [
			Tribe__PUE__Notices::INVALID_KEY => [],
		];

		while ( memory_get_usage() < $test_memory_limit * 0.75 ) {
			$data[ Tribe__PUE__Notices::INVALID_KEY ][] = 'plugin-' . count( $data[ Tribe__PUE__Notices::INVALID_KEY ] );
		}

		// Save the large dataset as an option
		update_option( Tribe__PUE__Notices::STORE_KEY, $data );

		unset( $data );

		$this->assertGreaterThan( memory_get_usage(), $test_memory_limit, 'Memory usage should not exceed the memory limit.' );

		$pue_notices = new Tribe__PUE__Notices();

		$this->assertGreaterThan( memory_get_usage(), $test_memory_limit, 'Memory usage should not exceed the memory limit.' );

		// The cleaning in the DB should be done just prior saving.
		$pue_notices->save_notices();

		$this->assertGreaterThan( memory_get_usage(), $test_memory_limit, 'Memory usage should not exceed the memory limit.' );
	}
}
