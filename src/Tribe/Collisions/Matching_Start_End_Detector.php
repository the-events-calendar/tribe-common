<?php

/**
 * Class Tribe__Collisions__Matching_Start_End_Detector
 *
 * A collision happens when two segments have the same start and end.
 */
class Tribe__Collisions__Matching_Start_End_Detector
	extends Tribe__Collisions__Detection_Strategy
	implements Tribe__Collisions__Detector_Interface {

	/**
	 * Detects the collision of a segment with specified start and end points.
	 *
	 * @param array $segment  An array defining the end and start of a segment in the format [<start>, <end>].
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 *
	 * @return bool|array Whether a collision was detected or not or the colliding "b" segment if $report is `true`
	 *
	 * @return bool|array Whether a collision was detected or not or the colliding "b" segment if $report is `true`
	 */
	protected function detect_collision( array $segment, array $b_starts, array $b_ends, $report = false ) {
		$start = $segment[0];
		$end   = $segment[1];

		while ( false !== $start_match = array_search( $start, $b_starts ) ) {
			$end_match = $end === $b_ends[ $start_match ];

			if ( $end_match ) {
				return $report ? array( $b_starts[ $start_match ], $b_ends[ $start_match ] ) : true;
			}

			unset( $b_starts[ $start_match ], $b_ends[ $start_match ] );
		}

		return false;
	}
}