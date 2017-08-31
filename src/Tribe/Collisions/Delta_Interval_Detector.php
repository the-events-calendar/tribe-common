<?php

/**
 * Class Tribe__Collisions__Delta_Interval_Detector
 *
 * A collision happens when a segment is contained in another segment +/- a delta.
 * E.g [4,7] will collide with [5,6] with a delta of 1 or above; (5-1 = 4) && (6+1 = 7).
 * E.g [5,6] will not collide with [3,4] with a delta of 1; (3-1 = 2) && (4+1 = 5); [5,6] is not contained in [2,5].
 */
class Tribe__Collisions__Delta_Interval_Detector
	extends Tribe__Collisions__Detection_Strategy
	implements Tribe__Collisions__Detector_Interface {

	/**
	 * @var int
	 */
	protected $delta;

	/**
	 * Tribe__Collisions__Delta_Interval_Detector constructor.
	 *
	 * @param int $delta The delta value that should be applied to detect collisions.
	 */
	public function __construct( $delta ) {
		$this->delta = $delta;
	}

	/**
	 * Detects the collision of a segment with specified start and end points.
	 *
	 * @param array $segment  An array defining the end and start of a segment in the format [<start>, <end>].
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 *
	 * @return bool|array Whether a collision was detected or not or the colliding "b" segment if $report is `true`
	 */
	protected function detect_collision( array $segment, array $b_starts, array $b_ends, $report = true ) {
		$start = $segment[0];
		$end = $segment[1];

		$intervals = array();
		$count = count( $b_starts );
		for ( $i = 0; $i < $count; $i ++ ) {
			$intervals[] = array( $b_starts[ $i ], $b_ends[ $i ] );
		}

		foreach ( $intervals as $interval ) {
			$lower = $interval[0] - $this->delta;
			$upper = $interval[1] + $this->delta;
			if ( $lower <= $start && $upper >= $end ) {
				return $report ? [ $interval[0], $interval[1] ] : true;
			}
		}

		return false;
	}
}