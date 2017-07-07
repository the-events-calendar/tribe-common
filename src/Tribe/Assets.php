<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class used to register and enqueue assets across our plugins
 *
 * @since 4.3
 */
class Tribe__Assets {
	/**
	 * Static Singleton Holder
	 *
	 * @var self|null
	 */
	protected static $instance;

	/**
	 * Stores all the Assets and it's configurations
	 *
	 * @var array
	 */
	private $assets = array();

	/**
	 * Stores the localized scripts for reference
	 *
	 * @var array
	 */
	private $localized = array();

	/**
	 * Static Singleton Factory Method
	 *
	 * @since 4.3
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
	 *
	 * @since 4.3
	 */
	private function __construct() {
		// Hook the actual registering of
		add_action( 'init', array( $this, 'register_in_wp' ), 1, 0 );
	}

	/**
	 * Register the Assets on the correct hooks
	 *
	 * @since 4.3
	 *
	 * @return void
	 */
	public function register_in_wp( $assets = null ) {
		if ( is_null( $assets ) ) {
			$assets = $this->assets;
		}

		if ( ! is_array( $assets ) ) {
			$assets = array( $assets );
		}

		foreach ( $assets as $asset ) {
			if ( 'js' === $asset->type ) {
				wp_register_script( $asset->slug, $asset->url, $asset->deps, $asset->version, $asset->in_footer );
			} else {
				wp_register_style( $asset->slug, $asset->url, $asset->deps, $asset->version, $asset->media );
			}

			// Register that this asset is actually registered on the WP methods
			$asset->is_registered = true;

			// If we don't have an action we don't even register the action to enqueue
			if ( ! is_string( $asset->action ) ) {
				continue;
			}

			// Enqueue the registered assets at the appropriate time
			if ( did_action( $asset->action ) > 0 ) {
				$this->enqueue();
			} else {
				add_action( $asset->action, array( $this, 'enqueue' ), $asset->priority );
			}
		}
	}

	/**
	 * Enqueues registered assets.
	 *
	 * This method is called on whichever action (if any) was declared during registration.
	 *
	 * It can also be called directly with a list of asset slugs to forcibly enqueue, which may be
	 * useful where an asset is required in a situation not anticipated when it was originally
	 * registered.
	 *
	 * @since 4.3
	 *
	 * @param string|array $forcibly_enqueue
	 */
	public function enqueue( $forcibly_enqueue = null ) {
		$forcibly_enqueue = (array) $forcibly_enqueue;

		foreach ( $this->assets as $asset ) {
			if ( $asset->already_enqueued ) {
				continue;
			}

			// Should this asset be enqueued regardless of the current filter/any conditional requirements?
			$must_enqueue = in_array( $asset->slug, $forcibly_enqueue );

			// Skip if the correct hook hasn't begun firing yet (unless we are forcibly enqueuing)
			if ( did_action( $asset->action ) < 1 && ! $must_enqueue ) {
				continue;
			}

			// If any single conditional returns true, then we need to enqueue the asset
			if ( ! is_string( $asset->action ) && ! $must_enqueue ) {
				continue;
			}

			// If this asset was late called
			if ( ! $asset->is_registered ) {
				$this->register_in_wp( $asset );
			}

			// Default to enqueuing the asset if there are no conditionals,
			// and default to not enqueuing it if there *are* conditionals
			$enqueue = empty( $asset->conditionals );

			// If we have a set of conditionals we loop on then and get if they are true
			foreach ( $asset->conditionals as $conditional ) {
				$enqueue = call_user_func( $conditional );
				if ( $enqueue ) {
					break;
				}
			}

			/**
			 * Allows developers to hook-in and prevent an asset from been loaded
			 *
			 * @since 4.3
			 *
			 * @param bool   $enqueue If we should enqueue or not a given asset
			 * @param object $asset   Which asset we are dealing with
			 */
			$enqueue = apply_filters( 'tribe_asset_enqueue', $enqueue, $asset );

			/**
			 * Allows developers to hook-in and prevent an asset from been loaded
			 *
			 * @since 4.3
			 *
			 * @param bool   $enqueue If we should enqueue or not a given asset
			 * @param object $asset   Which asset we are dealing with
			 */
			$enqueue = apply_filters( 'tribe_asset_enqueue_' . $asset->slug, $enqueue, $asset );

			if ( ! $enqueue && ! $must_enqueue ) {
				continue;
			}

			if ( 'js' === $asset->type ) {
				wp_enqueue_script( $asset->slug );

				// Only localize on JS and if we have data
				if ( ! empty( $asset->localize ) ) {
					/**
					 * check to ensure we haven't already localized it before
					 * @since 4.5.8
					 */
					if ( is_array( $asset->localize ) ) {
						foreach ( $asset->localize as $local_asset ) {
							if ( ! in_array( $local_asset->name, $this->localized ) ) {
								wp_localize_script( $asset->slug, $local_asset->name, $local_asset->data );
								$this->localized[] = $local_asset->name;
							}
						}
					} else {
						if ( ! in_array( $asset->localize->name, $this->localized ) ) {
							wp_localize_script( $asset->slug, $asset->localize->name, $asset->localize->data );
							$this->localized[] = $asset->localize->name;
						}
					}
				}
			} else {
				wp_enqueue_style( $asset->slug );
			}

			$asset->already_enqueued = true;
		}
	}

