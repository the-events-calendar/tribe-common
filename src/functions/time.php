<?php
/**
 * Time functions.
 *
 * @since 6.8.0
 */

/**
 * Gets the current time in the format Y-m-d H:i:s.u rounded to 4 decimals.
 * This function specifically has no dependencies any other code.
 *
 * @since 6.8.0
 *
 * @param DateInterval|null $add      An interval to add to the current time.
 *                                    Defaults to null.
 * @param DateInterval|null $sub      An interval to subtract from the current time.
 *                                    Defaults to null.
 * @param DateTimeZone|null $timezone The timezone to use. Defaults to null.
 *
 * @return string
 */
function tec_get_current_milliseconds( ?DateInterval $add = null, ?DateInterval $sub = null, ?DateTimeZone $timezone = null ): string {
	$time = ( new DateTimeImmutable( 'now', $timezone ) );
	if ( $add ) {
		$time = $time->add( $add );
	}
	if ( $sub ) {
		$time = $time->sub( $sub );
	}
	return $time->format( 'Y-m-d H:i:s' ) . '.' . str_pad( substr( microtime( true ), 11, 4 ), 4, '0', STR_PAD_RIGHT );
}

/**
 * Given a tec_get_current_milliseconds() millisecond datetime, convert it to a timestamp.
 *
 * @since 6.8.0
 *
 * @param ?string $milliseconds The milliseconds to convert to a timestamp.
 *
 * @return ?int
 */
function tec_from_milliseconds_to_timestamp( ?string $milliseconds = null ): ?int {
	if ( ! $milliseconds ) {
		return null;
	}

	[$datetime,] = explode( '.', $milliseconds );
	$time        = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', $datetime );
	return $time->getTimestamp();
}
