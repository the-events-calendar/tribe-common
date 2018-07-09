<?php

class Tribe__Repository__Usage_Exception extends Exception {

	public static function because_properties_should_be_set_correctly( $name, $object ) {
		$class = get_class( $object );

		return new self( "Property {$name} should be set with a setter method, injected in the constructor and/or defined in an extending class." );
	}

	public static function because_the_read_filter_is_not_defined( $key, $object ) {
		$class = get_class( $object );

		return new self( "The class {$class} does not define a {$key} read filter: either implement it or try to use the provided filters." );
	}
}
