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

		protected $registered_plugins = array();

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

		/**
		 * Adds a plugin to the active list
		 *
		 * @since tbd
		 *
		 * @param string $main_class Main/base class for this plugin
		 * @param string $version    Version number of plugin
		 * @param string $path       Path to the main plugin/bootstrap file
		 */
		public function add_registered_plugin( $main_class, $version = null, $path = null, $dependencies = null ) {

			$plugin = array(
				'class'        => $main_class,
				'version'      => $version,
				'path'         => $path,
				'dependencies' => $dependencies,
			);

			$this->registered_plugins[ $main_class ] = $plugin;
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
				'class'        => $main_class,
				'version'      => $version,
				'path'         => $path,
			);

			$this->active_plugins[ $main_class ] = $plugin;
		}


		/**
		 * Retrieves active plugin array
		 *
		 * @return array
		 */
		public function get_active_plugins() {
			//todo determine if this needed
			//$this->add_legacy_plugins();

			return $this->active_plugins;
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
			foreach ( $this->get_active_plugins() as $plugin ) {
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

		/**
		 * Retrieves Registered Plugin by Class Name from Array
		 *
		 * @since tbd
		 *
		 * @return array
		 */
		public function get_registered_plugin( $class ) {
			$plugins = $this->registered_plugins;

			return isset( $plugins[ $class ] ) ? $plugins[ $class ] : array();
		}

		/**
		 * Gets all dependencies or single class requirements
		 * if parent, co, add does not exist use array as is
		 * if they do exist check each one in turn
		 *
		 * @since tbd
		 *
		 * @param       $plugin
		 * @param array $dependencies
		 *
		 * @return bool
		 */
		public function has_valid_dependencies( $plugin, $dependencies = array() ) {

			if ( empty( $dependencies ) ) {
				return true;
			}

			$failed_dependency = 0;

			$admin_notice  = new Tribe__Admin__Notice__Plugin_Download( $plugin['path'] );

			foreach ( $dependencies as $class => $version ) {

				// if no class
				$checked_plugin    = $this->get_registered_plugin( $class );
				if ( ! empty( $checked_plugin ) ) {
					continue;
				}

				$is_active = $this->is_plugin_version( $class, $version );
				if ( ! empty( $is_active ) ) {
					continue;
				}

				$failed_dependency++;
			}

			return 0 < $failed_dependency ? false : true;
		}

		/**
		 *
		 *
		 * @since tbd
		 *
		 * @param       $file_path
		 * @param       $main_class
		 * @param       $version
		 * @param array $classes_req
		 * @param array $dependencies
		 */
		public function register_plugin( $file_path, $main_class, $version, $classes_req = array(), $dependencies = array() ) {

			//add all plugins to registered_plugins
			$this->add_registered_plugin( $main_class, $version, $file_path, $dependencies );

			// Checks to see if the plugins are active for extensions
			if ( ! empty( $classes_req ) && ! $this->has_requisite_plugins( $classes_req ) ) {
				$tribe_plugins = new Tribe__Plugins();
				$admin_notice  = new Tribe__Admin__Notice__Plugin_Download( $file_path );
				foreach ( $classes_req as $class => $plugin_version ) {
					$plugin    = $tribe_plugins->get_plugin_by_class( $class );
					$is_active = $this->is_plugin_version( $class, $plugin_version );
					$admin_notice->add_required_plugin( $plugin['short_name'], $plugin['thickbox_url'], $is_active );
				}
			}

			// only set The Events Calendar and Event Tickets to Active when registering
			if ( 'Tribe__Events__Main' === $main_class || 'Tribe__Tickets__Main' === $main_class ) {
				$this->add_active_plugin( $main_class, $version, $file_path );
			}

		}

		/**
		 * Checks if this plugin has permission to run, if not it notifies the admin
		 *
		 * @since tbd
		 *
		 * @param string $file_path    Full file path to the base plugin file
		 * @param string $main_class   The Main/base class for this plugin
		 * @param string $version      The version
		 * @param array  $classes_req  Any Main class files/tribe plugins required for this to run
		 * @param array  $dependencies an array of dependencies to check
		 *
		 * @return bool Indicates if plugin should continue initialization
		 */
		public function check_plugin( $main_class ) {

			$parent_dependencies = $co_dependencies = $addon_dependencies = false;

			//check if plugin is registered, if not return false
			$plugin = $this->get_registered_plugin( $main_class );
			if ( empty( $plugin ) ) {
				return false;
			}

			// check parent dependencies in add on
			if ( ! empty( $plugin['dependencies']['parent-dependencies'] ) ) {
				$parent_dependencies = $this->has_valid_dependencies( $plugin, $plugin['dependencies']['parent-dependencies'] );
			}
			//check co dependencies in add on
			if ( ! empty( $plugin['dependencies']['co-dependencies'] ) ) {
				$co_dependencies = $this->has_valid_dependencies( $plugin, $plugin['dependencies']['co-dependencies'] );
			}
			//check add-on dependencies from parent
			if ( ! empty( $plugin['dependencies']['addon-dependencies'] ) ) {
				$addon_dependencies = $this->has_valid_dependencies( $plugin, $plugin['dependencies']['addon-dependencies'] );
			}

			//if good then we set as active plugin and continue to load
			if ( $parent_dependencies && $co_dependencies && $addon_dependencies ) {
				$this->add_active_plugin( $main_class, $plugin['version'], $plugin['path'] );

				return true;
			}

			return false;

		}

		/**
		 * Registers older plugins that did not implement this class
		 *
		 * @TODO Consider removing this in 5.0
		 */
		public function add_legacy_plugins() {

			$tribe_plugins = new Tribe__Plugins();

			foreach ( $tribe_plugins->get_list() as $plugin ) {
				// Only add plugin if it's present and not already added
				if ( ! class_exists( $plugin['class'] ) || array_key_exists( $plugin['class'], $this->active_plugins ) ) {
					continue;
				}

				$ver_const = $plugin['class'] . '::VERSION';
				$version = defined( $ver_const ) ? constant( $ver_const ) : null;

				$this->add_active_plugin( $plugin['class'], $version );
			}
		}

	}

}
