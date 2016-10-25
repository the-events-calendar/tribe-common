<?php
/**
 * Class Tribe__Extension_Loader
 */
class Tribe__Extension_Loader {

	/**
	 * Plugin header data
	 *
	 * @var array {
	 *      Plugin header data
	 *
	 *      @param array $plugin_basename Plugin header key/value pairs.
	 * }
	 */
	private $plugin_data = array();

	/**
	 * Class instance.
	 *
	 * @var Tribe__Extension_Loader The singleton instance.
	 */
	private static $instance;

	/**
	 * Returns the singleton instance of this class.
	 *
	 * @return Tribe__Extension_Loader instance.
	 */
	public static function instance() {
		return null === self::$instance ? new self() : self::$instance;
	}

	/**
	 * Intializes each extension.
	 */
	private function __construct() {
		$prefixes = self::get_extension_file_prefixes();
		$extension_filepaths = self::get_plugins_with_prefix( $prefixes );

		foreach ( $extension_filepaths as $plugin_file ) {
			$this->instantiate_extension( $plugin_file );
		}
	}

	/**
	 * Get list of active plugins with a given prefix in the plugin folder path.
	 *
	 * @param string|array $prefix Prefixes you want to retrieve.
	 *
	 * @return array List of plugins with prefix in path.
	 */
	public static function get_plugins_with_prefix( $prefix ) {
		$plugin_list = wp_get_active_and_valid_plugins();

		if ( is_multisite() ) {
			$plugin_list = array_merge( $plugin_list, wp_get_active_network_plugins() );
		}

		$extension_list = array();

		foreach ( $plugin_list as $plugin ) {
			$base = plugin_basename( $plugin );

			if ( self::strpos_array( $base, $prefix ) === 0 ) {
				$extension_list[] = $plugin;
			}
		}

		return $extension_list;
	}

	/**
	 * Gets the plugin data from the plugin file header
	 *
	 * @param string $plugin_file Absolute path to plugin file containing header.
	 *
	 * @see get_plugin_data() for WP Admin only function this is similar to.
	 *
	 * @return array Plugin data; keys match capitalized file header declarations.
	 */
	public static function get_plugin_data( $plugin_file ) {
		$default_headers = array(
			'Name' => 'Plugin Name',
			'PluginURI' => 'Plugin URI',
			'Version' => 'Version',
			'ExtensionClass' => 'Extension Class',
			'ExtensionFile' => 'Extension File',
			'Description' => 'Description',
			'Author' => 'Author',
			'AuthorURI' => 'Author URI',
			'TextDomain' => 'Text Domain',
			'DomainPath' => 'Domain Path',
			'Network' => 'Network',
		);

		return get_file_data( $plugin_file, $default_headers, 'plugin' );
	}

	/**
	 * Behaves exactly like strpos(), but accepts an array of needles.
	 *
	 * @see strpos()
	 *
	 * @param string       $haystack String to search in.
	 * @param array|string $needles  Strings to search for.
	 * @param int          $offset   Starting position of search.
	 *
	 * @return false|int Integer position of first needle occurrence.
	 */
	public static function strpos_array( $haystack, $needles, $offset = 0 ) {
		$needles = (array) $needles;

		foreach ( $needles as $i ) {
			$search = strpos( $haystack, $i, $offset );

			if ( false !== $search ) {
				return $search;
			}
		}

		return false;
	}


	/**
	 * Gets tribe extension plugin foldername prefixes
	 *
	 * @return array Prefixes
	 */
	public static function get_extension_file_prefixes() {
		$prefixes = array( 'tribe-ext-' );

		/**
		 * Filter which plugin folder prefixes are considered tribe extensions.
		 *
		 * @param array $prefixes Extension plugin folder name prefixes.
		 */
		return apply_filters( 'tribe_extension_prefixes', $prefixes );
	}

	/**
	 * Instantiates an extension based on info in its plugin file header.
	 *
	 * @param string $plugin_file Full path to extension's plugin file header.
	 *
	 * @return bool Indicates if extension was instantiated successfully.
	 */
	public function instantiate_extension( $plugin_file ) {
		$plugin_data = $this->get_cached_plugin_data( $plugin_file );
		$plugin_folder = trailingslashit( dirname( $plugin_file ) );
		$success = false;

		// Set default extension file.
		// @TODO Discuss if this should default to something else like $plugin_file.
		if ( ! empty( $plugin_data['ExtensionFile'] ) ) {
			$class_file = $plugin_folder . $plugin_data['ExtensionFile'];
		} else {
			$class_file = $plugin_folder . 'extension.php';
		}

		if ( file_exists( $class_file ) ) {
			// Prevent loading class twice in edge cases where require_once wouldn't work.
			if ( ! class_exists( $plugin_data['ExtensionClass'] ) ) {
				require( $class_file );
			}
		} else {
			_doing_it_wrong(
				esc_html( $class_file ),
				'Extension file does not exist, please specify valid extension file.',
				'4.3'
			);
		}

		if ( class_exists( $plugin_data['ExtensionClass'] ) ) {
			// Instantiates extension instance.
			$plugin_data['ExtensionClass']::instance( $plugin_data['ExtensionClass'], $plugin_file );
			$success = true;
		} else {
			_doing_it_wrong(
				esc_html( $plugin_data['ExtensionClass'] ),
				'Specified extension class does not exist. Please double check that this class is declared in the extension file.',
				'4.3'
			);
		}

		return $success;
	}

	/**
	 * Retrieves plugin data from cache if it exists.
	 *
	 * @param string $plugin_path Path to plugin header file.
	 *
	 * @return array|null Plugin data or null.
	 */
	public function get_cached_plugin_data( $plugin_path ) {
		/*
		 * @TODO Discuss caching this data in the database.
		 *
		 * We could build the DB cache each time an admin visits a plugin install, update or info page.
		 * This would not account for manual plugin updates. So we could also rebuild the cache if:
		 * - Extension class or file does not exist
		 * - Extension has manually set version number and it does not match stored value in database.
		 * Or, we could continue on with the following flexibility and slight performance penalty.
		 */
		$plugin_basename = plugin_basename( $plugin_path );

		if ( ! array_key_exists( $plugin_basename, $this->plugin_data ) ) {
			$this->plugin_data[ $plugin_basename ] = self::get_plugin_data( $plugin_path );
		}

		return $this->plugin_data[ $plugin_basename ];
	}

	/**
	 * Prevent cloning the singleton with 'clone' operator
	 *
	 * @return void
	 */
	private function __clone() {
		_doing_it_wrong(
			__FUNCTION__,
			'Can not use this method on singletons.',
			'4.3'
		);
	}

	/**
	 * Prevent unserializing the singleton instance
	 *
	 * @return void
	 */
	private function __wakeup() {
		_doing_it_wrong(
			__FUNCTION__,
			'Can not use this method on singletons.',
			'4.3'
		);
	}
}
