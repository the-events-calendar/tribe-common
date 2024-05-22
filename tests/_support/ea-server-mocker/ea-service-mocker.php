<?php
/**
 * Plugin Name: Event Aggregator Server Mocker
 * Plugin URI: http://theAverageDev.com
 * Description: Mock Event Aggregator Server responses and interactions; symlink this from its location to the
 * WordPress plugins folder. Version: 1.0 Author: Modern Tribe Author URI: http://theeventscalendar.com License:
 * Private
 */

$mocker = new Tribe__Events__Aggregator_Mocker();

spl_autoload_register( array( $mocker, 'autoload' ) );

add_action( 'plugins_loaded', array( $mocker, 'mock' ), - 1 );

register_deactivation_hook( __FILE__, array( 'Tribe__Events__Aggregator_Mocker', 'deactivate' ) );

require_once dirname( __FILE__ ) . '/src/functions.php';

class Tribe__Events__Aggregator_Mocker {

	/**
	 * Adding a class that uses  options? Add it here.
	 *
	 * Keep in mind this is a testing tool and you want to put your nose in
	 * the database to check for stuff; refrain from using serialized options
	 * and make your life easier: you do not want to look at nested serialized arrays.
	 * Your database will survive the burden of the additional options: don't worry.
	 *
	 * @var Tribe__Events__Aggregator_Mocker__Option_Provider_Interface[]
	 */
	protected static $option_providers = array(
		'Tribe__Events__Aggregator_Mocker__Service_Options',
		'Tribe__Events__Aggregator_Mocker__License_Options',
		'Tribe__Events__Aggregator_Mocker__Cleaner',
		'Tribe__Events__Aggregator_Mocker__Recorder_Options',
	);

	/**
	 * Adding a class that needs to rebind implementations? Add its name here.
	 *
	 * @var array
	 */
	protected $bindings_providers = array(
		'Tribe__Events__Aggregator_Mocker__Service',
		'Tribe__Events__Aggregator_Mocker__Cleaner',
		'Tribe__Events__Aggregator_Mocker__License',
		// this has to go **after** the service mocker as it decorates a service implementation!
		'Tribe__Events__Aggregator_Mocker__Recorder',
	);

	/**
	 * Sweeps and cleans the mess we made.
	 */
	public static function deactivate() {
		foreach ( self::$option_providers as $provider ) {
			$provided = call_user_func( array( $provider, 'provides_options' ) );
			foreach ( $provided as $option ) {
				delete_option( $option );
			}
		}
		delete_option( 'ea_mocker-enable' );
	}

	public function autoload( $class ) {
		$prefix = 'Tribe__Events__Aggregator_Mocker__';
		if ( strpos( $class, $prefix ) === 0 ) {
			$class_path = str_replace( array( $prefix, '__' ), array( '', DIRECTORY_SEPARATOR ), $class );
			/** @noinspection PhpIncludeInspection */
			require dirname( __FILE__ ) . '/src/' . $class_path . '.php';
		}
	}

	public function mock() {
		$this->hook();

		if ( $this->is_disabled() ) {
			return;
		}

		add_action( 'tribe_events_bound_implementations', array( $this, 'replace_bindings' ) );
	}

	protected function hook() {
		add_action( 'init', array( new Tribe__Events__Aggregator_Mocker__Options_Page(), 'hook' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( new Tribe__Events__Aggregator_Mocker__Cleaner(), 'hook' ) );
		add_action( 'init', array( new Tribe__Events__Aggregator_Mocker__Cleaner_Options(), 'hook' ) );

		if ( $this->is_disabled() ) {
			return;
		}

		add_action( 'init', array( new Tribe__Events__Aggregator_Mocker__Service_Options(), 'hook' ) );
		add_action( 'admin_notices', array( new Tribe__Events__Aggregator_Mocker__Notices(), 'render' ) );
		add_action( 'init', array( new Tribe__Events__Aggregator_Mocker__License_Options(), 'hook' ) );
		add_action( 'init', array( new Tribe__Events__Aggregator_Mocker__Recorder_Options(), 'hook' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'ea-mocker-js', plugin_dir_url( __FILE__ ) . '/js/ea-mocker.js', array( 'jquery' ) );
		wp_enqueue_style( 'ea-mocker-style', plugin_dir_url( __FILE__ ) . '/css/ea-mocker-style.css' );
	}

	/**
	 * Replaces container bindings with mocking implementations.
	 */
	public function replace_bindings() {
		/** @var Tribe__Events__Aggregator_Mocker__Binding_Provider_Interface $provider */
		foreach ( $this->bindings_providers as $provider ) {
			$enable_on = call_user_func( array( $provider, 'enable_on' ) );
			$enabled = false;
			if ( true === $enable_on ) {
				call_user_func( array( $provider, 'bind' ) );
				continue;
			}

			foreach ( $enable_on as $option ) {
				$enabled = $enabled || (bool) get_option( $option );
			}
			if ( ! $enabled ) {
				continue;
			}

			call_user_func( array( $provider, 'bind' ) );
		}
	}

	/**
	 * @return bool
	 */
	protected function is_disabled() {
		$enabled = get_option( 'ea_mocker-enable' );

		return empty( $enabled );
	}
}
