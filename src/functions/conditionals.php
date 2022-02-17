<?php

/**
 * Determines if upsells should be hidden.
 *
 * @since TBD
 *
 * @return bool
 */
function tec_hide_upsell(): bool {
	// If upsells have been manually hidden, respect that.
	if ( defined( 'TEC_HIDE_UPSELL' ) ) {
		return tribe_is_truthy( TEC_HIDE_UPSELL );
	}

	// If upsells have been manually hidden, respect that.
	if ( defined( 'TRIBE_HIDE_UPSELL' ) ) {
		return tribe_is_truthy( TRIBE_HIDE_UPSELL );
	}

	$env_var = getenv( 'TEC_HIDE_UPSELL' );
	if ( false !== $env_var ) {
		return tribe_is_truthy( $env_var );
	}

	/**
	 * Allows filtering of the Upsells for anything using Common.
	 *
	 * @since TBD
	 *
	 * @param bool $hide Determines if Upsells are hidden.
	 */
	return tribe_is_truthy( apply_filters( 'tec_hide_upsell', false ) );
}