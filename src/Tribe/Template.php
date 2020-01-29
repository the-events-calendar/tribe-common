<?php

class Tribe__Template {
	/**
	 * The folders into which we will look for the template.
	 *
	 * @since  4.6.2
	 *
	 * @var array
	 */
	protected $folder = array();

	/**
	 * The origin class for the plugin where the template lives
	 *
	 * @since  4.6.2
	 *
	 * @var object
	 */
	public $origin;

	/**
	 * The local context for templates, mutable on every self::template() call
	 *
	 * @since  4.6.2
	 *
	 * @var array
	 */
	protected $context = [];

	/**
	 * The global context for this instance of templates
	 *
	 * @since  4.6.2
	 *
	 * @var array
	 */
	protected $global = array();

	/**
	 * Used for finding templates for public templates on themes inside of a folder.
	 *
	 * @since  4.10.2
	 *
	 * @var string[]
	 */
	protected $template_origin_base_folder = [ 'src', 'views' ];

	/**
	 * Allow changing if class will extract data from the local context
	 *
	 * @since  4.6.2
	 *
	 * @var boolean
	 */
	protected $template_context_extract = false;

	/**
	 * Base template for where to look for template
	 *
	 * @since  4.6.2
	 *
	 * @var array
	 */
	protected $template_base_path;

	/**
	 * Should we use a lookup into the list of folders to try to find the file
	 *
	 * @since  4.7.20
	 *
	 * @var  bool
	 */
	protected $template_folder_lookup = false;

	/**
	 * Create a class variable for the include path, to avoid conflicting with extract.
	 *
	 * @since  4.11.0
	 *
	 * @var  string
	 */
	protected $template_current_file_path;

	/**
	 * Configures the class origin plugin path
	 *
	 * @since  4.6.2
	 *
	 * @param  object|string  $origin   The base origin for the templates
	 *
	 * @return self
	 */
	public function set_template_origin( $origin = null ) {
		if ( empty( $origin ) ) {
			$origin = $this->origin;
		}

		if ( is_string( $origin ) ) {
			// Origin needs to be a class with a `instance` method
			if ( class_exists( $origin ) && method_exists( $origin, 'instance' ) ) {
				$origin = call_user_func( array( $origin, 'instance' ) );
			}
		}

		if ( empty( $origin->plugin_path ) && empty( $origin->pluginPath ) && ! is_dir( $origin ) ) {
			throw new InvalidArgumentException( 'Invalid Origin Class for Template Instance' );
		}

		if ( ! is_string( $origin ) ) {
			$this->origin = $origin;
			$this->template_base_path = untrailingslashit( ! empty( $this->origin->plugin_path ) ? $this->origin->plugin_path : $this->origin->pluginPath );
		} else {
			$this->template_base_path = untrailingslashit( (array) explode( '/', $origin ) );
		}

		return $this;
	}

	/**
	 * Configures the class with the base folder in relation to the Origin
	 *
	 * @since  4.6.2
	 *
	 * @param  array|string   $folder  Which folder we are going to look for templates
	 *
	 * @return self
	 */
	public function set_template_folder( $folder = null ) {
		// Allows configuring a already set class
		if ( ! isset( $folder ) ) {
			$folder = $this->folder;
		}

		// If Folder is String make it an Array
		if ( is_string( $folder ) ) {
			$folder = (array) explode( '/', $folder );
		}

		// Cast as Array and save
		$this->folder = (array) $folder;

		return $this;
	}

	/**
	 * Returns the array for which folder this template instance is looking into.
	 *
	 * @since 4.11.0
	 *
	 * @return array Current folder we are looking for templates.
	 */
	public function get_template_folder() {
		return $this->folder;
	}

	/**
	 * Configures the class with the base folder in relation to the Origin
	 *
	 * @since  4.7.20
	 *
	 * @param  mixed $value Should we look for template files in the list of folders.
	 *
	 * @return self
	 */
	public function set_template_folder_lookup( $value = true ) {
		$this->template_folder_lookup = tribe_is_truthy( $value );

		return $this;
	}

	/**
	 * Configures the class global context
	 *
	 * @since  4.6.2
	 *
	 * @param  array  $context  Default global Context
	 *
	 * @return self
	 */
	public function add_template_globals( $context = array() ) {
		// Cast as Array merge and save
		$this->global = wp_parse_args( (array) $context, $this->global );

		return $this;
	}

