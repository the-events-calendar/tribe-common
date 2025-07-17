<?php

namespace TEC\Common\Key_Value_Cache;

use TEC\Common\Tests\Provider\Controller_Test_Case;
use Tribe\Tests\Traits\With_Uopz;

/**
 * This uses the Controller test case to ease the registration under different cache contexts.
 */
class Function_API_Test extends Controller_Test_Case {
	use With_Uopz;

	protected $controller_class = Controller::class;

	private function deactivate_log_listener():void{
		remove_all_filters('tribe_log');
	}

	public function test_function_api_when_using_object_caching(): void {
		$this->set_fn_return( 'wp_using_ext_object_cache', true );

		// Register the controller that will find object caching available.
		$this->make_controller()->register();

		// The controller case is monitoring it, but it's not needed here.
		$this->deactivate_log_listener();

		$cache = tec_kv_cache();
		$this->assertInstanceOf( Object_Cache::class, $cache );

		// Test has() should return false for non-existent key.
		$this->assertFalse( $cache->has( 'test_key' ) );
		$this->assertFalse( wp_cache_get( 'test_key', Object_Cache::CACHE_GROUP ) );

		// Test set() and get() with expiration = 0 should fail.
		$this->assertFalse( $cache->set( 'test_key_zero', 'test_value', 0 ) );
		$this->assertFalse( $cache->has( 'test_key_zero' ) );
		$this->assertEquals( '', $cache->get( 'test_key_zero' ) );
		$this->assertFalse( wp_cache_get( 'test_key_zero', Object_Cache::CACHE_GROUP ) );

		// Test set() and get() with valid expiration.
		$this->assertTrue( $cache->set( 'test_key', 'test_value', 300 ) );
		$this->assertEquals( 'test_value', $cache->get( 'test_key' ) );
		$this->assertTrue( $cache->has( 'test_key' ) );
		// Verify the value is stored in WordPress cache.
		$this->assertEquals( 'test_value', wp_cache_get( 'test_key', Object_Cache::CACHE_GROUP ) );

		// Test get() with default.
		$this->assertEquals( 'default_value', $cache->get( 'non_existent_key', 'default_value' ) );
		$this->assertFalse( wp_cache_get( 'non_existent_key', Object_Cache::CACHE_GROUP ) );

		// Test update existing key.
		$this->assertTrue( $cache->set( 'test_key', 'updated_value', 300 ) );
		$this->assertEquals( 'updated_value', $cache->get( 'test_key' ) );
		// Verify the updated value in WordPress cache.
		$this->assertEquals( 'updated_value', wp_cache_get( 'test_key', Object_Cache::CACHE_GROUP ) );

		// Test with expiration.
		$this->assertTrue( $cache->set( 'expiring_key', 'expiring_value', 3600 ) );
		$this->assertEquals( 'expiring_value', $cache->get( 'expiring_key' ) );
		// Verify the value is stored in WordPress cache.
		$this->assertEquals( 'expiring_value', wp_cache_get( 'expiring_key', Object_Cache::CACHE_GROUP ) );

		// Test expiration below 300 seconds should not store value.
		$this->assertFalse( $cache->set( 'short_expiry_key', 'short_expiry_value', 299 ) );
		// Verify the value was not stored.
		$this->assertFalse( $cache->has( 'short_expiry_key' ) );
		$this->assertEquals( '', $cache->get( 'short_expiry_key' ) );
		$this->assertFalse( wp_cache_get( 'short_expiry_key', Object_Cache::CACHE_GROUP ) );

		// Test expiration exactly 300 seconds should store value.
		$this->assertTrue( $cache->set( 'min_expiry_key', 'min_expiry_value', 300 ) );
		// Verify the value was stored.
		$this->assertTrue( $cache->has( 'min_expiry_key' ) );
		$this->assertEquals( 'min_expiry_value', $cache->get( 'min_expiry_key' ) );
		$this->assertEquals( 'min_expiry_value', wp_cache_get( 'min_expiry_key', Object_Cache::CACHE_GROUP ) );

		// Test getJson().
		$json_data = [
			'name'  => 'test',
			'value' => 123,
		];
		$this->assertTrue( $cache->set( 'json_key', json_encode( $json_data ), 300 ) );
		$this->assertEquals( $json_data, $cache->get_json( 'json_key', true ) );
		$this->assertEquals( (object) $json_data, $cache->get_json( 'json_key', false ) );
		// Verify the JSON string is stored in WordPress cache.
		$this->assertEquals( json_encode( $json_data ), wp_cache_get( 'json_key', Object_Cache::CACHE_GROUP ) );

		// Test set_json().
		$json_data2 = [
			'status' => 'active',
			'count'  => 42,
			'nested' => [ 'deep' => 'value' ],
		];
		$expected_json_data2 = (object)[
			'status' => 'active',
			'count'  => 42,
			'nested' => (object)[ 'deep' => 'value' ],
		];
		$this->assertTrue( $cache->set_json( 'json_key2', $json_data2, 300 ) );
		$this->assertEquals( $json_data2, $cache->get_json( 'json_key2', true ) );
		$this->assertEquals( $expected_json_data2, $cache->get_json( 'json_key2', false ) );
		// Verify the JSON string is stored in WordPress cache.
		$this->assertEquals( wp_json_encode( $json_data2 ), wp_cache_get( 'json_key2', Object_Cache::CACHE_GROUP ) );

		// Test set_json() with object.
		$obj = (object) [ 'property' => 'value', 'number' => 123 ];
		$this->assertTrue( $cache->set_json( 'json_object', $obj, 300 ) );
		$retrieved_as_object = $cache->get_json( 'json_object', false );
		$this->assertEquals( $obj, $retrieved_as_object );

		// Test set_json() with scalar values.
		$this->assertTrue( $cache->set_json( 'json_string', 'test string', 300 ) );
		$this->assertEquals( 'test string', $cache->get_json( 'json_string', true ) );

		$this->assertTrue( $cache->set_json( 'json_number', 42, 300 ) );
		$this->assertEquals( 42, $cache->get_json( 'json_number', true ) );

		$this->assertTrue( $cache->set_json( 'json_bool', true, 300 ) );
		$this->assertEquals( true, $cache->get_json( 'json_bool', true ) );

		$this->assertTrue( $cache->set_json( 'json_null', null, 300 ) );
		$this->assertEquals( null, $cache->get_json( 'json_null', true ) );

		// Test delete().
		$cache->delete( 'test_key' );
		$this->assertFalse( $cache->has( 'test_key' ) );
		$this->assertEquals( '', $cache->get( 'test_key' ) );
		// Verify the key is deleted from WordPress cache.
		$this->assertFalse( wp_cache_get( 'test_key', Object_Cache::CACHE_GROUP ) );

		// Test flush().
		$cache->set( 'key1', 'value1', 300 );
		$cache->set( 'key2', 'value2', 300 );
		// Verify values are in WordPress cache before flush.
		$this->assertEquals( 'value1', wp_cache_get( 'key1', Object_Cache::CACHE_GROUP ) );
		$this->assertEquals( 'value2', wp_cache_get( 'key2', Object_Cache::CACHE_GROUP ) );

		$cache->flush();
		$this->assertFalse( $cache->has( 'key1' ) );
		$this->assertFalse( $cache->has( 'key2' ) );
		// Verify the keys are flushed from WordPress cache.
		$this->assertFalse( wp_cache_get( 'key1', Object_Cache::CACHE_GROUP ) );
		$this->assertFalse( wp_cache_get( 'key2', Object_Cache::CACHE_GROUP ) );

		// Test set_serialized() and get_serialized().
		$test_object = (object) [
			'property1' => 'value1',
			'property2' => 42,
			'property3' => [ 'nested' => 'array' ],
		];
		$this->assertTrue( $cache->set_serialized( 'serialized_object', $test_object, 300 ) );
		$retrieved_object = $cache->get_serialized( 'serialized_object' );
		$this->assertEquals( $test_object, $retrieved_object );

		// Test serializing array.
		$test_array = [ 'key1' => 'value1', 'key2' => [ 'nested' => true ] ];
		$this->assertTrue( $cache->set_serialized( 'serialized_array', $test_array, 300 ) );
		$retrieved_array = $cache->get_serialized( 'serialized_array' );
		$this->assertEquals( $test_array, $retrieved_array );

		// Test serializing scalar values.
		$this->assertTrue( $cache->set_serialized( 'serialized_int', 42, 300 ) );
		$this->assertSame( 42, $cache->get_serialized( 'serialized_int' ) );

		$this->assertTrue( $cache->set_serialized( 'serialized_bool', false, 300 ) );
		$this->assertSame( false, $cache->get_serialized( 'serialized_bool' ) );

		// Test get_serialized with non-existent key.
		$this->assertNull( $cache->get_serialized( 'non_existent_serialized' ) );

		// Test get_serialized with invalid serialized data.
		$cache->set( 'invalid_serialized', 'not serialized data', 300 );
		$this->assertNull( $cache->get_serialized( 'invalid_serialized' ) );
	}

