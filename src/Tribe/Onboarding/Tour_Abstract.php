<?php
namespace Tribe\Onboarding;

/**
 * Class Tour Abstract.
 *
 * @since TBD
 */
abstract class Tour_Abstract {

	/**
	 * The tour ID.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $tour_id;

	/**
	 * Times to display the tour.
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
	 * Should the tour display.
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
			&& ( tribe( 'onboarding' )->get_views( $this->tour_id ) > $this->times_to_display )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Return the tour steps.
	 *
	 * @since TBD
	 *
	 * @return array The tour steps.
	 */
	abstract function steps();

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
		 * We're displaying the tour.
		 *
		 * @since TBD.
		 *
		 * @param string $tour_id The tour id.
		 */
		do_action( 'tribe_onboarding_tour_display', $this->tour_id );

		// Increment the views when the tour is displayed.
		tribe( 'onboarding' )->increment_views( $this->tour_id );
	}

	/**
	 * Maybe localize tour data.
	 *
	 * @since TBD
	 *
	 * @param array $data The tour data.
	 * @return array $data The tour data.
	 */
	public function maybe_localize_tour( $data ) {

		if ( ! $this->should_display() ) {
			return $data;
		}

		// Trigger display action.
		$this->display();

		$data['steps']   = $this->steps();
		$data['classes'] = $this->css_classes();

		return $data;
	}
}
