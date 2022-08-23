<?php
/**
 * Class to manage Zapier api.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

use TEC\Common\Traits\With_AJAX;
use Tribe__Utils__Array as Arr;
use WP_User_Query;

/**
 * Class Api
 *
 * @since TBD
 *
 * @package TEC\Common\Zapier
 */
class Api extends Abstract_API_Key_Api {
	use With_AJAX;

	/**
	 * {@inheritDoc}
	 */
	public static $api_name = 'Zapier';

	/**
	 * {@inheritDoc}
	 */
	public static $api_id = 'zapier';

	/**
	 * An instance of the Template_Modifications.
	 *
	 * @since TBD
	 *
	 * @var Template_Modifications
	 */
	protected $template_modifications;

	/**
	 * The Actions name handler.
	 *
	 * @since TBD
	 *
	 * @var Actions
	 */
	protected $actions;

	/**
	 * API constructor.
	 *
	 * @since TBD
	 *
	 * @param Actions                $actions An instance of the Actions name handler.
	 * @param Template_Modifications $actions An instance of the Template_Modifications.
	 */
	public function __construct( Actions $actions, Template_Modifications $template_modifications ) {
		$this->actions                = $actions;
		$this->template_modifications = $template_modifications;
	}

	/**
	 * Get a random hash.
	 *
	 * @since  TBD
	 *
	 * @param string A optional prefix to the random hash.
	 *
	 * @return string A random hash.
	 */
	public function get_random_hash( $prefix = '' ) {
		if ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
			$hash = bin2hex( openssl_random_pseudo_bytes( 20 ) );
		}

		if ( ! empty( $hash ) ) {
			return $prefix . $hash;
		}

