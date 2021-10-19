<?php
/**
 * Notice for the Black Friday Sale
 *
 * @since 4.14.2
 */

namespace Tribe\Admin\Notice\Marketing;

use Tribe__Date_Utils as Dates;

/**
 * Class Black_Friday
 *
 * @since 4.14.2
 *
 * @package Tribe\Admin\Notice\Marketing
 */
class Black_Friday extends \Tribe\Admin\Notice\Date_Based {
	/**
	 * {@inheritDoc}
	 */
	public $slug = 'black-friday';

	/**
	 * {@inheritDoc}
	 */
	public $start_date = 'fourth Thursday of November';

	/**
	 * {@inheritDoc}
	 *
	 * 11am UTC is 3am PST and 5am EST
	 */
	public $start_time = 11;

	/**
	 * {@inheritDoc}
	 */
	public $end_date = 'December 1st';

	/**
	 * {@inheritDoc}
	 */
	public function display_notice() {
		\Tribe__Assets::instance()->enqueue( [ 'tribe-common-admin' ] );

		// Used in the template.
		$cta_url  = 'https://evnt.is/1aqi';
		$icon_url = \Tribe__Main::instance()->plugin_url . 'src/resources/images/icons/sale-burst.svg';

		ob_start();

		include \Tribe__Main::instance()->plugin_path . 'src/admin-views/notices/tribe-bf-general.php';

		return ob_get_clean();
	}

	/**
	 * Unix time for notice start.
	 * Note: we could instead use the ...notice_start_date filter to modify the date
	 *       but this seemed more straightforward for now.
	 *
	 * @since 4.14.2
	 *
	 * @return int $end_time The date & time the notice should start displaying, as a Unix timestamp.
	 */
	public function get_start_time() {
		$date = Dates::build_date_object( $this->start_date, 'UTC' );
		$date = $date->modify( '-3 days' );
		$date = $date->setTime( $this->start_time, 0 );

		/**
		* Allow filtering of the start date DateTime object,
		* to allow for things like "the day before" ( $date->modify( '-1 day' ) ) and such.
		*
		* @since 4.14.2
		*
		* @param \DateTime $date Date object for the notice start.
		*/
		$date = apply_filters( "tribe_{$this->slug}_notice_start_date", $date );

		return $date->format( 'U' );
	}
}
