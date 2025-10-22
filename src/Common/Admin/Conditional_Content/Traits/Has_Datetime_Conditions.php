<?php
/**
 * Trait for handling datetime-based conditional content display.
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */

namespace TEC\Common\Admin\Conditional_Content\Traits;

use Tribe__Date_Utils as Dates;
use Tribe__Template as Template;
use Tribe\Utils\Date_I18n;

/**
 * Trait Has_Datetime_Conditions
 *
 * Provides datetime-based conditional logic for displaying content.
 *
 * Classes using this trait MUST define:
 * - protected string $slug                    - Unique identifier for the content
 * - protected string $start_date              - Start date (any format accepted by Tribe__Date_Utils)
 * - protected string $end_date                - End date (any format accepted by Tribe__Date_Utils)
 * - protected int $start_time                 - Optional start time (hour, 0-23)
 * - protected int $end_time                   - Optional end time (hour, 0-23)
 * - public function get_slug(): string        - Method to get the full slug (for filters)
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */
trait Has_Datetime_Conditions {
	/**
	 * Stores the instance of the template engine that we will use for rendering the page.
	 *
	 * @since 6.9.8
	 *
	 * @var Template
	 */
	protected Template $template;

	/**
	 * Whether the promotional content is date bound.
	 *
	 * @since 6.9.8
	 *
	 * @return bool
	 */
	public function is_date_bound(): bool {
		return true;
	}

	/**
	 * Unix datetime for content start.
	 *
	 * @since 6.9.8
	 *
	 * @return ?Date_I18n Date Object.
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
		 * @since 6.9.8
		 *
		 * @param Date_i18n $date     Date object for the end date.
		 * @param static    $instance The conditional content object.
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
	 * @since 6.9.8
	 *
	 * @return ?Date_I18n Date Object.
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
		 * @since 6.9.8
		 *
		 * @param Date_i18n $date     Date object for the end date.
		 * @param object    $instance The conditional content object.
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
	 * @since 6.9.8
	 *
	 * @return boolean Whether the content should display.
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
		 * Allow filtering whether the content should display by date and time.
		 *
		 * @since 6.9.8
		 *
		 * @param bool   $should_display Whether the content should display.
		 * @param object $instance       The conditional content object.
		 */
		return (bool) apply_filters( "tec_admin_conditional_content_{$this->slug}_datetime_should_display", $display, $this );
	}

	/**
	 * Gets the instance of the template engine used for rendering the conditional template.
	 *
	 * @since 6.9.8
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
