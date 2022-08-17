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
class Api extends Abstract_API_Keys{
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
	 * Hash the specified data.
	 *
	 * @since TBD
	 *
	 * @param string $data Message to be hashed.
	 *
	 * @return string Hashed data.
	 */
	public static function api_hash( $data ) {
		return hash_hmac( 'sha256', $data, 'tec-common-zapier' );
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
		tribe( Template_Modifications::class )->get_settings_message_template( $message );

		$users_dropdown = $this->get_users_dropdown();

		// Add empty fields template
		tribe( Template_Modifications::class )->get_api_key_fields(
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

		$local_id = tribe_get_request_var( 'local_id' );
		if ( empty( $local_id ) ) {
			$message = _x(
				'Zapier API Key pair missing the local id.',
				'Zapier API Key pair missing local id message.',
				'tribe-common'
			);
			tribe( Template_Modifications::class )->get_settings_message_template( $message, 'error' );

			wp_die();
		}

		$name = tribe_get_request_var( 'name' );
		if ( empty( $name ) ) {
			$message = _x(
				'Zapier API Key pair missing a name.',
				'Zapier API Key pair missing a name message.',
				'tribe-common'
			);
			tribe( Template_Modifications::class )->get_settings_message_template( $message, 'error' );

			wp_die();
		}

		$user_id = tribe_get_request_var( 'user_id' );
		if ( empty( $user_id ) ) {
			$message = _x(
				'Zapier API Key pair missing a user id.',
				'Zapier API Key pair missing a user message.',
				'tribe-common'
			);
			tribe( Template_Modifications::class )->get_settings_message_template( $message, 'error' );

			wp_die();
		}

		$permissions = tribe_get_request_var( 'permissions' );
		if ( empty( $permissions ) ) {
			$message = _x(
				'Zapier API Key pair missing permissions.',
				'Zapier API Key pair missing permissions message.',
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
		//@todo - load fields template with key pair template
		//@todo - save the API Key fields
		//@todo - load saved API Key fields

		$api_key = [
			'consumer_id'     => $local_id,
			'consumer_secret' => $this->get_random_hash( 'ck_' ),
			'has_pair'        => true,
			'name'            => $name,
			'permissions'     => $permissions,
			'user_id'         => $user_id,
		];

		// Add empty fields template
		tribe( Template_Modifications::class )->get_api_key_fields(
			$local_id,
			$api_key,
			[],
			'generated'
		);

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

	/**
	 * Basic Authentication.
	 *
	 * SSL-encrypted requests are not subject to sniffing or man-in-the-middle
	 * attacks, so the request can be authenticated by simply looking up the user
	 * associated with the given consumer key and confirming the consumer secret
	 * provided is valid.
	 *
	 * @since 2.4-beta-1
	 *
	 * @return int|bool Returs the authenticated user's User ID if successfull. Otherwise, returns false.
	 */
	private function perform_basic_authentication() {
/*		$this->log_debug( __METHOD__ . '(): Running.' );

		$this->auth_method = 'basic_auth';
		$consumer_key      = '';
		$consumer_secret   = '';

		// If the $_GET parameters are present, use those first.
		if ( ! empty( $_GET['consumer_key'] ) && ! empty( $_GET['consumer_secret'] ) ) {
			$consumer_key    = $_GET['consumer_key']; // WPCS: sanitization ok.
			$consumer_secret = $_GET['consumer_secret']; // WPCS: sanitization ok.
		}

		// If the above is not present, we will do full basic auth.
		if ( ! $consumer_key && ! empty( $_SERVER['PHP_AUTH_USER'] ) && ! empty( $_SERVER['PHP_AUTH_PW'] ) ) {
			$consumer_key    = $_SERVER['PHP_AUTH_USER']; // WPCS: sanitization ok.
			$consumer_secret = $_SERVER['PHP_AUTH_PW']; // WPCS: sanitization ok.
		}

		// Stop if don't have any key.
		if ( ! $consumer_key || ! $consumer_secret ) {
			$this->log_error( __METHOD__ . '(): Aborting; credentials not found.' );

			return false;
		}

		// Get user data.
		$user = $this->get_user_data_by_consumer_key( $consumer_key );
		if ( empty( $user ) ) {
			$this->log_error( __METHOD__ . '(): Aborting; user not found.' );

			return false;
		}

		// Validate user secret.
		if ( ! hash_equals( $user->consumer_secret, $consumer_secret ) ) {
			$this->set_error( new WP_Error( 'gform_rest_authentication_error', __( 'Consumer secret is invalid.', 'gravityforms' ), array( 'status' => 401 ) ) );

			return false;
		}

		$this->log_debug( __METHOD__ . '(): Valid.' );

		return $this->set_user( $user );*/
	}
}
