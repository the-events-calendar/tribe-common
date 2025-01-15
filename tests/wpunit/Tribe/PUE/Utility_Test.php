<?php
declare( strict_types=1 );

use lucatume\WPBrowser\TestCase\WPTestCase;
use Tribe__PUE__Plugin_Info as Plugin_Info;
use Tribe__PUE__Utility as Utility;

class Utility_Test extends WPTestCase {
	public function test_from_plugin_info(): void {
		// Build a plugin info with all the possible fields of Plugin Info.
		$plugin_info_public_properties = ( new ReflectionClass( Plugin_Info::class ) )->getProperties( ReflectionProperty::IS_PUBLIC );
		$plugin_info_prop_names        = array_map(
			fn( \ReflectionProperty $prop ) => $prop->getName(),
			$plugin_info_public_properties
		);
		$plugin_info_data              = array_combine(
			$plugin_info_prop_names,
			array_map(
				fn( string $name ) => 'some- ' . $name,
				$plugin_info_prop_names
			)
		);
		$plugin_info_json              = json_encode( $plugin_info_data );
		$plugin_info                   = Plugin_Info::from_json( $plugin_info_json );
		// Extract the `$copy_fields` value from the class.
		$plugin_utility_copy_fields = \Closure::bind(
			fn() => self::$copy_fields,
			null,
			Utility::class
		)();

		$utility = Utility::from_plugin_info( $plugin_info );

		foreach ( $plugin_utility_copy_fields as $field ) {
			// If the property is not explicitly set, this will throw a \ReflectionException that will fail the test.
			$field_property = new \ReflectionProperty( Utility::class, $field );
			$this->assertInstanceOf( \ReflectionProperty::class, $field_property );
			$this->assertEquals( $plugin_info_data[ $field ], $utility->{$field} );
		}
	}
}
