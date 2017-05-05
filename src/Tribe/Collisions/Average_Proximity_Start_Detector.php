<?php

class Tribe__Collisions__Average_Proximity_Start_Detector
	extends Tribe__Collisions__Closest_Unique_Start_Detector
	implements Tribe__Collisions__Detector_Interface {

	/**
	 * A margin that should be applied to the delta to allow for broader matching.
	 * @var int
	 */
	protected $margin;

	/**
	 * Tribe__Collisions__Average_Proximity_Start_Detector constructor.
	 *
	 * @param int $margin
	 */
	public function __construct( $margin = 0 ) {
		$this->margin = $margin;
	}

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
		$b_sets = func_get_args();
		$set_a = array_shift( $b_sets );

		$b_sets = array_filter( $b_sets );
		if ( empty( $b_sets ) ) {
			return $set_a;
		}

		// On PHP 5.2 you cannot use func_get_args as a param
		$args = func_get_args();
		$intersected = call_user_func_array( array( $this, 'intersect' ), $args );

		$diffed = array();

		foreach ( $set_a as $candidate ) {
			if ( ! in_array( $candidate, $intersected ) ) {
				$diffed[] = $candidate;
			}
		}

		return $diffed;
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
		// On PHP 5.2 you cannot use func_get_args as a param
		$args = func_get_args();
		$reported = call_user_func_array( array( 'parent', 'report_intersect' ), $args );

		if ( empty( $reported ) ) {
			return array();
		}

		$a_sets = reset( $reported );
		$b_sets = end( $reported );

		$count = count( $a_sets );

		$starts = array();
		for ( $i = 0; $i < $count; $i ++ ) {
			$starts[] = array( $a_sets[ $i ][0], $b_sets[ $i ][0] );
		}

		$average_distance = $this->get_distance_threshold( $starts );

		$average_distance += $this->margin;

		// sanity check
		$average_distance = $average_distance < 0 ? 0 : $average_distance;

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

	/**
	 * Returns the distance threshold calculated according to the rule internals.
	 *
	 * @param array $starts An array of value couples in the format [a_start, b_start].
	 *
	 * @return float|int The distance threshold
	 */
	protected function get_distance_threshold( $starts ) {
		$average = 0;

		// exclude coincident starts from the average
		$ne_starts = array_filter( $starts, array( $this, 'not_equal' ) );
		$ne_starts_count = count( $ne_starts );

		if ( empty( $ne_starts_count ) ) {
			return $average;
		}

		$ne_starts_distance = array_map( array( $this, 'get_distance' ), $ne_starts );
		if ( $ne_starts_count ) {
			$average = ( array_sum( $ne_starts_distance ) / $ne_starts_count );
		}

		return $average;
	}

	/**
	 * Sets the margin that should be applied to the average distance to broaden the matches.
	 *
	 * @param int $margin
	 */
	public function set_margin( $margin ) {
		if ( ! is_numeric( $margin ) ) {
			throw new InvalidArgumentException( __( 'Margin must be an integer', 'tribe-common' ) );
		}

		$this->margin = intval( $margin );
	}

	protected function not_equal( array $starts ) {
		return $starts[0] !== $starts[1];
	}

	protected function get_distance( array $starts ) {
		return abs( $starts[1] - $starts[0] );
	}

}