	private function run_table_cache_tests(): void {
		global $wpdb;

		// Register the controller that will find object caching not available.
		$this->make_controller()->register();

		// The controller case is monitoring it, but it's not needed here.
		$this->deactivate_log_listener();

		$cache = tec_kv_cache();
		$this->assertInstanceOf( Key_Value_Cache_Table::class, $cache );

		// Test has() should return false for non-existent key.
		$this->assertFalse( $cache->has( 'test_key' ) );

		// Test set() and get() with expiration = 0 should fail.
		$this->assertFalse( $cache->set( 'test_key_zero', 'test_value', 0 ) );
		$this->assertFalse( $cache->has( 'test_key_zero' ) );
		$this->assertEquals( '', $cache->get( 'test_key_zero' ) );

		// Test set() and get() with valid expiration.
		$this->assertTrue( $cache->set( 'test_key', 'test_value', 300 ) );
		$this->assertEquals( 'test_value', $cache->get( 'test_key' ) );
		$this->assertTrue( $cache->has( 'test_key' ) );

		// Test get() with default.
		$this->assertEquals( 'default_value', $cache->get( 'non_existent_key', 'default_value' ) );

		// Test update existing key.
		$this->assertTrue( $cache->set( 'test_key', 'updated_value', 300 ) );
		$this->assertEquals( 'updated_value', $cache->get( 'test_key' ) );

		// Test with expiration - future expiration
		$this->assertTrue( $cache->set( 'expiring_key', 'expiring_value', 3600 ) );
		$this->assertEquals( 'expiring_value', $cache->get( 'expiring_key' ) );
		$this->assertTrue( $cache->has( 'expiring_key' ) );

		// Test expiration below 300 seconds should not store value.
		$this->assertFalse( $cache->set( 'short_expiry_key', 'short_expiry_value', 299 ) );
		// Verify the value was not stored.
		$this->assertFalse( $cache->has( 'short_expiry_key' ) );
		$this->assertEquals( '', $cache->get( 'short_expiry_key' ) );

		// Test expiration exactly 300 seconds should store value.
		$this->assertTrue( $cache->set( 'min_expiry_key', 'min_expiry_value', 300 ) );
		// Verify the value was stored.
		$this->assertTrue( $cache->has( 'min_expiry_key' ) );
		$this->assertEquals( 'min_expiry_value', $cache->get( 'min_expiry_key' ) );

		// Test getJson().
		$json_data = [
			'name'  => 'test',
			'value' => 123,
		];
		$this->assertTrue( $cache->set( 'json_key', json_encode( $json_data ), 300 ) );
		$this->assertEquals( $json_data, $cache->get_json( 'json_key', true ) );
		$this->assertEquals( (object) $json_data, $cache->get_json( 'json_key', false ) );

		// Test invalid JSON
		$this->assertTrue( $cache->set( 'invalid_json', 'not json', 300 ) );
		$this->assertNull( $cache->get_json( 'invalid_json' ) );

		// Test set_json().
		$json_data2 = [
			'status' => 'active',
			'count'  => 42,
			'nested' => [ 'deep' => 'value' ],
		];
		$expected_json_data2 = (object)[
			'status' => 'active',
			'count'  => 42,
			'nested' => (object)[ 'deep' => 'value' ],
		];
		$this->assertTrue( $cache->set_json( 'json_key2', $json_data2, 300 ) );
		$this->assertEquals( $json_data2, $cache->get_json( 'json_key2', true ) );
		$this->assertEquals( $expected_json_data2, $cache->get_json( 'json_key2', false ) );

		// Test set_json() with object.
		$obj = (object) [ 'property' => 'value', 'number' => 123 ];
		$this->assertTrue( $cache->set_json( 'json_object', $obj, 300 ) );
		$retrieved_as_object = $cache->get_json( 'json_object', false );
		$this->assertEquals( $obj, $retrieved_as_object );

		// Test set_json() with scalar values.
		$this->assertTrue( $cache->set_json( 'json_string', 'test string', 300 ) );
		$this->assertEquals( 'test string', $cache->get_json( 'json_string', true ) );

		$this->assertTrue( $cache->set_json( 'json_number', 42, 300 ) );
		$this->assertEquals( 42, $cache->get_json( 'json_number', true ) );

		$this->assertTrue( $cache->set_json( 'json_bool', true, 300 ) );
		$this->assertEquals( true, $cache->get_json( 'json_bool', true ) );

		$this->assertTrue( $cache->set_json( 'json_null', null, 300 ) );
		$this->assertEquals( null, $cache->get_json( 'json_null', true ) );

		// Test delete().
		$cache->delete( 'test_key' );
		$this->assertFalse( $cache->has( 'test_key' ) );
		$this->assertEquals( '', $cache->get( 'test_key' ) );

		// Test key length validation (max 191 chars).
		$long_key = str_repeat( 'a', 192 );
		$this->assertFalse( $cache->set( $long_key, 'value', 300 ) );
		// Verify the value was not stored.
		$this->assertFalse( $cache->has( $long_key ) );
		$this->assertEquals( '', $cache->get( $long_key ) );

		$valid_key = str_repeat( 'a', 191 );
		$this->assertTrue( $cache->set( $valid_key, 'value', 300 ) );
		// Verify the value was stored.
		$this->assertTrue( $cache->has( $valid_key ) );
		$this->assertEquals( 'value', $cache->get( $valid_key ) );

		// Test flush().
		$cache->set( 'key1', 'value1', 300 );
		$cache->set( 'key2', 'value2', 300 );
		$cache->flush();
		$this->assertFalse( $cache->has( 'key1' ) );
		$this->assertFalse( $cache->has( 'key2' ) );

		// Verify database is actually empty after flush.
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}tec_kv_cache" );
		$this->assertEquals( 0, $count );

