<?php

class Tribe__Cron {

	/**
	 * Filters the cron schedules to add intervals we need.
	 *
	 * @param array $schedules
	 *
	 * @return array
	 */
	public function filter_cron_schedules( array $schedules ) {
		$schedules[ 'thirty_seconds' ] = array(
			'interval' => 30,
			'display'  => esc_html__( 'Every Thirty Seconds' ),
		);

		return $schedules;
	}

	/**
	 * Schedules cron events we need in our plugins.
	 */
	public function schedule() {
		if ( ! wp_next_scheduled( 'tribe_queue_work' ) ) {
			wp_schedule_event( time(), 'thirty_seconds', 'tribe_queue_work' );
		}
	}
}