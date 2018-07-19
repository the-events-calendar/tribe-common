<?php

/**
 * Class Tribe__Repository__Usage_Error
 *
 * @since TBD
 *
 * Thrown to indicate an error in the repository usage by a developer; this
 * is meant to be used to help developers to use the repository.
 */
class Tribe__Repository__Usage_Error extends Exception {

	/**
	 * Do not ally dynamic set of properties on the repository; protected
	 * properties are read-only.
	 *
	 * @since TBD
	 *
	 * @param string                       $name   The name of the property the client code is trying to set.
	 * @param Tribe__Repository__Interface $object The instance of the repository.
	 *
	 * @return Tribe__Repository__Usage_Error
	 */
	public static function because_properties_should_be_set_correctly( $name, $object ) {
		$class = get_class( $object );

		return new self( "Property {$name} should be set with a setter method, injected in the constructor and/or defined in an extending class." );
	}

	/**
	 * Clearly indicate that a filter is not defined on the repository in use.
	 *
	 * This is to allow for more clear comprehension of errors related to
	 * missing filters.
	 *
	 * @since TBD
	 *
	 * @param string                       $key    The filter the client code is trying to use.
	 * @param Tribe__Repository__Interface $object The instance of the repository.
	 *
	 * @return Tribe__Repository__Usage_Error
	 */
	public static function because_the_read_filter_is_not_defined( $key, $object ) {
		$class = get_class( $object );

		return new self( "The class {$class} does not define a {$key} read filter: either implement it or try to use the provided filters." );
	}

	/**
	 * Indicates that a property is not defined on the repository.
	 *
	 * @since TBD
	 *
	 * @param string                       $name The name of the property the client code is trying to read.
	 * @param Tribe__Repository__Interface $object
	 *
	 * @return Tribe__Repository__Usage_Error
	 */
	public static function because_property_is_not_defined( $name, $object ) {
		$class = get_class( $object );

		return new self( "The {$class} class does not define a {$name} property; add it by decorating or extending this class." );
	}

	/**
	 * Indicates that a field cannot be updated by the repository class.
	 *
	 * @since TBD
	 *
	 * @param string                              $key
	 * @param Tribe__Repository__Update_Interface $object
	 *
	 * @return Tribe__Repository__Usage_Error
	 */
	public static function because_this_field_cannot_be_updated( $key, $object ) {
		$class = get_class( $object );

		return new self( "The {$class} class does not allow udpating the {$key} field; allow it by decorating or extending this class." );
	}

	/**
	 * Indicates that the `set` method of the Update repository is being used incorrectly.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Repository__Update_Interface $object
	 *
	 * @return Tribe__Repository__Usage_Error
	 */
	public static function because_udpate_key_should_be_a_string( $object ) {
		$class = get_class( $object );

		return new self( 'The key used in the `set` method should be a string; if you want to set multiple fields at once use the `set_args` method.' );
	}

	/**
	 * Indicates that a magic `__call` method redirection could not find the method
	 * in any sub-repository.
	 *
	 * @since TBD
	 *
	 * @param string                       $method
	 * @param Tribe__Repository__Interface $object
	 *
	 * @return Tribe__Repository__Usage_Error
	 */
	public static function because_the_called_method_was_not_found( $method, $object ) {
		$class = get_class( $object );

		return new self( "The {$method} method is not defined by any of the sub-repositories; if one of the sub-repositories this repository is handling does provided the method add it in the `__call_map` property." );
	}
}
