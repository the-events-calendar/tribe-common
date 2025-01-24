<?php
/**
 * Plugin Update Utility Class.
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

if ( ! class_exists( 'Tribe__PUE__Utility' ) ) {

	/**
	 * A simple container class for holding information about an available update.
	 *
	 * @version 1.7
	 * @access  public
	 */
	class Tribe__PUE__Utility {
		public $id = 0;
		public $plugin;
		public $slug;
		public $version;
		public $homepage;
		public $download_url;
		public $sections = [];
		public $upgrade_notice;
		public $custom_update;
		public $license_error;
		public $auth_url;
		/**
		 * @var bool
		 */
		public $api_expired;
		/**
		 * @var bool
		 */
		public $api_upgrade;
		/**
		 * @var bool
		 */
		public $api_invalid;
		/**
		 * @var string
		 */
		public $api_invalid_message;
		/**
		 * @var string
		 */
		public $api_inline_invalid_message;

		/**
		 * A list of fields that will be copied from the Plugin Info object to this.
		 *
		 * @since 6.4.2
		 *
		 * @var string[]
		 */
		private static array $copy_fields = [
			'id',
			'slug',
			'version',
			'homepage',
			'download_url',
			'upgrade_notice',
			'sections',
			'plugin',
			'api_expired',
			'api_upgrade',
			'api_invalid',
			'api_invalid_message',
			'api_inline_invalid_message',
			'custom_update',
		];

		/**
		 * Create a new instance of Tribe__PUE__Utility from its JSON-encoded representation.
		 *
		 * @param string $json
		 *
		 * @return Tribe__PUE__Utility
		 */
		public static function from_json( $json ) {
			//Since update-related information is simply a subset of the full plugin info,
			//we can parse the update JSON as if it was a plugin info string, then copy over
			//the parts that we care about.
			$pluginInfo = Tribe__PUE__Plugin_Info::from_json( $json );
			if ( $pluginInfo != null ) {
				return self::from_plugin_info( $pluginInfo );
			} else {
				return null;
			}
		}

		/**
		 * Create a new instance of Tribe__PUE__Utility based on an instance of Tribe__PUE__Plugin_Info.
		 * Basically, this just copies a subset of fields from one object to another.
		 *
		 * @since 6.4.2 Refactored to extract the copy fields to a static property.
		 *
		 * @param Tribe__PUE__Plugin_Info $info
		 *
		 * @return Tribe__PUE__Utility
		 */
		public static function from_plugin_info( $info ) {
			$update     = new Tribe__PUE__Utility();

			foreach ( self::$copy_fields as $field ) {
				if ( ! isset( $info->$field ) ) {
					continue;
				}

				$update->$field = $info->$field;
			}

			return $update;
		}

		/**
		 * Transform the update into the format used by WordPress native plugin API.
		 *
		 * @param ?string $plugin_file Plugin bootstrap file path.
		 *
		 * @return object
		 */
		public function to_wp_format( $plugin_file = null ) {
			$update = new StdClass;

			$id     = $this->id;
			$plugin = $this->plugin;

			if ( empty( $this->id ) ) {
				$id = 'stellarwp/plugins/' . $this->slug;
			}

			if ( empty( $plugin ) && ! empty( $plugin_file ) ) {
				$plugin = $plugin_file;
			}

			$update->id          = $id;
			$update->plugin      = $plugin;
			$update->slug        = $this->slug;
			$update->new_version = $this->version;
			$update->url         = $this->homepage;
			$update->package     = $this->download_url;
			if ( ! empty( $this->upgrade_notice ) ) {
				$update->upgrade_notice = $this->upgrade_notice;
			}

			// Support custom $update properties coming straight from PUE
			if ( ! empty( $this->custom_update ) ) {
				$custom_update = get_object_vars( $this->custom_update );

				foreach ( $custom_update as $field => $custom_value ) {
					if ( is_object( $custom_value ) ) {
						$custom_value = get_object_vars( $custom_value );
					}

					$update->$field = $custom_value;
				}
			}

			return $update;
		}
	}
}
