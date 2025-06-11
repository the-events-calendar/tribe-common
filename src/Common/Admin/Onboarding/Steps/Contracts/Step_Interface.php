<?php
/**
 * Contract for Wizard step processors..
 *
 * @since 6.7.0
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
 * @since 6.7.0
 *
 * @package TEC\Common\Admin\Onboarding\Steps
 */
interface Step_Interface {
	/**
	 * Handles extracting and processing the pertinent data
	 * for this step from the wizard request.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param WP_REST_Request  $request  The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function handle( $response, $request ): WP_REST_Response;

	/**
	 * Process the step data.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Request  $response The request object.
	 * @param WP_REST_Response $request The response to be altered and returned.
	 *
	 * @return WP_REST_Response
	 */
	public function process( $response, $request ): WP_REST_Response;
}
