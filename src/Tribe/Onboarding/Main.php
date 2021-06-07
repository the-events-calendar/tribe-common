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

	/**
	 * Get the views for an onboarding element.
	 *
	 * @since TBD
	 *
	 * @param string $id The onboarding ID (tour or hint).
	 *
	 * @return mixed The views for the given ID.
	 */
	public function get_views( $id = '' ) {

		if ( empty( $id ) ) {
			return;
		}

		$option = tribe_get_option( 'tribe_onboarding_views', [] );

		if ( ! isset( $option[ $id ] ) ) {
			return;
		}

		return intval( $option[ $id ] );
	}

	/**
	 * Increment views for an onboarding element.
	 *
	 * @since TBD
	 *
	 * @param string $id The onboarding ID (tour or hint).
	 * @return void
	 */
	public function increment_views( $id ) {
		$option = tribe_get_option( 'tribe_onboarding_views', [] );
		$views  = 0;

		if ( isset( $option[ $id ] ) ) {
			$views = intval( $option[ $id ] );
		}

		// Increment views and save.
		$views++;
		$option[ $id ] = $views;

		tribe_update_option( 'tribe_onboarding_views', $option );

		return $views;
	}
}
