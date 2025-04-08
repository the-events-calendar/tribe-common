<?php
/**
 * Contract for Wizard step processors..
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Onboarding\Steps
 */

namespace TEC\Common\Admin\Onboarding\Steps\Contracts;

use WP_REST_Response;
use WP_REST_Request;

/**
 * Class Step_Interface
 *
 * @todo Move to common.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Onboarding\Steps
 */
interface Step_Interface {
	/**
	 * Handles extracting and processing the pertinent data
	 * for this step from the wizard request.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param WP_REST_Request  $request  The request object.
	 *
	 * @return WP_REST_Response
	 */
	public static function handle( $response, $request ): WP_REST_Response;

	/**
	 * Process the step data.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request  $response The request object.
	 * @param WP_REST_Response $request The response to be altered and returned.
	 *
	 * @return WP_REST_Response
	 */
	public static function process( $response, $request ): WP_REST_Response;
}
