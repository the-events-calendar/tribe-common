<?php
defined( 'WPINC' ) || die; // Do not load directly.

/**
 * Base Extension class
 *
 * Avoid using static states within this class unless it's global for all extensions.
 * Some extension authors may lack a thorough understanding of OOP and inheritance.
 * This is built with such ones in mind.
 *
 * @package Tribe
 * @subpackage Extension
 * @since 4.3.1
 */
abstract class Tribe__Extension {

	/**
	 * Extension arguments
	 *
	 * @var array {
	 *      Each argument for this extension instance
	 *
	 *      @type string $version Extension's semantic version number.
	 *                            Can be manually set in child to boost performance.
	 *
	 *      @type string $url     Extension's tec.com page.
	 *
	 *      @type string $hook    Action/hook to fire init() on.
	 *
	 *      @type string $file    File path containing plugin header.
	 *                            Can be manually set in child to boost performance.
	 *
	 *      @type string $class   Extension's main class name.
	 *
	 *      @type array  $requires {
	 *          Each plugin this extension requires
	 *
	 *          @type string $main_class Minimum version number
	 *      }
	 *
	 *      @type array  $meta_links {
	 *          Meta links added to extension in WP Admin > Plugins page
	 *
	 *          @type string $name URL
	 *      }
	 *
	 *      @type array $plugin_data If the plugin file header is parsed, the
	 *                               resulting data is stored in this.
	 * }
	 */
	protected $args = array();

	/**
	 * The various extension instances
	 *
	 * @var array {
	 *      Each instance of an extension that extends this class
	 *
	 *      @type object $child_class_name instance
	 * }
	 */
	private static $instances = array();

	/**
	 * Get singleton instance of child class
	 *
	 * @param string $child_class (optional) Name of child class.
	 * @param string $plugin_file Required for the first time this instance is called.
	 *
	 * @return object|null The extension's instance, or nothing if it can't be instantiated
	 */
	public static function instance( $child_class = null, $plugin_file = null ) {
		// Defaults to the name of the class that called this instance.
		$child_class = empty( $child_class ) ? self::get_called_class() : $child_class;

		// @see self::get_called_class() for the reason why this could be empty
		if ( empty( $child_class ) ) {
			return null;
		}

		if ( ! isset( self::$instances[ $child_class ] ) ) {

			if ( ! is_string( $plugin_file ) ) {
				_doing_it_wrong(
					__FUNCTION__,
					'The first time you call an instance you must pass the $plugin_file argument.',
					'4.3'
				);
				return null;
			}

			self::$instances[ $child_class ] = new $child_class( $plugin_file );
		}

		return self::$instances[ $child_class ];
	}

	/**
	 * Initializes the extension.
	 *
	 * Waits until after the init hook has fired.
	 *
	 * @param string $plugin_file The full path to the plugin file.
	 */
	final private function __construct( string $plugin_file ) {
		$this->set( 'file', $plugin_file );

		$this->construct();

		// The init() action/hook.
		$init_hook = $this->get_init_hook();

		// Continue plugin run after $init_hook has fired.
		if ( did_action( $init_hook ) > 0 ) {
			$this->register();
		} else {
			add_action( $init_hook, array( $this, 'register' ) );
		}
	}

	/**
	 * Empty function typically overriden by child class
	 */
	protected function construct() {}

	/**
	 * This is where the magic begins
	 *
	 * Declare this inside the child and put any custom code inside of it.
	 */
	abstract function init();

	/**
	 * Adds a Tribe Plugin to the list of plugins this extension depends upon.
	 *
	 * If this plugin is not present or does not exceed the specified version
	 * init() will not run.
	 *
	 * @param string      $main_class      The Main class for this Tribe plugin.
	 * @param string|null $minimum_version Minimum acceptable version of plugin.
	 */
	final protected function add_required_plugin( $main_class, $minimum_version = null ) {
		$this->set( array( 'requires', $main_class ), $minimum_version );
	}

	/**
	 * Set the extension's tec.com URL
	 *
	 * @param string $url URL to the extension's page.
	 */
	final protected function set_url( $url ) {
		$this->set( 'url', $url );
		$this->add_meta_link( __( 'View Details' ), $url );
	}

	/**
	 * Checks if the extension has permission to run, if so runs init() in child class
	 */
	final public function register() {
		$is_plugin_authorized = tribe_register_plugin(
			$this->get_plugin_file(),
			$this->get( 'class', get_class( $this ) ),
			$this->get_version(),
			$this->get( 'requires', array() )
		);

		if ( $is_plugin_authorized ) {
			$this->init();
		}
	}

	/**
	 * Gets the full path to the extension's plugin file
	 *
	 * Sets default if the arg is blank.
	 *
	 * @return string File path
	 */
	final public function get_plugin_file() {
		return $this->get( 'plugin_file' );
	}

