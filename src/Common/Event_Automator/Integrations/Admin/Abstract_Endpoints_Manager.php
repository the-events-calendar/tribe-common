<?php
/**
 * Abstract Class to manage endpoints.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Admin
 */

namespace TEC\Event_Automator\Integrations\Admin;

use TEC\Event_Automator\Integrations\REST\V1\Interfaces\REST_Endpoint_Interface;
use TEC\Event_Automator\Traits\With_AJAX;

/**
 * Class Abstract_Endpoints_Manager
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Admin
 */
abstract class Abstract_Endpoints_Manager {

	use With_AJAX;

	/**
	 * The name of the API
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public static $api_name;

	/**
	 * The id of the API
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public static $api_id;

	/**
	 * An instance of the Template_Modifications.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Template_Modifications
	 */
	protected $template_modifications;

	/**
	 * The Actions name handler.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Actions
	 */
	protected $actions;

	/**
	 * Endpoints.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var array<string|mixed> An array of Endpoints.
	 */
	protected $endpoints;

	/**
	 * Get an endpoint by id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $endpoint_id The id of an endpoint.
	 * @param string $type An optional
	 *
	 * @return bool|REST_Endpoint_Interface An endpoint instance or boolean if not found.
	 */
	public function get_endpoint( string $endpoint_id, string $type = '' ) {
		$endpoint_details = wp_list_filter( $this->endpoints, [
			'id' => $endpoint_id
		] );

		$endpoint_class = array_key_first( $endpoint_details );
		if ( empty( $endpoint_class ) ) {
			return false;
		}

		$endpoint = tribe( $endpoint_class );
		if ( empty( $endpoint->get_endpoint_type() ) ) {
			return false;
		}

		if ( $type && $endpoint->get_endpoint_type() !== $type ) {
			return false;
		}

		return $endpoint;
	}

	/**
	 * Clear the provided endpoint queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param REST_Endpoint_Interface $endpoint An endpoint instance.
	 *
	 * @return bool Whether the queue is cleared.
	 */
	protected function clear_endpoint( REST_Endpoint_Interface $endpoint ): bool {
		if ( empty( $endpoint->trigger ) ) {
			return false;
		}

		$endpoint->trigger->set_queue( [] );

		return true;
	}

	/**
	 * Disable the provided endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param REST_Endpoint_Interface $endpoint An endpoint instance.
	 *
	 * @return bool Whether the endpoint is disable.
	 */
	protected function disable_endpoint( REST_Endpoint_Interface $endpoint ): bool {
		$endpoint->set_endpoint_enabled( false );
		$this->clear_endpoint( $endpoint );
		$endpoint->clear_endpoint_last_access();

		return true;
	}

	/**
	 * Enable the provided endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param REST_Endpoint_Interface $endpoint An endpoint instance.
	 *
	 * @return bool Whether the endpoint is enabled.
	 */
	protected function enable_endpoint( REST_Endpoint_Interface $endpoint ): bool {
		$endpoint->set_endpoint_enabled( true );

		return true;
	}

