<?php

interface Tribe__Collisions__Detector_Interface {
	/**
	 * Computes the collision-based difference of two arrays of segments returning an array of elements from the first
	 * array not colliding with any element from the second array according to the collision detection strategy
	 * implemented by the class.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $a An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 * @param array $b An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function diff( array $a, array $b );

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
	public function points_to_segments( array $starts, $length );
}