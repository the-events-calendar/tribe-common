<?php

if ( ! function_exists( 'tribe_array_merge_recursive' ) ) {
	/**
	 * Recursively merge two arrays preserving keys.
	 *
	 * @link http://php.net/manual/en/function.array-merge-recursive.php#92195
	 *
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array
	 */
	function tribe_array_merge_recursive( array &$array1, array &$array2 ) {
		$merged = $array1;

		foreach ( $array2 as $key => &$value ) {
			if ( is_array( $value ) && isset( $merged [ $key ] ) && is_array( $merged [ $key ] ) ) {
				$merged [ $key ] = tribe_array_merge_recursive( $merged [ $key ], $value );
			} else {
				$merged [ $key ] = $value;
			}
		}

		return $merged;
	}
}

if ( ! function_exists( 'tribe_register_plugin' ) ) {
	/**
	 * Checks if this plugin has permission to run, if not it notifies the admin
	 *
	 * @param string $file_path   Full file path to the base plugin file
	 * @param string $main_class  The Main/base class for this plugin
	 * @param string $version     The version
	 * @param array  $classes_req Any Main class files/tribe plugins required for this to run
	 *
	 * @return bool Indicates if plugin should continue initialization
	 */
	function tribe_register_plugin( $file_path, $main_class, $version, $classes_req = array() ) {
		$tribe_dependency = Tribe__Dependency::instance();
		$should_plugin_run = true;

		// Checks to see if the plugins are active
		if ( ! empty( $classes_req ) && ! $tribe_dependency->has_requisite_plugins( $classes_req ) ) {
			$should_plugin_run = false;

			$tribe_plugins = new Tribe__Plugins();
			$admin_notice  = new Tribe__Admin__Notice__Plugin_Download( $file_path );

			foreach ( $classes_req as $class => $version ) {
				$plugin    = $tribe_plugins->get_plugin_by_class( $class );
				$is_active = $tribe_dependency->is_plugin_version( $class, $version );
				$admin_notice->add_required_plugin( $plugin['short_name'], $plugin['thickbox_url'], $is_active );
			}
		}

		if ( $should_plugin_run ) {
			$tribe_dependency->add_active_plugin( $main_class, $version, $file_path );
		}

		return $should_plugin_run;
	}
}

if ( ! function_exists( 'tribe_append_path' ) ) {
	/**
	 * Append a path fragment to a URL preserving query arguments
	 * and fragments.
	 *
	 * @param string $url A full URL in the `http://example.com/?query=var#frag` format.
	 * @param string $path The path to append to the existing, if any, one., e.g. `/some/path`
	 *
	 * @return mixed|string
	 *
	 * @since 4.3
	 */
	function tribe_append_path( $url, $path ) {
		$path = trim( $path, '/' );

		$query = @parse_url( $url, PHP_URL_QUERY );
		$frag  = @parse_url( $url, PHP_URL_FRAGMENT );

		if ( ! ( empty( $query ) && empty( $frag ) ) ) {
			$url   = str_replace( '?' . $query, '', $url );
			$url   = str_replace( '#' . $frag, '', $url );
			$query = $query ? '?' . $query : '';
			$frag  = $frag ? '#' . $frag : '';
		}

		$url = trailingslashit( esc_url_raw( trailingslashit( $url ) . $path ) );
		$url .= $query . $frag;

		return $url;
	}
}

if ( ! function_exists( 'tribe_exit' ) ) {
	/**
	 * Filterable `die` wrapper.
	 *
	 * @param string $status
	 *
	 * @return void|mixed Depending on the handler this function might return
	 *                    a value or `die` before anything is returned.
	 */
	function tribe_exit( $status = '' ) {
		$handler = 'die';

		/**
		 * Filters the callback to call in place of `die()`.
		 *
		 * @param callable $handler The `die` replacement callback.
		 * @param string   $status  The exit/die status.
		 */
		$handler = apply_filters( 'tribe_exit', $handler, $status );

		// Die and exit are language constructs that cannot be used as callbacks on all PHP runtimes
		if ( 'die' === $handler || 'exit' === $handler ) {
			exit;
		}

		return call_user_func( $handler, $status );
	}
}