	/**
	 * Configures if the class will extract context for template
	 *
	 * @since  4.6.2
	 *
	 * @param  bool  $value  Should we extract context for templates
	 *
	 * @return self
	 */
	public function set_template_context_extract( $value = false ) {
		// Cast as bool and save
		$this->template_context_extract = tribe_is_truthy( $value );

		return $this;
	}

	/**
	 * Sets a Index inside of the global or local context
	 * Final to prevent extending the class when the `get` already exists on the child class
	 *
	 * @since  4.6.2
	 *
	 * @see    Tribe__Utils__Array::set
	 *
	 * @param  array    $index     Specify each nested index in order.
	 *                             Example: array( 'lvl1', 'lvl2' );
	 * @param  mixed    $default   Default value if the search finds nothing.
	 * @param  boolean  $is_local  Use the Local or Global context
	 *
	 * @return mixed The value of the specified index or the default if not found.
	 */
	final public function get( $index, $default = null, $is_local = true ) {
		$context = $this->get_global_values();

		if ( true === $is_local ) {
			$context = $this->get_local_values();
		}

		/**
		 * Allows filtering the the getting of Context variables, also short circuiting
		 * Following the same strucuture as WP Core
		 *
		 * @since  4.6.2
		 *
		 * @param  mixed    $value     The value that will be filtered
		 * @param  array    $index     Specify each nested index in order.
		 *                             Example: array( 'lvl1', 'lvl2' );
		 * @param  mixed    $default   Default value if the search finds nothing.
		 * @param  boolean  $is_local  Use the Local or Global context
		 * @param  self     $template  Current instance of the Tribe__Template
		 */
		$value = apply_filters( 'tribe_template_context_get', null, $index, $default, $is_local, $this );
		if ( null !== $value ) {
			return $value;
		}

		return Tribe__Utils__Array::get( $context, $index, $default );
	}

	/**
	 * Sets a Index inside of the global or local context
	 * Final to prevent extending the class when the `set` already exists on the child class
	 *
	 * @since  4.6.2
	 *
	 * @see    Tribe__Utils__Array::set
	 *
	 * @param  string|array  $index     To set a key nested multiple levels deep pass an array
	 *                                  specifying each key in order as a value.
	 *                                  Example: array( 'lvl1', 'lvl2', 'lvl3' );
	 * @param  mixed         $value     The value.
	 * @param  boolean       $is_local  Use the Local or Global context
	 *
	 * @return array Full array with the key set to the specified value.
	 */
	final public function set( $index, $value = null, $is_local = true ) {
		if ( true === $is_local ) {
			$this->context = Tribe__Utils__Array::set( $this->context, $index, $value );

			return $this->context;
		}

		$this->global = Tribe__Utils__Array::set( $this->global, $index, $value );

		return $this->global;
	}