	/**
	 * Returns the path to a minified version of a js or css file, if it exists.
	 * If the file does not exist, returns false.
	 *
	 * @since 4.3
	 *
	 * @param string $url   The path or URL to the un-minified file.
	 *
	 * @return string|false The path/url to minified version or false, if file not found.
	 */
	public static function maybe_get_min_file( $url ) {
		$urls = array();
		// If need add the Min Files
		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
			if ( substr( $url, - 3, 3 ) === '.js' ) {
				$urls[] = substr_replace( $url, '.min', - 3, 0 );
			}

			if ( substr( $url, - 4, 4 ) === '.css' ) {
				$urls[] = substr_replace( $url, '.min', - 4, 0 );
			}
		}

		// Add the actual url after having the Min file added
		$urls[] = $url;

		// Check for all Urls added to the array
		foreach ( $urls as $key => $url ) {
			//set path to file for Windows
			$file = $url;
			//Set variable for content normalized directory
			$normalized_content_dir = wp_normalize_path( WP_CONTENT_DIR );

			//Detect if $url is actually a file path
			if ( false !== strpos( $url, $normalized_content_dir ) ) {
				// Turn file Path to URL in Windows
				$url = str_replace( $normalized_content_dir, content_url(), $url );
			} else {
				// Turn URL into file Path
				$file = str_replace( content_url(), $normalized_content_dir, $url );
			}

			//if file exists return url
			if ( file_exists( $file ) ) {
				return $url;
			}
		}

