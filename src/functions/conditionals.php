<?php

/**
 * Determines if upsells should be hidden.
 *
 * @since TBD
 *
 * @param string $slug Which upsell is this conditional for, if nothing passed it will apply to all.
 *
 * @return bool
 */
function tec_hide_upsell( string $slug = 'all' ): bool {
	$verify = static function( $needle, $haystack ) {
		// In all cases if true or false boolean we return that.
		if ( is_bool( $haystack ) ) {
			return $haystack;
		}

		// check for truthy or the `all` match.
		$truthy = tribe_is_truthy( $haystack );
		if ( $truthy || 'all' === $haystack ) {
			return $truthy;
		}

		// Now allow multiple to be targeted as a string.
		$constant = explode( '|', $haystack );
		return in_array( 'all', $constant, true ) || in_array( $needle, $constant, true );
	};

	// If upsells have been manually hidden, respect that.
	if ( defined( 'TEC_HIDE_UPSELL' ) ) {
		return $verify( $slug, TEC_HIDE_UPSELL );
	}

	// If upsells have been manually hidden, respect that.
	if ( defined( 'TRIBE_HIDE_UPSELL' ) ) {
		return $verify( $slug, TRIBE_HIDE_UPSELL );
	}

	$env_var = getenv( 'TEC_HIDE_UPSELL' );
	if ( false !== $env_var ) {
		return $verify( $slug, $env_var );
	}

	/**
	 * Allows filtering of the Upsells for anything using Common.
	 *
	 * @since TBD
	 *
	 * @param bool|string $hide Determines if Upsells are hidden.
	 */
	$haystack = apply_filters( 'tec_hide_upsell', false );

	return $verify( $slug, $haystack );
}