<?php
/**
 * Various Marketing notices, e.g. Black Friday sales or special coupon initiatives.
 *
 * @todo Get image to use in notice
 * @todo Get TEC-only message
 * @todo Get TEC & ET message
 * @todo Finalize message display and image styles
 * @todo Figure out how to QA this
 */
class Tribe__Admin__Notice__Marketing {

	/**
	 * Register the various Marketing notices.
	 *
	 * @since
	 */
	public function hook() {
		$this->bf_2018_hook_notice();
	}

	/**
	 * Register the Black Friday 2018 notice.
	 *
	 * @since TBD
	 */
	public function bf_2018_hook_notice() {

		tribe_notice(
			'black-friday-2018',
			array( $this, 'bf_2018_display_notice' ),
			array(
				'type'    => 'warning',
				'dismiss' => 1,
				'wrap'    => false,
			),
			array( $this, 'bf_2018_should_display' )
		);
	}

	/**
	 * Whether the Black Friday 2018 notice should display.
	 *
	 * Unix times for Nov 20 2018 @ 6am UTC and Nov 26 2018 @ 6am UTC.
	 * 6am UTC is midnight for TheEventsCalendar.com, which uses the America/Los_Angeles time zone.
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function bf_2018_should_display() {
		return true;

		// $bf_sale_start = $this->get_bf_2018_start_time();
		// $bf_sale_end   = $this->get_bf_2018_end_time();

		//return $bf_sale_start <= time() && time() < $bf_sale_end;
	}

	/**
	 * Unix time for Nov 20 2018 @ 6am UTC. (6am UTC is midnight for TheEventsCalendar.com, which uses the America/Los_Angeles time zone).
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_bf_2018_start_time() {
		/**
		 * Allow filtering of the Black Friday sale start date, mainly for testing purposes.
		 *
		 * @since TBD
		 *
		 * @param int $bf_start_date Unix time for Nov 20 2018 @ 6am UTC.
		 */
		return apply_filters( 'tribe_bf_2018_start_time', 1542693600 );
	}

	/**
	 * Unix time for Nov 26 2018 @ 6am UTC. (6am UTC is midnight for TheEventsCalendar.com, which uses the America/Los_Angeles time zone).
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_bf_2018_end_time() {
		/**
		 * Allow filtering of the Black Friday sale end date, mainly for testing purposes.
		 *
		 * @since TBD
		 *
		 * @param int $bf_end_date Unix time for Nov 20 2018 @ 6am UTC.
		 */
		return apply_filters( 'tribe_bf_2018_end_time', 1543212000 );
	}

	/**
	 * HTML for the Black Friday 2018 notice.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function bf_2018_display_notice() {

		$tribe_dependency = Tribe__Dependency::instance();
		$tec_is_active    = $tribe_dependency->is_plugin_active( 'Tribe__Events__Main' );
		$et_is_active     = $tribe_dependency->is_plugin_active( 'Tribe__Tickets__Main' );

		$mascot_url = Tribe__Main::instance()->plugin_url . 'src/resources/images/mascot.png';
		$end_time   = $this->get_bf_2018_end_time();

		ob_start();

		if ( $tec_is_active && ! $et_is_active ) {
			include Tribe__Main::instance()->plugin_path . 'src/admin-views/notices/tribe-bf-2018-tec.php';
		} elseif ( $et_is_active && ! $tec_is_active ) {
			include Tribe__Main::instance()->plugin_path . 'src/admin-views/notices/tribe-bf-2018-et.php';
		} else {
			include Tribe__Main::instance()->plugin_path . 'src/admin-views/notices/tribe-bf-2018-general.php';
		}

		return ob_get_clean();
	}
}
