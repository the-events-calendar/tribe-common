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
	 * Return if it's on page where it should be displayed.
	 *
	 * @since TBD
	 *
	 * @return bool True if it is on page.
	 */
	public function is_on_page() {
		return true;
	}

	/**
	 * Should the hints display.
	 *
	 * @return boolean True if it should display.
	 */
	public function should_display() {
		if ( ! $this->is_on_page() ) {
			return false;
		}

		return true;
	}

	/**
	 * Return the hints data.
	 *
	 * @return array The hints.
	 */
	abstract function hints();

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

		$data['hints']   = $this->hints();
		$data['classes'] = $this->css_classes();

		return $data;
	}
}
