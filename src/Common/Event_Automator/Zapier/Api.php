<?php
/**
 * Class to manage Zapier api.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier
 */

namespace TEC\Event_Automator\Zapier;

use TEC\Event_Automator\Integrations\Connections\Integration_AJAX;

/**
 * Class Api
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @since 6.0.0 Migrated to Common from Event Automator - Utilize Integration_AJAX and Integration_Connections to share coding among integrations.
 *
 * @package TEC\Event_Automator\Zapier
 */
class Api extends Integration_AJAX {

	/**
	 * @inheritdoc
	 */
	public static $api_name = 'Zapier';

	/**
	 * @inheritdoc
	 */
	public static $api_id = 'zapier';

	/**
	 * @inerhitDoc
	 */
	protected $all_api_keys_key = 'tec_zapier_api_keys';

	/**
	 * @inheritDoc
	 */
	protected $single_api_key_prefix = 'tec_zapier_api_key_';

	/**
	 * @inheritDoc
	 */
	protected static $api_secret_key = 'tec_automator_zapier_secret_key';

	/**
	 * API constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Actions                $actions An instance of the Actions name handler.
	 * @param Template_Modifications $actions An instance of the Template_Modifications.
	 */
	public function __construct( Actions $actions, Template_Modifications $template_modifications ) {
		$this->actions                = $actions;
		$this->template_modifications = $template_modifications;

		// Setup API secret key for integration.
		$this->setup_api_secret();
	}

	/**
	 * @inheritDoc
	 */
	public function save_connection( $consumer_id, $name, $user_id, $permissions ) {
		$message = _x(
			'Zapier API Key Connection generated.',
			'Zapier API Key Connection generated message.',
			'tribe-common'
		);
		$this->template_modifications->print_settings_message_template( $message );

		$api_key_data = [
			'consumer_id'     => $consumer_id,
			'consumer_secret' => $this->get_random_hash( 'ck_' ),
			'has_pair'        => true,
			'name'            => esc_html( stripslashes( $name ) ),
			'permissions'     => $permissions,
			'last_access'     => '-',
			'user_id'         => $user_id,
		];

		$this->set_api_key_by_id( $api_key_data );

		// Add empty fields template
		$this->template_modifications->get_connection_fields(
			$this,
			$consumer_id,
			$api_key_data,
			[],
			'saved'
		);

		return;
	}

	/**
	 * @inheritDoc
	 */
	public static function get_confirmation_to_delete_connection() {
		return sprintf(
			_x(
				'Are you sure you want to revoke this Zapier connection? This operation cannot be undone. Existing Zapier connections tied will no longer work.',
				'The message to display to confirm a user would like to revoke a Zapier connection.',
				'tribe-common',
				'tribe-common'
			),
		);
	}

	/**
	 * Generate a Zapier API Key pair.
	 * @deprecated 1.4.0 - use ajax_generate_connection_access();
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure and the html of the API Key fields.
	 */
	public function ajax_generate_api_key_pair( $nonce ) {
		_deprecated_function( __METHOD__, '1.4.0', 'ajax_generate_connection_access()');

		if ( ! $this->check_ajax_nonce( $this->actions::$generate_action, $nonce ) ) {
			return false;
		}

		$consumer_id = tribe_get_request_var( 'consumer_id' );
		if ( empty( $consumer_id ) ) {
			$message = _x(
				'Zapier API Key pair missing the local id.',
				'Zapier API Key pair missing local id message.',
				'tribe-common'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$name = tribe_get_request_var( 'name' );
		if ( empty( $name ) ) {
			$message = _x(
				'Zapier API Key pair missing a name.',
				'Zapier API Key pair missing a name message.',
				'tribe-common'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$user_id = tribe_get_request_var( 'user_id' );
		if ( empty( $user_id ) ) {
			$message = _x(
				'Zapier API Key pair missing a user id.',
				'Zapier API Key pair missing a user message.',
				'tribe-common'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$permissions = tribe_get_request_var( 'permissions' );
		if ( empty( $permissions ) ) {
			$message = _x(
				'Zapier API Key pair missing permissions.',
				'Zapier API Key pair missing permissions message.',
				'tribe-common'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$message = _x(
			'Zapier API Key pair generated.',
			'Zapier API Key pair generated message.',
			'tribe-common'
		);
		$this->template_modifications->print_settings_message_template( $message );

		$api_key_data = [
			'consumer_id'     => $consumer_id,
			'consumer_secret' => $this->get_random_hash( 'ck_' ),
			'has_pair'        => true,
			'name'            => esc_html( stripslashes( $name ) ),
			'permissions'     => $permissions,
			'last_access'     => '-',
			'user_id'         => $user_id,
		];

		$this->set_api_key_by_id( $api_key_data );

		// Add empty fields template
		$this->template_modifications->get_connection_fields(
			$this,
			$consumer_id,
			$api_key_data,
			[],
			'generated'
		);

		wp_die();
	}

	/**
	 * Get the confirmation text for revoking an api_key.
	 * @deprecated 1.4.0 - Use get_confirmation_to_delete_connection() instead.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The confirmation text.
	 */
	public static function get_confirmation_to_revoke_api_key() {
		_deprecated_function( __METHOD__, '1.4.0', 'get_confirmation_to_delete_connection');

		return sprintf(
			_x(
				'Are you sure you want to revoke this Zapier API Key pair? This operation cannot be undone. Existing Zapier connections tied to this API Key will no longer work.',
				'The message to display to confirm a user would like to revoke a Zapier API Key pair.',
				'tribe-common'
			),
		);
	}

	/**
	 * Handles the request to revoke a Zapier API Key pair.
	 *
	 * @deprecated 1.4.0 - Use ajax_delete_connection() instead.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string|null $nonce The nonce that should accompany the request.
	 *
	 * @return bool Whether the request was handled or not.
	 */
	public function ajax_revoke( $nonce = null ) {
		_deprecated_function( __METHOD__, '1.4.0', 'ajax_delete_connection' );

		if ( ! $this->check_ajax_nonce( $this->actions::$delete_connection, $nonce ) ) {
			return false;
		}

		$consumer_id = tribe_get_request_var( 'consumer_id' );
		$api_key     = $this->get_api_key_by_id( $consumer_id );
		// If no consumer id found, fail the request.
		if ( empty( $consumer_id ) || empty( $api_key ) ) {
			$error_message = _x(
				'Zapier API Key pair was not deleted, the consumer id or the API Key information were not found.',
				'Zapier API Key pair is missing information to delete failure message.',
				'tribe-common'
			);

			$this->template_modifications->print_settings_message_template( $error_message, 'error' );

			wp_die();
		}

		$success = $this->delete_api_key_by_id( $consumer_id );
		if ( $success ) {
			$message = _x(
				'Zapier API Key pair was successfully deleted',
				'Zapier API Key pair has been deleted success message.',
				'tribe-common'
			);

			$this->template_modifications->print_settings_message_template( $message );

			wp_die();
		}

		$error_message = _x(
			'Zapier API Key pair was not deleted',
			'Zapier API Key pair could not be deleted failure message.',
			'tribe-common'
		);

		$this->template_modifications->print_settings_message_template( $error_message, 'error' );

		wp_die();
	}
}
