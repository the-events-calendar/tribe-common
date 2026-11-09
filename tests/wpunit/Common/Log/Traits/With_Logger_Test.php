<?php

namespace TEC\Common\Log\Traits;

use Codeception\TestCase\WPTestCase;
use Tribe__Log as Log;

class With_Logger_Test extends WPTestCase {
	/**
	 * @test
	 */
	public function it_should_log_through_tribe_log_tagged_with_the_using_class(): void {
		$logged = [];
		add_action(
			'tribe_log',
			static function ( ...$args ) use ( &$logged ) {
				$logged[] = $args;
			},
			10,
			3
		);

		$logger = new class() {
			use With_Logger;

			public function run(): void {
				$this->log( Log::WARNING, 'Something went wrong.', [ 'order_id' => 23 ] );
			}
		};
		$logger->run();

		$this->assertSame(
			[
				[
					Log::WARNING,
					'Something went wrong.',
					[
						'source'   => get_class( $logger ),
						'order_id' => 23,
					],
				],
			],
			$logged
		);
	}

	/**
	 * @test
	 */
	public function it_should_let_the_context_override_the_source(): void {
		$logged = [];
		add_action(
			'tribe_log',
			static function ( ...$args ) use ( &$logged ) {
				$logged[] = $args;
			},
			10,
			3
		);

		$logger = new class() {
			use With_Logger;

			public function run(): void {
				$this->log( Log::DEBUG, 'Grouped.', [ 'source' => 'Feature' ] );
			}
		};
		$logger->run();

		$this->assertSame( [ [ Log::DEBUG, 'Grouped.', [ 'source' => 'Feature' ] ] ], $logged );
	}
}
