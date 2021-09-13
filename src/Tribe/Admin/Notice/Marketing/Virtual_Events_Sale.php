<?php
/**
 * Notice for the Virtual Events Sale
 *
 * @since TBD
 */

namespace Tribe\Admin\Notice\Marketing;

use Tribe__Date_Utils as Dates;

/**
 * Class Virtual_Events_Sale
 *
 *
 * @since TBD
 *
 * @package Tribe\Admin\Notice\Marketing
 */
class Virtual_Events_Sale extends \Tribe\Admin\Notice\Date_Based {
	/**
	 * {@inheritDoc}
	 */
	public $slug = 'virtual-events-sale';

	/**
	 * {@inheritDoc}
	 */
	public $start_date = 'September 9th, 2021';

	/**
	 * {@inheritDoc}
	 *
	 * 1pm UTC is 6am PDT (-7) and 9am EDT (-4)
	 */
	public $start_time = 13;

	/**
	 * {@inheritDoc}
	 */
	public $end_date = 'November 19th, 2021';

	/**
	 * {@inheritDoc}
	 *
	 * 5am UTC is 9pm PST (-8) and 12am EST (-5)
	 */
	public $end_time = 5;

	/**
	 * {@inheritDoc}
	 */
	public function display_notice() {
		\Tribe__Assets::instance()->enqueue( [ 'tribe-common-admin' ] );

		// Used in the template.
		$cta_url  = 'https://evnt.is/1awj';

		ob_start();

		include \Tribe__Main::instance()->plugin_path . 'src/admin-views/notices/virtual-events-sale.php';

		return ob_get_clean();
	}

    /**
	 * {@inheritDoc}
	 */
	public function should_display() {
        // if virtual events is already installed, no need to show notice.
        if( defined( 'EVENTS_VIRTUAL_FILE' ) ){
            return false;
        }

		return parent::should_display();
	}
}
