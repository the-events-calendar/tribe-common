<?php
/**
 * Main Tribe Common class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'Tribe__Main' ) ) {
	return;
}

class Tribe__Main {
	const EVENTSERROROPT      = '_tribe_events_errors';
	const OPTIONNAME          = 'tribe_events_calendar_options';
	const OPTIONNAMENETWORK   = 'tribe_events_calendar_network_options';

	const VERSION           = '3.12a1';
	const FEED_URL          = 'https://theeventscalendar.com/feed/';

	protected $plugin_context;
	protected $plugin_context_class;

	public static $tribe_url = 'http://tri.be/';
	public static $tec_url = 'http://theeventscalendar.com/';

	public $plugin_dir;
	public $plugin_path;
	public $plugin_url;

	/**
	 * constructor
	 */
	public function __construct( $context = null ) {
		if ( is_object( $context ) ) {
			$this->plugin_context = $context;
			$this->plugin_context_class = get_class( $context );
		}

		$this->plugin_path = trailingslashit( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );
		$this->plugin_dir  = trailingslashit( basename( $this->plugin_path ) );
		$this->plugin_url  = plugins_url( $this->plugin_dir );

		$this->init_libraries();
		$this->add_hooks();
	}

	/**
	 * Get's the instantiated context of this class. I.e. the object that instantiated this one.
	 */
	public function context() {
		return $this->plugin_context;
	}

	/**
	 * Get's the class name of the instantiated plugin context of this class. I.e. the class name of the object that instantiated this one.
	 */
	public function context_class() {
		return $this->plugin_context_class;
	}

	/**
	 * initializes all required libraries
	 */
	public function init_libraries() {
		Tribe__Debug::instance();
		Tribe__Settings_Manager::instance();

		require_once $this->plugin_path . 'common/src/functions/template-tags/general.php';
		require_once $this->plugin_path . 'common/src/functions/template-tags/date.php';
		require_once $this->plugin_path . 'common/src/functions/template-tags/day.php';
	}

	/**
	 * Registers resources that can/should be enqueued
	 */
	public function register_resources() {
		$resources_url = plugins_url( 'common/src/resources', dirname( dirname( dirname( __FILE__ ) ) ) );

		wp_register_style(
			'tribe-common-admin',
			$resources_url . '/css/tribe-common-admin.css',
			array(),
			apply_filters( 'tribe_events_css_version', self::VERSION )
		);

		wp_register_script(
			'ba-dotimeout',
			$resources_url . '/js/jquery.ba-dotimeout.js',
			array(
				'jquery',
			),
			apply_filters( 'tribe_events_css_version', self::VERSION ),
			true
		);

		wp_register_script(
			'tribe-inline-bumpdown',
			$resources_url . '/js/inline-bumpdown.js',
			array(
				'ba-dotimeout',
			),
			apply_filters( 'tribe_events_css_version', self::VERSION ),
			true
		);
	}

	/**
	 * Adds core hooks
	 */
	public function add_hooks() {
		add_action( 'plugins_loaded', array( 'Tribe__App_Shop', 'instance' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_resources' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function admin_enqueue_scripts( $screen ) {
		wp_enqueue_script( 'tribe-inline-bumpdown' );
		wp_enqueue_style( 'tribe-common-admin' );
	}

	/**
	 * Returns the post types registered by Tribe plugins
	 */
	public static function get_post_types() {
		// we default the post type array to empty in tribe-common. Plugins like TEC add to it
		return apply_filters( 'tribe_post_types', array() );
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
	 * Helper function for getting Post Id. Accepts null or a post id. If no $post object exists, returns false to avoid a PHP NOTICE
	 *
	 * @param int $postId (optional)
	 *
	 * @return int post ID
	 */
	public static function post_id_helper( $post_id = null ) {
		if ( $post_id != null && is_numeric( $post_id ) > 0 ) {
			return (int) $post_id;
		} elseif ( is_object( $post_id ) && ! empty( $post_id->ID ) ) {
			return (int) $post_id->ID;
		} else {
			global $post;
			if ( is_object( $post ) ) {
				return get_the_ID();
			} else {
				return false;
			}
		}
	}

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Tribe__Main
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
