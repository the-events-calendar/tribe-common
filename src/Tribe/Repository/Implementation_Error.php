<?php
class Tribe__Repository__Implementation_Error extends Exception {

	public static function because_property_is_not_defined( $name, $object ) {
		$class = get_class($object);
		return new self("The {$class} class does not define a {$name} property; add it by decorating or extending this class.");
	}
}