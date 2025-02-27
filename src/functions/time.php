<?php
/**
 * Gets the current time in the format Y-m-d H:i:s.u rounded to 4 decimals.
 * This function specifically has no dependencies any other code.
 *
 * @since TBD
 *
 * @return string
 */
function tec_get_current_milliseconds(): string {
	return ( new DateTimeImmutable( 'now' ) )->format( 'Y-m-d H:i:s' ) . '.' . str_pad( substr( microtime( true ), 11,4 ), 4, '0', STR_PAD_RIGHT );
}
