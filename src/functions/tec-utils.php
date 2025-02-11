<?php
/**
 * Utility functions that rely on tribe(). To be called after tribe() has been initialized.
 */

use TEC\Common\lucatume\DI52\ContainerException;

if ( ! function_exists( 'tec_copy_to_clipboard_button' ) ) {
	/**
	 * Output a button to copy the content of an element to the clipboard.
	 *
	 * @since 6.0.3
	 *
	 * @param string $content_to_copy The content to copy to the clipboard.
	 * @param bool   $output_button   Whether to output the button or just the target element.
	 * @param string $aria_label      The aria-label attribute for the button.
	 *
	 * @return string
	 */
	function tec_copy_to_clipboard_button( string $content_to_copy, bool $output_button = true, string $aria_label = '' ): string {
		$cache_key = 'tec_copy_to_clipboard_counter';
		$counter   = tribe( 'cache' )->get( $cache_key, '', 1 );

		$target        = 'tec-copy-text-target-' . $counter;
		$notice_target = 'tec-copy-to-clipboard-notice-content-' . $counter;

		$aria_label = $aria_label ? $aria_label : __( 'Copy to clipboard', 'the-events-calendar' );
		tribe( 'cache' )->set( $cache_key, ++$counter, Tribe__Cache::NON_PERSISTENT );
		if ( $output_button ) :
			?>
			<a
				title="<?php echo esc_attr( $aria_label ); ?>"
				href="javascript:void(0)"
				aria-label="<?php echo esc_attr( $aria_label ); ?>"
				aria-describedby="<?php echo esc_attr( $notice_target ); ?>"
				data-clipboard-action="copy"
				data-notice-target=".<?php echo esc_attr( $notice_target ); ?>"
				data-clipboard-target=".<?php echo esc_attr( $target ); ?>"
				class="tec-copy-to-clipboard tribe-dashicons"
				role="button"
			>
				<input type="text" readonly value="<?php echo esc_attr( $content_to_copy ); ?>" aria-hidden="true" />
				<span class="dashicons dashicons-admin-page" aria-hidden="true"></span>
			</a>
		<?php
		endif;
		?>
		<span class="screen-reader-text <?php echo esc_attr( $target ); ?>"><?php echo esc_html( $content_to_copy ); ?></span>
		<div class="tec-copy-to-clipboard-notice">
			<div class="tec-copy-to-clipboard-notice-content <?php echo esc_attr( $notice_target ); ?>" aria-live="polite">
			</div>
		</div>
		<?php

		// When they want to print the button outside of this function they need to be aware of the target.
		return $target;
	}
}

if ( ! function_exists( 'tribe_register_plugin' ) ) {
	/**
	 * Checks if this plugin has permission to run, if not it notifies the admin
	 *
	 * @param string $file_path    Full file path to the base plugin file.
	 * @param string $main_class   The Main/base class for this plugin.
	 * @param string $version      The version.
	 * @param array  $classes_req  Any Main class files/tribe plugins required for this to run.
	 * @param array  $dependencies an array of dependencies to check.
	 */
	function tribe_register_plugin( $file_path, $main_class, $version, $classes_req = [], $dependencies = [] ) {
		$tribe_dependency = tribe( Tribe__Dependency::class );
		$tribe_dependency->register_plugin( $file_path, $main_class, $version, $classes_req, $dependencies );
	}
}

if ( ! function_exists( 'tribe_get_class_instance' ) ) {
	/**
	 * Gets the class instance / Tribe Container from the passed object or string.
	 *
	 * @since 4.10.0
	 *
	 * @see   \TEC\Common\lucatume\DI52\Builders\ValueBuilder\App::isBound()
	 * @see   \tribe()
	 *
	 * @param string|object $class The plugin class' singleton name, class name, or instance.
	 *
	 * @return mixed|object|Tribe__Container|null Null if not found, else the result from tribe().
	 */
	function tribe_get_class_instance( $class ) {
		if ( is_object( $class ) ) {
			return $class;
		}

		if ( ! is_string( $class ) ) {
			return null;
		}

		// Check if class exists and has instance getter method.
		if ( class_exists( $class ) ) {
			if ( method_exists( $class, 'instance' ) ) {
				return $class::instance();
			}

			if ( method_exists( $class, 'get_instance' ) ) {
				return $class::get_instance();
			}
		}

		try {
			return tribe()->has( $class ) ? tribe()->get( $class ) : null;
		} catch ( ContainerException $exception ) {
			return null;
		}
	}
}

/**
 * Get the next increment of a cached incremental value.
 *
 * @since 4.14.7
 *
 * @param string $key Cache key for the incrementor.
 * @param string $expiration_trigger The trigger that causes the cache key to expire.
 * @param int    $default The default value of the incrementor.
 *
 * @return int
 **/
function tribe_get_next_cached_increment( $key, $expiration_trigger = '', $default = 0 ) {
	$cache = tribe( 'cache' );
	$value = (int) $cache->get( $key, $expiration_trigger, $default );
	++$value;
	$cache->set( $key, $value, \Tribe__Cache::NON_PERSISTENT, $expiration_trigger );

	return $value;
}

if ( ! function_exists( 'tribe_check_plugin' ) ) {
	/**
	 * Checks if this plugin has permission to run, if not it notifies the admin
	 *
	 * @since 4.9
	 *
	 * @param string $main_class The Main/base class for this plugin.
	 *
	 * @return bool Indicates if plugin should continue initialization
	 */
	function tribe_check_plugin( $main_class ) {

		$tribe_dependency = tribe( Tribe__Dependency::class );

		return $tribe_dependency->check_plugin( $main_class );
	}
}
