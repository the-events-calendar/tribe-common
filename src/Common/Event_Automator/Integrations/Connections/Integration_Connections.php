<?php
/**
 * Abstract Class to Manage Connections to an Integration.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */

namespace TEC\Event_Automator\Integrations\Connections;

use TEC\Event_Automator\Traits\Last_Access;
use TEC\Event_Automator\Traits\With_AJAX;
use WP_Error;
use WP_User;
use WP_User_Query;
use Tribe__Utils__Array as Arr;
use Exception;
use TEC\Common\Firebase\JWT\JWT;
use TEC\Common\Firebase\JWT\Key;

/**
 * Class Integration_Connections
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Connections
 */
abstract class Integration_Connections {
	use With_AJAX;
	use Last_Access;

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
	 * The API secret used for JWT tokens.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $api_secret;

	/**
	 * The API secret key used to store it.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $api_secret_key;

	/**
	 * Whether an api_key has been loaded for the API to use.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var boolean
	 */
	protected $api_key_loaded = false;

	/**
	 * The name of the loaded api_key.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public $loaded_api_key_name = '';

	/**
	 * The key to get the option with a list of all API Keys.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $all_api_keys_key;

	/**
	 * The prefix to save all single API Key with.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $single_api_key_prefix;

	/**
	 * The hashed consumer id of the api key loaded.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $consumer_id = '';

	/**
	 * The consumer secret of the api key loaded.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $consumer_secret = '';

	/**
	 * The permissions the API Key pair has access to.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $permissions = 'read';

	/**
	 * The last access of the API Key pair.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $last_access = '';

	/**
	 * The WordPress user id of the loaded api_key.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var integer
	 */
	protected $user_id = 0;

	/**
	 * The WP_User object.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var WP_User
	 */
	protected $user;

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
	 * Checks whether the current API is ready to use.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return bool Whether the current API has a loaded api_key.
	 */
	public function is_ready() {
		return ! empty( $this->api_key_loaded );
	}

	/**
	 * Get the id of the API .
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The id of the API.
	 */
	public static function get_api_id() {
		return static::$api_id;
	}

	/**
	 * Get a random hash.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $prefix A optional prefix to the random hash.
	 * @param int    $length A optional length of the random hash.
	 *
	 * @return string A random hash.
	 */
	public function get_random_hash( $prefix = '', $length = 20 ) {
		if ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
			$hash = bin2hex( openssl_random_pseudo_bytes( $length ) );
		}

		if ( ! empty( $hash ) ) {
			return $prefix . $hash;
		}

