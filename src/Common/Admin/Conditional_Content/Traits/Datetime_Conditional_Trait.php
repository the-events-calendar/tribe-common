<?php
/**
 * Trait Datetime_Conditional_Trait
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */

namespace TEC\Common\Admin\Conditional_Content\Traits;

use Tribe__Date_Utils as Dates;
use Tribe\Utils\Date_I18n;

/**
 * Trait for date-based conditional content functionality.
 *
 * @since TBD
 */
trait Datetime_Conditional_Trait {
	/**
	 * Start date.
	 *
	 * @since 6.3.0
	 *
	 * @var string
	 */
	protected string $start_date;

	/**
	 * Start time.
	 *
	 * @since 6.3.0
	 *
	 * @var int
	 */
	protected int $start_time;

	/**
	 * End date.
	 *
	 * @since 6.3.0
	 *
	 * @var string
	 */
	protected string $end_date;

	/**
	 * End time.
	 *
	 * @since 6.3.0
	 *
	 * @var int
	 */
	protected int $end_time;

	/**
	 * Built dates.
	 * In the format of [ 'start' => Date_I18n, 'end' => Date_I18n ].
	 *
	 * @since TBD
	 *
	 * @var array<string, Date_I18n>
	 */
	protected array $built_dates = [];

	/**
	 * Sets the date and time configuration for the conditional content.
	 * This method allows concrete classes to configure the trait's properties.
	 *
	 * @since TBD
	 *
	 * @param string $start_date_str The start date string (e.g., 'November 26th').
	 * @param string $end_date_str   The end date string (e.g., 'December 3rd').
	 * @param int    $start_time_int The start time (e.g., 4 for 4 AM).
	 * @param int    $end_time_int   The end time (e.g., 7 for 7 AM).
	 */
	protected function set_datetime_configuration( string $start_date_str, string $end_date_str, int $start_time_int, int $end_time_int ): void {
		$this->start_date = $start_date_str;
		$this->end_date   = $end_date_str;
		$this->start_time = $start_time_int;
		$this->end_time   = $end_time_int;
	}

	/**
	 * Register actions and filters.
	 *
	 * @since 6.3.0
	 *
	 * @return void
	 */
	abstract public function hook(): void; // This needs to be abstract here if the trait relies on it.

	/**
	 * Get the built dates.
	 *
	 * @since TBD
	 *
	 * @return array<string, Date_I18n>
	 */
	protected function get_built_dates(): array {
		if ( empty( $this->built_dates ) ) {
			// Ensure properties are set before trying to build dates.
			if ( ! isset( $this->start_date, $this->end_date, $this->start_time, $this->end_time ) ) {
				// Provide a fallback or throw an error if configuration is missing
				// For now, setting sensible defaults if not configured.
				$this->start_date ??= 'now';
				$this->end_date   ??= 'now';
				$this->start_time ??= 0;
				$this->end_time   ??= 0;
			}

			$start = Dates::build_date_object( $this->start_date, 'UTC' );
			$end   = Dates::build_date_object( $this->end_date, 'UTC' );

			$this->built_dates['start'] = $start->setTime( $this->start_time, 0 );
			$this->built_dates['end']   = $end->setTime( $this->end_time, 0 );
		}

		return $this->built_dates;
	}

	/**
	 * Unix datetime for content start.
	 *
	 * @since 6.3.0
	 *
	 * @return ?Date_I18n - Date Object
	 */
	protected function get_start_date(): ?Date_I18n {
		$date = $this->get_built_dates()['start'];

		/**
		 * Allow filtering of the start date.
		 *
		 * @since 6.3.0
		 *
		 * @param Date_i18n $date     Date object for the end date. Includes start time.
		 * @param static    $instance The conditional content object.
		 */
		return apply_filters( "tec_admin_conditional_content_{$this->get_slug()}_start_date", $date, $this );
	}

	/**
	 * Unix datetime for content end.
	 *
	 * @since 6.3.0
	 *
	 * @return ?Date_I18n - Date Object
	 */
	protected function get_end_date(): ?Date_I18n {
		$date = $this->get_built_dates()['end'];

		/**
		 * Allow filtering of the end date.
		 *
		 * @since 6.3.0
		 *
		 * @param Date_i18n $date     Date object for the end date. Includes end time.
		 * @param object    $instance The conditional content object.
		 */
		return apply_filters( "tec_admin_conditional_content_{$this->get_slug()}_end_date", $date, $this );
	}

	/**
	 * Whether the content should display based on date constraints.
	 *
	 * @since 6.3.0
	 * @since TBD Renamed from should_display() to is_date_valid() to better reflect its specific purpose.
	 *
	 * @return boolean - Whether the content should display based on date constraints
	 */
	protected function is_date_valid(): bool {
		$now          = Dates::build_date_object( 'now', 'UTC' );
		$notice_start = $this->get_built_dates()['start'];
		$notice_end   = $this->get_built_dates()['end'];

		$display = $notice_start <= $now && $now < $notice_end;

		/**
		 * Allow filtering whether the content should display based on date constraints.
		 *
		 * @since 6.3.0
		 * @since TBD Filter name updated to reflect method name change.
		 *
		 * @param bool   $display  Whether the content should display.
		 * @param object $instance The conditional content object.
		 */
		return (bool) apply_filters( "tec_admin_conditional_content_{$this->get_slug()}_is_date_valid", $display, $this );
	}

	/**
	 * Helper to register this trait's specific display condition filter.
	 *
	 * @since TBD
	 *
	 * @param string $hook_name     The name of the filter to register.
	 * @param int    $priority      The priority of the filter.
	 * @param int    $accepted_args The number of arguments the filter accepts.
	 *
	 * @return void
	 */
	protected function register_datetime_display_hook( $hook_name, $priority = 10, $accepted_args = 2 ) {
		/**
		 * Filters the content creative based on datetime.
		 *
		 * @since TBD
		 *
		 * @param bool   $should_display  Current display status from previous filters.
		 * @param object $instance      The promotional content instance. (Added for consistency)
		 */
		add_filter( $hook_name, [ $this, 'filter_datetime_display_condition' ], $priority, $accepted_args );
	}

	/**
	 * Filter callback for display condition based on datetime.
	 *
	 * @since TBD
	 *
	 * @param bool   $should_display  Current display status from previous filters.
	 * @param object $instance      The promotional content instance. (Added for consistency).
	 *
	 * @return bool
	 */
	public function filter_datetime_display_condition( $should_display, $instance ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		// If a previous filter already set it to false, keep it false.
		if ( ! $should_display ) {
			return false;
		}

		$is_active_by_datetime = $this->is_date_valid();

		if ( ! $is_active_by_datetime ) {
			$should_display = false;
		}

		return $should_display;
	}

	/**
	 * This trait relies on `get_slug()` which is abstract in Promotional_Content_Abstract.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	abstract protected function get_slug(): string;
}
