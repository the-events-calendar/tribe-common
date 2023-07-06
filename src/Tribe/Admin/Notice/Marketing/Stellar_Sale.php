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
	public $slug = 'stellar-sale';

	/**
	 * {@inheritDoc}
	 */
	public $start_date = 'July 24th, 2023';

	/**
	 * {@inheritDoc}
	 *
	 * 7am UTC is midnight PDT (-7) and 3am EDT (-4)
	 */
	public $start_time = 19;

	/**
	 * {@inheritDoc}
	 */
	public $end_date = 'July 31st, 2023';

	/**
	 * {@inheritDoc}
	 *
	 * 7am UTC is midnight PDT (-7) and 3am EDT (-4)
	 */
	public $end_time = 19;

	/**
	 * {@inheritDoc}
	 */
	public $extension_date = 'August 2nd, 2023';

	/**
	 * {@inheritDoc}
	 *
	 * 7am UTC is midnight PDT (-7) and 3am EDT (-4)
	 */
	public $extension_time = 19;

	/**
	 * {@inheritDoc}
	 */
	public function display_notice() {
		\Tribe__Assets::instance()->enqueue( [ 'tribe-common-admin' ] );

		// Used in the template.
		$cta_url      = 'https://evnt.is/1bcv';
		$stellar_url  = 'https://evnt.is/1bcu';
		$end_date     = $this->get_end_time();
		$template_args = [
			'cta_url'      => 'https://evnt.is/1bcv',
			'stellar_url'  => 'https://evnt.is/1bcu',
			'end_date'     => $this->get_end_time(),
		];
		$dependency   = tribe( \Tribe__Dependency::class );

		if ( $dependency->has_active_premium_plugin() ) {
			return $this->get_template()->template( 'notices/tribe-stellar-sale-premium', $template_args, false );
		} else {
			return $this->get_template()->template( 'notices/tribe-stellar-sale', $template_args, false );
		}
	}
}