		// Fallback hash if openssl_random_pseudo_bytes returns empty it will return a 40 character hash.
		// $length is the minimum length of the random number generated in this context.
		return $prefix . sha1( wp_rand( $length ) );
	}

	/**
	 * Set up the API secret key to use for generating tokens.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	protected function setup_api_secret() {
		// Get the API Secret or set if empty.
		$api_secret = $this->get_api_secret_option();
		if ( empty( $api_secret ) ) {
			$api_secret = $this->set_api_secret_option();
		}
		$this->api_secret = $api_secret;
	}

	/**
	 * Get the API secret key for this API class.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The secret key used for JWT tokens or empty string if not set.
	 */
	protected function get_api_secret_option() {
		return get_option( static::$api_secret_key );
	}

	/**
	 * Set the API secret key for this API class.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The secret key used for JWT tokens.
	 */
	protected function set_api_secret_option() {
		$api_secret = $this->get_random_hash( '', 128 );
		update_option( static::$api_secret_key, $api_secret );

		return $api_secret;
	}

	/**
	 * Get the API secret key for this API class.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The secret key used for JWT tokens.
	 */
	public function get_api_secret() {
		return $this->api_secret;
	}

	/**
	 * Decode the JWT access_token.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $access_token The JWT access_token to decode.
	 *
	 * @return array<string|string>|WP_Error An array of the API Key pair or WP_Error.
	 */
	public function decode_jwt( $access_token ) {
		try {
			$decoded = JWT::decode( $access_token, new Key( $this->get_api_secret(), 'HS256' ) );

			if ( $decoded->iss != get_bloginfo( 'url' ) ) {
				$error_message = _x(
					'Access_token issuer does not match with this server.',
					'JWT access_token issuer does not match with this server error message.',
					'tribe-common'
				);

				return new WP_Error( 'bad_issuer', $error_message, [ 'status' => 401 ] );
			} elseif ( ! isset( $decoded->data->consumer_id, $decoded->data->consumer_secret ) ) {
				$error_message = _x(
					'Access_token is missing data.',
					'JWT access_token s missing data error message.',
					'tribe-common'
				);

				return new WP_Error( 'bad_request', $error_message, [ 'status' => 401 ] );
			}

			return [
				'consumer_id'     => $decoded->data->consumer_id,
				'consumer_secret' => $decoded->data->consumer_secret,
				'app_name'        => empty( $decoded->data->app_name ) ? '' : $decoded->data->app_name,
			];

		} catch ( Exception $e ) {
			return new WP_Error( 'invalid_token', $e->getMessage(), [ 'status' => 403 ] );
		}
	}

	/**
	 * Load a specific api_key into the API.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|string> $api_key         An api_key with the fields to access the API.
	 * @param string               $consumer_secret An optional consumer secret used to verify a connection.
	 *
	 * @return bool|WP_Error Return true if loaded or WP_Error otherwise.
	 */
	public function load_api_key( array $api_key, $consumer_secret ) {
		$valid = $this->is_valid_api_key( $api_key, $consumer_secret );
		if ( is_wp_error( $valid ) ) {
			return $valid;
		}

		$this->init_api_key( $api_key );

		return true;
	}

	/**
	 * Load a specific api_key by the id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id     The consumer id to get and load a connection.
	 * @param string $consumer_secret The consumer secret used to verify a connection.
	 *
	 * @return bool|WP_Error Whether the page is loaded or a WP_Error code.
	 */
	public function load_api_key_by_id( $consumer_id, $consumer_secret ) {
		$consumer_id = strpos( $consumer_id, 'ci_' ) === 0
			? static::api_hash( $consumer_id )
			: $consumer_id;

		$api_key = $this->get_api_key_by_id( $consumer_id );

		// Return false if no api_key.
		if ( empty( $api_key ) ) {
			$error_msg = _x( 'Consumer ID failed to load, please check the value and try again.', 'Account failed to load failure message.', 'tribe-common' );

			return new WP_Error( 'integration_consumer_id_not_found', $error_msg, [ 'status' => 400 ] );
		}

		return $this->load_api_key( $api_key, $consumer_secret );
	}

	/**
	 * Check if an api_key has all the information to be valid.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|string> $api_key An api_key with the fields to access the API.
	 * @param string $consumer_secret The consumer secret to verify the connection with.
	 *
	 * @return bool|WP_Error Return true if loaded or WP_Error otherwise.
	 */
	protected function is_valid_api_key( $api_key, $consumer_secret ) {
		//@todo disabled as this fails in the REST test. is_ssl() has to be set to true in those tests.
/*		if ( ! is_ssl() ) {
			$error = _x(
				'SSL is required to Authorize the integration.',
				'API Key authorization error for no SSL.',
				'tribe-common'
			);

			return new WP_Error( 'integration_no_ssl', $error, [ 'status' => 400 ] );
		}*/

		if ( empty( $api_key['consumer_id'] ) ) {
			$error = _x(
				'Consumer ID is required to authorize your connection.',
				'Connection authorization error for no consumer secret.',
				'tribe-common'
			);

			return new WP_Error( 'integration_no_consumer_id', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['consumer_secret'] ) ) {
			$error = _x(
				'Consumer Secret is required to authorize your connection.',
				'Connection authorization error for no consumer secret.',
				'tribe-common'
			);

			return new WP_Error( 'integration_no_consumer_secret', $error, [ 'status' => 400 ] );
		}

		$this->consumer_secret = $api_key['consumer_secret'];
		$consumer_secret       = $this->hash_the_secret( $consumer_secret );
		$secret_match          = $this->check_secret( $consumer_secret );
		if ( empty( $secret_match ) ) {
			$error = _x(
				'Consumer Secret does not match.',
				'Connection authorization error for the consumer secrets not matching.',
				'tribe-common'
			);

			return new WP_Error( 'integration_consumer_secret_no_match', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['name'] ) ) {
			$error = _x(
				'Account is missing a name.',
				'Connection authorization error for no account name.',
				'tribe-common'
			);

			return new WP_Error( 'integration_no_api_key_name', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['permissions'] ) ) {
			$error = _x(
				'Account is missing permissions.',
				'Connection authorization error for no account permissions.',
				'tribe-common'
			);

			return new WP_Error( 'integration_no_api_key_permissions', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['user_id'] ) ) {
			$error = _x(
				'Account is missing a user, please select one and try again..',
				'Connection authorization error for no user selected.',
				'tribe-common'
			);

			return new WP_Error( 'integration_no_api_key_user_id', $error, [ 'status' => 400 ] );
		}

		$user = get_user_by( 'id', $api_key['user_id'] );
		if ( is_wp_error( $user ) ) {
			$error = _x(
				'Selected user could not be loaded.',
				'Connection authorization error for account user not loading.',
				'tribe-common'
			);

			return new WP_Error( 'integration_no_api_key_user_loading', $error, [ 'status' => 400 ] );
		}

		$this->user = $user;

		return true;
	}

	/**
	 * Initialize a connection to use for the API.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|string> $api_key An api_key with the fields to access the API.
	 */
	protected function init_api_key( $api_key ) {
		$this->consumer_id         = $api_key['consumer_id'];
		$this->consumer_secret     = $api_key['consumer_secret'];
		$this->permissions         = $api_key['permissions'];
		$this->last_access         = $api_key['last_access'];
		$this->user_id             = $api_key['user_id'];
		$this->api_key_loaded      = true;
		$this->loaded_api_key_name = $api_key['name'];
	}

	/**
	 * Get the listing of connections.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param boolean $all_data Whether to return all api_key data, default is only name and status.
	 *
	 * @return array<string|string> $list_of_api_keys An array of all the connections.
	 */
	public function get_list_of_api_keys( $all_data = false ) {
		$list_of_api_keys = get_option( $this->all_api_keys_key, [] );
		foreach ( $list_of_api_keys as $consumer_id => $api_key ) {
			if ( empty( $api_key['name'] ) ) {
				continue;
			}
			$list_of_api_keys[ $consumer_id ]['name'] = $api_key['name'];

			// If false (default ) skip getting all the api_key data.
			if ( empty( $all_data ) ) {
				continue;
			}
			$api_key_data = $this->get_api_key_by_id( $consumer_id );

			$list_of_api_keys[ $consumer_id ] = $api_key_data;
		}

		return $list_of_api_keys;
	}

	/**
	 * Update the list of Connections with provided api_key.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|string> $api_key_data The array of data for an api_key to add to the list.
	 */
	protected function update_list_of_api_keys( $api_key_data ) {
		$api_keys = $this->get_list_of_api_keys();
		$api_id   = static::$api_id;

		/**
		 * Fires after before the api_key list is updated for an API.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string,mixed>  An array of connections formatted for options dropdown.
		 * @param array<string|string> $api_key_data The array of data for an api_key to add to the list.
		 * @param string               $api_id       The id of the API in use.
		 */
		do_action( "tec_automator_before_update_{$api_id}_api_keys", $api_keys, $api_key_data, $api_id );

		$api_keys[ esc_attr( $api_key_data['consumer_id'] ) ] = [
			'name'   => esc_attr( $api_key_data['name'] ),
		];

		update_option( $this->all_api_keys_key, $api_keys );
	}

	/**
	 * Delete from the list of connections the provided api_key.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The id of the single api_key to save.
	 */
	protected function delete_from_list_of_api_keys( $consumer_id ) {
		$api_keys                        = $this->get_list_of_api_keys();
		unset( $api_keys[ $consumer_id ] );

		update_option( $this->all_api_keys_key, $api_keys );
	}

	/**
	 * Get a Single connection by id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The id of the single api_key.
	 *
	 * @return array<string|string> $api_key The api_key data or empty array if no api_key.
	 */
	public function get_api_key_by_id( $consumer_id ) {
		return get_option( $this->single_api_key_prefix . $consumer_id, [] );
	}

	/**
	 * Set a connection with the provided id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|string> $api_key_data A specific api_key data to save.
	 */
	public function set_api_key_by_id( array $api_key_data ) {
		// hash the consumer id and secret if they start with the prefix.
		$api_key_data['consumer_id'] = strpos( $api_key_data['consumer_id'], 'ci_' ) === 0
			? static::api_hash( $api_key_data['consumer_id'] )
			: $api_key_data['consumer_id'];

		$api_key_data['consumer_secret'] = strpos( $api_key_data['consumer_secret'], 'ck_' ) === 0
			? static::api_hash( $api_key_data['consumer_secret'] )
			: $api_key_data['consumer_secret'];

		update_option( $this->single_api_key_prefix . $api_key_data['consumer_id'], $api_key_data, false );

		$this->update_list_of_api_keys( $api_key_data );
	}

	/**
	 * Updates the last access valid access of the connection.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The id of the single api_key.
	 * @param string $app_name    The optional app name used with this connection.
	 */
	public function set_api_key_last_access( $consumer_id, $app_name = '' ) {
		$hashed_consumer_id = strpos( $consumer_id, 'ci_' ) === 0
			? static::api_hash($consumer_id )
			: $consumer_id;

		$api_key_data                = $this->get_api_key_by_id( $hashed_consumer_id );
		$api_key_data['last_access'] = $this->get_last_access( $app_name );

		update_option( $this->single_api_key_prefix . $hashed_consumer_id, $api_key_data, false );
	}

	/**
	 * Delete an api_key by ID.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_id The id of the single api_key.
	 *
	 * @return bool Whether the api_key has been deleted and the access access_token revoked.
	 */
	public function delete_api_key_by_id( $consumer_id ) {
		delete_option( $this->single_api_key_prefix . $consumer_id );

		$this->delete_from_list_of_api_keys( $consumer_id );

		return true;
	}

	/**
	 * Maybe hash the consumer secret.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_secret The consumer secret to verify the connection with.
	 *
	 * @return string The hashed consumer secret.
	 */
	protected function hash_the_secret( $consumer_secret) {
		return strpos( $consumer_secret, 'ck_' ) === 0
			? static::api_hash( $consumer_secret )
			: $consumer_secret;
	}

	/**
	 * Check the consumer secret.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $consumer_secret The consumer secret to verify the connection with.
	 *
	 * @return boolean Whether the consumer secret matches the saved one.
	 */
	protected function check_secret( $consumer_secret) {
		// Validate user secret.
		if ( ! hash_equals( $this->consumer_secret, $consumer_secret ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Hash the specified text.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $text Text to be hashed.
	 *
	 * @return string The hashed text.
	 */
	public static function api_hash( $text ) {
		return hash_hmac( 'sha256', $text, 'tec-automator-' . static::$api_id );
	}

	/**
	 * Get the WP_User object from thelLoaded connection.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return WP_User|null Returns the WP_User object or null if not loaded.
	 */
	public function get_user() {
		return ! empty( $this->user ) ? $this->user : null;
	}

	/**
	 * Get the WP_User_Query query results.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string|mixed> An array of user objects.
	 */
	public function get_users() {
		$args = [
		    'number' => 1000,
		    'role__in' => [ 'Administrator', 'Editor' ],
		];
		$api_id = static::$api_id;

		/**
		 * Filters the argument array to query users for the connection fields user dropdown.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string|mixed> The default array to query users for the connection fields user dropdown.
		 */
		$args = apply_filters( "tec_event_automator_{$api_id}_api_get_user_arguments", $args );

		// Custom query.
		$user_query = new WP_User_Query( $args );

		return $user_query->results;
	}

	/**
	 * Get a user's information formatted for internal use.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
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
	 * Get list of users formatted for options dropdown.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string,mixed>  An array of WordPress Users to use for connection.
	 */
	public function get_users_options_list() {
		$available_users = $this->get_users();
		$current_user_id = get_current_user_id();

		if ( empty( $available_users ) ) {
			return [];
		}

		$users = [];
		foreach ( $available_users as $user ) {
			$user_info = $this->get_formatted_user_info( $user );

			if (
				empty( $user_info['name'] ) ||
				empty( $user_info['id'] ) ||
				empty( $user_info['email'] )
			) {
				continue;
			}

			$users[] = [
				'text'     => (string) trim( $user_info['name'] ),
				'sort'     => (string) trim( $user_info['name'] ),
				'id'       => (string) $user_info['id'],
				'value'    => (string) $user_info['id'],
				'selected' => $current_user_id === (int) $user_info['id'] ? true : false,
			];
		}

		// Sort the users array by text(email).
		$sort_arr = array_column( $users, 'sort' );
		array_multisort( $sort_arr, SORT_ASC, $users );

		return $users;
	}

	/**
	 * Get users dropdown fields for a tribe dropdown input.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string,mixed>  An array of WordPress Users to use in a tribe dropdown input.
	 */
	public function get_users_dropdown() {
		$users           = $this->get_users_options_list();
		$current_user_id = get_current_user_id();
		$api_id          = static::$api_id;

		$users_dropdown = [
			'label'          => _x(
				'Users',
				'The label of the users dropdown for an integration.',
				'tribe-common'
			),
			'id'             => "tec-settings-{$api_id}-users",
			'classes_label'  => [ 'screen-reader-text' ],
			'classes_select' => [ 'tec-settings-form__users-dropdown', "tec-settings-{$api_id}-details-api-key__users-dropdown" ],
			'name'           => "tec_automator_{$api_id}[]['users']",
			'selected'       => $current_user_id,
			'users_count'    => count( $users ),
			'users_arr'      => $users,
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
