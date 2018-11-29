<?php
/**
 * An abstraction layer to handle feature detection queries the plugin components
 * might need.
 *
 * @since 4.7.23
 */

/**
 * Class Tribe__Feature_Detection
 *
 * @since 4.7.23
 */
class Tribe__Feature_Detection {
	/**
	 * The name of the transient storing the support check results.
	 *
	 * @var string
	 */
	public static $transient = 'tribe_feature_detection';

	/**
	 * Checks whether async, AJAX-based, background processing is supported or not.
	 *
	 * To avoid making this costly check on each load the result of this check is cached
	 * in the `tribe_feature_detection` transient, under the `supports_async_process` key.
	 *
	 * @since 4.7.23
	 *
	 * @param bool $force Whether to use the cache value, if available, or force the check
	 *                    to be made again.
	 *
	 * @return bool Whether async, AJAX-based, background processing is supported or not.
	 */
	public function supports_async_process( $force = false ) {
		/**
		 * Filters whether async, AJAX-based, processing is supported or not.
		 *
		 * Returning a non `null` value here will make this method bail and
		 * return the filtered value immediately.
		 *
		 * @since 4.7.23
		 *
		 * @param bool $supports_async_process Whether async, AJAX-based, processing is supported or not.
		 * @param bool $force                  Whether the check is forcing the cached value to be refreshed
		 *                                     or not.
		 */
		$supports_async_process = apply_filters( 'tribe_supports_async_process', null, $force );
		if ( null !== $supports_async_process ) {
			return (bool) $supports_async_process;
		}

		$cached = get_transient( self::$transient );

		if (
			$force
			|| false === $cached
			|| ( is_array( $cached ) && ! isset( $cached['supports_async_process'] ) )
		) {
			/*
			 * Build and dispatch the tester: if it works a transient should be set.
			 */
			$tester = new Tribe__Process__Tester();
			$tester->dispatch();

			$wait_up_to             = 10;
			$start                  = time();
			$supports_async_process = false;

			while ( ! $supports_async_process && time() <= $start + $wait_up_to ) {
				$supports_async_process = (bool) get_transient( $tester->get_canary_transient() );
			}

			// Remove it not to spoof future checks.
			delete_transient( $tester->get_canary_transient() );

			$cached['supports_async_process'] = $supports_async_process;

			set_transient( self::$transient, $cached, WEEK_IN_SECONDS );
		}

		return (bool) $cached['supports_async_process'];
	}
}