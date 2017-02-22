<?php

/**
 * Class Tribe__Collisions__Start_End_In_Interval_Detector
 *
 * A collision happens when a segment has its start AND its end in in contained in another segment.
 */
class Tribe__Collisions__Start_End_In_Interval_Detector
	extends Tribe__Collisions__Detection_Strategy
	implements Tribe__Collisions__Detector_Interface {

	/**
	 * Detects the collision of a segment with specified start and end points.
	 *
	 * @param array $segment  An array defining the end and start of a segment in the format [<start>, <end>].
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 * @param bool  $report   Whether the colliding "b" segment should be returned or not.
	 *
	 * @return bool|array Whether a collision was detected or not or the colliding "b" segment if $report is `true`
	 */
	protected function detect_collision( array $segment, array $b_starts, array $b_ends, $report = false ) {
		$start = $segment[0];
		$end   = $segment[1];

		$intervals = array();
		$count     = count( $b_starts );
		for ( $i = 0; $i < $count; $i ++ ) {
			$intervals[] = array( $b_starts[ $i ], $b_ends[ $i ] );
		}

		foreach ( $intervals as $interval ) {
			if ( $interval[0] <= $start && $interval[1] >= $end ) {
				return $report ? array( $interval[0], $interval[1] ) : true;
			}
		}

		return false;
	}
}