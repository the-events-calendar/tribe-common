<?php
/**
 * Main Tribe Common class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'Tribe__Events__Main' ) ) {
	return;
}

class Tribe__Main {
	const EVENTSERROROPT      = '_tribe_events_errors';
	const OPTIONNAME          = 'tribe_events_calendar_options';
	const OPTIONNAMENETWORK   = 'tribe_events_calendar_network_options';

	const VERSION           = '3.12a1';
	const INFO_API_URL      = 'http://wpapi.org/api/plugin/the-events-calendar.php';
	const WP_PLUGIN_URL     = 'http://wordpress.org/extend/plugins/the-events-calendar/';

	public $plugin_dir;
	public $plugin_path;
	public $plugin_url;

	/**
	 * constructor
	 */
	public function __construct() {
		$this->plugin_path = trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) );
		$this->plugin_dir  = trailingslashit( basename( $this->plugin_path ) );
		$this->plugin_url  = plugins_url( $this->plugin_dir );

		$this->init_libraries();
	}

	/**
	 * initializes all required libraries
	 */
	public function init_libraries() {
		Tribe__Debug::instance();
		Tribe__Settings_Manager::instance();
	}

	/**
	 * Insert an array after a specified key within another array.
	 *
	 * @param $key
	 * @param $source_array
	 * @param $insert_array
	 *
	 * @return array
	 *
	 */
	public static function array_insert_after_key( $key, $source_array, $insert_array ) {
		if ( array_key_exists( $key, $source_array ) ) {
			$position     = array_search( $key, array_keys( $source_array ) ) + 1;
			$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
		} else {
			// If no key is found, then add it to the end of the array.
			$source_array += $insert_array;
		}

		return $source_array;
	}

	/**
	 * Insert an array immediately before a specified key within another array.
	 *
	 * @param $key
	 * @param $source_array
	 * @param $insert_array
	 *
	 * @return array
	 */
	public static function array_insert_before_key( $key, $source_array, $insert_array ) {
		if ( array_key_exists( $key, $source_array ) ) {
			$position     = array_search( $key, array_keys( $source_array ) );
			$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
		} else {
			// If no key is found, then add it to the end of the array.
			$source_array += $insert_array;
		}

		return $source_array;
	}

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Tribe__Events__Main
	 */
	public static function instance() {
		static $instance;

		if ( ! $instance ) {
			$class_name = __CLASS__;
			$instance = new $class_name;
		}

		return $instance;
	}
}
