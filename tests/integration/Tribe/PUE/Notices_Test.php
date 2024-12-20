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
		$options = get_option( 'tribe_pue_key_notices' );

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

	public function test_merge_recursive_bug_with_same_plugin(): void {
		// Plugin name to test
		$plugin_name = 'plugin-merge-test';

		// Pre-set the option to simulate existing saved notices
		$initial_saved_notices = [
			Tribe__PUE__Notices::INVALID_KEY => [
				$plugin_name => [true],
			],
		];
		update_option('tribe_pue_key_notices', $initial_saved_notices);

		for ($i = 0; $i < 5; $i++) {
			// Recreate the tribe instance to simulate typical usage
			$pue_notices = tribe('pue.notices');

			// Add the same notice repeatedly
			$pue_notices->add_notice(Tribe__PUE__Notices::INVALID_KEY, $plugin_name);

			// Save notices to trigger merging in the next call
			$pue_notices->save_notices();
		}

		// Retrieve the final notices from the database
		$options = get_option('tribe_pue_key_notices');

		codecept_debug($options);
		return;

		// Assertions to check if the plugin is duplicated
		$this->assertArrayHasKey(
			Tribe__PUE__Notices::INVALID_KEY,
			$options,
			'The "invalid_key" key should exist in the options array.'
		);

		$invalid_key_plugins = $options[Tribe__PUE__Notices::INVALID_KEY];

		$this->assertArrayHasKey(
			$plugin_name,
			$invalid_key_plugins,
			"The plugin {$plugin_name} should exist under 'invalid_key'."
		);

		// Ensure the value is not duplicated (array_merge_recursive bug can cause this)
		$this->assertIsBool(
			$invalid_key_plugins[$plugin_name],
			"The plugin {$plugin_name} should not be duplicated or stored as an array."
		);

		$this->assertTrue(
			$invalid_key_plugins[$plugin_name],
			"The plugin {$plugin_name} should be set to true in 'invalid_key'."
		);
	}


}
