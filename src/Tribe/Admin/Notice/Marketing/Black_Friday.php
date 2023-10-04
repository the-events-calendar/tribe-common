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
	 */
	public $end_date = 'November 29';

	/**
	 * {@inheritDoc}
	 */
	public $end_time = 23;

	/**
	 * {@inheritDoc}
	 */
	public $icon_url = 'images/icons/horns-white.svg';

	/**
	 * {@inheritDoc}
	 */
	public function display_notice() {
		\Tribe__Assets::instance()->enqueue( [ 'tribe-common-admin' ] );

		// Set up template variables.
		$template_args = [
			'icon_url' => tribe_resource_url( $this->icon_url, false, null, \Tribe__Main::instance() ),
			'cta_url'  => 'https://evnt.is/1aqi',
			'end_date' => $this->get_end_time()->format_i18n( 'F jS' ),
		];

		// Get the Black Friday notice content.
		$content = $this->get_template()->template( 'notices/tribe-bf-general', $template_args, false );

		return $content;
	}

	/**
	 * Unix time for notice start.
	 *
	 * @since 4.14.2
	 *
	 * @return \Tribe\Utils\Date_I18n - Date Object
	 */
	public function get_start_time() {
		$date = parent::get_start_time();
		$date = $date->modify( '-3 days' );

		return $date;
	}

	/**
	 * Enqueue additional assets for the notice.
	 *
	 * @since TBD
	 */
	public function enqueue_additional_assets() {
		if ( ! $this->should_display() ) {
			return;
		}
		// Adds the Montserrat font from Google Fonts.
		wp_enqueue_style( 'tec_black_friday_font', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700' );
	}
}
