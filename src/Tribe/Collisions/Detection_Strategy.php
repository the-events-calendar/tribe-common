<?php

abstract class Tribe__Collisions__Detection_Strategy {

	/**
	 * Computes the collision-based difference of two arrays of segments returning an array of elements from the first
	 * array not colliding with any element from the second array according to the collision detection strategy
	 * implemented by the class.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $a     An array of elements each defining the start and end of a segment in the format [<start>,
	 *                     <end>].
	 * @param array $b,... An array (ore more arrays) of elements each defining the start and end of a segment in the
	 *                     format [<start>, <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function diff( array $a, array $b ) {
		$bs = func_get_args();
		$a  = array_shift( $bs );

		$bs = array_filter( $bs );

		if ( empty( $bs ) ) {
			return $a;
		}

		usort( $a, array( $this, 'compare_starts' ) );

		$diffed        = array();
		$diffed_starts = array();
		$diffed_ends   = array();

		foreach ( $bs as $b ) {
			usort( $b, array( $this, 'compare_starts' ) );

			$b_starts = wp_list_pluck( $b, 0 );
			$b_ends   = wp_list_pluck( $b, 1 );

			foreach ( $a as $segment ) {
				if ( $this->detect_collision( $segment, $b_starts, $b_ends ) ) {
					continue;
				}

				// avoid duplicates
				if ( $this->detect_collision( $segment, $diffed_starts, $diffed_ends ) ) {
					continue;
				}

				$diffed_starts[] = $segment[0];
				$diffed_ends[]   = $segment[1];
				$diffed[]        = $segment;
			}
		}

		return $diffed;
	}

	/**
	 * Detects the collision of a segment with specified start and end points.
	 *
	 * @param array $segment  An array defining the end and start of a segment in the format [<start>, <end>].
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 *
	 * @return bool Whether a collision was detected or not.
	 */
	abstract protected function detect_collision( array $segment, array $b_starts, array $b_ends );

	/**
	 * Compares two segments.
	 *
	 * Used in `usort` calls.
	 *
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 *
	 * @return int
	 */
	public function compare_starts( array $a, array $b ) {
		if ( $a[0] == $b[0] ) {
			return 0;
		}

		return ( $a[0] < $b[0] ) ? - 1 : 1;
	}

	/**
	 * Returns an array of segments given starts and length.
	 *
	 * Note: points are segments with a length of 0.
	 *
	 * @param array $starts An array of starting points
	 * @param int   $length The length of each segment
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function points_to_segments( array $starts, $length ) {
		$segments = array();

		foreach ( $starts as $start ) {
			$segments[] = array( $start, $start + $length );
		}

		return $segments;
	}
}