<?php
namespace Tribe\Onboarding;

/**
 * Class Hints Abstract.
 *
 * @since TBD
 */
abstract class Hints_Abstract {

	/**
	 * The hints ID.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $hints_id;

	/**
	 * Times to display the hints.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	public $times_to_display;

	/**
	 * Return if it's on page where it should be displayed.
	 *
	 * @since TBD
	 *
	 * @return bool True if it is on page.
	 */
	public function is_on_page() {
		return false;
	}

	/**
	 * Should the hints display.
	 *
	 * @since TBD
	 *
	 * @return boolean True if it should display.
	 */
	public function should_display() {
		// Bail if it's not on the page we want to display.
		if ( ! $this->is_on_page() ) {
			return false;
		}

		// Bail if the `Times to display` is set and it was reached.
		if (
			is_numeric( $this->times_to_display )
			&& ( tribe( 'onboarding' )->get_views( $this->hints_id ) > $this->times_to_display )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Return the hints data.
	 *
	 * @since TBD
	 *
	 * @return array The hints.
	 */
	abstract function hints();

	/**
	 * Return the CSS classes.
	 *
	 * @since TBD
	 *
	 * @return array The CSS classes.
	 */
	public function css_classes() {
		return [];
	}

	/**
	 * Handle the display.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	private function display() {
		/**
		 * We're displaying the hints.
		 *
		 * @since TBD.
		 *
		 * @param string $hints_id The hints id.
		 */
		do_action( 'tribe_onboarding_hints_display', $this->hints_id );

		// Increment the views when the hints are displayed.
		tribe( 'onboarding' )->increment_views( $this->hints_id );
	}

	/**
	 * Maybe localize hints data.
	 *
	 * @since TBD
	 *
	 * @param array $data The hints data.
	 * @return array $data The hints data.
	 */
	public function maybe_localize_hints( $data ) {

		if ( ! $this->should_display() ) {
			return $data;
		}

		// Trigger display action.
		$this->display();

		$data['hints']   = $this->hints();
		$data['classes'] = $this->css_classes();

		return $data;
	}
}
