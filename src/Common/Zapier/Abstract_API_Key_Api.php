<?php
/**
 * Abstract Class to Manage Multiple API Keys for Zapier.
 *
 * @since TBD
 *
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

use TEC\Common\Traits\With_AJAX;
use WP_Error;

/**
 * Class Abstract_API_Key_Api
 *
 * @since TBD
 *
 * @package TEC\Common\Zapier
 */
abstract class Abstract_API_Key_Api {
	use With_AJAX;

	/**
	 * Whether an api_key has been loaded for the API to use.
	 *
	 * @since TBD
	 *
	 * @var boolean
	 */
	protected $api_key_loaded = false;

	/**
	 * The name of the loaded api_key.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $loaded_api_key_name = '';

	/**
	 * The key to get the option with a list of all API Keys.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $all_api_keys_key = 'tec_zapier_api_keys';

	/**
	 * The prefix to save all single API Key with.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $single_api_key_prefix = 'tec_zapier_api_key_';

	/**
	 * The hashed consumer id of the api key loaded.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $consumer_id = '';

	/**
	 * The consumer secret of the api key loaded.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $consumer_secret = '';

	/**
	 * The permissions the API Key pair has access to.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $permissions = 'read';

	/**
	 * The WordPress user id of the loaded api_key.
	 *
	 * @since TBD
	 *
	 * @var integer
	 */
	protected $user_id = 0;


	/**
	 * Checks whether the current API is ready to use.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the current API has a loaded api_key.
	 */
	public function is_ready() {
		return ! empty( $this->api_key_loaded );
	}