	/**
	 * Merges local and global context, and saves it locally
	 *
	 * @since  4.6.2
	 *
	 * @param  array  $context  Local Context array of data
	 * @param  string $file     Complete path to include the PHP File
	 * @param  array  $name     Template name
	 *
	 * @return array
	 */
	public function merge_context( $context = [], $file = null, $name = null ) {
		// Allow for simple null usage as well as array() for nothing
		if ( is_null( $context ) ) {
			$context = [];
		}

		// Applies new local context on top of Global + Previous local.
		$context = wp_parse_args( (array) $context, $this->get_values() );

		/**
		 * Allows filtering the Local context
		 *
		 * @since  4.6.2
		 *
		 * @param array  $context   Local Context array of data
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		$this->context = apply_filters( 'tribe_template_context', $context, $file, $name, $this );

		return $this->context;
	}

	/**
	 * Fetches the path for locating files in the Plugin Folder
	 *
	 * @since  4.7.20
	 *
	 * @return string
	 */
	protected function get_template_plugin_path() {
		// Craft the plugin Path
		$path = array_merge( (array) $this->template_base_path, $this->folder );

		// Implode to avoid Window Problems
		$path = implode( DIRECTORY_SEPARATOR, $path );

		/**
		 * Allows filtering of the base path for templates
		 *
		 * @since  4.7.20
		 *
		 * @param string $path      Complete path to include the base plugin folder
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		return apply_filters( 'tribe_template_plugin_path', $path, $this );
	}

	/**
	 * Fetches the Namespace for the public paths, normally folders to look for
	 * in the theme's directory.
	 *
	 * @since  4.7.20
	 * @since  4.11.0  Added param $plugin_namespace.
	 *
	 * @param string $plugin_namespace Overwrite the origin namespace with a given one.
	 *
	 * @return array Namespace where we to look for templates.
	 */
	protected function get_template_public_namespace( $plugin_namespace ) {
		$namespace = [
			'tribe',
		];

		if ( ! empty( $plugin_namespace ) ) {
			$namespace[] = $plugin_namespace;
		} elseif ( ! empty( $this->origin->template_namespace ) ) {
			$namespace[] = $this->origin->template_namespace;
		}

		/**
		 * Allows filtering of the base path for templates
		 *
		 * @since  4.7.20
		 *
		 * @param array  $namespace Which is the namespace we will look for files in the theme
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		return apply_filters( 'tribe_template_public_namespace', $namespace, $this );
	}

	/**
	 * Fetches which base folder we look for templates in the origin plugin.
	 *
	 * @since  4.10.2
	 *
	 * @return array The base folders we look for templates in the origin plugin.
	 */
	public function get_template_origin_base_folder() {
		/**
		 * Allows filtering of the base path for templates.
		 *
		 * @since 4.10.2
		 *
		 * @param array  $namespace Which is the base folder we will look for files in the plugin.
		 * @param self   $template  Current instance of the Tribe__Template.
		 */
		return apply_filters( 'tribe_template_origin_base_folder', $this->template_origin_base_folder, $this );
	}

	/**
	 * Fetches the path for locating files given a base folder normally theme related.
	 *
	 * @since  4.7.20
	 * @since  4.11.0 Added the param $namespace.
	 *
	 * @param  mixed  $base      Base path to look into.
	 * @param  string $namespace Adds the plugin namespace to the path returned.
	 *
	 * @return string  The public path for a given base.˙˙
	 */
	protected function get_template_public_path( $base, $namespace ) {

		// Craft the plugin Path
		$path = array_merge( (array) $base, (array) $this->get_template_public_namespace( $namespace ) );

		// Pick up if the folder needs to be aded to the public template path.
		$folder = array_diff( $this->folder, $this->get_template_origin_base_folder() );

		if ( ! empty( $folder ) ) {
			$path = array_merge( $path, $folder );
		}

		// Implode to avoid Window Problems
		$path = implode( DIRECTORY_SEPARATOR, $path );

		/**
		 * Allows filtering of the base path for templates
		 *
		 * @since  4.7.20
		 *
		 * @param string $path      Complete path to include the base public folder
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		return apply_filters( 'tribe_template_public_path', $path, $this );
	}

	/**
	 * Fetches the folders in which we will look for a given file
	 *
	 * @since  4.7.20
	 *
	 * @return array
	 */
	protected function get_template_path_list() {
		$folders = [];

		$folders['plugin'] = [
			'id'        => 'plugin',
			'priority'  => 20,
			'path'      => $this->get_template_plugin_path(),
		];

		/**
		 * Allows filtering of the list of folders in which we will look for the
		 * template given.
		 *
		 * @since  4.7.20
		 *
		 * @param  array  $folders   Complete path to include the base public folder
		 * @param  self   $template  Current instance of the Tribe__Template
		 */
		$folders = (array) apply_filters( 'tribe_template_path_list', $folders, $this );

		uasort( $folders, 'tribe_sort_by_priority' );

		return $folders;
	}

	/**
	 * Get the list of theme related folders we will look up for the template.
	 *
	 * @since 4.11.0
	 *
	 * @param string $namespace Which plugin namespace we are looking for.
	 *
	 * @return array
	 */
	protected function get_template_theme_path_list( $namespace ) {
		$folders = [];

		$folders['child-theme'] = [
			'id'       => 'child-theme',
			'priority' => 10,
			'path'     => $this->get_template_public_path( STYLESHEETPATH, $namespace ),
		];
		$folders['parent-theme'] = [
			'id'       => 'parent-theme',
			'priority' => 15,
			'path'     => $this->get_template_public_path( TEMPLATEPATH, $namespace ),
		];

		/**
		 * Allows filtering of the list of theme folders in which we will look for the template.
		 *
		 * @since  4.11.0
		 *
		 * @param  array   $folders     Complete path to include the base public folder.
		 * @param  string  $namespace   Loads the files from a specified folder from the themes.
		 * @param  self    $template    Current instance of the Tribe__Template.
		 */
		$folders = (array) apply_filters( 'tribe_template_theme_path_list', $folders, $namespace, $this );

		uasort( $folders, 'tribe_sort_by_priority' );

		return $folders;
	}

	/**
	 * Tries to locate the correct file we want to load based on the Template class
	 * configuration and it's list of folders
	 *
	 * @since  4.7.20
	 *
	 * @param  mixed  $name  File name we are looking for
	 *
	 * @return string
	 */
	public function get_template_file( $name ) {
		// If name is String make it an Array
		if ( is_string( $name ) ) {
			$name = (array) explode( '/', $name );
		}

		$folders    = $this->get_template_path_list();
		$found_file = false;
		$namespace  = false;

		foreach ( $folders as $folder ) {
			if ( empty( $folder['path'] ) ) {
				continue;
			}

			// Build the File Path
			$file = implode( DIRECTORY_SEPARATOR, array_merge( (array) $folder['path'], $name ) );

			// Append the Extension to the file path
			$file .= '.php';

			// Skip non-existent files
			if ( file_exists( $file ) ) {
				$found_file = $file;
				$namespace = ! empty(  $folder['namespace'] ) ?  $folder['namespace'] : false;
				break;
			}
		}

		if ( $this->template_folder_lookup ) {
			$theme_folders = $this->get_template_theme_path_list( $namespace );

			foreach ( $theme_folders as $folder ) {
				if ( empty( $folder['path'] ) ) {
					continue;
				}

				// Build the File Path
				$file = implode( DIRECTORY_SEPARATOR, array_merge( (array) $folder['path'], $name ) );

				// Append the Extension to the file path
				$file .= '.php';

				// Skip non-existent files
				if ( file_exists( $file ) ) {
					$found_file = $file;
				}
			}
		}

		if ( $found_file ) {
			/**
			 * A more Specific Filter that will include the template name
			 *
			 * @since  4.6.2
			 * @since  4.7.20   The $name param no longer contains the extension
			 *
			 * @param string $file      Complete path to include the PHP File
			 * @param array  $name      Template name
			 * @param self   $template  Current instance of the Tribe__Template
			 */
			return apply_filters( 'tribe_template_file', $found_file, $name, $this );
		}

		// Couldn't find a template on the Stack
		return false;
	}

	/**
	 * A very simple method to include a Template, allowing filtering and additions using hooks.
	 *
	 * @since  4.6.2
	 *
	 * @param string  $name    Which file we are talking about including
	 * @param array   $context Any context data you need to expose to this file
	 * @param boolean $echo    If we should also print the Template
	 *
	 * @return string|false Either the final content HTML or `false` if no template could be found.
	 */
	public function template( $name, $context = [], $echo = true ) {
		static $file_exists    = [];
		static $files          = [];
		static $template_names = [];

		// Key we'll use for in-memory caching of expensive operations.
		$cache_name_key = is_array( $name ) ? implode( '/', $name ) : $name;

		// Cache template name massaging so we don't have to repeat these actions.
		if ( ! isset( $template_names[ $cache_name_key ] ) ) {
			// If name is String make it an Array
			if ( is_string( $name ) ) {
				$name = (array) explode( '/', $name );
			}

			// Clean this Variable
			$name = array_map( 'sanitize_title_with_dashes', $name );

			$template_names[ $cache_name_key ] = $name;
		}

		// Cache file location and existence.
		if ( ! isset( $file_exists[ $cache_name_key ] ) || ! isset( $files[ $cache_name_key ] ) ) {
			// Check if the file exists
			$files[ $cache_name_key ] = $file = $this->get_template_file( $name );

			// Check if it's a valid variable
			if ( ! $file ) {
				return $file_exists[ $cache_name_key ] = false;
			}

			// Before we load the file we check if it exists
			if ( ! file_exists( $file ) ) {
				return $file_exists[ $cache_name_key ] = false;
			}

			$file_exists[ $cache_name_key ] = true;
		}

		// If the file doesn't exist, bail.
		if ( ! $file_exists[ $cache_name_key ] ) {
			return false;
		}

		// Use filename stored in cache.
		$file                   = $files[ $cache_name_key ];
		$name                   = $template_names[ $cache_name_key ];
		$origin_folder_appendix = array_diff( $this->folder, $this->template_origin_base_folder );

		if ( $origin_namespace = $this->template_get_origin_namespace( $file ) ) {
			$legacy_namespace = array_merge( (array) $origin_namespace, $name );
			$namespace        = array_merge( (array) $origin_namespace, $origin_folder_appendix, $name );
		} else {
			$legacy_namespace = $name;
			$namespace        = array_merge( $origin_folder_appendix, $legacy_namespace );
		}

		// Setup the Hook name
		$legacy_hook_name = implode( '/', $legacy_namespace );
		$hook_name        = implode( '/', $namespace );

		/**
		 * Allow users to filter the HTML before rendering
		 *
		 * @since  4.11.0
		 *
		 * @param string $html      The initial HTML
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		$pre_html = apply_filters( 'tribe_template_pre_html', null, $file, $name, $this );

		/**
		 * Allow users to filter the HTML by the name before rendering
		 *
		 * E.g.:
		 *    `tribe_template_pre_html:events/blocks/parts/details`
		 *    `tribe_template_pre_html:events/embed`
		 *    `tribe_template_pre_html:tickets/login-to-purchase`
		 *
		 * @since  4.11.0
		 *
		 * @param string $html      The initial HTML
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		$pre_html = apply_filters( "tribe_template_pre_html:$hook_name", $pre_html, $file, $name, $this );

		if ( null !== $pre_html ) {
			return $pre_html;
		}

		ob_start();

		// Merges the local data passed to template to the global scope
		$this->merge_context( $context, $file, $name );

		/**
		 * Fires an Action before including the template file
		 *
		 * @since  4.6.2
		 * @since  4.7.20   The $name param no longer contains the extension
		 *
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		do_action( 'tribe_template_before_include', $file, $name, $this );

		/**
		 * Fires an Action for a given template name before including the template file
		 *
		 * E.g.:
		 *    `tribe_template_before_include:events/blocks/parts/details`
		 *    `tribe_template_before_include:events/embed`
		 *    `tribe_template_before_include:tickets/login-to-purchase`
		 *
		 * @deprecated   4.11.0
		 * @since  4.7.20
		 *
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		do_action( "tribe_template_before_include:$legacy_hook_name", $file, $name, $this );

		/**
		 * Fires an Action for a given template name before including the template file
		 *
		 * E.g.:
		 *    `tribe_template_before_include:events/blocks/parts/details`
		 *    `tribe_template_before_include:events/embed`
		 *    `tribe_template_before_include:tickets/login-to-purchase`
		 *
		 * @since  4.7.20
		 *
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		do_action( "tribe_template_before_include:$hook_name", $file, $name, $this );

		$this->template_safe_include( $file );

		/**
		 * Fires an Action after including the template file
		 *
		 * @since  4.6.2
		 * @since  4.7.20   The $name param no longer contains the extension
		 *
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		do_action( 'tribe_template_after_include', $file, $name, $this );

		/**
		 * Fires an Action for a given template name after including the template file
		 *
		 * E.g.:
		 *    `tribe_template_after_include:events/blocks/parts/details`
		 *    `tribe_template_after_include:events/embed`
		 *    `tribe_template_after_include:tickets/login-to-purchase`
		 *
		 * @deprecated 4.11.0
		 * @since  4.7.20
		 *
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		do_action( "tribe_template_after_include:$legacy_hook_name", $file, $name, $this );

		/**
		 * Fires an Action for a given template name after including the template file
		 *
		 * E.g.:
		 *    `tribe_template_after_include:events/blocks/parts/details`
		 *    `tribe_template_after_include:events/embed`
		 *    `tribe_template_after_include:tickets/login-to-purchase`
		 *
		 * @since  4.7.20
		 *
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		do_action( "tribe_template_after_include:$hook_name", $file, $name, $this );

		// Only fetch the contents after the action
		$html = ob_get_clean();

		/**
		 * Allow users to filter the final HTML
		 *
		 * @since  4.6.2
		 * @since  4.7.20   The $name param no longer contains the extension
		 *
		 * @param string $html      The final HTML
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		$html = apply_filters( 'tribe_template_html', $html, $file, $name, $this );

		/**
		 * Allow users to filter the final HTML by the name
		 *
		 * E.g.:
		 *    `tribe_template_html:events/blocks/parts/details`
		 *    `tribe_template_html:events/embed`
		 *    `tribe_template_html:tickets/login-to-purchase`
		 *
		 * @deprecated   4.11.0
		 * @since  4.7.20
		 *
		 * @param string $html      The final HTML
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		$html = apply_filters( "tribe_template_html:$legacy_hook_name", $html, $file, $name, $this );

		/**
		 * Allow users to filter the final HTML by the name
		 *
		 * E.g.:
		 *    `tribe_template_html:events/blocks/parts/details`
		 *    `tribe_template_html:events/embed`
		 *    `tribe_template_html:tickets/login-to-purchase`
		 *
		 * @since  4.7.20
		 *
		 * @param string $html      The final HTML
		 * @param string $file      Complete path to include the PHP File
		 * @param array  $name      Template name
		 * @param self   $template  Current instance of the Tribe__Template
		 */
		$html = apply_filters( "tribe_template_html:$hook_name", $html, $file, $name, $this );

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}

	/**
	 * Based on a path it determines what is the namespace that should be used.
	 *
	 * @since 4.11.0
	 *
	 * @param string $path Which file we are going to load.
	 *
	 * @return string|false The found namespace for that path or false.
	 */
	public function template_get_origin_namespace( $path ) {
		$matching_namespace = false;
		/**
		 * Allows more namespaces to be added based on the path of the file we are loading.
		 *
		 * @since 4.11.0
		 *
		 * @param array  $namespace_map Indexed array containing the namespace as the key and path to `strpos`.
		 * @param string $path          Path we will do the `strpos` to validate a given namespace.
		 * @param self   $template      Current instance of the template class.
		 */
		$namespace_map = (array) apply_filters( 'tribe_template_origin_namespace_map', [], $path, $this );

		foreach ( $namespace_map as $namespace => $contains_string ) {
			// Skip when we dont have the namespace path.
			if ( false === strpos( $path, $contains_string ) ) {
				continue;
			}

			$matching_namespace = $namespace;

			// Once the first namespace is found it breaks out.
			break;
		}

		if ( empty( $matching_namespace ) && ! empty( $this->origin->template_namespace ) ) {
			$matching_namespace = $this->origin->template_namespace;
		}

		return $matching_namespace;
	}

	/**
	 * Includes a give PHP inside of a safe context.
	 *
	 * This method is required to prevent template files messing with local variables used inside of the
	 * `self::template` method. Also shelters the template loading from any possible variables that could
	 * be overwritten by the context.
	 *
	 * @since 4.11.0
	 *
	 * @param string $file Which file will be included with safe context.
	 *
	 * @return void
	 */
	public function template_safe_include( $file ) {
		// We use this instance variable to prevent collisions.
		$this->template_current_file_path = $file;
		unset( $file );

		// Only do this if really needed (by default it wont).
		if ( true === $this->template_context_extract && ! empty( $this->context ) ) {
			// Make any provided variables available in the template variable scope.
			extract( $this->context ); // @phpcs:ignore
		}

		include $this->template_current_file_path;

		// After the include we reset the variable.
		unset( $this->template_current_file_path );
	}

	/**
	 * Sets a number of values at the same time.
	 *
	 * @since 4.9.11
	 *
	 * @param array $values   An associative key/value array of the values to set.
	 * @param bool  $is_local Whether to set the values as global or local; defaults to local as the `set` method does.
	 *
	 * @see   Tribe__Template::set()
	 */
	public function set_values( array $values = [], $is_local = true ) {
		foreach ( $values as $key => $value ) {
			$this->set( $key, $value, $is_local );
		}
	}

	/**
	 * Returns the Template global context.
	 *
	 * @since 4.9.11
	 *
	 * @return array An associative key/value array of the Template global context.
	 */
	public function get_global_values() {
		return $this->global;
	}

	/**
	 * Returns the Template local context.
	 *
	 * @since 4.9.11
	 *
	 * @return array An associative key/value array of the Template local context.
	 */
	public function get_local_values() {
		return $this->context;
	}

	/**
	 * Returns the Template global and local context values.
	 *
	 * Local values will override the template global context values.
	 *
	 * @since 4.9.11
	 *
	 * @return array An associative key/value array of the Template global and local context.
	 */
	public function get_values() {
		return array_merge( $this->get_global_values(), $this->get_local_values() );
	}
}
