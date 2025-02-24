<?php
/**
 * Plugin Info Class
 *
 * This is a direct port to Tribe Commons of the PUE classes contained
 * in The Events Calendar.
 *
 * @todo switch all plugins over to use the PUE utilities here in Commons
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// phpcs:disable Squiz.Commenting.VariableComment.Missing

if ( ! class_exists( 'Tribe__PUE__Plugin_Info' ) ) {
	/**
	 * A container class for holding and transforming various plugin metadata.
	 * @version 1.7
	 * @access public
	 */
	class Tribe__PUE__Plugin_Info {
		// Most fields map directly to the contents of the plugin's info.json file.
		public $name;
		public $plugin;
		public $slug;
		public $version;
		public $homepage;
		public $sections;
		public $download_url;
		public $home_url;
		public $origin_url;
		public $zip_url;
		public $icon_svg_url;
		public $auth_url;
		public $file_prefix;

		public $author;
		public $author_homepage;

		public $requires;
		public $auth_required;
		public $is_authorized;
		public $tested;
		public $upgrade_notice;

		public $rating;
		public $num_ratings;
		public $downloaded;
		public $release_date;
		public $last_updated;
		public $expiration;
		public $daily_limit;
		public $custom_update;

		public $api_expired;
		public $api_invalid;
		public $api_upgrade;
		public $api_message;
		public $api_inline_invalid_message;
		public $api_invalid_message;

		public $license_error;
		public $license_key;

		public $new_install_key;
		public $replacement_key;

		public $id = 0; // The native WP.org API returns numeric plugin IDs, but they're not used for anything.

		/**
		 * Create a new instance of Tribe__PUE__Plugin_Info from JSON-encoded plugin info
		 * returned by an external update API.
		 *
		 * @since 6.5.1 Added whitelist for checking $key.
		 *
		 * @param string $json Valid JSON string representing plugin info.
		 *
		 * @return Tribe__PUE__Plugin_Info New instance of Tribe__PUE__Plugin_Info, or NULL on error.
		 */
		public static function from_json( $json ) {
			// Decode the JSON response.
			$api_response = json_decode( $json );

			// Get the first item of the response array.
			if ( $api_response && ! empty( $api_response->results ) ) {
				$api_response = current( $api_response->results );
			}

			if ( empty( $api_response ) || ! is_object( $api_response ) ) {
				return null;
			}

			// Normalize keys by stripping the "plugin_" prefix.
			$normalized_response = [];
			foreach ( get_object_vars( $api_response ) as $key => $value ) {
				if ( strpos( $key, 'plugin_' ) === 0 ) {
					$normalized_key = substr( $key, strlen( 'plugin_' ) );
				} else {
					$normalized_key = $key;
				}
				$normalized_response[ $normalized_key ] = $value;
			}

			// Basic validation after normalization.
			$is_valid = ( ! empty( $normalized_response['name'] )
						&& ! empty( $normalized_response['version'] ) )
						|| ( isset( $normalized_response['api_invalid'] )
						|| isset( $normalized_response['no_api'] )
						);
			if ( ! $is_valid ) {
				return null;
			}

			// Populate the object.
			$plugin_info = new self();
			foreach ( $normalized_response as $key => $value ) {
				if ( $plugin_info->check_whitelisted_keys( $key ) ) {
					$plugin_info->$key = $value;
				}
			}

			return $plugin_info;
		}

		/**
		 * Get the whitelist of valid keys (class properties).
		 *
		 * @since 6.5.1
		 *
		 * @return array List of valid property names.
		 */
		public function get_whitelisted_keys(): array {
			return array_keys( get_class_vars( __CLASS__ ) );
		}

		/**
		 * Check if a given key is in the whitelist.
		 *
		 * @since 6.5.1
		 *
		 * @param string $key The key to check.
		 *
		 * @return bool True if the key is whitelisted, false otherwise.
		 */
		public function check_whitelisted_keys( string $key ): bool {
			$valid_keys = $this->get_whitelisted_keys();

			return in_array( $key, $valid_keys, true );
		}

		/**
		 * Transform plugin info into the format used by the native WordPress.org API
		 *
		 * @return object
		 */
		public function to_wp_format() {
			$info = new StdClass;

			// The custom update API is built so that many fields have the same name and format
			// as those returned by the native WordPress.org API. These can be assigned directly.
			$sameFormat = [
				'name',
				'slug',
				'version',
				'requires',
				'tested',
				'rating',
				'upgrade_notice',
				'num_ratings',
				'downloaded',
				'homepage',
				'last_updated',
				'api_expired',
				'api_upgrade',
				'api_invalid',
			];

			foreach ( $sameFormat as $field ) {
				if ( isset( $this->$field ) ) {
					$info->$field = $this->$field;
				} else {
					$info->$field = null;
				}
			}

			//Other fields need to be renamed and/or transformed.
			$info->download_link = $this->download_url;

			if ( ! empty( $this->author_homepage ) ) {
				$info->author = sprintf( '<a href="%s">%s</a>', esc_url( $this->author_homepage ), $this->author );
			} else {
				$info->author = $this->author;
			}

			if ( is_object( $this->sections ) ) {
				$info->sections = get_object_vars( $this->sections );
			} elseif ( is_array( $this->sections ) ) {
				$info->sections = $this->sections;
			} else {
				$info->sections = [ 'description' => '' ];
			}

			return $info;
		}
	}
}