	/**
	 * Get the extension's version number
	 *
	 * @return string Semantic version number
	 */
	final public function get_version() {
		return $this->get_arg_or_plugin_data( 'version', 'Version' );
	}

	/**
	 * Get the extension's plugin name
	 *
	 * @return string Plugin name
	 */
	final public function get_name() {
		return $this->get_arg_or_plugin_data( 'name', 'Name' );
	}

	/**
	 * Get the extension's description
	 *
	 * @return string Plugin description
	 */
	final public function get_description() {
		return $this->get_arg_or_plugin_data( 'description', 'Description' );
	}

	/**
	 * Get's the action/hook for the extensions init()
	 *
	 * @return string Action/hook
	 */
	final public function get_init_hook() {
		return $this->get( 'hook', 'tribe_plugins_loaded' );
	}

	/**
	 * Gets the plugin data from the plugin file header
	 *
	 * This is somewhat resource intensive, so data is stored in $args
	 * in case of subsequent calls.
	 *
	 * @see get_plugin_data() for WP Admin only function this is similar to.
	 *
	 * @return array Plugin data; keys match capitalized file header declarations.
	 */
	final public function get_plugin_data() {
		$plugin_data = $this->get( 'plugin_data' );

		// Set version number to match plugin header.
		if ( empty( $plugin_data ) ) {
			$default_headers = array(
				'Name' => 'Plugin Name',
				'PluginURI' => 'Plugin URI',
				'Version' => 'Version',
				'Description' => 'Description',
				'Author' => 'Author',
				'AuthorURI' => 'Author URI',
				'TextDomain' => 'Text Domain',
				'DomainPath' => 'Domain Path',
				'Network' => 'Network',
			);

			$plugin_data = get_file_data( $this->get_plugin_file(), $default_headers, 'plugin' );
			$this->set( 'plugin_data', $plugin_data );
		}

		return $plugin_data;
	}

	/**
	 * Retrieves any args whose default value is stored in the plugin file header
	 *
	 * @param string $arg             The key for arg.
	 * @param string $plugin_data_key The key for the arg in the file header.
	 *
	 * @return string|null String if set, otherwise null.
	 */
	final public function get_arg_or_plugin_data( $arg, $plugin_data_key ) {
		$arg_value = $this->get( $arg, null );

		// See if the arg is already set, if not get default from plugin data and set it.
		if ( null === $arg_value ) {
			$pdata = $this->get_plugin_data();
			$arg_value = isset( $pdata[ $plugin_data_key ] ) ? $pdata[ $plugin_data_key ] : null;
		}

		return $arg_value;
	}

	/**
	 * Sets an arg, including one nested inside of multidimensional array
	 *
	 * @param string|array $key    To set an arg nested multiple levels deep pass an array
	 *                             specifying each key in order as a value.
	 *                             Example: array( 'lvl1', 'lvl2', 'lvl3' );
	 * @param mixed         $value The value.
	 */
	final protected function set( $key, $value ) {

		// Convert strings and such to array.
		$key = (array) $key;

		// This reference will point to the arg, however many levels deep it is.
		$arg = &$this->args;

		// Multiple nested keys specified, iterate through each level.
		foreach ( $key as $i ) {
			// Ensure current array depth can have children set.
			if ( ! is_array( $arg ) ) {
				// $arg is set but is not an array. Converting it to an array
				// would likely lead to unexpected problems for whatever first set it.
				$error = sprintf(
					'Attempted to set $args[%1s] but %2s is already set and is not an array.',
					implode( $key, '][' ),
					$i
				);

				_doing_it_wrong( __FUNCTION__, esc_html( $error ), '4.3' );
				break;
			} elseif ( ! isset( $arg[ $i ] ) ) {
				$arg[ $i ] = array();
			}

			// Dive one level deeper into nested array.
			$arg = &$arg[ $i ];
		}

		$arg = $value;
	}

	/**
	 * Retrieves arg, including one nested inside of a multidimensional array
	 *
	 * @param string|array $key     To select an arg nested multiple levels deep pass an
	 *                              array specifying each key in order as a value.
	 *                              Example: array( 'lvl1', 'lvl2', 'lvl3' );
	 * @param null         $default Value to return if nothing is set.
	 *
	 * @return mixed Returns the args value or the default if arg is not found.
	 */
	final public function get( $key, $default = null ) {
		return self::search_var( $this->args, $key, $default );
	}

