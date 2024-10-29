<?php

namespace TEC\Common\Admin\Conditional_Content;

use Tribe__Date_Utils as Dates;
use Tribe__Template as Template;
use Tribe\Utils\Date_I18n;

/**
 * Abstract class for conditional content.
 *
 * @since 6.3.0
 */
abstract class Datetime_Conditional_Abstract {
	/**
	 * Item slug.
	 *
	 * @since 6.3.0
	 */
	protected string $slug;

	/**
	 * Start date.
	 *
	 * @since 6.3.0
	 */
	protected string $start_date;

	/**
	 * Start time.
	 *
	 * @since 6.3.0
	 */
	protected string $start_time;

	/**
	 * End date.
	 *
	 * @since 6.3.0
	 */
	protected string $end_date;

	/**
	 * End time.
	 *
	 * @since 6.3.0
	 */
	protected string $end_time;

	/**
	 * Stores the instance of the template engine that we will use for rendering the page.
	 *
	 * @since 6.3.0
	 *
	 * @var Template
	 */
	protected Template $template;

	/**
	 * Register actions and filters.
	 *
	 * @since 6.3.0
	 *
	 * @return void
	 */
	abstract public function hook(): void;

	/**
	 * Unix datetime for content start.
	 *
	 * @since 6.3.0
	 *
	 * @return ?Date_I18n - Date Object
	 */
	protected function get_start_time(): ?Date_I18n {
		$date = Dates::build_date_object( $this->start_date, 'UTC' );
		// If not set, set to midnight.
		if ( empty( $this->start_time ) ) {
			$this->start_time = 0;
		}

		$date = $date->setTime( $this->start_time, 0 );

		/**
		 * Allow filtering of the start date for testing.
		 *
		 * @since 6.3.0
		 *
		  * @param Date_i18n $date - Date object for the end date.
		 * @param static $this
		 */
		$date = apply_filters( "tec_admin_conditional_content_{$this->slug}_start_date", $date, $this );

		if ( ! $date instanceof Date_I18n ) {
			return null;
		}

		return $date;
	}

	/**
	 * Unix datetime for content end.
	 *
	 * @since 6.3.0
	 *
	 * @return ?Date_I18n - Date Object
	 */
	protected function get_end_time(): ?Date_I18n {
		$date = Dates::build_date_object( $this->end_date, 'UTC' );
		// If not set, set to midnight.
		if ( empty( $this->end_time ) ) {
			$this->end_time = 0;
		}

		$date = $date->setTime( $this->end_time, 0 );

		/**
		 * Allow filtering of the end date for testing.
		 *
		 * @since 6.3.0
		 *
		 * @param Date_i18n $date - Date object for the end date.
		 * @param object    $this
		 */
		$date = apply_filters( "tec_admin_conditional_content_{$this->slug}_end_date", $date, $this );

		if ( ! $date instanceof Date_I18n ) {
			return null;
		}

		return $date;
	}

	/**
	 * Whether the content should display.
	 *
	 * @since 6.3.0
	 *
	 * @return boolean - Whether the content should display
	 */
	protected function should_display(): bool {
		$now          = Dates::build_date_object( 'now', 'UTC' );
		$notice_start = $this->get_start_time();
		$notice_end   = $this->get_end_time();

		// Failed dates should yield false.
		if ( $notice_end === null || $notice_start === null ) {
			return false;
		}

		$display = $notice_start <= $now && $now < $notice_end;

		/**
		 * Allow filtering whether the content should display.
		 *
		 * @since 6.3.0
		 *
		 * @param bool   $should_display - whether the content should display
		 * @param object $this           - the conditional content object
		 */
		return (bool) apply_filters( "tec_admin_conditional_content_{$this->slug}_should_display", $display, $this );
	}

	/**
	 * Gets the instance of the template engine used for rendering the conditional template.
	 *
	 * @since 6.3.0
	 *
	 * @return Template
	 */
	public function get_template(): Template {
		if ( empty( $this->template ) ) {
			$this->template = new Template();
			$this->template->set_template_origin( \Tribe__Main::instance() );
			$this->template->set_template_folder( 'src/admin-views/conditional_content' );
			$this->template->set_template_context_extract( true );
			$this->template->set_template_folder_lookup( false );
		}

		return $this->template;
	}
}
