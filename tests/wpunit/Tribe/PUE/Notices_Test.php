<?php

namespace Tribe\PUE;

use lucatume\WPBrowser\TestCase\WPTestCase;
use Tribe__PUE__Notices as Notices;

/**
 * @group notices
 */
class Notices_Test extends WPTestCase {

	/**
	 * @before
	 */
	protected function reset_options(): void {
		update_option( Notices::STORE_KEY, [] );
	}

	/**
	 * @dataProvider provider_has_notice_scenarios
	 * @test
	 */
	public function it_handles_has_notice_correctly( $option_value, $plugin_name, $expected ): void {
		update_option( Notices::STORE_KEY, $option_value );

		$notices = new Notices();
		$result  = $notices->has_notice( $plugin_name );

		$this->assertSame(
			$expected,
			$result,
			sprintf(
				'Expected has_notice("%s") to return %s',
				$plugin_name,
				$expected ? 'true' : 'false'
			)
		);
	}

	public function provider_has_notice_scenarios(): \Generator {
		yield 'empty option, no notices' => [
			[],
			'Event Aggregator',
			false,
		];

		yield 'expired key for Event Aggregator' => [
			[
				'expired_key' => [ 'Event Aggregator' => true ],
				'invalid_key' => [],
				'upgrade_key' => [],
			],
			'Event Aggregator',
			true,
		];

		yield 'corrupted empty string key should be ignored' => [
			[
				'expired_key' => [ '' => true ],
				'invalid_key' => [],
				'upgrade_key' => [],
			],
			'',
			false,
		];

		yield 'invalid key should be detected' => [
			[
				'invalid_key' => [ 'Filter Bar' => true ],
				'expired_key' => [],
				'upgrade_key' => [],
			],
			'Filter Bar',
			true,
		];

		yield 'upgrade key should be detected' => [
			[
				'upgrade_key' => [ 'The Events Calendar PRO' => true ],
				'invalid_key' => [],
				'expired_key' => [],
			],
			'The Events Calendar PRO',
			true,
		];
	}

	/**
	 * @test
	 */
	public function add_notice_should_ignore_empty_plugin_name(): void {
		$notices = new Notices();

		// Try to add an empty notice.
		$notices->add_notice( Notices::EXPIRED_KEY, '' );

		$saved = get_option( Notices::STORE_KEY, [] );

		$this->assertArrayNotHasKey(
			'',
			$saved[ Notices::EXPIRED_KEY ] ?? [],
			'add_notice() should not persist empty plugin names.'
		);
	}
}
