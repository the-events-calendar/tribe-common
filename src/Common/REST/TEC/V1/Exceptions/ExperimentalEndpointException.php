<?php
/**
 * Exception for experimental endpoints.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Exceptions
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Exceptions;

use Exception;
use WP_Error;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase, Generic.CodeAnalysis.UselessOverridingMethod.Found

/**
 * Exception for experimental endpoints.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Exceptions
 */
class ExperimentalEndpointException extends Exception {
	/**
	 * Returns the WP_Error object for the exception.
	 *
	 * @since 6.9.0
	 *
	 * @return WP_Error
	 */
	public function to_wp_error(): WP_Error {
		return new WP_Error( 'missing_experimental_endpoint_acknowledgement', $this->getMessage(), [ 'status' => 400 ] );
	}
}
