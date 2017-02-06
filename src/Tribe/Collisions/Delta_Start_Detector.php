<?php

/**
 * Class Tribe__Collisions__Delta_Start_Detector
 *
 * A collisions happens when a segment start is contained in another segment +/- a delta.
 * E.g. [4,7] collides with [5,6] +/- 1 (5-1 = 4 and 6+1 = 7)
 * E.g. [4,7] collides with [4,5] +/- 0
 * E.g. [4,7] does not collide with [1,2] +/- 1
 * E.g. [4,7] collides with [2,3] +/- 1
 */
class Tribe__Collisions__Delta_Start_Detector
	extends Tribe__Collisions__Delta_Interval_Detector
	implements Tribe__Collisions__Detector_Interface {

	/**
	 * Detects the collision of a segment with specified start and end points.
	 *
	 * @param array $segment  An array defining the end and start of a segment in the format [<start>, <end>].
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 * @param bool $report Whether the colliding "b" segment should be returned or not.
	 *
	 * @return bool|array Whether a collision was detected or not or the colliding "b" segment if $report is `true`
	 */
	protected function detect_collision( array $segment, array $b_starts, array $b_ends, $report = false ) {
		$start = $segment[0];

		$intervals = array();
		$count     = count( $b_starts );
		for ( $i = 0; $i < $count; $i ++ ) {
			$intervals[] = array( $b_starts[ $i ], $b_ends[ $i ] );
		}

		foreach ( $intervals as $interval ) {
			$lower = $interval[0] - $this->delta;
			$upper = $interval[1] + $this->delta;
			if ( $lower <= $start && $upper >= $start ) {
				return $report ? [ $interval[0], $interval[1] ] : true;
			}
		}

		return false;
	}
}