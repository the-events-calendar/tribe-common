<?php

abstract class Tribe__Collisions__Detection_Strategy {

	/**
	 * Computes the collision-based lax intersection of two or more arrays of segments returning an array of elements
	 * from the first array colliding with at least one element from the second array according to the collision
	 * detection strategy implemented by the class. If more than one array is specified then a segment from the first
	 * array has to collide with at least one segment from one of the intersecting segment arrays; this method will
	 * work exactly like `intersect` when applied to 1 vs 1 arrays of segments; the "lax" part comes into play when
	 * used in 1 vs many.
	 *
	 * @see Tribe__Collisions__Detection_Strategy::intersect()
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of arrays of elements each defining the start and end of a segment in the format
	 *               [<start>, <end>]; the first array contains the segments of $a that collided while the second array
	 *               contains the segments that did collide with each colliding element of $a
	 */
	public function report_touch( array $set_a, array $set_b ) {
		$b_sets = func_get_args();
		$set_a = array_shift( $b_sets );

		$touching = array();
		$matching = array();

		foreach ( $b_sets as $set_b ) {
			list( $touched, $matched ) = $this->report_intersect( $set_a, $set_b );
			$touching[] = $touched;
			$matching[] = $matched;
		}

		$duplicate_detector = new Tribe__Collisions__Matching_Start_End_Detector();

		$merged = array_shift( $touching );
		foreach ( $touching as $t ) {
			$merged = array_merge( $merged, $duplicate_detector->diff( $t, $merged ) );
		}

		$merged_matches = array_shift( $matching );
		foreach ( $matching as $m ) {
			$merged_matches = array_merge( $merged_matches, $duplicate_detector->diff( $m, $merged_matches ) );
		}

		return array( $merged, $merged_matches );
	}

	/**
	 * Computes the collision-based intersection of two or more arrays of segments returning an array of elements from
	 * the first array colliding with at least one element from the second array according to the collision detection
	 * strategy implemented by the class.
	 * If more than one array is specified then a segment from the first array has to collide with at least one segment
	 * from each intersecting segment.
	 * If a lax intersection is needed use `touch`.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @see Tribe__Collisions__Detection_Strategy::touch()
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of arrays of elements each defining the start and end of a segment in the format
	 *               [<start>, <end>]; the first array contains the segments of $a that collided while the second array
	 *               contains the segments that did collide with each colliding element of $a
	 */
	public function report_intersect( array $set_a, array $set_b ) {
		$args = func_get_args();
		$set_a = array_shift( $args );

		return $this->collide( $set_a, $args, false, true );
	}

	/**
	 *
	 * Detects collisions betwenn 2+ arrays of segments.
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 * @param bool  $discard   Whether the detection of a collisions should discard (diff) or keep (intersect) a
	 *                         segment
	 *                         from the first array.
	 * @param bool  $report    Whether colliding segments should be reported or not; defaults to `false`.
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	protected function collide( array $set_a, array $set_b, $discard = true, $report = false ) {
		$b_sets = (array) $set_b;

		usort( $set_a, array( $this, 'compare_starts' ) );

		$diffed = array();
		$diffed_starts = array();
		$diffed_ends = array();
		$reported = array();

		// no matter the strategy a "duplicate" is always a segment with same start and end
		$duplicate_collision_detector = new Tribe__Collisions__Matching_Start_End_Detector();

		foreach ( $b_sets as $set_b ) {
			usort( $set_b, array( $this, 'compare_starts' ) );

			$b_starts = wp_list_pluck( $set_b, 0 );
			$b_ends = wp_list_pluck( $set_b, 1 );

			foreach ( $set_a as $segment ) {
				$match = $this->detect_collision( $segment, $b_starts, $b_ends, true );

				if ( $discard === (bool) $match ) {
					if ( false !== $i = array_search( $segment, $diffed ) ) {
						unset( $diffed[ $i ], $reported[ $i ] );
					}
					continue;
				}

				// avoid duplicates
				if ( $duplicate_collision_detector->detect_collision( $segment, $diffed_starts, $diffed_ends ) ) {
					continue;
				}

				if ( $report && false === $discard && true === (bool) $match ) {
					$reported[] = $match;
				}

				$diffed_starts[] = $segment[0];
				$diffed_ends[] = $segment[1];
				$diffed[] = $segment;
			}

			if ( empty( $diffed ) ) {
				break;
			}

			$set_a = $diffed;
		}

		$diffed = array_values( $diffed );
		$reported = array_values( $reported );

		return $report ? array( $diffed, $reported ) : $diffed;
	}

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
	abstract protected function detect_collision( array $segment, array $b_starts, array $b_ends, $report = false );

	/**
	 * Computes the collision-based difference of two or more arrays of segments returning an array of elements from
	 * the first array not colliding with any element from the second array according to the collision detection
	 * strategy implemented by the class.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function diff( array $set_a, array $set_b ) {
		$args = func_get_args();
		$set_a = array_shift( $args );

		return $this->collide( $set_a, $args, true );
	}

	/**
	 * Computes the collision-based lax intersection of two or more arrays of segments returning an array of elements
	 * from the first array colliding with at least one element from the second array according to the collision
	 * detection strategy implemented by the class. If more than one array is specified then a segment from the first
	 * array has to collide with at least one segment from one of the intersecting segment arrays; this method will
	 * work exactly like `intersect` when applied to 1 vs 1 arrays of segments; the "lax" part comes into play when
	 * used in 1 vs many.
	 *
	 * @see Tribe__Collisions__Detection_Strategy::intersect()
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function touch( array $set_a, array $set_b ) {
		$b_sets = func_get_args();
		$set_a = array_shift( $b_sets );

		$touching = array();

		foreach ( $b_sets as $set_b ) {
			$touching[] = $this->intersect( $set_a, $set_b );
		}

		$duplicate_detector = new Tribe__Collisions__Matching_Start_End_Detector();
		$merged = array_shift( $touching );
		foreach ( $touching as $t ) {
			$merged = array_merge( $merged, $duplicate_detector->diff( $t, $merged ) );
		}

		return array_values( array_filter( $merged ) );
	}

	/**
	 * Computes the collision-based intersection of two or more arrays of segments returning an array of elements from
	 * the first array colliding with at least one element from the second array according to the collision detection
	 * strategy implemented by the class.
	 * If more than one array is specified then a segment from the first array has to collide with at least one segment
	 * from each intersecting segment.
	 * If a lax intersection is needed use `touch`.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @see Tribe__Collisions__Detection_Strategy::touch()
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $b_set,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function intersect( array $set_a, array $b_set ) {
		$args = func_get_args();
		$set_a = array_shift( $args );

		return $this->collide( $set_a, $args, false );
	}

	/**
	 * Compares two segments starting points.
	 *
	 * Used in `usort` calls.
	 *
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 *
	 * @return int
	 */
	public function compare_starts( array $segment_a, array $segment_b ) {
		if ( $segment_a[0] == $segment_b[0] ) {
			return 0;
		}

		return ( $segment_a[0] < $segment_b[0] ) ? - 1 : 1;
	}

	/**
	 * Returns an array of segments given starts and length.
	 *
	 * Note: points are segments with a length of 0.
	 *
	 * @param array $set_starts An array of starting points
	 * @param int   $set_length The length of each segment
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function points_to_segments( array $set_starts, $set_length ) {
		$segments = array();

		foreach ( $set_starts as $start ) {
			$segments[] = array( $start, $start + $set_length );
		}

		return $segments;
	}
}