	/**
	 * Find a value nested inside of a multidimensional array or object
	 *
	 * Example: search_var( $a, [ 0, 1, 2 ] ) returns the value of $a[0][1][2].
	 *
	 * @param  array $variable  Array or object to search within.
	 * @param  array $indexes   Specify each nested index in order.
	 *                          Example: array( 'lvl1', 'lvl2' );
	 * @param  mixed $default   Default value if the search finds nothing.
	 *
	 * @return mixed The value of the specified index or the default if not found.
	 */
	public static function search_var( $variable, $indexes, $default = null ) {
		if ( is_object( $variable ) ) {
			$variable = (array) $variable;
		}

		if ( ! is_array( $variable ) ) {
			return $default;
		}

		foreach ( (array) $indexes as $index ) {
			if ( ! is_array( $variable ) || ! isset( $variable[ $index ] ) ) {
				$variable = $default;
				break;
			}

			$variable = $variable[ $index ];
		}

		return $variable;
	}

	/**
	 * Add a meta link to the plugins list page
	 *
	 * @param string $title Title of the meta link.
	 * @param string $url   URL for the link.
	 */
	final public function add_meta_link( $title, $url ) {
		$this->set( array( 'meta_links', $title ), $url );

		$callback = array( $this, 'filter_meta_links' );
		// See if we need to hook our filter up.
		if ( ! has_action( 'plugin_row_meta', $callback ) ) {
			add_action( 'plugin_row_meta', $callback, 10, 2 );
		}
	}

	/**
	 * Adds meta links to this extension on the plugins list page
	 *
	 * @param array  $links The current plugin's links.
	 * @param string $file  The plugin currently being filtered.
	 *
	 * @return array Filtered action links array.
	 */
	final public function filter_meta_links( $links, $file ) {
		$plugin_basename = basename( $this->get_plugin_file() );
		$arg_links = $this->get( 'meta_links', array() );

		if ( $file !== $plugin_basename ) {
			return $links;
		}

		foreach ( $arg_links as $title => $url ) {
			$links[] = sprintf(
				'<a href="%1s" target="_blank">%2s</a>',
				esc_url( $url ),
				esc_html( $title )
			);
		}

		return $links;
	}

	/**
	 * Gets the name of the class the method is called in; typically will be a child class
	 *
	 * This uses some hackery if the server is on PHP 5.2, and it can fail in rare
	 * circumstances causing a null value to be returned.
	 *
	 * @return string|null Class name
	 */
	final protected static function get_called_class() {
		$class_name = null;

		if ( function_exists( 'get_called_class' ) ) {
			// For PHP 5.3+ we can use the late static binding class name.
			$class_name = get_called_class();
		} else {
			// For PHP 5.2 and under we hack around the lack of late static bindings.
			try {
				$backtraces = debug_backtrace();

				// Grab each class from the backtrace.
				foreach ( $backtraces as $i ) {
					$class = null;

					if ( array_key_exists( 'class', $i ) ) {
						// Direct call to a class.
						$class = $i['class'];
					} elseif (
						array_key_exists( 'function', $i ) &&
						strpos( $i['function'], 'call_user_func' ) === 0 &&
						array_key_exists( 'args', $i ) &&
						is_array( $i['args'] ) &&
						is_array( $i['args'][0] ) &&
						isset( $i['args'][0][0] )
					) {
						// Found a call from call_user_func... and $i['args'][0][0] is present
						// indicating a static call to a method.
						$class = $i['args'][0][0];
					} else {
						// Slight performance boost from skipping ahead.
						continue;
					}

					// Check to see if the parent is the current class.
					// The first backtrace with a matching parent is our class.
					if ( get_parent_class( $class ) === __CLASS__ ) {
						$class_name = $class;
						break;
					}
				}
			} catch ( Exception $e ) {
				// Host has disabled or misconfigured debug_backtrace().
				$exception = new Tribe__Exception( $e );
				$exception->handle();
			}
		}

		// Class name was not set by debug_backtrace() hackery.
		if ( null === $class_name ) {
			tribe_notice( 'tribe_debug_backtrace_disabled', array( __CLASS__, 'notice_debug_backtrace' ) );
		}

		return $class_name;
	}

	/**
	 * Echoes error message indicating user is on PHP 5.2 and debug_backtrace is disabled
	 */
	final public static function notice_debug_backtrace() {
		printf(
			'<p>%s</p>',
			esc_html__( 'Unable to run Tribe Extensions. Your website host is running PHP 5.2 or older, and has likely disabled or misconfigured debug_backtrace(). You, or your website host, will need to upgrade PHP or properly configure debug_backtrace() for Tribe Extensions to work.', 'tribe-common' )
		);
	}

	/**
	 * Prevent cloning the singleton with 'clone' operator
	 *
	 * @return void
	 */
	final private function __clone() {
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
	final private function __wakeup() {
		_doing_it_wrong(
			__FUNCTION__,
			'Can not use this method on singletons.',
			'4.3'
		);
	}
}