		// If we don't have any real file return false
		return false;
	}

	/**
	 * Register an Asset and attach a callback to the required action to display it correctly
	 *
	 * @since 4.3
	 *
	 * @param  object       $origin    The main Object for the plugin you are enqueueing the script/style for
	 * @param  string       $slug      Slug to save the asset
	 * @param  string       $file      Which file will be loaded, either CSS or JS
	 * @param  array        $deps      Dependencies
	 * @param  string|null  $action    (Optional) A WordPress Action, if set needs to happen after: `wp_enqueue_scripts`, `admin_enqueue_scripts`, or `login_enqueue_scripts`
	 * @param  string|array $arguments {
	 *     Optional. Array or string of parameters for this asset
	 *
	 *     @type string|null  $action         Which WordPress action this asset will be loaded on
	 *     @type int          $priority       Priority in which this asset will be loaded on the WordPress action
	 *     @type string       $file           The relative path to the File that will be enqueued, uses the $origin to get the full path
	 *     @type string       $type           Asset Type, `js` or `css`
	 *     @type array        $deps           An array of other asset as dependencies
	 *     @type string       $version        Version number, used for cache expiring
	 *     @type string       $media          Used only for CSS, when to load the file
	 *     @type bool         $in_footer      A boolean determining if the javascript should be loaded on the footer
	 *     @type array|object $localize       Variables needed on the JavaScript side {
	 *          @type string 		$name     Name of the JS variable
	 *          @type string|array  $data     Contents of the JS variable
	 *     }
	 *     @type callable[]   $conditionals   An callable method or an array of them, that will determine if the asset is loaded or not
	 * }
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
				$origin = call_user_func( array( $origin, 'instance' ) );
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

		// Fetches the version on the Origin Version constant
		$version = constant( $origin_name . '::VERSION' );

		// Default variables to prevent notices
		$defaults = array(
			'action'        => null,
			'priority'      => 10,
			'file'          => false,
			'type'          => null,
			'deps'          => array(),
			'version'       => $version,
			'media'         => 'all',
			'in_footer'     => true,
			'localize'      => array(),
			'conditionals'  => array(),
			'is_registered' => false,
		);

		// Merge Arguments
		$asset = (object) wp_parse_args( $arguments, $defaults );

		// Enforce these one
		$asset->slug             = $slug;
		$asset->file             = $file;
		$asset->deps             = $deps;
		$asset->origin           = $origin;
		$asset->origin_name      = $origin_name;
		$asset->action           = $action;
		$asset->already_enqueued = false;

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
		 * @todo remove on 4.6
		 * @deprecated 4.3
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

		// Ensures that we have a priority over 1
		if ( $asset->priority < 1 ) {
			$asset->priority = 1;
		}

		$is_vendor = strpos( $asset->file, 'vendor/' ) !== false ? true : false;

		// Setup the actual URL
		if ( filter_var( $asset->file, FILTER_VALIDATE_URL ) ) {
			$asset->url = $asset->file;
		} else {
			$asset->url = $this->maybe_get_min_file( tribe_resource_url( $asset->file, false, ( $is_vendor ? '' : null ), $asset->origin ) );
		}

		// If you are passing localize, you need `name` and `data`
		if ( ! empty( $asset->localize ) && ( is_array( $asset->localize ) || is_object( $asset->localize ) ) ) {
			$asset->localize = (object) $asset->localize;
			if ( is_array( $asset->localize ) && empty( $asset->localize['name'] )  ) {
				foreach ( $asset->localize as $index => $local ) {
					$asset->localize[ $index ] = (object) $local;
				}
			} else {
				$asset->localize = (object) $asset->localize;

				// if we don't have both reset localize
				if ( ! isset( $asset->localize->data, $asset->localize->name ) ) {
					$asset->localize = array();
				}
			}
		}

		// Looks for a single conditional callable and places it in an Array
		if ( ! empty( $asset->conditionals ) && is_callable( $asset->conditionals ) ) {
			$asset->conditionals = array( $asset->conditionals );
		}

		/**
		 * Filter an Asset loading variables
		 *
		 * @param object $asset
		 */
		$asset = apply_filters( 'tribe_asset_pre_register', $asset );

		// Set the Asset on the array of notices
		$this->assets[ $slug ] = $asset;

		// Return the Slug because it might be modified
		return $asset;
	}

	/**
	 * Removes an Asset from been registered and enqueue
	 *
	 * @since 4.3
	 *
	 * @param  string $slug Slug of the Asset
	 *
	 * @return bool
	 */
	public function remove( $slug ) {
		if ( ! $this->exists( $slug ) ) {
			return false;
		}

		unset( $this->assets[ $slug ] );
		return true;
	}

	/**
	 * Get the Asset Object configuration
	 *
	 * @since 4.3
	 *
	 * @param  string $slug Slug of the Asset
	 *
	 * @return bool
	 */
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

	/**
	 * Checks if an Asset exists
	 *
	 * @param  string $slug Slug of the Asset
	 *
	 * @return bool
	 */
	public function exists( $slug ) {
		return is_object( $this->get( $slug ) ) ? true : false;
	}
}
