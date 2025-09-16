<?php
/**
 * Provides methods to check ajax requests.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits;
 */

namespace TEC\Event_Automator\Traits;

/**
 * Trait With_AJAX
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits;
 */
trait With_AJAX {

	/**
	 * Checks if the current AJAX request is valid and authorized or not.
	 *
	 * In a normal flow, where the AJAX response is not intercepted by an handler, the method will echo an error data
	 * and `die`.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string      $action The action to check the AJAX referer and the nonce against.
	 * @param string|null $nonce  The nonce to check, the `null` value is allowed and will always fail.
	 *
	 * @return bool Whether the AJAX referer and nonce are valid or not.
	 */
	protected function check_ajax_nonce( $action, $nonce = null ) {
		if (
			! check_ajax_referer( $action )
			|| ! wp_verify_nonce( $nonce, $action )
		) {
			wp_send_json_error(
				[
					'status'  => 'fail',
					'code'    => 'invalid-nonce',
					'message' => _x( 'The provided nonce is not valid.', 'Ajax error message.', 'tribe-common' ),
				],
				403
			);

			return false;
		}

		return true;
	}

	/**
	 * Checks the request post ID is set and corresponds to an event.
	 *
	 * While the method will return a boolean value, in the normal flow, where AJAX requests are not intercepted by
	 * handlers, the method will return the failure JSON response and `die`.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int|null $post_id The post ID of the post to check or `null` to use the one from the request variable.
	 *
	 * @return \WP_Post|false Either the event post object, as decorated by the `tribe_get_event` function, or `false`
	 *                        if AJAX responses are handled and the post is not valid.
	 */
	protected function check_ajax_post( $post_id = null ) {
		$post_id = $post_id ? : tribe_get_request_var( 'post_id', false );

		if ( empty( $post_id ) ) {
			$error = _x(
				'The post ID is missing from the request.',
				'An error raised in the context of an API integration.',
				'tribe-common'
			);

			wp_send_json_error(
				[
					'status'  => 'fail',
					'code'    => 'missing-post-id',
					'message' => $error,
				],
				400
			);

			return false;
		}

		$event = tribe_get_event( $post_id );

		if ( ! $event instanceof \WP_Post ) {
			wp_send_json_error(
				[
					'status' => 'fail',
					'code'   => 'event-not-found',
				],
				404
			);

			return false;
		}

		return $event;
	}
}
