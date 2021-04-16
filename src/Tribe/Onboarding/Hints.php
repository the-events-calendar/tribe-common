<?php
namespace Tribe\Onboarding;

/**
 * Class Hints
 *
 * @since TBD
 */
class Hints {

	/**
	 * Get the hints.
	 *
	 * @return array $steps The hints data.
	 */
	public function data() {
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
	 * Localize hints data.
	 *
	 * @since TBD
	 *
	 * @param string $hook
	 * @return void
	 */
	public function localize( $hook ) {
		$data = $this->data();

		wp_localize_script( 'tribe-onboarding-js', 'TribeOnboardingHints', $data );
	}
}
