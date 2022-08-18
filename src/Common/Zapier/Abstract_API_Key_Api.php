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
	 * Whether an account has been loaded for the API to use.
	 *
	 * @since TBD
	 *
	 * @var boolean
	 */
	protected $account_loaded = false;

	/**
	 * The name of the loaded account.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $loaded_account_name = '';

	/**
	 * The key to get the option with a list of all API Keys.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $all_account_key = 'tec_zapier_api_keys';

	/**
	 * The prefix to save all single API Key with.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $single_account_prefix = 'tec_zapier_api_key_';

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
	 * Checks whether the current API is ready to use.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the current API has a loaded account.
	 */
	public function is_ready() {
		return ! empty( $this->account_loaded );
	}

	/**
	 * Load a specific account into the API.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $account An account with the fields to access the API.
	 *
	 * @return boolean Whether the account is loaded into the class to use for the API, default is false.
	 */
	public function load_account( array $account = [] ) {
		if ( $this->is_valid_account( $account ) ) {
			$this->init_account( $account );

			return true;
		}

		return false;
	}


	/**
	 * Load a specific account by the id.
	 *
	 * @since TBD
	 *
	 * @param string $consumer_id The account id to get and load for use with the API.
	 *
	 * @return bool|string Whether the page is loaded or an error code. False or code means the page did not load.
	 */
	public function load_account_by_id( $consumer_id ) {
		$account = $this->get_account_by_id( $consumer_id );

		// Return not-found if no account.
		if ( empty( $account ) ) {
			return 'not-found';
		}

		// Return disabled if the is disabled.
		if ( empty( $account['status'] ) ) {
			return 'disabled';
		}

		return $this->load_account( $account );
	}

	/**
	 * Check if an account has all the information to be valid.
	 *
	 * It will attempt to refresh the access token if it has expired.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $account An account with the fields to access the API.
	 *
	 * @return bool
	 */
	protected function is_valid_account( $account ) {
		if ( empty( $account['consumer_id'] ) ) {
			return false;
		}
		if ( empty( $account['consumer_secret'] ) ) {
			return false;
		}
		if ( empty( $account['name'] ) ) {
			return false;
		}
		if ( empty( $account['permissions'] ) ) {
			return false;
		}
		if ( empty( $account['user_id'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialize an API Key to use for the API.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $account An account with the fields to access the API.
	 */
	protected function init_account( $account ) {
		$this->consumer_id         = $account['consumer_id'];
		$this->consumer_secret     = $account['consumer_secret'];
		$this->account_loaded      = true;
		$this->loaded_account_name = $account['name'];
	}

	/**
	 * Get the listing of API Keys.
	 *
	 * @since TBD
	 *
	 * @param boolean $all_data Whether to return all account data, default is only name and status.
	 *
	 * @return array<string|string> $list_of_api_keys An array of all the API Keys.
	 */
	public function get_list_of_api_keys( $all_data = false ) {
		$list_of_api_keys = get_option( $this->all_account_key, [] );
		foreach ( $list_of_api_keys as $consumer_id => $account ) {
			if ( empty( $account['name'] ) ) {
				continue;
			}
			$list_of_api_keys[ $consumer_id ]['name'] = $account['name'];

			// If false (default ) skip getting all the account data.
			if ( empty( $all_data ) ) {
				continue;
			}
			$api_key_data = $this->get_account_by_id( $consumer_id );

			$list_of_api_keys[ $consumer_id ] = $api_key_data;
		}

		return $list_of_api_keys;
	}

	/**
	 * Update the list of API Keys with provided account.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $api_key_data The array of data for an account to add to the list.
	 */
	protected function update_list_of_api_keys( $api_key_data ) {
		$api_keys = $this->get_list_of_api_keys();

		/**
		 * Fires after before the account list is updated for an API.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed>  An array of API Keys formatted for options dropdown.
		 * @param array<string|string> $api_key_data The array of data for an account to add to the list.
		 * @param string               $api_id       The id of the API in use.
		 */
		do_action( 'tec_common_before_update_zapier_api_keys', $api_keys, $api_key_data, static::$api_id );

		$api_keys[ esc_attr( $api_key_data['consumer_id'] ) ] = [
			'name'   => esc_attr( $api_key_data['name'] ),
		];

		update_option( $this->all_account_key, $api_keys );
	}

	/**
	 * Delete from the list of API Keys the provided account.
	 *
	 * @since TBD
	 *
	 * @param string $consumer_id The id of the single account to save.
	 */
	protected function delete_from_list_of_api_keys( $consumer_id ) {
		$api_keys                        = $this->get_list_of_api_keys();
		unset( $api_keys[ $consumer_id ] );

		update_option( $this->all_account_key, $api_keys );
	}

	/**
	 * Get a Single API Key by id.
	 *
	 * @since TBD
	 *
	 * @param string $consumer_id The id of the single account.
	 *
	 * @return array<string|string> $account The account data or empty array if no account.
	 */
	public function get_account_by_id( $consumer_id ) {
		return get_option( $this->single_account_prefix . $consumer_id, [] );
	}

	/**
	 * Set an API Key with the provided id.
	 *
	 * @since TBD
	 *
	 * @param array<string|string> $api_key_data A specific account data to save.
	 */
	public function set_account_by_id( array $api_key_data ) {
		// hash the consumer id and secret if they start with the prefix.
		$api_key_data['consumer_id'] = strpos( $api_key_data['consumer_id'], 'ci_' ) === 0
			? static::api_hash( $api_key_data['consumer_id'] )
			: $api_key_data['consumer_id'];

		$api_key_data['consumer_secret'] = strpos( $api_key_data['consumer_secret'], 'ck_' ) === 0
			? static::api_hash( $api_key_data['consumer_secret'] )
			: $api_key_data['consumer_secret'];

		update_option( $this->single_account_prefix . $api_key_data['consumer_id'], $api_key_data, false );

		$this->update_list_of_api_keys( $api_key_data );
	}

	/**
	 * Delete an account by ID.
	 *
	 * @since TBD
	 *
	 * @param string $consumer_id The id of the single account.
	 *
	 * @return bool Whether the account has been deleted and the access token revoked.
	 */
	public function delete_account_by_id( $consumer_id ) {
		delete_option( $this->single_account_prefix . $consumer_id );

		$this->delete_from_list_of_api_keys( $consumer_id );

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
