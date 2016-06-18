<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class used to register and enqueue assets across our plugins
 */
class Tribe__Assets {
	/**
	 * Static Singleton Holder
	 *
	 * @var self|null
	 */
	protected static $instance;

	/**
	 * Static Singleton Factory Method
	 *
	 * @return self
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register the Methods in the correct places
	 */
	private function __construct() {
		// Hook the actual rendering of notices
		add_action( 'init', array( $this, 'hook' ) );
	}

	public function hook() {
		foreach ( $this->assets as $asset ) {
			if ( ! is_string( $asset->action ) ) {
				continue;
			}

			add_action( $asset->action, array( $this, 'enqueue' ), $asset->priority );
		}
	}

	public function enqueue() {
		foreach ( $this->assets as $asset ) {
			// Skip if we are not on the correct filter
			if ( current_filter() !== $asset->action ) {
				continue;
			}

			// If here is no action we are just registering this asset
			if ( ! is_string( $asset->action ) ) {
				continue;
			}

			// If we have a set of conditionals we loop on then and get if they are true
			if ( ! empty( $asset->conditionals ) ) {
				$conditionals = array();
				foreach ( $asset->conditionals as $conditional ) {
					$conditionals[] = call_user_func_array( $conditional, array() );
				}

				// If any of them are false we skip this one
				if ( in_array( false, $conditionals ) ) {
					continue;
				}
			}

			if ( 'js' === $asset->type ) {
				wp_enqueue_script( $asset->slug );

				// Only localize on JS and if we have data
				if ( ! empty( $asset->localize ) ) {
					wp_localize_script( $asset->slug, $asset->localize->name, $asset->localize->data );
				}
			} else {
				wp_enqueue_style( $asset->slug );
			}
		}
	}

	/**
	 * Returns the path to a minified version of a js or css file, if it exists.
	 * If the file does not exist, returns false.
	 *
	 * @param string $url   The path or URL to the un-minified file.
	 *
	 * @return string|false The path/url to minified version or false, if file not found.
	 */
	public static function maybe_get_min_file( $url ) {
		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
			if ( substr( $url, - 3, 3 ) === '.js' ) {
				$url = substr_replace( $url, '.min', - 3, 0 );
			}

			if ( substr( $url, - 4, 4 ) === '.css' ) {
				$url = substr_replace( $url, '.min', - 4, 0 );
			}
		}

		$file = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $url );
		if ( file_exists( $file ) ) {
			return $url;
		} else {
			return false;
		}
	}

	/**
	 * Stores all the Assets and it's configurations
	 *
	 * @var array
	 */
	private $assets = array();

	/**
	 * Register a Asset and attach a callback to the required action to display it correctly
	 *
	 * @param  string   $slug      Slug to save the asset
	 * @param  callable $callback  A callable Method/Fuction to actually display the asset
	 * @param  array    $arguments Arguments to Setup a asset
	 *
	 * @return string
	 */
	public function register( $origin, $slug, $file, $deps = array(), $action = null, $arguments = array() ) {
		// Prevent weird stuff here
		$slug = sanitize_title_with_dashes( $slug );

		if ( $this->exists( $slug ) ) {
			return $this->get( $slug );
		}

		if ( is_string( $origin ) ) {
			// Origin needs to be a class with a `instance` method and a Version constant
			if ( class_exists( $origin ) && method_exists( $origin, 'instance' ) && defined( $origin . '::VERSION' ) ) {
				$origin = call_user_func_array( array( $origin, 'instance' ) );
			}
		}

		if ( is_object( $origin ) ) {
			$origin_name = get_class( $origin );

			if ( ! defined( $origin_name . '::VERSION' ) ) {
				// If we have a Object and we don't have instance or version
				return false;
			}
		} else {
			return false;
		}

		// Uses Common by default
		$version = constant( $origin_name . '::VERSION' );

		// Default variables to prevent notices
		$defaults = array(
			'action'       => null,
			'priority'     => 10,
			'file'         => false,
			'type'         => null,
			'deps'         => array(),
			'version'      => $version,
			'media'        => 'all',
			'in_footer'    => true,
			'localize'     => array(),
			'conditionals' => array(),
		);

		// Merge Arguments
		$asset = (object) wp_parse_args( $arguments, $defaults );

		// Enforce these one
		$asset->slug        = $slug;
		$asset->file        = $file;
		$asset->deps        = $deps;
		$asset->origin      = $origin;
		$asset->origin_name = $origin_name;
		$asset->action      = $action;

		// If we don't have a type on the arguments we grab from the File path
		if ( is_null( $asset->type ) ) {
			if ( substr( $asset->file, -3, 3 ) === '.js' ) {
				$asset->type = 'js';
			} elseif ( substr( $asset->file, -4, 4 ) === '.css' ) {
				$asset->type = 'css';
			}
		}

		// If asset type is wrong don't register
		if ( ! in_array( $asset->type, array( 'js', 'css' ) ) ) {
			return false;
		}

		/**
		 * Deprecated filter to allow changing version based on the type of Asset
		 *
		 * @param string $version
		 */
		$asset->version = apply_filters( "tribe_events_{$asset->type}_version", $asset->version );

		/**
		 * Filter to change version number on assets
		 *
		 * @param string $version
		 * @param object $asset
		 */
		$asset->version = apply_filters( 'tribe_asset_version', $asset->version, $asset );

		// Clean these
		$asset->priority  = absint( $asset->priority );
		$asset->in_footer = (bool) $asset->in_footer;
		$asset->media     = esc_attr( $asset->media );

		$is_vendor = strpos( $asset->file, 'vendor/' ) !== false ? true : false;

		// Setup the actual URL
		$asset->url = $this->maybe_get_min_file( tribe_resource_url( $asset->file, false, ( $is_vendor ? '' : null ), $asset->origin ) );

		// If you are passing localize, you need `name` and `data`
		if ( ! empty( $asset->localize ) && ( is_array( $asset->localize ) || is_object( $asset->localize ) ) ) {
			$asset->localize = (object) $asset->localize;

			// if we don't have both reset localize
			if ( ! isset( $asset->localize->data, $asset->localize->name ) ) {
				$asset->localize = array();
			}
		}

		/**
		 * Filter an Asset loading variables
		 * @param object $asset
		 */
		$asset = apply_filters( 'tribe_asset', $asset );

		// Set the Asset on the array of notices
		$this->assets[ $slug ] = $asset;

		if ( 'js' === $asset->type ) {
			wp_register_script( $asset->slug, $asset->url, $asset->deps, $asset->version, $asset->in_footer );
		} else {
			wp_register_style( $asset->slug, $asset->url, $asset->deps, $asset->version, $asset->media );
		}

		// Return the Slug because it might be modified
		return $asset;
	}

	public function remove( $slug ) {
		if ( ! $this->exists( $slug ) ) {
			return false;
		}

		unset( $this->assets[ $slug ] );
		return true;
	}

	public function get( $slug = null ) {
		// Prevent weird stuff here
		$slug = sanitize_title_with_dashes( $slug );

		if ( is_null( $slug ) ) {
			return $this->assets;
		}

		if ( ! empty( $this->assets[ $slug ] ) ) {
			return $this->assets[ $slug ];
		}

		return null;
	}

	public function exists( $slug ) {
		return is_object( $this->get( $slug ) ) ? true : false;
	}
}