	/**
	 * Load a specific api_key into the API.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $api_key         An api_key with the fields to access the API.
	 * @param string               $consumer_secret An optional consumer secret used to verify an API Key pair.
	 *
	 * @return bool|WP_Error Return true if loaded or WP_Error otherwise.
	 */
	public function load_api_key( array $api_key = [], $consumer_secret = '' ) {
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
	 * @since TBD
	 *
	 * @param string $consumer_id     The consumer id to get and load an API Key pair.
	 * @param string $consumer_secret The consumer secret used to verify an API Key pair.
	 *
	 * @return bool|string Whether the page is loaded or an error code. False or code means the page did not load.
	 */
	public function load_api_key_by_id( $consumer_id, $consumer_secret ) {
		$consumer_id = strpos( $consumer_id, 'ci_' ) === 0
			? static::api_hash( $consumer_id )
			: $consumer_id;

		$api_key = $this->get_api_key_by_id( $consumer_id );

		// Return false if no api_key.
		if ( empty( $api_key ) ) {
			return false;
		}

		return $this->load_api_key( $api_key, $consumer_secret );
	}

	/**
	 * Check if an api_key has all the information to be valid.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $api_key An api_key with the fields to access the API.
	 * @param string $consumer_secret The consumer secret to verify the API Key with.
	 *
	 * @return bool|WP_Error Return true if loaded or WP_Error otherwise.
	 */
	protected function is_valid_api_key( $api_key, $consumer_secret ) {
		if ( ! is_ssl() ) {
			$error = _x(
				'SSL is required to Authorize Zapier API Keys.',
				'Zapier API Key authorization error for no SSL.',
				'tribe-common'
			);

			return new WP_Error( 'rest_no_ssl', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['consumer_id'] ) ) {
			$error = _x(
				'Consumer ID is required to Authorize Zapier API Keys.',
				'Zapier API Key authorization error for no consumer secret.',
				'tribe-common'
			);

			return new WP_Error( 'rest_no_consumer_id', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['consumer_secret'] ) ) {
			$error = _x(
				'Consumer Secret is required to Authorize Zapier API Keys.',
				'Zapier API Key authorization error for no consumer secret.',
				'tribe-common'
			);

			return new WP_Error( 'rest_no_consumer_secret', $error, [ 'status' => 400 ] );
		}

		$this->consumer_secret = $api_key['consumer_secret'];
		$secret_match          = $this->check_secret( $consumer_secret );
		if ( empty( $secret_match ) ) {
			$error = _x(
				'Consumer Secret does not match.',
				'Zapier API Key authorization error for the consumer secrets not matching.',
				'tribe-common'
			);

			return new WP_Error( 'rest_consumer_secret_no_match', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['name'] ) ) {
			$error = _x(
				'Zapier API Key is missing a name.',
				'Zapier API Key authorization error for no API Key name.',
				'tribe-common'
			);

			return new WP_Error( 'rest_no_api_key_name', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['permissions'] ) ) {
			$error = _x(
				'Zapier API Key is missing permissions.',
				'Zapier API Key authorization error for no API Key permissions.',
				'tribe-common'
			);

			return new WP_Error( 'rest_no_api_key_permissions', $error, [ 'status' => 400 ] );
		}

		if ( empty( $api_key['user_id'] ) ) {
			$error = _x(
				'Zapier API Key is a user id.',
				'Zapier API Key authorization error for no API Key user id.',
				'tribe-common'
			);

			return new WP_Error( 'rest_no_api_key_user_id', $error, [ 'status' => 400 ] );
		}

		$user = get_user_by( 'id', $api_key['user_id'] );
		if ( is_wp_error( $user ) ) {
			$error = _x(
				'Zapier API Key could not load the WordPress user.',
				'Zapier API Key authorization error for API Key user not loading.',
				'tribe-common'
			);

			return new WP_Error( 'rest_no_api_key_user_loading', $error, [ 'status' => 400 ] );
		}

		$this->user = $user;

		return true;
	}

	/**
	 * Initialize an API Key to use for the API.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $api_key An api_key with the fields to access the API.
	 */
	protected function init_api_key( $api_key ) {
		$this->consumer_id         = $api_key['consumer_id'];
		$this->consumer_secret     = $api_key['consumer_secret'];
		$this->permissions         = $api_key['permissions'];
		$this->user_id             = $api_key['user_id'];
		$this->api_key_loaded      = true;
		$this->loaded_api_key_name = $api_key['name'];
	}

	/**
	 * Get the listing of API Keys.
	 *
	 * @since TBD
	 *
	 * @param boolean $all_data Whether to return all api_key data, default is only name and status.
	 *
	 * @return array<string|string> $list_of_api_keys An array of all the API Keys.
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
	 * Update the list of API Keys with provided api_key.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $api_key_data The array of data for an api_key to add to the list.
	 */
	protected function update_list_of_api_keys( $api_key_data ) {
		$api_keys = $this->get_list_of_api_keys();

		/**
		 * Fires after before the api_key list is updated for an API.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed>  An array of API Keys formatted for options dropdown.
		 * @param array<string|string> $api_key_data The array of data for an api_key to add to the list.
		 * @param string               $api_id       The id of the API in use.
		 */
		do_action( 'tec_common_before_update_zapier_api_keys', $api_keys, $api_key_data, static::$api_id );

		$api_keys[ esc_attr( $api_key_data['consumer_id'] ) ] = [
			'name'   => esc_attr( $api_key_data['name'] ),
		];

		update_option( $this->all_api_keys_key, $api_keys );
	}

	/**
	 * Delete from the list of API Keys the provided api_key.
	 *
	 * @since TBD
	 *
	 * @param string $consumer_id The id of the single api_key to save.
	 */
	protected function delete_from_list_of_api_keys( $consumer_id ) {
		$api_keys                        = $this->get_list_of_api_keys();
		unset( $api_keys[ $consumer_id ] );

		update_option( $this->all_api_keys_key, $api_keys );
	}

	/**
	 * Get a Single API Key by id.
	 *
	 * @since TBD
	 *
	 * @param string $consumer_id The id of the single api_key.
	 *
	 * @return array<string|string> $api_key The api_key data or empty array if no api_key.
	 */
	public function get_api_key_by_id( $consumer_id ) {
		return get_option( $this->single_api_key_prefix . $consumer_id, [] );
	}

	/**
	 * Set an API Key with the provided id.
	 *
	 * @since TBD
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
	 * Delete an api_key by ID.
	 *
	 * @since TBD
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
	 * Check the consumer secret.
	 *
	 * @since TBD
	 *
	 * @param string $consumer_secret The consumer secret to verify the API Key with.
	 *
	 * @return boolean Whether the consumer secret matches the saved one.
	 */
	protected function check_secret( $consumer_secret) {
		$consumer_secret = strpos( $consumer_secret, 'ck_' ) === 0
			? static::api_hash( $consumer_secret )
			: $consumer_secret;

		// Validate user secret.
		if ( ! hash_equals( $this->consumer_secret, $consumer_secret ) ) {
			return false;
		}

		return true;
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
}
