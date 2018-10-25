<?php
/**
 * Various Marketing notices, e.g. Black Friday sales or special coupon initiatives.
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
				'wrap'    => 'p',
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
		return true;
	}

	/**
	 * HTML for the Black Friday 2018 notice.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function bf_2018_display_notice() {

		return sprintf( 'Hey now this is the marketing notice!' );
	}
}
