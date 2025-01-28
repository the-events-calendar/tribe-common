<?php

namespace Tribe\PUE;

use Codeception\TestCase\WPTestCase;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__PUE__Plugin_Info;

class Plugin_Info_Test extends WPTestCase {
	use With_Uopz;

	/**
	 * @test
	 */
	public function it_should_return_all_whitelisted_keys() {
		$plugin_info   = new Tribe__PUE__Plugin_Info();
		$expected_keys = array_keys( get_class_vars( Tribe__PUE__Plugin_Info::class ) );

		$this->assertEquals( $expected_keys, $plugin_info->get_whitelisted_keys(), 'Whitelist keys do not match expected properties.' );
	}

	/**
	 * @test
	 */
	public function it_should_validate_keys_against_whitelist_correctly() {
		$plugin_info = new Tribe__PUE__Plugin_Info();
		$valid_key   = 'name'; // An existing property of the class.
		$invalid_key = 'non_existent_key'; // A key not defined in the class.

		$this->assertTrue( $plugin_info->check_whitelisted_keys( $valid_key ), "'$valid_key' should be a valid whitelist key." );
		$this->assertFalse( $plugin_info->check_whitelisted_keys( $invalid_key ), "'$invalid_key' should not be a valid whitelist key." );
	}

	/**
	 * @test
	 */
	public function it_should_populate_only_whitelisted_properties_from_json() {
		$json = wp_json_encode(
			[
				'name'                => 'Sample Plugin',
				'version'             => '1.0.0',
				'non_whitelisted_key' => 'Should not be added',
				'plugin_homepage'     => 'https://example.com', // Plugin prefix to be stripped.
			]
		);

		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $json );

		$this->assertInstanceOf( Tribe__PUE__Plugin_Info::class, $plugin_info, 'from_json did not return an instance of Tribe__PUE__Plugin_Info.' );
		$this->assertEquals( 'Sample Plugin', $plugin_info->name, 'Property "name" was not set correctly.' );
		$this->assertEquals( '1.0.0', $plugin_info->version, 'Property "version" was not set correctly.' );
		$this->assertEquals( 'https://example.com', $plugin_info->homepage, 'Property "homepage" was not set correctly after prefix removal.' );
		$this->assertObjectNotHasAttribute( 'non_whitelisted_key', $plugin_info, 'Non-whitelisted key should not be added to the object.' );
	}

	/**
	 * @test
	 */
	public function it_should_return_null_for_invalid_json_input() {
		$invalid_json = 'invalid json string';
		$plugin_info  = Tribe__PUE__Plugin_Info::from_json( $invalid_json );

		$this->assertNull( $plugin_info, 'from_json should return null for invalid JSON input.' );
	}

	/**
	 * @test
	 */
	public function it_should_return_null_when_required_fields_are_missing() {
		$json = wp_json_encode(
			[
				'non_whitelisted_key' => 'This should be ignored',
			]
		);

		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $json );

		$this->assertNull( $plugin_info, 'from_json should return null when required fields are missing.' );
	}

	/**
	 * @test
	 */
	public function it_should_handle_empty_json() {
		$empty_json  = '{}';
		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $empty_json );

		$this->assertNull( $plugin_info, 'from_json should return null for empty JSON.' );
	}

	/**
	 * @test
	 */
	public function it_should_handle_nested_json_with_valid_and_invalid_keys() {
		$json = wp_json_encode(
			[
				'results' => [
					[
						'name'                => 'Nested Plugin',
						'version'             => '2.0.0',
						'non_whitelisted_key' => 'Ignored',
					],
				],
			]
		);

		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $json );

		$this->assertInstanceOf( Tribe__PUE__Plugin_Info::class, $plugin_info, 'from_json did not return an instance for nested JSON.' );
		$this->assertEquals( 'Nested Plugin', $plugin_info->name, 'Property "name" in nested JSON was not set correctly.' );
		$this->assertEquals( '2.0.0', $plugin_info->version, 'Property "version" in nested JSON was not set correctly.' );
		$this->assertObjectNotHasAttribute( 'non_whitelisted_key', $plugin_info, 'Non-whitelisted key in nested JSON should not be added.' );
	}

	/**
	 * @test
	 */
	public function it_should_ignore_plugin_prefix_in_json_keys() {
		$json = wp_json_encode(
			[
				'plugin_name'    => 'Prefixed Plugin',
				'plugin_version' => '3.1.4',
				'plugin_extra'   => 'Ignored',
			]
		);

		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $json );

		$this->assertInstanceOf( Tribe__PUE__Plugin_Info::class, $plugin_info, 'from_json did not return an instance for prefixed JSON keys.' );
		$this->assertEquals( 'Prefixed Plugin', $plugin_info->name, 'Property "name" was not set correctly from prefixed JSON key.' );
		$this->assertEquals( '3.1.4', $plugin_info->version, 'Property "version" was not set correctly from prefixed JSON key.' );
		$this->assertObjectNotHasAttribute( 'plugin_extra', $plugin_info, 'Non-whitelisted prefixed key should not be added to the object.' );
	}

	/**
	 * @test
	 */
	public function it_should_return_null_for_json_missing_required_fields() {
		$json = json_encode(
			[
				'plugin_author' => 'Test Author', // No 'name' or 'version'.
			]
		);

		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $json );

		$this->assertNull( $plugin_info, 'from_json should return null when required fields are missing.' );
	}

	/**
	 * @test
	 */
	public function it_should_create_instance_for_json_with_api_invalid_or_no_api() {
		$json = wp_json_encode(
			[
				'plugin_api_invalid' => true, // Valid because `api_invalid` is set.
			]
		);

		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $json );

		$this->assertInstanceOf( Tribe__PUE__Plugin_Info::class, $plugin_info, 'from_json should create an instance when `api_invalid` is set.' );
		$this->assertTrue( $plugin_info->api_invalid, 'Property "api_invalid" was not set correctly.' );
	}

	/**
	 * @test
	 */
	public function it_should_create_instance_with_name_and_version() {
		$json = wp_json_encode(
			[
				'plugin_name'    => 'Valid Plugin',
				'plugin_version' => '1.2.3',
			]
		);

		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $json );

		$this->assertInstanceOf( Tribe__PUE__Plugin_Info::class, $plugin_info, 'from_json should create an instance when `name` and `version` are set.' );
		$this->assertEquals( 'Valid Plugin', $plugin_info->name, 'Property "name" was not set correctly.' );
		$this->assertEquals( '1.2.3', $plugin_info->version, 'Property "version" was not set correctly.' );
	}

	/**
	 * @test
	 */
	public function it_should_return_null_for_empty_json() {
		$json = '{}';

		$plugin_info = Tribe__PUE__Plugin_Info::from_json( $json );

		$this->assertNull( $plugin_info, 'from_json should return null for empty JSON.' );
	}
}
