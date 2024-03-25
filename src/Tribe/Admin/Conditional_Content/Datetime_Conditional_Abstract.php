<?php
/**
 * Abstract Datetime Conditional
 */

namespace Tribe\Admin\Conditional_Content;

use Tribe__Date_Utils as Dates;

/**
 * Abstract class for conditional content.
 *
 * @since 4.14.7
 */
abstract class Datetime_Conditional_Abstract {
	/**
	 * Item slug.
	 *
	 * @since 4.14.7
	 *
	 * @var string
	 */
	protected $slug = '';

	/**
	 * Start date.
	 *
	 * @since 4.14.7
	 *
	 * @var string
	 */
	protected $start_date;

	/**
	 * Start time.
	 *
	 * @since 4.14.7
	 *
	 * @var string
	 */
	protected $start_time;

	/**
	 * End date.
	 *
	 * @since 4.14.7
	 *
	 * @var string
	 */
	protected $end_date;

	/**
	 * End time.
	 *
	 * @since 4.14.7
	 *
	 * @var string
	 */
	protected $end_time;

	/**
	 * Stores the instance of the template engine that we will use for rendering the page.
	 *
	 * @since 4.14.7
	 *
	 * @var \Tribe__Template
	 */
	protected $template;

	/**
	 * Register actions and filters.
	 *
	 * @since 4.14.7
	 * @return void
	 */
	abstract public function hook();

	/**
	 * Unix datetime for content start.
	 *
	 * @since 4.14.7
	 *
	 * @return \Tribe\Utils\Date_I18n - Date Object
	 */
	protected function get_start_time() {
		$date = Dates::build_date_object( $this->start_date, 'UTC' );
		// If not set, set to midnight.
		if ( empty( $this->start_time ) ) {
			$this->start_time = 0;
		}

		$date = $date->setTime( $this->start_time, 0 );

		/**
		 * Allow filtering of the start date for testing.
		 *
		 * @since 4.14.7
		 * @param \DateTime $date     Unix timestamp for start date.
		 * @param object    $instance the conditional content object.
		 */
		$date = apply_filters( "tec_admin_conditional_content_{$this->slug}_start_date", $date, $this );

		return $date;
	}

	/**
	 * Unix datetime for content end.
	 *
	 * @since 4.14.7
	 *
	 * @return \Tribe\Utils\Date_I18n - Date Object
	 */
	protected function get_end_time() {
		$date = Dates::build_date_object( $this->end_date, 'UTC' );
		// If not set, set to midnight.
		if ( empty( $this->end_time ) ) {
			$this->end_time = 0;
		}

		$date = $date->setTime( $this->end_time, 0 );

		/**
		 * Allow filtering of the end date for testing.
		 *
		 * @since 4.14.7
		 * @param \DateTime $date Unix timestamp for end date.
		 * @param object    $instance the conditional content object.
		 */
		$date = apply_filters( "tec_admin_conditional_content_{$this->slug}_end_date", $date, $this );

		return $date;
	}

	/**
	 * Whether the content should display.
	 *
	 * @since 4.14.7
	 *
	 * @return boolean Whether the content should display.
	 */
	protected function should_display() {
		$now          = Dates::build_date_object( 'now', 'UTC' );
		$notice_start = $this->get_start_time();
		$notice_end   = $this->get_end_time();
		$display      = $notice_start <= $now && $now < $notice_end;

		/**
		 * Allow filtering whether the content should display.
		 *
		 * @since 4.14.7
		 * @param bool $should_display whether the content should display.
		 * @param object $instance     the conditional content object.
		 */
		return apply_filters( "tec_admin_conditional_content_{$this->slug}_should_display", $display, $this );
	}

	/**
	 * Gets the template instance used to setup the rendering of the page.
	 *
	 * @since 4.14.7
	 *
	 * @return \Tribe__Template
	 */
	public function get_template() {
		if ( empty( $this->template ) ) {
			$this->template = new \Tribe__Template();
			$this->template->set_template_origin( \Tribe__Main::instance() );
			$this->template->set_template_folder( 'src/admin-views' );
			$this->template->set_template_context_extract( true );
			$this->template->set_template_folder_lookup( false );
		}

		return $this->template;
	}
}
