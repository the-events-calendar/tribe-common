<?php

class Tribe__Collisions__Average_Proximity_Start_Detector
	extends Tribe__Collisions__Closest_Unique_Start_Detector
	implements Tribe__Collisions__Detector_Interface {
	/**
	 * Computes the collision-based difference of two or more arrays of segments returning an array of elements from
	 * the first array not colliding with any element from the second array according to the collision detection
	 * strategy implemented by the class.
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
		$a = array_shift( $bs );

		if ( empty( array_filter( $bs ) ) ) {
			return $a;
		}

		$intersected = call_user_func_array( array( $this, 'intersect' ), func_get_args() );

		$diffed = array();

		foreach ( $a as $candidate ) {
			if ( ! in_array( $candidate, $intersected ) ) {
				$diffed[] = $candidate;
			}
		}

		return $diffed;
	}

	protected function get_distance( array $starts ) {
		return abs( $starts[1] - $starts[0] );
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
	 * @param array $a     An array of elements each defining the start and end of a segment in the format [<start>,
	 *                     <end>].
	 * @param array $b,... An array (ore more arrays) of elements each defining the start and end of a segment in the
	 *                     format [<start>, <end>].
	 *
	 * @return array An array of arrays of elements each defining the start and end of a segment in the format
	 *               [<start>, <end>]; the first array contains the segments of $a that collided while the second array
	 *               contains the segments that did collide with each colliding element of $a
	 */
	public function report_intersect( array $a, array $b ) {
		$reported = call_user_func_array( array( 'parent', 'report_intersect' ), func_get_args() );

		if ( empty( $reported ) ) {
			return array();
		}

		$as = reset( $reported );
		$bs = end( $reported );

		$count = count( $as );

		$starts = array();
		for ( $i = 0; $i < $count; $i ++ ) {
			$starts[] = array( $as[ $i ][0], $bs[ $i ][0] );
		}

		$average_distance = array_sum( array_map( array( $this, 'get_distance' ), $starts ) ) / $count;

		$surviving = array( array(), array() );
		for ( $i = 0; $i < $count; $i ++ ) {
			$current_a = $reported[0][ $i ];
			$current_b = $reported[1][ $i ];

			if ( abs( $current_b[0] - $current_a[0] ) <= $average_distance ) {
				$surviving[0][] = $current_a;
				$surviving[1][] = $current_b;
			}
		}

		return $surviving;
	}
}