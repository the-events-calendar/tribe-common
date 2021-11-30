<?php

namespace Tribe\Traits;

trait Reflection_Tools {

	public function get_reflection_class( $object ) {
		return new \ReflectionClass( $object );
	}

	public function get_object_property_names( $object, $access = \ReflectionProperty::IS_PUBLIC ) {
		$properties = $this->get_object_properties( $object, $access );

		return array_map( function( $property ) {
			return $property->name;
		}, $properties );

	}

	public function get_object_properties( $object, $access = \ReflectionProperty::IS_PUBLIC ) {
		$reflection = new \ReflectionClass( $object );
		$vars       = $reflection->getProperties( $access );

		do {
			$vars = array_merge( $vars, $reflection->getProperties( $access ) );
		} while ( $reflection = $reflection->getParentClass() );

		return $vars;
	}

	public function get_object_method_names( $object, $access = \ReflectionProperty::IS_PUBLIC ) {
		$properties = $this->get_object_methods( $object, $access );

		return array_map( function( $property ) {
			return $property->name;
		}, $properties );

	}

	public function get_object_methods( $object, $access = \ReflectionProperty::IS_PUBLIC ) {
		$reflection = new \ReflectionClass( $object );
		$vars       = $reflection->getMethods( $access );

		do {
			$vars = array_merge( $vars, $reflection->getProperties( $access ) );
		} while ( $reflection = $reflection->getParentClass() );

		return $vars;
	}
}