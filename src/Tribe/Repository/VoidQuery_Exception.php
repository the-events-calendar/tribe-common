<?php

/**
 * Class Tribe__Repository__Void_Query_Exception
 *
 * @since TBD
 */
class Tribe__Repository__Void_Query_Exception extends Exception {
	public static function because_the_query_would_yield_no_results( $reason ) {
		return new self( "The query would yield no results due to {$reason}, this excpetion should be handled" );
	}
}