	/**
	 * Handles clearing an endpoint queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string|null $nonce The nonce that should accompany the request.
	 *
	 * @return bool|string Whether the request was handled or html for messages and a endpoint row.
	 */
	public function ajax_clear( $nonce = null ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$clear_action, $nonce ) ) {
			return false;
		}

		$endpoint_id = tribe_get_request_var( 'endpoint_id' );
		// If no endpoint id found, fail the request.
		if ( empty( $endpoint_id ) ) {
			$error_message = _x( 'Endpoint was not cleared, the endpoint id was not found.', 'Endpoint id is missing information to clear it.', 'tribe-common' );

			$this->template_modifications->print_settings_message_template( $error_message, 'error' );

			wp_die( $error_message );
		}

		$endpoint = $this->get_endpoint( $endpoint_id, 'queue' );
		if ( empty( $endpoint ) ) {
			$message = _x( 'Endpoint was not cleared as it could not be loaded.', 'Endpoint was not loaded failure message.', 'tribe-common' );

			$this->template_modifications->print_settings_message_template( $message );

			wp_die( $message );
		}

		$success = $this->clear_endpoint( $endpoint );
		if ( $success ) {
			$message = _x( 'Endpoint was successfully cleared.', 'Endpoint has been cleared success message.', 'tribe-common' );

			$endpoint_details = $endpoint->get_endpoint_details();
			$this->template_modifications->print_settings_message_template( $message );
			$this->template_modifications->print_endpoint_row( $endpoint_details, $this );

			wp_die( $message );
		}

		$error_message = _x( 'Endpoint was not cleared.', 'was not cleared failure message.', 'tribe-common' );

		$this->template_modifications->print_settings_message_template( $error_message, 'error' );

		wp_die( $error_message );
	}

	/**
	 * Handles disabling an endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string|null $nonce The nonce that should accompany the request.
	 *
	 * @return bool|string Whether the request was handled or html for messages and a endpoint row.
	 */
	public function ajax_disable( $nonce = null ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$disable_action, $nonce ) ) {
			return false;
		}

		$endpoint_id = tribe_get_request_var( 'endpoint_id' );
		// If no endpoint id found, fail the request.
		if ( empty( $endpoint_id ) ) {
			$error_message = _x( 'Endpoint was not disabled, the endpoint id was not found.', 'Endpoint id is missing information to disable it.', 'tribe-common' );

			$this->template_modifications->print_settings_message_template( $error_message, 'error' );

			wp_die( $error_message );
		}

		$endpoint = $this->get_endpoint( $endpoint_id );
		if ( empty( $endpoint ) ) {
			$message = _x( 'Endpoint was not disabled as it could not be loaded.', 'Endpoint was not loaded failure message.', 'tribe-common' );

			$this->template_modifications->print_settings_message_template( $message );

			wp_die( $message );
		}

		$success = $this->disable_endpoint( $endpoint );
		if ( $success ) {
			$message = _x( 'Endpoint was successfully disabled.', 'Endpoint has been disabled success message.', 'tribe-common' );

			$endpoint_details = $endpoint->get_endpoint_details();
			$this->template_modifications->print_settings_message_template( $message );
			$this->template_modifications->print_endpoint_row( $endpoint_details, $this );

			wp_die( $message );
		}

		$error_message = _x( 'Endpoint was not disabled', 'endpoint could not be enabled it error message.', 'tribe-common' );

		$this->template_modifications->print_settings_message_template( $error_message, 'error' );

		wp_die( $error_message );
	}

	/**
	 * Handles enabling an endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string|null $nonce The nonce that should accompany the request.
	 *
	 * @return bool|string Whether the request was handled or html for messages and a endpoint row.
	 */
	public function ajax_enable( $nonce = null ) {
		if (!  $this->check_ajax_nonce( $this->actions::$enable_action, $nonce ) ) {
			return false;
		}

		$endpoint_id = tribe_get_request_var( 'endpoint_id' );
		// If no endpoint id found, fail the request.
		if ( empty( $endpoint_id ) ) {
			$error_message = _x( 'Endpoint was not enabled, the endpoint id was not found.', 'endpoint id is missing information to enable it.', 'tribe-common' );

			$this->template_modifications->print_settings_message_template( $error_message, 'error' );

			wp_die( $error_message );
		}

		$endpoint = $this->get_endpoint( $endpoint_id );
		if ( empty( $endpoint ) ) {
			$message = _x( 'Endpoint was not loaded.', 'Endpoint was not loaded failure message.', 'tribe-common' );

			$this->template_modifications->print_settings_message_template( $message );

			wp_die( $message );
		}

		$success = $this->enable_endpoint( $endpoint );
		if ( $success ) {
			$message = _x( 'Endpoint was successfully enabled', 'Endpoint has been enabled success message.', 'tribe-common' );

			$endpoint_details = $endpoint->get_endpoint_details();
			$this->template_modifications->print_settings_message_template( $message );
			$this->template_modifications->print_endpoint_row( $endpoint_details, $this );

			wp_die( $message );
		}

		$error_message = _x( 'Endpoint was not enabled', 'endpoint could not be enabled it error message.', 'tribe-common' );

		$this->template_modifications->print_settings_message_template( $error_message, 'error' );

		wp_die( $error_message );
	}

	/**
	 * Get the confirmation text for clearing an endpoint queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The confirmation text.
	 */
	public static function get_confirmation_to_clear_endpoint_queue() : string {
		return sprintf(
			_x(
				'Are you sure you want to clear this Endpoint queue? This operation cannot be undone.',
				'The message to display to confirm a user would like to clear a endpoint queue.',
				'tribe-common'
			),
		);
	}

	/**
	 * Get the confirmation text for disabling an endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @$type string An optional type of disable message to display. Default is queue.
	 *
	 * @return string The confirmation text.
	 */
	public static function get_confirmation_to_disable_endpoint( string $type = 'queue' ) : string {
		if ( $type === 'authorize' ) {
			return sprintf(
				_x(
					'Are you sure you want to disable this Endpoint? This action will prevent this integration from being able to create an access token.',
					'The message to display to confirm a user would like to disable an authorize endpoint.',
					'tribe-common'
				),
			);
		}

		return sprintf(
			_x(
				'Are you sure you want to disable this Endpoint? This action will clear the queue and the last access. This operation cannot be undone.',
				'The message to display to confirm a user would like to disable a queue endpoint.',
				'tribe-common'
			),
		);
	}

	/**
	 * Get the confirmation text for enabling a endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The confirmation text.
	 */
	public static function get_confirmation_to_enable_endpoint() : string {
		return sprintf(
			_x(
				'Are you sure you want to enable this Endpoint?',
				'The message to display to confirm a user would like to enable an endpoint.',
				'tribe-common'
			),
		);
	}
}
