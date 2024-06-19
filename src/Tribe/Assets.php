<?php

use TEC\Common\StellarWP\Assets\Asset;
use TEC\Common\StellarWP\Assets\Assets;
use Tribe__Utils__Array as Arr;

/**
 * Class used to register and enqueue assets across our plugins.
 *
 * @since 4.3
 */
class Tribe__Assets {
	/**
	 * Static Singleton Factory Method.
	 *
	 * @since 4.3
	 *
	 * @return self
	 */
	public static function instance() {
		return tribe( 'assets' );
	}

	/**
	 * Register the Methods in the correct places.
	 *
	 * @since 4.3
	 * @since 5.3.0 Emptied of all hooks in favor of the stellarwp/assets library.
	 */
	public function __construct() {
	}

	/**
	 * Hooks the filters used to register the assets.
	 *
	 * @since 5.3.0
	 *
	 * @return void
	 */
	public function hook(): void {
		add_filter( 'stellarwp/assets/tec/enqueue', [ $this, 'proxy_enqueue_filter' ], 10, 2 );
	}

	/**
	 * Proxies the generic stellarwp/assets/enqueue filter to apply the TEC ones.
	 *
	 * @since 5.3.0
	 *
	 * @param bool  $enqueue If we should enqueue or not a given asset.
	 * @param Asset $asset Which asset we are dealing with.
	 *
	 * @return mixed|null
	 */
	public function proxy_enqueue_filter( $enqueue, $asset ) {
		/**
		 * Allows developers to hook-in and prevent an asset from being loaded.
		 *
		 * @since 4.3
		 * @since 5.3.0 Moved here from the `Tribe__Assets` class.
		 *
		 * @param bool $enqueue If we should enqueue or not a given asset.
		 * @param object $asset Which asset we are dealing with.
		 */
		$enqueue = apply_filters( 'tribe_asset_enqueue', $enqueue, $asset );

		/**
		 * Allows developers to hook-in and prevent an asset from being loaded.
		 *
		 * @since 4.3
		 * @since 5.3.0 Moved here from the `Tribe__Assets` class.
		 *
		 * @param bool $enqueue If we should enqueue or not a given asset.
		 * @param object $asset Which asset we are dealing with.
		 */
		return apply_filters( "tribe_asset_enqueue_{$asset->get_slug()}", $enqueue, $asset );
	}

