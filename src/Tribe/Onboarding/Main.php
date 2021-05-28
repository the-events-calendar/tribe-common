<?php
namespace Tribe\Onboarding;

/**
 * Class
 *
 * @since TBD
 */
class Main {

	/**
	 * Get the tour steps.
	 *
	 * @since TBD
	 *
	 * @return array $steps The tour data.
	 */
	public function tour_data() {
		/**
		 * Filter the data we're using to localize the tour steps.
		 *
		 * Since TBD
		 *
		 * @param array $data An array with the tour data.
		 *
		 * @return array $data An array with the tour data.
		 */
		$data = apply_filters( 'tribe_onboarding_tour_data', [] );

		return $data;
	}

	/**
	 * Get the hints.
	 *
	 * @since TBD
	 *
	 * @return array $steps The hints data.
	 */
	public function hints_data() {
		/**
		 * Filter the data we're using to localize the hints.
		 *
		 * Since TBD
		 *
		 * @param array $data An array with the hints data.
		 *
		 * @return array $data An array with the hints data.
		 */
		$data = apply_filters( 'tribe_onboarding_hints_data', [] );

		return $data;
	}

	/**
	 * Localize tour data.
	 *
	 * @since TBD
	 *
	 * @param string $hook The current admin page.
	 * @return void
	 */
	public function localize_tour( $hook ) {
		$data = $this->tour_data();

		wp_localize_script( 'tribe-onboarding-js', 'TribeOnboardingTour', $data );
	}

	/**
	 * Localize hints data.
	 *
	 * @since TBD
	 *
	 * @param string $hook The current admin page.
	 * @return void
	 */
	public function localize_hints( $hook ) {
		$data = $this->hints_data();

		wp_localize_script( 'tribe-onboarding-js', 'TribeOnboardingHints', $data );
	}
}
