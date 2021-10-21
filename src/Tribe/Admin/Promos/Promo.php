<?php
namespace Tribe\Admin\Promos;

use Tribe__Date_Utils as Dates;

/**
 * Abstract class for promos.
 *
 * @since TBD
 */
abstract class Promo {
	/**
	 * Promo slug.
	 */
	protected $slug = '';

	/**
	 * Start date.
	 */
	protected $start_date;

	/**
	 * End Date.
	 */
	protected $end_date;

	/**
	 * Register actions and filters.
	 *
	 * @since TBD
	 * @return void
	 */
	abstract function hook();

	/**
	 * Unix datetime for promo start.
	 *
	 * @since TBD
	 * @return int - Unix timestamp
	 */
	protected function get_start_time() {
		$date = Dates::build_date_object( $this->start_date, 'UTC' );

		/**
		 * Allow filtering of the start date for testing.
		 *
		 * @since TBD
		 * @param \DateTime $date - Unix timestamp for start date
		 */
		$date = apply_filters( "tec_promo_{$this->slug}_start_date", $date );

		return $date->format( 'U' );
	}

	/**
	 * Unix datetime for promo end.
	 *
	 * @since TBD
	 * @return int - Unix timestamp
	 */
	protected function get_end_time() {
		$date = Dates::build_date_object( $this->end_date, 'UTC' );

		return $date->format( 'U' );
	}

    /**
	 * Whether the promo should display.
	 *
	 * @since TBD
	 * @return boolean - Whether the promo should display
	 */
	protected function should_display() {
		$now          = Dates::build_date_object( 'now', 'UTC' )->format( 'U' );
		$notice_start = $this->get_start_time();
		$notice_end   = $this->get_end_time();

		return $notice_start <= $now && $now < $notice_end;
	}
}