	/**
	 * Depending on how certain scripts are loaded and how much cross-compatibility is required we need to be able to
	 * create noConflict backups and restore other scripts, which normally need to be printed directly on the scripts.
	 *
	 * @since 5.0.0
	 *
	 * @param string $tag    Tag we are filtering.
	 * @param string $handle Which is the ID/Handle of the tag we are about to print.
	 *
	 * @return string Script tag with the before and after strings attached to it.
	 *
	 * @deprecated 5.3.0
	 */
	public function filter_print_before_after_script( $tag, $handle ): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		_deprecated_function( __METHOD__, '5.3.0', '' );
		return '';
	}

	/**
	 * Handles adding localization data, when attached to `script_loader_tag` which allows dependencies to load in their
	 * localization data as well.
	 *
	 * @since 4.13.0
	 *
	 * @param string $tag    Tag we are filtering.
	 * @param string $handle Which is the ID/Handle of the tag we are about to print.
	 *
	 * @return string Script tag with the localization variable HTML attached to it.
	 *
	 * @deprecated 5.3.0
	 */
	public function filter_add_localization_data( $tag, $handle ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		_deprecated_function( __METHOD__, '5.3.0', '' );
		return '';
	}

	/**
	 * Filters the Script tags to attach Async and/or Defer based on the rules we set in our Asset class.
	 *
	 * @since 4.13.0
	 *
	 * @param string $tag    Tag we are filtering.
	 * @param string $handle Which is the ID/Handle of the tag we are about to print.
	 *
	 * @return string Script tag with the defer and/or async attached.
	 *
	 * @deprecated 5.3.0
	 */
	public function filter_tag_async_defer( $tag, $handle ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		_deprecated_function( __METHOD__, '5.3.0', '' );
		return '';
	}

	/**
	 * Filters the Script tags to attach type=module based on the rules we set in our Asset class.
	 *
	 * @since 4.14.14
	 *
	 * @param string $tag    Tag we are filtering.
	 * @param string $handle Which is the ID/Handle of the tag we are about to print.
	 *
	 * @return string Script tag with the type=module
	 *
	 * @deprecated 5.3.0
	 */
	public function filter_modify_to_module( $tag, $handle ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		_deprecated_function( __METHOD__, '5.3.0', '' );
		return '';
	}

	/**
	 * Register the Assets on the correct hooks.
	 *
	 * @since 4.3
	 * @param array|object|null $assets Array of asset objects, single asset object, or null.
	 *
	 * @return void
	 *
	 * @deprecated 5.3.0
	 */
	public function register_in_wp( $assets = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		_deprecated_function( __METHOD__, '5.3.0', '' );
	}

	/**
	 * Enqueues registered assets based on their groups.
	 *
	 * @since 4.7
	 * @since 5.3.0 Refactored to use the stellarwp/assets library.
	 *
	 * @uses  TEC\Common\StellarWP\Assets\Assets::enqueue_group()
	 *
	 * @param string|array $groups           Which groups will be enqueued.
	 * @param bool         $forcibly_enqueue Whether to ignore conditional requirements when enqueuing.
	 */
	public function enqueue_group( $groups, $forcibly_enqueue = true ) {
		Assets::instance()->enqueue_group( $groups, $forcibly_enqueue );
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
	 * @since 5.3.0 Refactored to use the stellarwp/assets library.
	 *
	 * @uses  TEC\Common\StellarWP\Assets\Assets::enqueue()
	 *
	 * @param string|array $assets           Which assets to enqueue.
	 * @param bool         $forcibly_enqueue Whether to ignore conditional requirements when enqueuing.
	 */
	public function enqueue( $assets = [], $forcibly_enqueue = true ) {
		Assets::instance()->enqueue( $assets, $forcibly_enqueue );
	}

	/**
	 * Returns the path to a minified version of a js or css file, if it exists.
	 * If the file does not exist, returns false.
	 *
	 * @since 4.3
	 * @since 4.5.10 Removed ability to pass a filepath as $url
	 *
	 * @param string $url The absolute URL to the un-minified file.
	 *
	 * @return string|false The url to the minified version or false, if file not found.
	 */
	public static function maybe_get_min_file( $url ) {
		static $wpmu_plugin_url;
		static $wp_plugin_url;
		static $wp_content_url;
		static $plugins_url;
		static $base_dirs;

		$urls = [];
		if ( ! isset( $wpmu_plugin_url ) ) {
			$wpmu_plugin_url = set_url_scheme( WPMU_PLUGIN_URL );
		}

		if ( ! isset( $wp_plugin_url ) ) {
			$wp_plugin_url = set_url_scheme( WP_PLUGIN_URL );
		}

		if ( ! isset( $wp_content_url ) ) {
			$wp_content_url = set_url_scheme( WP_CONTENT_URL );
		}

		if ( ! isset( $plugins_url ) ) {
			$plugins_url = plugins_url();
		}

		if ( ! isset( $base_dirs ) ) {
			$base_dirs[ WPMU_PLUGIN_DIR ] = wp_normalize_path( WPMU_PLUGIN_DIR );
			$base_dirs[ WP_PLUGIN_DIR ]   = wp_normalize_path( WP_PLUGIN_DIR );
			$base_dirs[ WP_CONTENT_DIR ]  = wp_normalize_path( WP_CONTENT_DIR );
		}

		if ( 0 === strpos( $url, $wpmu_plugin_url ) ) {
			// URL inside WPMU plugin dir.
			$base_dir = $base_dirs[ WPMU_PLUGIN_DIR ];
			$base_url = $wpmu_plugin_url;
		} elseif ( 0 === strpos( $url, $wp_plugin_url ) ) {
			// URL inside WP plugin dir.
			$base_dir = $base_dirs[ WP_PLUGIN_DIR ];
			$base_url = $wp_plugin_url;
		} elseif ( 0 === strpos( $url, $wp_content_url ) ) {
			// URL inside WP content dir.
			$base_dir = $base_dirs[ WP_CONTENT_DIR ];
			$base_url = $wp_content_url;
		} elseif ( 0 === strpos( $url, $plugins_url ) ) {
			$base_dir = $base_dirs[ WP_PLUGIN_DIR ];
			$base_url = $plugins_url;
		} else {
			// Resource needs to be inside wp-content or a plugins dir.
			return false;
		}

		$script_debug = defined( 'SCRIPT_DEBUG' ) && tribe_is_truthy( SCRIPT_DEBUG );

		// Strip the plugin URL and make this relative.
		$relative_location = str_replace( $base_url, '', $url );

		if ( $script_debug ) {
			// Add the actual url after having the min file added.
			$urls[] = $relative_location;
		}

		// If needed add the Min Files.
		if ( substr( $relative_location, -3, 3 ) === '.js' ) {
			$urls[] = substr_replace( $relative_location, '.min', - 3, 0 );
		} elseif ( substr( $relative_location, -4, 4 ) === '.css' ) {
			$urls[] = substr_replace( $relative_location, '.min', - 4, 0 );
		}

		if ( ! $script_debug ) {
			// Add the actual url after having the min file added.
			$urls[] = $relative_location;
		}

		// Check for all Urls added to the array.
		foreach ( $urls as $partial_path ) {
			$file_path = wp_normalize_path( $base_dir . $partial_path );
			$file_url  = $base_url . $partial_path;

			if ( file_exists( $file_path ) ) {
				return $file_url;
			}
		}

		// If we don't have any real file return false.
		return false;
	}

	/**
	 * Register an Asset and attach a callback to the required action to display it correctly.
	 *
	 * @since 4.3
	 *
	 * @param object            $origin    The main object for the plugin you are enqueueing the asset for.
	 * @param string            $slug      Slug to save the asset - passes through `sanitize_title_with_dashes()`.
	 * @param string            $file      The asset file to load (CSS or JS), including non-minified file extension.
	 * @param array             $deps      The list of dependencies or callable function that will return a list of dependencies.
	 * @param string|array|null $action    The WordPress action(s) to enqueue on, such as `wp_enqueue_scripts`,
	 *                                     `admin_enqueue_scripts`, or `login_enqueue_scripts`.
	 * @param string|array      $arguments {
	 *     Optional. Array or string of parameters for this asset.
	 *
	 *     @type array|string|null  $action         The WordPress action(s) this asset will be enqueued on.
	 *     @type int                $priority       Priority in which this asset will be loaded on the WordPress action.
	 *     @type string             $file           The relative path to the File that will be enqueued, uses the $origin to get the full path.
	 *     @type string             $type           Asset Type, `js` or `css`.
	 *     @type array              $deps           An array of other asset as dependencies.
	 *     @type string             $version        Version number, used for cache expiring.
	 *     @type string             $media          Used only for CSS, when to load the file.
	 *     @type bool               $in_footer      A boolean determining if the javascript should be loaded on the footer.
	 *     @type array|object       $localize       {
	 *          Variables needed on the JavaScript side.
	 *
	 *          @type string       $name     Name of the JS variable.
	 *          @type string|array $data     Contents of the JS variable.
	 *     }
	 *     @type callable[]   $conditionals   An callable method or an array of them, that will determine if the asset is loaded or not.
	 * }
	 *
	 * @return object|false The registered object or false on error.
	 */
	public function register( $origin, $slug, $file, $deps = [], $action = null, $arguments = [] ) {
		// Origin needs to be a class with a `instance` method and a Version constant.
		if (
			is_string( $origin )
			&& class_exists( $origin, false )
			&& defined( $origin . '::VERSION' )
			&& method_exists( $origin, 'instance' )
		) {
			$origin = call_user_func( [ $origin, 'instance' ] );
		}

		if ( is_object( $origin ) ) {
			$origin_name = get_class( $origin );

			if ( ! defined( $origin_name . '::VERSION' ) ) {
				// If we have an Object, and we don't have instance or version.
				return false;
			}
		} else {
			return false;
		}

		// Infer the type from the file extension, if not passed.
		$type = empty( $arguments['type'] ) ?
			substr( $file, strrpos( $file, '.' ) + 1 )
			: $arguments['type'];

		// Work out the root path from the origin.
		$root_path = trailingslashit( ! empty( $origin->plugin_path ) ? $origin->plugin_path : $origin->pluginPath ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		// Follow symlinks.
		$root_path = str_replace( trailingslashit( dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) ), trailingslashit( WP_PLUGIN_DIR ), $root_path );

		// Fetches the version on the Origin Version constant if not passed.
		$version = $arguments['version'] ?? constant( $origin_name . '::VERSION' );

		/**
		 * Filter to change version number on assets.
		 *
		 * @since 4.3
		 * @since 5.3.0 The second filter argument is now deprecated; added the slug as a third argument.
		 *
		 * @param string $version The version number.
		 * @param null $deprecated Used to be the asset object, it will now be always null.
		 * @param string $slug The slug of the asset.
		 */
		$version = apply_filters( 'tribe_asset_version', $version, null, $slug );

		$asset = Asset::add( $slug, $file, $version, $root_path );

		if ( ! empty( $action ) ) {
			foreach ( (array) $action as $enqueue_on ) {
				$asset->enqueue_on( $enqueue_on, $arguments['priority'] ?? null );
			}
		}

		$asset->set_type( $type );

		if ( isset( $arguments['media'] ) ) {
			$asset->set_media( $arguments['media'] );
		}

		if ( isset( $arguments['conditionals'] ) ) {
			if ( is_callable( $arguments['conditionals'] ) ) {
				// One array argument is a callable, so call it.
				$asset->set_condition( $arguments['conditionals'] );
			} else {
				// Build a condition closure out of a list of conditionals.
				$conditionals = (array) $arguments['conditionals'];

				// Pluck the operator from the conditionals.
				$operator = Arr::get( $conditionals, 'operator', 'OR' );
				// Keep the callables.
				$conditional_callables = array_values( array_filter( $conditionals, 'is_callable' ) );

				if ( $operator === 'OR' ) {
					// Build a Closure condition that will return true if any of the callables return true.
					$asset->set_condition(
						function () use ( $conditional_callables ) {
							foreach ( $conditional_callables as $condition ) {
								if ( $condition() ) {
									return true;
								}
							}

							return false;
						}
					);
				} else {
					// Build a Closure condition that will return true if all of the callables return true.
					$asset->set_condition(
						function () use ( $conditional_callables ) {
							foreach ( $conditional_callables as $condition ) {
								if ( ! $condition() ) {
									return false;
								}
							}

							return true;
						}
					);
				}
			}
		}

		if ( isset( $deps ) ) {
			if ( is_callable( $deps ) ) {
				$asset->set_dependencies( $deps );
			} else {
				foreach ( (array) $deps as $dependency ) {
					$asset->add_dependency( $dependency );
				}
			}
		}

		if ( isset( $arguments['groups'] ) ) {
			foreach ( (array) $arguments['groups'] as $group ) {
				$asset->add_to_group( $group );
			}
		}

		if ( isset( $arguments['print_before'] ) ) {
			$asset->print_before( $arguments['print_before'] );
		}

		if ( isset( $arguments['print_after'] ) ) {
			$asset->print_after( $arguments['print_after'] );
		}

		if ( ! empty( $arguments['localize'] ) && ( is_array( $arguments['localize'] ) || is_object( $arguments['localize'] ) ) ) {
			// Normalize to an array.
			$localize = (array) $arguments['localize'];

			if ( isset( $localize['name'], $localize['data'] ) ) {
				// Single instance of localize.
				$localize = [ $localize ];
			} else {
				// Normalize to an array of valid arrays.
				$localize = array_values(
					array_filter(
						array_map(
							static fn( $l ) => (array) $l,
							$localize
						),
						static fn( array $l ) => isset( $l['name'], $l['data'] )
					)
				);
			}

			/** @var array<array{name: string, data: array|callable}> $localize */
			foreach ( $localize as $l ) {
				$asset->add_localize_script( $l['name'], $l['data'] );

			}
		}

		if ( isset( $arguments['translations'], $arguments['translations']['domain'], $arguments['translations']['path'] ) ) {
			$domain = $arguments['translations']['domain'];
			$path   = $arguments['translations']['path'];
			$asset->call_after_enqueue( fn() => wp_set_script_translations( $slug, $domain, $path ) );
		}

		if ( isset( $arguments['after_enqueue'] ) ) {
			$asset->call_after_enqueue( $arguments['after_enqueue'] );
		}

		if ( isset( $arguments['in_footer'] ) ) {
			if ( $arguments['in_footer'] ) {
				$asset->in_footer();
			} else {
				$asset->in_header();
			}
		}

		if ( ! empty( $arguments['module'] ) ) {
			$asset->set_as_module();
		}

		if ( ! empty( $arguments['defer'] ) ) {
			$asset->set_as_deferred();
		}

		if ( ! empty( $arguments['async'] ) ) {
			$asset->set_as_async();
		}

		if ( ! empty( $arguments['print'] ) ) {
			$asset->print();
		}

		$asset->register();

		return $asset;
	}

	/**
	 * Parse the localize argument for a given asset object.
	 *
	 * @since 4.9.12
	 *
	 * @param  stdClass $asset Argument that set that asset.
	 *
	 * @return stdClass
	 *
	 * @deprecated 5.3.0
	 */
	public function parse_argument_localize( stdClass $asset ) {
		_deprecated_function( __METHOD__, '5.3.0', '' );
		return $asset;
	}

	/**
	 * Removes an Asset from been registered and enqueue.
	 *
	 * @since 4.3
	 * @since 5.3.0 Refactored to use the stellarwp/assets library.
	 *
	 * @param  string $slug Slug of the Asset.
	 *
	 * @return bool
	 */
	public function remove( $slug ) {
		return Assets::instance()->remove( $slug );
	}

	/**
	 * Get the Asset Object configuration.
	 *
	 * @since 4.3
	 * @since 4.11.0  Added $sort param.
	 * @since 5.3.0 Refactored to use the stellarwp/assets library.
	 *
	 * @param string|array $slug Slug of the Asset.
	 * @param boolean      $sort  If we should do any sorting before returning.
	 *
	 * @return array|object|null Array of asset objects, single asset object, or null if looking for a single asset but
	 *                           it was not in the array of objects.
	 */
	public function get( $slug = null, $sort = true ) {
		return Assets::instance()->get( $slug, $sort );
	}

	/**
	 * Checks if an Asset exists.
	 *
	 * @param  string|array $slug Slug of the Asset.
	 *
	 * @since 5.3.0 Refactored to use the stellarwp/assets library.
	 *
	 * @return bool
	 */
	public function exists( $slug ) {
		return Assets::instance()->exists( $slug );
	}

	/**
	 * Prints the `script` (JS) and `link` (CSS) HTML tags associated with one or more assets groups.
	 *
	 * The method will force the scripts and styles to print overriding their registration and conditional.
	 *
	 * @since 4.12.6
	 * @since 5.3.0 Refactored to use the stellarwp/assets library.
	 *
	 * @param string|array $group Which group(s) should be enqueued.
	 * @param bool         $echo  Whether to print the group(s) tag(s) to the page or not; default to `true` to
	 *                            print the HTML `script` (JS) and `link` (CSS) tags to the page.
	 *
	 * @return string The `script` and `link` HTML tags produced for the group(s).
	 */
	public function print_group( $group, $echo = true ) {
		Assets::instance()->print_group( $group, $echo );
	}

	/**
	 * Enqueue StellarWP fonts.
	 *
	 * @since 5.1.3
	 *
	 * @return void
	 */
	public function enqueue_stellar_wp_fonts() {
		wp_enqueue_style(
			'stellar-wp-inconsolata-font',
			'https://fonts.googleapis.com/css2?family=Inconsolata&display=swap'
		);
	}
}
