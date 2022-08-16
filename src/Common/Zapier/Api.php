<?php
/**
 * Class to manage Zapier api.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

use WP_User_Query;
use Tribe__Utils__Array as Arr;
use TEC\Common\Traits\With_AJAX;

/**
 * Class Api
 *
 * @since TBD
 *
 * @package TEC\Common\Zapier
 */
class Api {
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
	 * @param Actions $actions An instance of the Actions name handler.
	 */
	public function __construct( Actions $actions ) {
		$this->actions = $actions;
	}

	public function get_list_of_keys() {
		return [];
	}

	/**
	 * Get a unique Id.
	 *
	 * @since TBD
	 *
	 * @return string A unique id to use as the local id.
	 */
	public function get_unique_id() {
		return uniqid();
	}

	/**
	 * Add a Zapier API Key fields using ajax.
	 *
	 * @since TBD
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure and the html of the api key fields.
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
		tribe( Template_Modifications::class )->get_settings_message_template( $message );

		$users_dropdown = $this->get_users_dropdown();

		// Add empty fields template
		tribe( Template_Modifications::class )->get_api_key_fields(
			$this->get_unique_id(),
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
	 * @return string An html message for success or failure and the html of the api key fields.
	 */
	public function ajax_generate_api_key_pair( $nonce ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$generate_action, $nonce ) ) {
			return false;
		}

		//@todo - detect if correct information is here
		/**
		 * 					local_id: localId,
		 					name: integrationName,
		 					user: intergrationUser,
		 					permissions: permissions,
		 */
		$local_id = tribe_get_request_var( 'local_id' );
		// If no local id found, fail the request.
		if ( empty( $local_id ) ) {
			$message = _x(
				'Zapier API Key pair missing local id.',
				'Zapier API Key pair missing local id message.',
				'tribe-common'
			);
			tribe( Template_Modifications::class )->get_settings_message_template( $message, 'error' );

			wp_die();
		}


		$message = _x(
			'Zapier API Key pair generated.',
			'Zapier API Key pair generated message.',
			'tribe-common'
		);
		tribe( Template_Modifications::class )->get_settings_message_template( $message );

		//@todo - generate the keys
		//@todo - save the api key fields
		//@todo - load fields template with key pair template.

		// Add empty fields template
/*		tribe( Template_Modifications::class )->get_api_key_fields(
			$this->get_unique_id(),
			[
				'name'         => '',
				'api_key'      => '',
			],
			$users_dropdown
		);*/

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
		$args = array(
		    'number' => 1000,
		    'role__in' => [ 'Administrator', 'Editor' ],
		);

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
			'classes_select' => [ 'tec-settings__users-dropdown', 'tec-settings-zapier__users-dropdown' ],
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
