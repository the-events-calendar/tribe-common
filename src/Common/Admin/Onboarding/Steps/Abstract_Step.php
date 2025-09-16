<?php
/**
 * Abstract step-handler class for the onboarding wizard.
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Admin\Onboarding\Steps
 */

namespace TEC\Common\Admin\Onboarding\Steps;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Abstract_Step
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Admin\Onboarding\Steps
 */
abstract class Abstract_Step implements Contracts\Step_Interface {
	/**
	 * The tab number for this step.
	 *
	 * @since 6.7.0
	 *
	 * @var int
	 */
	public const TAB_NUMBER = 0;

	/**
	 * Passes the request and data to the handler.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param WP_REST_Request  $request  The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function handle( $response, $request ): WP_REST_Response {
		// If it's already an error, bail.
		if ( $response->is_error() ) {
			return $response;
		}

		// Ensure we should be processing this step.
		if ( ! $this->should_process( $request ) ) {
			return $response;
		}

		return $this->process( $response, $request );
	}

	/**
	 * Check if the current tab is one we should be processing.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function should_process( $request ): bool {
		return $this->tab_check( $request );
	}

	/**
	 * Check if the current tab is the one we should be processing.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function tab_check( $request ): bool {
		$params = $request->get_params();
		// If the current tab is less than this tab, we don't need to do anything yet.
		return isset( $params['currentTab'] ) && absint( $params['currentTab'] ) >= static::TAB_NUMBER;
	}

	/**
	 * Add a message to the response.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param string           $message  The message to add.
	 * @param ?int             $status   The status code.
	 *
	 * @return WP_REST_Response
	 */
	public function add_message( $response, $message, ?int $status = null ): WP_REST_Response {
		$data            = $response->get_data();
		$data['message'] = array_merge( (array) $data['message'], [ $message ] );

		$response->set_data( $data );
		if ( $status ) {
			$response->set_status( $status );
		}

		return $response;
	}

	/**
	 * Add a message to the response.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param string           $message  The message to add.
	 *
	 * @return WP_REST_Response
	 */
	public function add_fail_message( $response, $message ): WP_REST_Response {
		return $this->add_message( $response, $message, 500 );
	}

	/**
	 * Process the step.
	 *
	 * @since 6.7.0
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param WP_REST_Request  $request  The request object.
	 *
	 * @return WP_REST_Response
	 */
	abstract public function process( $response, $request ): WP_REST_Response;
}
