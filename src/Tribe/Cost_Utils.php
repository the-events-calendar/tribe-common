<?php


class Tribe__Cost_Utils {

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Tribe__Events__Cost_Utils
	 */
	public static function instance() {
		static $instance;

		if ( ! $instance ) {
			$instance = new self;
		}

		return $instance;
	}

	/**
	 * Fetch the possible separators
	 *
	 * @return array
	 */
	public function get_separators() {
		$separators = array( ',', '.' );

		/**
		 * Filters the cost string possible separators, those must be only 1 char.
		 *
		 * @param array $separators Defaults to comma (",") and period (".")
		 */
		return apply_filters( 'tribe_events_cost_separators', $separators );
	}

	/**
	 * Returns the regular expression that shold be used to  identify a valid
	 * cost string.
	 *
	 * @return string
	 */
	public function get_cost_regex() {
		$separators = '[\\' . implode( '\\', $this->get_separators() ) . ']?';
		$cost_regex = '(' . $separators . '([\d]+)' . $separators . '([\d]*))';

		/**
		 * Filters the regular expression that will be used to identify a valid cost
		 * string.
		 *
		 * @param string $cost_regex
		 *
		 * @deprecated 4.3 Use `tribe_cost_regex` instead
		 */
		$cost_regex = apply_filters(
			'tribe_events_cost_regex', $cost_regex
		);

		/**
		 * Filters the regular expression that will be used to identify a valid cost
		 * string.
		 *
		 * @param string $cost_regex
		 */
		$cost_regex = apply_filters('tribe_cost_regex', $cost_regex);

		return $cost_regex;
	}

	/**
	 * Check if a String is a valid cost
	 *
	 * @param  string $cost String to be checked
	 *
	 * @return boolean
	 */
	public function is_valid_cost( $cost, $allow_negative = true ) {
		return preg_match( $this->get_cost_regex(), trim( $cost ) );
	}

}