		// Test set_serialized() and get_serialized().
		$test_object = (object) [
			'property1' => 'value1',
			'property2' => 42,
			'property3' => [ 'nested' => 'array' ],
		];
		$this->assertTrue( $cache->set_serialized( 'serialized_object', $test_object, 300 ) );
		$retrieved_object = $cache->get_serialized( 'serialized_object' );
		$this->assertEquals( $test_object, $retrieved_object );

		// Test serializing array.
		$test_array = [ 'key1' => 'value1', 'key2' => [ 'nested' => true ] ];
		$this->assertTrue( $cache->set_serialized( 'serialized_array', $test_array, 300 ) );
		$retrieved_array = $cache->get_serialized( 'serialized_array' );
		$this->assertEquals( $test_array, $retrieved_array );

		// Test serializing scalar values.
		$this->assertTrue( $cache->set_serialized( 'serialized_int', 42, 300 ) );
		$this->assertSame( 42, $cache->get_serialized( 'serialized_int' ) );

		$this->assertTrue( $cache->set_serialized( 'serialized_bool', false, 300 ) );
		$this->assertSame( false, $cache->get_serialized( 'serialized_bool' ) );

		// Test get_serialized with non-existent key.
		$this->assertNull( $cache->get_serialized( 'non_existent_serialized' ) );

		// Test get_serialized with invalid serialized data.
		$cache->set( 'invalid_serialized', 'not serialized data', 300 );
		$this->assertNull( $cache->get_serialized( 'invalid_serialized' ) );
	}

	public function test_function_when_using_table_cache(): void {
		$this->set_fn_return( 'wp_using_ext_object_cache', false );
		$this->run_table_cache_tests();
	}

	public function test_function_when_forcing_use_of_table_cache(): void {
		// The controller case is monitoring it, but it's not needed here.
		$this->deactivate_log_listener();
		$this->set_fn_return( 'wp_using_ext_object_cache', true );
		add_filter( 'tec_key_value_cache_force_use_of_table_cache', '__return_true' );
		$this->run_table_cache_tests();
	}
}
