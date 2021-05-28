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
	 * Return if it's on page where it should be displayed.
	 *
	 * @since TBD
	 *
	 * @return bool True if it is on page.
	 */
	public function is_on_page() {
		// @todo: check if we want to abstract this or do something different.
		return true;
	}

	/**
	 * Should the tour display.
	 *
	 * @return boolean True if it should display.
	 */
	public function should_display() {
		if ( ! $this->is_on_page() ) {
			return false;
		}

		// @todo: Check if we can implement a way to save how many times it was seen/displayed and use that as a bool.

		return true;
	}

	/**
	 * Return the tour steps.
	 *
	 * @return array The tour steps.
	 */
	abstract function steps();

	/**
	 * Return the CSS classes.
	 *
	 * @return array The CSS classes.
	 */
	public function css_classes() {
		return [];
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

		$data['steps']   = $this->steps();
		$data['classes'] = $this->css_classes();

		return $data;
	}
}
