<?php

class Tribe__Collisions__Median_Proximity_Start_Detector
	extends Tribe__Collisions__Average_Proximity_Start_Detector {
	/**
	 * Returns the distance threshold calculated according to the rule internals.
	 *
	 * @param array $starts An array of value couples in the format [a_start, b_start].
	 *
	 * @return float|int The distance threshold
	 */
	protected function get_distance_threshold( $starts ) {
		$median = 0;

		// exclude coincident starts from the median
		$ne_starts = array_filter( $starts, array( $this, 'not_equal' ) );
		$ne_starts_count = count( $ne_starts );

		if ( empty ( $ne_starts_count ) ) {
			return $median;
		}

		if ( $ne_starts_count === 1 ) {
			$ne_starts_median_i = 0;
		} else {
			$ne_starts_median_i = max( 0, floor( ( $ne_starts_count + 1 ) / 2 ) - 1 );
		}

		$ne_starts_distances = array_map( array( $this, 'get_distance' ), $ne_starts );
		sort( $ne_starts_distances );

		$median = $ne_starts_distances[ $ne_starts_median_i ];

		return $median;
	}
}
