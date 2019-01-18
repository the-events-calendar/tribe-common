<?php

/**
 * Class Tribe__Context
 *
 * @since 4.7.7
 * @since TBD Made the context immutable.
 */
class Tribe__Context {

	const NOT_FOUND = '__not_found__';

	/**
	 * An array defining the properties the context will be able to read and provide.
	 *
	 * This is the configuration that should be modified to add/remove/modify values and locations
	 * provided by the context.
	 * Each entry has the shape [ <key> => <locations> ].
	 * The key is used to identify the property that will be accessible with the `get` method,
	 * e.g. `$context->get( 'event_display', 'list' );`.
	 * The locations is a list of locations the context will search, top to bottom, left to right, to find a value that's
	 * not empty or the default one, here's a list of supported lookup locations:
	 *
	 * request_var - look into $_GET, $_POST, $_PUT, $_DELETE, $_REQUEST.
	 * query_var - get the value from the main WP_Query object query vars.
	 * tribe_option - get the value from a Tribe option.
	 * option - get the value from a database option.
	 * transient - get the value from a transient.
	 * constant - get the value from a constant, can also be a class constant with <class>::<const>.
	 * global_var - get the value from a global variable
	 * static_prop - get the value from a class static property, format: `array( $class, $prop )`.
	 * prop - get the value from a tribe() container binding, format `array( $binding, $prop )`.
	 * 'static_method' - get the value from a class static method.
	 * 'method' - get the value calling a method on a tribe() container binding.
	 * 'func' - get the value from a function.
	 *
	 * @var array
	 */
	protected static $locations = array(
		'posts_per_page' => array(
			'request_var'  => 'posts_per_page',
			'tribe_option' => array( 'posts_per_page', 'postsPerPage' ),
			'option'       => 'posts_per_page',
		),
		'event_display'  => array(
			'request_var' => 'tribe_event_display',
			'query_var'   => 'eventDisplay',
		),
	);

	/**
	 * Whether the context of the current HTTP request is an AJAX one or not.
	 *
	 * @var bool
	 */
	protected $doing_ajax;

	/**
	 * Whether the context of the current HTTP request is a Cron one or not.
	 *
	 * @var bool
	 */
	protected $doing_cron;

	/**
	 * A request-based array cache to store the values fetched by the context.
	 *
	 * @var array
	 */
	protected $request_cache = array();

	/**
	 * Whether we are currently creating a new post, a post of post type(s) or not.
	 *
	 * @since 4.7.7
	 *
	 * @param null $post_type The optional post type to check.
	 *
	 * @return bool Whether we are currently creating a new post, a post of post type(s) or not.
	 */
	public function is_new_post( $post_type = null ) {
		global $pagenow;
		$is_new = 'post-new.php' === $pagenow;

		return $is_new && $this->is_editing_post( $post_type );
	}

	/**
	 * Whether we are currently editing a post(s), post type(s) or not.
	 *
	 * @since 4.7.7
	 *
	 * @param null|array|string|int $post_or_type A post ID, post type, an array of post types or post IDs, `null`
	 *                                            to just make sure we are currently editing a post.
	 *
	 * @return bool
	 */
	public function is_editing_post( $post_or_type = null ) {
		global $pagenow;
		$is_new  = 'post-new.php' === $pagenow;
		$is_post = 'post.php' === $pagenow;

		if ( ! $is_new && ! $is_post ) {
			return false;
		}

		if ( null !== $post_or_type ) {
			$lookup = array( $_GET, $_POST, $_REQUEST );

			$current_post = Tribe__Utils__Array::get_in_any( $lookup, 'post', get_post() );

			if ( is_numeric( $post_or_type ) ) {

				$post = $is_post ? get_post( $post_or_type ) : null;

				return ! empty( $post ) && $post == $current_post;
			}

			$post_types = is_array( $post_or_type ) ? $post_or_type : array( $post_or_type );

			$post = $is_post ? $current_post : null;

			if ( count( array_filter( $post_types, 'is_numeric' ) ) === count( $post_types ) ) {
				return ! empty( $post ) && in_array( $post->ID, $post_types );
			}

			if ( $is_post && $post instanceof WP_Post ) {
				$post_type = $post->post_type;
			} else {
				$post_type = Tribe__Utils__Array::get_in_any( $lookup, 'post_type', 'post' );
			}

			return (bool) count( array_intersect( $post_types, array( $post_type ) ) );
		}

		return $is_new || $is_post;
	}

