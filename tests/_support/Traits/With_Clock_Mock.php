<?php
/**
 * Wraps the Clock Mock library for easier use in testing.
 *
 * @since 6.3.0
 *
 * @package Traits;
 */

namespace Tribe\Tests\Traits;

use SlopeIt\ClockMock\ClockMock;

/**
 * Class With_Clock_Mock.
 *
 * @since 6.3.0
 *
 * @package Traits;
 */
trait With_Clock_Mock {
	/**
	 * Whether the clock mock library was used to freeze the time.
	 *
	 * @since 6.3.0
	 *
	 * @var bool
	 */
	private bool $clock_mock_frozen = false;

	/**
	 * @after
	 */
	public function unfreeze_time_after_test(): void {
		if ( ! $this->clock_mock_frozen ) {
			return;
		}

		ClockMock::reset();
	}

	/**
	 * Using the clock mock library, set the current PHP time and date functions to a specific time.
	 *
	 * @since 6.3.0
	 *
	 * @param \DateTimeInterface $datetime The datetime to freeze the clock to.
	 *
	 * @return void Time is frozen.
	 */
	protected function freeze_time( \DateTimeInterface $datetime ): void {
		$this->clock_mock_frozen = true;
		ClockMock::freeze( $datetime );
	}

	/**
	 * Unfreeze the time.
	 *
	 * @since 6.3.0
	 *
	 * @return void Time is unfrozen.
	 */
	protected function unfreeze_time(): void {
		if ( ! $this->clock_mock_frozen ) {
			return;
		}

		$this->clock_mock_frozen = false;
		ClockMock::reset();
	}
}
