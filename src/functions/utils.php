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
		$tribe_plugins = Tribe__Dependency::instance();

		if ( $tribe_plugins->has_requisite_plugins( $classes_req ) ) {
			$tribe_plugins->add_active_plugin( $main_class, $version, $file_path );

			return true;
		} elseif ( is_admin() ) {
			$tribe_plugins = new Tribe__Plugins();
			$admin_notice  = new Tribe__Plugin_Download_Notice( $file_path );

			foreach ( $classes_req as $class => $version ) {
				$plugin = $tribe_plugins->get_plugin_by_class( $class );
				$admin_notice->add_required_plugin( $plugin['short_name'], $plugin['thickbox_url'] );
			}
		}

		return false;
	}
}