		// Fallback hash if openssl_random_pseudo_bytes returns empty.
		return $prefix . sha1( wp_rand() );
	}

	/**
	 * Add a Zapier API Key fields using ajax.
	 *
	 * @since TBD
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure and the html of the API Key fields.
	 */
	public function ajax_add_api_key( $nonce ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$add_aki_key_action, $nonce ) ) {
			return false;
		}

		$message = _x(
			'Zapier API Key fields added. Add a description, choose a user, and generate a key pair to save it.',
			'Zapier API Key new fields are added message.',
			'tribe-common'
		);
		$this->template_modifications->get_settings_message_template( $message );

		$users_dropdown = $this->get_users_dropdown();

		// Add empty fields template
		$this->template_modifications->get_api_key_fields(
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
	 * Generate a Zapier API Key pair.
	 *
	 * @since TBD
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure and the html of the API Key fields.
	 */
	public function ajax_generate_api_key_pair( $nonce ) {
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
			$this->template_modifications->get_settings_message_template( $message, 'error' );

			wp_die();
		}

		$name = tribe_get_request_var( 'name' );
		if ( empty( $name ) ) {
			$message = _x(
				'Zapier API Key pair missing a name.',
				'Zapier API Key pair missing a name message.',
				'tribe-common'
			);
			$this->template_modifications->get_settings_message_template( $message, 'error' );

			wp_die();
		}

		$user_id = tribe_get_request_var( 'user_id' );
		if ( empty( $user_id ) ) {
			$message = _x(
				'Zapier API Key pair missing a user id.',
				'Zapier API Key pair missing a user message.',
				'tribe-common'
			);
			$this->template_modifications->get_settings_message_template( $message, 'error' );

			wp_die();
		}

		$permissions = tribe_get_request_var( 'permissions' );
		if ( empty( $permissions ) ) {
			$message = _x(
				'Zapier API Key pair missing permissions.',
				'Zapier API Key pair missing permissions message.',
				'tribe-common'
			);
			$this->template_modifications->get_settings_message_template( $message, 'error' );

			wp_die();
		}

		$message = _x(
			'Zapier API Key pair generated.',
			'Zapier API Key pair generated message.',
			'tribe-common'
		);
		$this->template_modifications->get_settings_message_template( $message );

		$api_key_data = [
			'consumer_id'     => $consumer_id,
			'consumer_secret' => $this->get_random_hash( 'ck_' ),
			'has_pair'        => true,
			'name'            => $name,
			'permissions'     => $permissions,
			'user_id'         => $user_id,
		];

		$this->set_account_by_id( $api_key_data );

		// Add empty fields template
		$this->template_modifications->get_api_key_fields(
			$this,
			$consumer_id,
			$api_key_data,
			[],
			'generated'
		);

		wp_die();
	}

	/**
	 * Get the confirmation text for deleting an account.
	 *
	 * @since TBD
	 *
	 * @return string The confirmation text.
	 */
	public static function get_confirmation_to_revoke_api_key() {
		return sprintf(
			_x(
				'Are you sure you want to revoke this Zapier API Key pair? This operation cannot be undone. Existing Zapier connections tied to this API Key will no longer work.',
				'The message to display to confirm a user would like to revoke a Zapier API Key pair.',
				'the-events-calendars'
			),
		);
	}

	/**
	 * Handles the request to revoke a Zapier API key pair.
	 *
	 * @since TBD
	 *
	 * @param string|null $nonce The nonce that should accompany the request.
	 *
	 * @return bool Whether the request was handled or not.
	 */
	public function ajax_revoke( $nonce = null ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$revoke_action, $nonce ) ) {
			return false;
		}

		$consumer_id = tribe_get_request_var( 'consumer_id' );
		$account    = $this->get_account_by_id( $consumer_id );
		// If no consumer id found, fail the request.
		if ( empty( $consumer_id ) || empty( $account ) ) {
			$error_message = _x(
				'Zapier API Key pair was not revoked, the consumer id or the account were not found.',
				'Zapier API Key pair is missing information to revoke failure message.',
				'the-events-calendar'
			);

			$this->template_modifications->get_settings_message_template( $error_message, 'error' );

			wp_die();
		}

		$success = $this->delete_account_by_id( $consumer_id );
		if ( $success ) {
			$message = _x(
				'Zapier API Key pair was successfully revoked',
				'Zapier API Key pair has been revoked success message.',
				'the-events-calendar'
			);

			$this->template_modifications->get_settings_message_template( $message );

			wp_die();
		}

		$error_message = _x(
			'Zapier API Key pair was not revoked',
			'Zapier API Key pair could not be revoked failure message.',
			'the-events-calendar'
		);

		$this->template_modifications->get_settings_message_template( $error_message, 'error' );

		wp_die();
	}

	/**
	 * Get the WP_User_Query query results.
	 *
	 * @since TBD
	 *
	 * @return array<string|mixed> An array of user objects.
	 */
	public function get_users() {
		$args = [
		    'number' => 1000,
		    'role__in' => [ 'Administrator', 'Editor' ],
		];

		/**
		 * Filters the argument array to query users for the API Key fields user dropdown.
		 *
		 * @since TBD
		 *
		 * @param array<string|mixed> The default array to query users for the API Key fields user dropdown.
		 */
		$args = apply_filters( 'tec_common_zapier_api_get_user_arguments', $args );

		// Custom query.
		$my_user_query = new WP_User_Query( $args );

		return $my_user_query->results;
	}

	/**
	 * Get a user's information formatted for internal use.
	 *
	 * @since TBD
	 *
	 * @param array<string|mixed> $user Information for a user from an API,
	 *
	 * @return array<string|mixed> An array of a user's information formatted for internal use.
	 */
	protected function get_formatted_user_info( $user ) {
		$user_data              = $user->data;
		$user_info              = [];
		$user_info['name']      = Arr::get( $user_data, 'user_login', '' );
		$user_info['id']        = Arr::get( $user_data, 'ID', '' );
		$user_info['email']     = Arr::get( $user_data, 'user_email', '' );

		return $user_info;
	}

	/**
	 * Get list of hosts formatted for options dropdown.
	 *
	 * @since TBD
	 *
	 * @return array<string,mixed>  An array of WordPress Users to use for API Key pairs.
	 */
	public function get_formatted_hosts_list() {
		$available_users = $this->get_users();
		if ( empty( $available_users ) ) {
			return [];
		}

		$hosts = [];
		foreach ( $available_users as $user ) {
			$user_info = $this->get_formatted_user_info( $user );

			if (
				empty( $user_info['name'] ) ||
				empty( $user_info['id'] ) ||
				empty( $user_info['email'] )
			) {
				continue;
			}

			$hosts[] = [
				'text'             => (string) trim( $user_info['name'] ),
				'sort'             => (string) trim( $user_info['name'] ),
				'id'               => (string) $user_info['id'],
				'value'            => (string) $user_info['id'],
			];
		}

		// Sort the hosts array by text(email).
		$sort_arr = array_column( $hosts, 'sort' );
		array_multisort( $sort_arr, SORT_ASC, $hosts );

		return $hosts;
	}

	/**
	 * Get users dropdown fields for a tribe dropdown input.
	 *
	 * @since TBD
	 *
	 * @return array<string,mixed>  An array of WordPress Users to use in a tribe dropdown input.
	 */
	public function get_users_dropdown() {
		$users = $this->get_formatted_hosts_list();

		$users_dropdown = [
			'label'          => _x(
				'Users',
				'The label of the users dropdown for Zapier.',
				'tribe-common'
			),
			'id'             => 'tec-settings-zapier-users',
			'classes_select' => [ 'tec-settings__users-dropdown', 'tec-settings-zapier-details-api-key__users-dropdown' ],
			'name'           => "tec_common_zapier[]['users']",
			'selected'       => '',
			'hosts_count'    => count( $users ),
			'hosts_arr'      => $users,
			'attrs'          => [
				'placeholder'       => _x(
				    'Select a User',
				    'The placeholder for the dropdown to select a user.',
				    'tribe-common'
				),
				'data-prevent-clear' => true,
				'data-force-search'  => true,
				'data-options'       => json_encode( $users ),
			],
		];

		return $users_dropdown;
	}
}
