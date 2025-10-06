<?php
/**
 * Test file for triggering conditionals in workflows.
 */

/**
 * Test function.
 *
 * @since TBD
 *
 * @return bool
 */
function test_function() {
	$true = true;

	/**
	 * Test filter.
	 *
	 * @since TBD
	 *
	 * @param bool $true The true value.
	 */
	return apply_filters( 'tec_pup_test_function', $true );
}
