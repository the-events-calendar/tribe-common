<?php
// Don't load directly
defined( 'WPINC' ) or die;

if ( ! class_exists( 'Tribe__Dependency' ) ) {
	/**
	 * Tracks which tribe plugins are currently activated
	 */
	class Tribe__Dependency {

		/**
		 * An multidimensional array of active tribe plugins in the following format
		 *
		 * array(
		 *  'class'   => 'main class name',
		 *  'version' => 'version num', (optional)
		 *  'path'    => 'Path to the main plugin/bootstrap file' (optional)
		 * )
		 */
		protected $active_plugins = array();

		/**
		 * Static Singleton Holder
		 *
		 * @var self
		 */
		private static $instance;


		/**
		 * Static Singleton Factory Method
		 *
		 * @return self
		 */
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}


		public function __construct() {
			$this->add_legacy_plugins();
		}


		/**
		 * Registers older plugins that did not use this class
		 *
		 * @TODO Consider removing this in 5.0
		 */
		private function add_legacy_plugins() {
			// Version 4.2 and under of the plugins do not register themselves here, so we'll register them

			$tribe_plugins = new Tribe__Plugins();

			foreach ( $tribe_plugins->get_list() as $plugin ) {
				if ( ! class_exists( $plugin['class'] ) ) {
					continue;
				}

				$ver_const = $plugin['class'] . '::VERSION';
				$version = defined( $ver_const ) ? constant( $ver_const ) : null;

				$this->add_active_plugin( $plugin['class'], $version );
			}
		}


		/**
		 * Retrieves active plugin array
		 *
		 * @return array
		 */
		public function get_active_plugins() {
			return $this->active_plugins;
		}


		/**
		 * Adds a plugin to the active list
		 *
		 * @param string $main_class Main/base class for this plugin
		 * @param string $version    Version number of plugin
		 * @param string $path       Path to the main plugin/bootstrap file
		 */
		public function add_active_plugin( $main_class, $version = null, $path = null ) {

			$plugin = array(
				'class'   => $main_class,
				'version' => $version,
				'path'    => $path,
			);

			$this->active_plugins[ $main_class ] = $plugin;
		}


		/**
		 * Searches the plugin list for key/value pair and return the full details for that plugin
		 *
		 * @param string $search_key The array key this value will appear in
		 * @param string $search_val The value itself
		 *
		 * @return array|null
		 */
		public function get_plugin_by_key( $search_key, $search_val ) {
			foreach ( $this->active_plugins as $plugin ) {
				if ( isset( $plugin[ $search_key ] ) && $plugin[ $search_key ] === $search_val ) {
					return $plugin;
				}
			}

			return null;
		}


		/**
		 * Retrieves the plugins details by class name
		 *
		 * @param string $main_class Main/base class for this plugin
		 *
		 * @return array|null
		 */
		public function get_plugin_by_class( $main_class ) {
			return $this->get_plugin_by_key( 'class', $main_class );
		}


		/**
		 * Retrieves the version of the plugin
		 *
		 * @param string $main_class Main/base class for this plugin
		 *
		 * @return string|null Version
		 */
		public function get_plugin_version( $main_class ) {
			$plugin = $this->get_plugin_by_class( $main_class );

			return ( isset( $plugin['version'] ) ? $plugin['version'] : null );
		}


		/**
		 * Checks if the plugin is active
		 *
		 * @param string $main_class Main/base class for this plugin
		 *
		 * @return bool
		 */
		public function is_plugin_active( $main_class ) {
			return ( $this->get_plugin_by_class( $main_class ) !== null );
		}


		/**
		 * Checks if a plugin is active and has the specified version
		 *
		 * @param string $main_class Main/base class for this plugin
		 * @param string $version Version to do a compare against
		 * @param string $compare Version compare string, defaults to >=
		 *
		 * @return bool
		 */
		public function is_plugin_version( $main_class, $version, $compare = '>=' ) {

			if ( ! $this->is_plugin_active( $main_class ) ) {
				return false;
			} elseif ( version_compare( $this->get_plugin_version( $main_class ), $version, $compare ) ) {
				return true;
			} elseif ( $this->get_plugin_version( $main_class ) === null ) {
				// If the plugin version is not set default to assuming it's a compatible version
				return true;
			}

			return false;
		}


		/**
		 * Checks if each plugin is active and exceeds the specified version number
		 *
		 * @param array $plugins_required Each item is a 'class_name' => 'min version' pair. Min ver can be null.
		 *
		 * @return bool
		 */
		public function has_requisite_plugins( $plugins_required = array() ) {

			foreach ( $plugins_required as $class => $version ) {
				// Return false if the plugin is not set or is a lesser version
				if ( ! $this->is_plugin_active( $class ) ) {
					return false;
				}

				if ( null !== $version && ! $this->is_plugin_version( $class, $version ) ) {
					return false;
				}
			}

			return true;
		}

	}

}
