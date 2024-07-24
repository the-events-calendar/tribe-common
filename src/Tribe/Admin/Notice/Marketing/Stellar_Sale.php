<?php
/**
 * Notice for the Stellar Sale
 *
 * @since 4.14.2
 */

namespace Tribe\Admin\Notice\Marketing;

/**
 * Class Stellar_Sale
 *
 * @since 4.14.2
 *
 * @package Tribe\Admin\Notice\Marketing
 */
class Stellar_Sale extends \Tribe\Admin\Notice\Date_Based {
	/**
	 * {@inheritDoc}
	 */
	public $slug = 'stellar-sale-2024';

	/**
	 * {@inheritDoc}
	 */
	public $start_date = 'July 23rd, 2024';

	/**
	 * {@inheritDoc}
	 *
	 * 7am UTC is midnight PDT (-7) and 3am EDT (-4)
	 */
	public $start_time = 19;

	/**
	 * {@inheritDoc}
	 */
	public $end_date = 'July 30th, 2024';

	/**
	 * {@inheritDoc}
	 *
	 * 7am UTC is midnight PDT (-7) and 3am EDT (-4)
	 */
	public $end_time = 19;

	/**
	 * {@inheritDoc}
	 */
	public function display_notice() {
		\Tribe__Assets::instance()->enqueue( [ 'tribe-common-admin' ] );

		// Used in the template.
		$cta_url     = 'https://evnt.is/1bdv';
		$stellar_url = 'https://evnt.is/1bdw';

		$template_args = [
			'heading'           => __( 'Make it yours.', 'tribe-common' ),
			'sub_heading'       => __( 'Save 40% on all The Events Calendar products.', 'tribe-common' ),
			'content'           => __( 'Take <b>40%</b> off all premium The Events Calendar products during the annual Stellar Sale. Now through July 30.', 'tribe-common' ),
			'cta_link_text'     => _x( 'Shop now', 'Shop now link text', 'tribe-common' ),
			'cta_url'           => $cta_url,
			'stellar_link_text' => _x( 'View all StellarWP Deals', 'View all StellarWP Deals link text', 'tribe-common' ),
			'stellar_url'       => $stellar_url,
		];

		$dependency = tribe( \Tribe__Dependency::class );

		if ( $dependency->has_active_premium_plugin() ) {
			// Determine the copy based on the active plugins.
			$has_events_calendar_pro = $dependency->is_plugin_active( 'Tribe__Events__Pro__Main' );
			$has_event_tickets_plus  = $dependency->is_plugin_active( 'Tribe__Tickets_Plus__Main' );

			if ( $has_events_calendar_pro && ! $has_event_tickets_plus ) {
				$template_args['sub_heading']  = __( 'Save 40% on Filter Bar.', 'tribe-common' );
				$template_args['stellar_copy'] = __( 'Add filters to your calendar during the annual Stellar Sale. Now through July 30.', 'tribe-common' );
			} elseif ( $has_event_tickets_plus && ! $has_events_calendar_pro ) {
				$template_args['sub_heading']  = __( 'Save 40% on The Events Calendar Bundles.', 'tribe-common' );
				$template_args['stellar_copy'] = __( 'Take <b>40%</b> off when you upgrade to a bundle during the annual Stellar Sale. Now through July 30.', 'tribe-common' );
			} else {
				$template_args['heading']      = __( 'Make it stellar.', 'tribe-common' );
				$template_args['sub_heading']  = __( 'Save 40% on all StellarWP products.', 'tribe-common' );
				$template_args['stellar_copy'] = __( 'Take <b>40%</b> off all brands during the annual Stellar Sale. Now through July 30.', 'tribe-common' );
			}
		}

		return $this->get_template()->template( 'notices/tribe-stellar-sale', $template_args, false );
	}
}
