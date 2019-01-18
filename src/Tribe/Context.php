<?php

/**
 * Class Tribe__Context
 *
 * @since 4.7.7
 * @since TBD Made the context immutable.
 */
class Tribe__Context {

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
	 * 'function' - get the value from a function.
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
		$value = $default;
		$locations = self::$locations[ $key ];

		if ( ! isset( $locations ) ) {
			return $value;
		}

		if ( isset( $this->request_cache[ $key ] ) ) {
			$value = $this->request_cache[ $key ];
		} else {
			foreach ( $locations as $location => $keys ) {
				$keys = (array) $keys;

				switch ( $location ) {
					case 'request_var':
						// 'request_var' => 'tribe_event_display'.
						foreach ( $keys as $request_var ) {
							$value = tribe_get_request_var( $request_var, $default );
							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'query_var':
						// 'query_var' => 'is_home'.
						global $wp_query;
						foreach ( $keys as $query_var ) {
							$value = $wp_query->get( $query_var, $default );
							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'tribe_option':
						// 'tribe_option' => 'posts_per_page'.
						foreach ( $keys as $option_name ) {
							$value = tribe_get_option( $option_name, $default );
							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'option':
						// 'option' => 'posts_per_page'.
						foreach ( $keys as $option_name ) {
							$value = get_option( $option_name, $default );
							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'transient':
						// 'transient' => 'foo'.
						foreach ( $keys as $transient ) {
							$value = get_transient( $transient );
							if ( false !== $value ) {
								break;
							}
						}
						break;
					case 'constant':
						// 'constant' => 'BAR'.
						foreach ( $keys as $constant ) {
							$value = defined( $constant ) ? constant( $constant ) : $default;
							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'global_var':
						// 'global_var' => 'bar'.
						foreach ( $keys as $var ) {
							$value = isset( $GLOBALS[ $var ] ) ? $GLOBALS[ $var ] : $default;
							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'static_prop':
						// 'static_prop' =>array( 'Foo_Class', 'bar' ).
						foreach ( $keys as $class => $prop ) {
							$value = $default;

							if ( class_exists( $class ) && property_exists( $class, $prop ) ) {
								// PHP 5.2 compat, on PHP 5.3+ $class::$$prop
								$vars = get_class_vars( $class );

								return $vars[ $prop ];
							}

							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'prop':
						// 'prop' => array( 'events.some.foo', 'bar' ).
						foreach ( $keys as $binding => $prop ) {
							$value = tribe()->offsetExists( $binding ) && property_exists( tribe( $binding ), $prop )
								? tribe( $binding )->{$prop}
								: $default;

							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'static_method':
						// 'prop' => array( 'Foo_Class', 'bar_method' ).
						foreach ( $keys as $clas => $method ) {
							$value = class_exists( $class ) && method_exists( $class, $method )
								? call_user_func( array( $class, $method ) )
								: $default;

							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'method':
						foreach ( $keys as $binding => $method ) {
							if ( tribe()->offsetExists( $binding ) ) {
								$implementation = tribe( $binding );
								if ( method_exists( $implementation, $method ) ) {
									$value = $implementation->$method();
								}
							}

							if ( $value !== $default ) {
								break;
							}
						}
						break;
					case 'function':
						foreach ( $keys as $function ) {
							if ( function_exists( $function ) ) {
								$value = $function();
							}

							if ( $value !== $default ) {
								break;
							}
						}
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
}
