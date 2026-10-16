<?php


/**
 * Class Tribe__Ajax__Operations
 *
 * Handles common AJAX operations.
 */
class Tribe__Ajax__Operations {

	public function verify_or_exit( $nonce, $action, $exit_data = [] ) {
		if ( ! wp_verify_nonce( $nonce, $action ) ) {
			exit( $exit_data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, StellarWP.XSS.EscapeOutput.OutputNotEscaped -- AJAX response body assembled by the caller.
		}

		return true;
	}

	public function exit_data( $data = [] ) {
		exit( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, StellarWP.XSS.EscapeOutput.OutputNotEscaped -- AJAX response body assembled by the caller.
	}
}
