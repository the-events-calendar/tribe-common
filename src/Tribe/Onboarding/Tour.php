<?php
namespace Tribe\Onboarding;

/**
 * Class Tour
 *
 * @since TBD
 */
class Tour {

	/**
	 * Get the tour steps.
	 *
	 * @return array $steps The tour data.
	 */
	public function data() {
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
	 * Localize tour data.
	 *
	 * @since TBD
	 *
	 * @param string $hook
	 * @return void
	 */
	public function localize( $hook ) {
		$data = $this->data();

		wp_localize_script( 'tribe-onboarding-js', 'TribeOnboardingTour', $data );
	}
}
