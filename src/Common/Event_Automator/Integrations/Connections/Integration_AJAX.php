<?php
/**
 * Abstract Class to Manage Integration Connections AJAX.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */

namespace TEC\Event_Automator\Integrations\Connections;

use TEC\Event_Automator\Traits\Last_Access;
use TEC\Event_Automator\Traits\With_AJAX;

/**
 * Class Integration_AJAX
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */
abstract class Integration_AJAX extends Integration_Connections {
	use With_AJAX;
	use Last_Access;

	/**
	 * Add Connection fields using ajax.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure and the html of the connection fields.
	 */
	public function ajax_add_connection( $nonce ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$add_connection, $nonce ) ) {
			return false;
		}

		$users_dropdown = $this->get_users_dropdown();

		// Add empty fields template
		$this->template_modifications->get_connection_fields(
			$this,
			$this->get_random_hash( 'ci_' ),
			[
				'name'         => '',
				'api_key'      => '',
			],
			$users_dropdown
		);

		wp_die();
	}

	/**
	 * Create Connection Access.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure of generating connection access.
	 */
	public function ajax_create_connection_access( $nonce ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$create_access, $nonce ) ) {
			return false;
		}

		$consumer_id = tribe_get_request_var( 'consumer_id' );
		if ( empty( $consumer_id ) ) {
			$message = _x(
				'Connection is missing the local id.',
				'Connection is missing local id message.',
				'tribe-common'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$name = tribe_get_request_var( 'name' );
		if ( empty( $name ) ) {
			$message = _x(
				'Connection is missing a name.',
				'Connection is missing a name message.',
				'tribe-common'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$user_id = tribe_get_request_var( 'user_id' );
		if ( empty( $user_id ) ) {
			$message = _x(
				'Connection is missing a user id.',
				'Connection is missing a user message.',
				'tribe-common'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$permissions = tribe_get_request_var( 'permissions' );
		if ( empty( $permissions ) ) {
			$message = _x(
				'Connection is missing permissions.',
				'Connection is missing permissions message.',
				'tribe-common'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$this->save_connection( $consumer_id, $name, $user_id, $permissions );

		wp_die();
	}

	/**
	 * Save the Connection for an integration.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The consumer id.
	 * @param string $name        The name of the connection.
	 * @param int    $user_id     The user id of the connection.
	 * @param string $permissions The permissions for the connection.
	 *
	 * @return string An html message for success or failure of generating connection access.
	 */
	abstract public function save_connection( $consumer_id, $name, $user_id, $permissions );

	/**
	 * Get the confirmation text for deleting an api_key.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The confirmation text.
	 */
	abstract public static function get_confirmation_to_delete_connection();

	/**
	 * Handles the request to delete an integration connection.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string|null $nonce The nonce that should accompany the request.
	 *
	 * @return bool Whether the request was handled or not.
	 */
	public function ajax_delete_connection( $nonce = null ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$delete_connection, $nonce ) ) {
			return false;
		}

		$consumer_id = tribe_get_request_var( 'consumer_id' );
		$api_key     = $this->get_api_key_by_id( $consumer_id );
		// If no consumer id found, fail the request.
		if ( empty( $consumer_id ) || empty( $api_key ) ) {
			$error_message = _x(
				'Connection was not deleted, the consumer id or the API Key information were not found.',
				'Connection is missing information to delete failure message.',
				'tribe-common'
			);

			$this->template_modifications->print_settings_message_template( $error_message, 'error' );

			wp_die();
		}

		$success = $this->delete_api_key_by_id( $consumer_id );
		if ( $success ) {
			$message = _x(
				'Connection was successfully deleted',
				'Connection deleted success message.',
				'tribe-common'
			);

			$this->template_modifications->print_settings_message_template( $message );

			wp_die();
		}

		$error_message = _x(
			'Connection was not deleted',
			'Connection could not be deleted failure message.',
			'tribe-common'
		);

		$this->template_modifications->print_settings_message_template( $error_message, 'error' );

		wp_die();
	}
}
