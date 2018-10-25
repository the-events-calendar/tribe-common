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
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function bf_2018_should_display() {

		// Unix times for Nov 20 2018 @ 6am UTC and Nov 26 2018 @ 6am UTC.
		// 6am UTC is midnight for TheEventsCalendar.com, which uses the America/Los_Angeles time zone.
		$bf_sale_start_unix = 1542693600;
		$bf_sale_end_unix   = 1543212000;

		return $bf_sale_start_unix < time() && time() < $bf_sale_end_unix;
	}

	/**
	 * HTML for the Black Friday 2018 notice.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function bf_2018_display_notice() {

		$mascot = esc_url( Tribe__Main::instance()->plugin_url . 'src/resources/images/mascot.png' );

		ob_start(); ?>
			<div class="tribe-marketing-notice">
				<div class="tribe-notice-icon">
					<img src="<?php echo esc_url( $mascot ); ?>" />
				</div>
				<div class="tribe-notice-content">
					<p>Hey now this is the marketing notice!
				</div>
			</div>
		<?php

		return ob_get_clean();
	}
}
