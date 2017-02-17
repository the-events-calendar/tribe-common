<?php

interface Tribe__Collisions__Detector_Interface {
	/**
	 * Computes the collision-based difference of two or more arrays of segments returning an array of elements from
	 * the first array not colliding with any element from the second array according to the collision detection
	 * strategy implemented by the class.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $set_a An array of elements each defining the start and end of a segment in the format [<start>,
	 *                     <end>].
	 * @param array $set_b An array of elements each defining the start and end of a segment in the format [<start>,
	 *                     <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function diff( array $set_a, array $set_b );

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
	public function points_to_segments( array $set_starts, $set_length );

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
	public function compare_starts( array $segment_a, array $segment_b );

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
	public function report_touch( array $set_a, array $set_b );

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
	public function report_intersect( array $set_a, array $set_b );

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
	public function touch( array $set_a, array $set_b );

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
	public function intersect( array $set_a, array $b_set );
}