	/**
	 * Helper function to indicate whether the current execution context is AJAX.
	 *
	 * This method exists to allow us test code that behaves differently depending on the execution
	 * context.
	 *
	 * @since 4.7.12
	 * @since TBD Removed the $doing_ajax parameter.
	 *
	 * @return boolean
	 */
	public function doing_ajax() {
		return function_exists( 'wp_doing_ajax' )
			? wp_doing_ajax()
			: defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	/**
	 * Checks whether the context of the current HTTP request is a Cron one or not.
	 *
	 * @since 4.7.23
	 * @since TBD Removed the $doing_cron parameter.
	 *
	 * @return bool Whether the context of the current HTTP request is a Cron one or not.
	 */
	public function doing_cron() {
		return function_exists( 'wp_doing_cron' )
			? wp_doing_cron()
			: defined( 'DOING_CRON' ) && DOING_CRON;
	}

	/**
	 * Returns the current display mode.
	 *
	 * This is read from the the request variables first and from the main query object second.
	 *
	 * @since TBD
	 *
	 * @return string The global event display or an empty string if not set in any of the sources above.
	 */
	public function get_event_display() {
		$request_value = tribe_get_request_var( 'tribe_event_display' );
		global $wp_query;
		$query_value = $wp_query->get( 'eventDisplay', '' );

		return $request_value ? $request_value : $query_value;
	}

	/**
	 * Gets a value reading it from the location(s) defined in the `Tribe__Context::$props
	 *
	 * @since TBD
	 *
	 * @param string $key The key of the variable to fetch.
	 * @param mixed|null $default The default value to return if not found.
	 *
	 * @return mixed The value from the first location that can provide it or the default
	 *               value if not found.
	 */
	public function get( $key, $default = null ) {
		$value         = $default;
		$the_locations = self::$locations[ $key ];

		if ( ! isset( $the_locations ) ) {
			return $value;
		}

		if ( isset( $this->request_cache[ $key ] ) ) {
			$value = $this->request_cache[ $key ];
		} else {
			foreach ( $the_locations as $location => $keys ) {
				$this_value = $this->$location( (array) $keys, $default );

				if ( $default !== $this_value ) {
					$value = $this_value;
					break;
				}
			}
		}

		/**
		 * Filters the value fetched from the context for a key.
		 *
		 * Useful for testing and local override.
		 *
		 * @since TBD
		 *
		 * @param mixed $value The value as fetched from the context.
		 */
		$value = apply_filters( "tribe_context_{$key}", $value );

		return $value;
	}

	/**
	 * Alters the context.
	 *
	 * Due to its immutable nature setting values on the context will NOT modify the
	 * context but return a modified clone.
	 * If you need to modify the global context update the location(s) it should read from
	 * and call the `refresh` method.
	 * Example: `$widget_context = tribe_context()->alter( $widget_args );`.
	 *
	 * @since TBD
	 *
	 * @param array $values An associative array of key-value pairs to modify the context.
	 *
	 * @return \Tribe__Context A clone, with modified, values, of the context the method was called on.
	 */
	public function alter( array $values  ) {
		$clone = clone $this;

		$clone->request_cache = array_merge( $clone->request_cache, $values );

		return $clone;
	}

	/**
	 * Clears the context cache forcing a re-fetch of the variables from the context.
	 *
	 * @since TBD
	 *
	 * @param string $key An optional specific key to refresh, if passed only this key
	 *                    will be refreshed.
	 */
	public function refresh( $key = null ) {
		if ( null !== $key ) {
			unset( $this->request_cache[ $key ] );
		} else {
			$this->request_cache = array();
		}
	}

	/**
	 * Reads the value from one or more $_REQUEST vars.
	 *
	 * @since TBD
	 *
	 * @param array $request_vars The list of request vars to lookup, in order.
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function request_var( array $request_vars, $default ) {
		$value = $default;

		foreach ( $request_vars as $request_var ) {
			$this_value = tribe_get_request_var( $request_var, self::NOT_FOUND );
			if ( $this_value !== self::NOT_FOUND ) {
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more global WP_Query object query variables.
	 *
	 * @since TBD
	 *
	 * @param array $query_vars The list of query vars to look up, in order.
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function query_var( array $query_vars, $default ) {
		$value = $default;

		global $wp_query;
		foreach ( $query_vars as $query_var ) {
			$this_value = $wp_query->get( $query_var, self::NOT_FOUND );
			if ( $this_value !== self::NOT_FOUND ) {
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one more more `tribe_option`s.
	 *
	 * @since TBD
	 *
	 * @param array $tribe_options The list of `tribe_option`s to lookup, in order.
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function tribe_option( array $tribe_options, $default ) {
		$value = $default;

		foreach ( $tribe_options as $option_name ) {
			$this_value = tribe_get_option( $option_name, self::NOT_FOUND );
			if ( $this_value !== self::NOT_FOUND ) {
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more options.
	 *
	 * @since TBD
	 *
	 * @param array $options The list of options to lookup, in order.
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function option( array $options, $default ) {
		$value = $default;

		foreach ( $options as $option_name ) {
			$this_value = get_option( $option_name, self::NOT_FOUND );
			if ( $this_value !== self::NOT_FOUND ) {
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more transients.
	 *
	 * @since TBD
	 *
	 * @param array $transients The list of transients to lookup, in order.
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function transient( array $transients, $default ) {
		$value = $default;

		foreach ( $transients as $transient ) {
			$this_value = get_transient( $transient );
			if ( false !== $this_value ) {
				$value = $this_value;
				/*
				 * This will fail when the value is actually `false`.
				 */
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more constants.
	 *
	 * @since TBD
	 *
	 * @param array $constants The list of constants to lookup, in order.
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function constant( array $constants, $default ) {
		$value = $default;

		foreach ( $constants as $constant ) {
			$this_value = defined( $constant ) ? constant( $constant ) : self::NOT_FOUND;
			if ( $this_value !== self::NOT_FOUND ) {
				$value = $this_value;
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more global variable.
	 *
	 * @since TBD
	 *
	 * @param array $global_vars The list of global variables to look up, in order.
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function global_var( array $global_vars, $default ) {
		$value = $default;

		foreach ( $global_vars as $var ) {
			$this_value = isset( $GLOBALS[ $var ] ) ? $GLOBALS[ $var ] : self::NOT_FOUND;
			if ( $this_value !== self::NOT_FOUND ) {
				$value = $this_value;
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more class static properties.
	 *
	 * @since TBD
	 *
	 * @param array $classes_and_props An associative array in the shape [ <class> => <prop> ].
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function static_prop( array $classes_and_props, $default ) {
		$value = $default;

		foreach ( $classes_and_props as $class => $prop ) {
			if ( class_exists( $class ) ) {
				// PHP 5.2 compat, on PHP 5.3+ $class::$$prop
				$vars  = get_class_vars( $class );
				$this_value = isset( $vars[ $prop ] ) ? $vars[ $prop ] : self::NOT_FOUND;

				if ( $this_value !== self::NOT_FOUND ) {
					$value = $this_value;
					break;
				}
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more properties of implementations bound in the `tribe()` container.
	 *
	 * @since TBD
	 *
	 * @param array $bindings_and_props An associative array in the shape [ <binding> => <prop> ].
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first valid value found or the default value.
	 */
	protected function prop( array $bindings_and_props, $default ) {
		$value = $default;

		foreach ( $bindings_and_props as $binding => $prop ) {
			$this_value = tribe()->offsetExists( $binding ) && property_exists( tribe( $binding ), $prop )
				? tribe( $binding )->{$prop}
				: self::NOT_FOUND;

			if ( $this_value !== self::NOT_FOUND ) {
				$value = $this_value;
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the values from one or more static class methods.
	 *
	 * @since TBD
	 *
	 * @param array $classes_and_methods An associative array in the shape [ <class> => <method> ].
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first value that's not equal to the default one, the default value
	 *               otherwise.
	 */
	protected function static_method( array $classes_and_methods, $default ) {
		$value = $default;

		foreach ( $classes_and_methods as $class => $method ) {
			$this_value = class_exists( $class ) && method_exists( $class, $method )
				? call_user_func( array( $class, $method ) )
				: self::NOT_FOUND;

			if ( $this_value !== self::NOT_FOUND ) {
				$value = $this_value;
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more methods called on implementations bound in the `tribe()` container.
	 *
	 * @since TBD
	 *
	 * @param array $bindings_and_methods An associative array in the shape [ <binding> => <method> ].
	 * @param mixed $default              The default value to return.
	 *
	 * @return mixed The first value that's not equal to the default one, the default value
	 *               otherwise.
	 */
	protected function method( array $bindings_and_methods, $default ) {
		$value = $default;
		$this_value = self::NOT_FOUND;

		foreach ( $bindings_and_methods as $binding => $method ) {
			if ( tribe()->offsetExists( $binding ) ) {
				$implementation = tribe( $binding );
				if ( method_exists( $implementation, $method ) ) {
					$this_value = $implementation->$method();
				}
			}

			if ( $this_value !== self::NOT_FOUND ) {
				$value = $this_value;
				break;
			}
		}

		return $value;
	}

	/**
	 * Reads the value from one or more functions until one returns a value that's not the default one.
	 *
	 * @since TBD
	 *
	 * @param array $functions An array of functions to call, in order.
	 * @param mixed $default The default value to return.
	 *
	 * @return mixed The first value that's not equal to the default one, the default value
	 *               otherwise.
	 */
	protected function func( array $functions, $default ) {
		$value = $default;
		$this_value = self::NOT_FOUND;

		foreach ( $functions as $function ) {
			if ( function_exists( $function ) ) {
				$this_value = $function();
			}

			if ( $this_value !== self::NOT_FOUND ) {
				$value = $this_value;
				break;
			}
		}

		return $value;
	}
}
