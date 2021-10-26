<?php
namespace Tribe\Admin\Conditional_Content;

use Tribe__Date_Utils as Dates;

/**
 * Set up for Black Friday promo.
 *
 * @since TBD
 */
class Black_Friday extends Datetime_Conditional_Abstract {
	/**
	 * Promo slug.
	 *
	 * @since TBD
	 */
	protected $slug = 'black_friday';

	/**
	 * End Date.
	 *
	 * @since TBD
	 */
	protected $end_date = 'November 30th';

	/**
	 * Register actions and filters.
	 *
	 * @since TBD
	 * @return void
	 */
	public function hook() {
		add_action( 'tribe_general_settings_tab_fields', [ $this, 'add_conditional_content' ] );
	}

	/**
	 * Start the Monday before Thanksgiving.
	 *
	 * @since TBD
	 * @return int - Unix timestamp
	 */
	protected function get_start_time() {
		$date = Dates::build_date_object( 'fourth Thursday of November', 'UTC' );
		$date = $date->modify( '-3 days' );
		$date = $date->setTime( $this->start_time, 0 );

		/**
		 * Allow filtering of the start date for testing.
		 *
		 * @since TBD
		 * @param \DateTime $date - Unix timestamp for start date
		 */
		$date = apply_filters( "tec_admin_conditional_content_{$this->slug}_start_date", $date );

		return $date->format( 'U' );
	}

	/**
	 * Replace the opening markup for the general settings info box.
	 *
	 * @since TBD
	 * @return void
	 */
	public function add_conditional_content( $fields ) {
		// Check if the content should currently be displayed.
		if( ! $this->should_display() ) {
			return $fields;
		}

		// Set up template variables.
		$images_dir =  \Tribe__Main::instance()->plugin_url . 'src/resources/images/';
		$template_args = [
			'branding_logo' => $images_dir . 'logo/tec-brand.svg',
			'background_image' => $images_dir . 'marketing/bf-promo.png',
			'button_link' => 'https://evnt.is/1aqi',
		];

		// Get the Black Friday promo content.
		$content = $this->get_template()->template( 'conditional_content/black-friday', $template_args, false );

		// Replace starting info box markup.
		$fields['info-start']['html'] = '<div id="modern-tribe-info">' . $content;

		return $fields;
	}
}
