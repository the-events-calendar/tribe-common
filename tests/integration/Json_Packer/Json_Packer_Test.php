<?php

namespace Tribe\tests\integration\Json_Packer;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use lucatume\WPBrowser\TestCase\WPTestCase;
use stdClass;
use TEC\Common\Json_Packer\Json_Packer;

// Test classes for the tests.
// phpcs:disable
class Test_User {
	private string $username;
	private string $email;
	private bool $active;

	public function __construct( string $username, string $email, bool $active ) {
		$this->username = $username;
		$this->email    = $email;
		$this->active   = $active;
	}

	public function getUsername(): string {
		return $this->username;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function isActive(): bool {
		return $this->active;
	}
}

class Test_Admin_User extends Test_User {
	private array $permissions;

	public function __construct( string $username, string $email, bool $active, array $permissions ) {
		parent::__construct( $username, $email, $active );
		$this->permissions = $permissions;
	}

	public function getPermissions(): array {
		return $this->permissions;
	}
}
class Test_Address {
	private string $street;
	private string $city;
	private string $state;
	private string $zip;

	public function __construct( string $street, string $city, string $state, string $zip ) {
		$this->street = $street;
		$this->city   = $city;
		$this->state  = $state;
		$this->zip    = $zip;
	}

	public function getStreet(): string {
		return $this->street;
	}

	public function getCity(): string {
		return $this->city;
	}

	public function getState(): string {
		return $this->state;
	}

	public function getZip(): string {
		return $this->zip;
	}
}

class Test_User_With_Address extends Test_User {
	private Test_Address $address;

	public function __construct( string $username, string $email, bool $active, Test_Address $address ) {
		parent::__construct( $username, $email, $active );
		$this->address = $address;
	}

	public function getAddress(): Test_Address {
		return $this->address;
	}
}

class Test_User_With_Friend extends Test_User {
	private ?Test_User_With_Friend $friend = null;

	public function setFriend( Test_User_With_Friend $friend ): void {
		$this->friend = $friend;
	}

	public function getFriend(): ?Test_User_With_Friend {
		return $this->friend;
	}
}

class Test_Object_With_Uninitialized_Property {
	private string $initialized = 'initialized value';
	private string $uninitialized;

	public function hasInitialized(): bool {
		return $this->initialized === 'initialized value';
	}
}
// @phpcs::enable

class Json_Packer_Test extends WPTestCase {
	public static function scalar_values_provider(): array {
		return [
			'null value'           => [ null, 'null' ],
			'boolean true'         => [ true, 'boolean' ],
			'boolean false'        => [ false, 'boolean' ],
			'integer zero'         => [ 0, 'integer' ],
			'positive integer'     => [ 42, 'integer' ],
			'negative integer'     => [ -42, 'integer' ],
			'float zero'           => [ 0.0, 'float' ],
			'positive float'       => [ 3.14, 'float' ],
			'negative float'       => [ -3.14, 'float' ],
			'empty string'         => [ '', 'string' ],
			'simple string'        => [ 'Hello World', 'string' ],
			'string with unicode'  => [ 'Hello 世界 🌍', 'string' ],
			'string with newlines' => [ "Line 1\nLine 2\nLine 3", 'string' ],
		];
	}

	/**
	 * @test
	 * @dataProvider scalar_values_provider
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_scalar_values( $value, string $expected_type ) {
		$packer = new Json_Packer();

		$packed = $packer->pack( $value );

		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$decoded = json_decode( $packed, true );
		$this->assertEquals( $expected_type, $decoded['type'] );

		$unpacked = $packer->unpack( $packed );
		if ( is_float( $value ) && $value === 0.0 ) {
			// PHP's json_decode converts 0.0 to 0 (integer).
			$this->assertEquals( $value, $unpacked );
		} else {
			$this->assertSame( $value, $unpacked );
		}
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_empty_array() {
		$packer = new Json_Packer();
		$value = [];

		$packed = $packer->pack( $value );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed );
		$this->assertSame( $value, $unpacked );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_sequential_array() {
		$packer = new Json_Packer();
		$value = [ 1, 2, 3, 'four', 5.5 ];

		$packed = $packer->pack( $value );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed );
		$this->assertSame( $value, $unpacked );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_associative_array() {
		$packer = new Json_Packer();
		$value = [
			'name'   => 'John',
			'age'    => 30,
			'active' => true,
		];

		$packed = $packer->pack( $value );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed );
		$this->assertSame( $value, $unpacked );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_nested_arrays() {
		$packer = new Json_Packer();
		$value = [
			'users'    => [
				[
					'id'   => 1,
					'name' => 'Alice',
				],
				[
					'id'   => 2,
					'name' => 'Bob',
				],
			],
			'settings' => [
				'theme'         => 'dark',
				'notifications' => false,
			],
		];

		$packed = $packer->pack( $value );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed );
		$this->assertSame( $value, $unpacked );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_stdclass_object() {
		$packer = new Json_Packer();
		$obj         = new stdClass();
		$obj->name   = 'Test';
		$obj->value  = 123;
		$obj->active = true;

		$packed = $packer->pack( $obj );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed );
		$this->assertInstanceOf( stdClass::class, $unpacked );
		$this->assertEquals( $obj->name, $unpacked->name );
		$this->assertEquals( $obj->value, $unpacked->value );
		$this->assertEquals( $obj->active, $unpacked->active );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_datetime_object() {
		$packer = new Json_Packer();
		$value = new DateTime( '2024-01-15 10:30:00', new DateTimeZone( 'UTC' ) );

		$packed = $packer->pack( $value );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed );
		$this->assertInstanceOf( DateTime::class, $unpacked );
		$this->assertEquals( $value->format( 'Y-m-d H:i:s' ), $unpacked->format( 'Y-m-d H:i:s' ) );
		$this->assertEquals( $value->getTimezone()->getName(), $unpacked->getTimezone()->getName() );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_datetimeimmutable_object() {
		$packer = new Json_Packer();
		$value = new DateTimeImmutable( '2024-01-15 10:30:00', new DateTimeZone( 'America/New_York' ) );

		$packed = $packer->pack( $value );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed );
		$this->assertInstanceOf( DateTimeImmutable::class, $unpacked );
		$this->assertEquals( $value->format( 'Y-m-d H:i:s' ), $unpacked->format( 'Y-m-d H:i:s' ) );
		$this->assertEquals( $value->getTimezone()->getName(), $unpacked->getTimezone()->getName() );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_custom_object_with_private_properties() {
		$packer = new Json_Packer();
		$value = new Test_User( 'john_doe', 'john@example.com', true );
		$allowed_classes = [ Test_User::class ];

		$packed = $packer->pack( $value, $allowed_classes );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed, true, $allowed_classes );
		$this->assertInstanceOf( Test_User::class, $unpacked );
		$this->assertEquals( 'john_doe', $unpacked->getUsername() );
		$this->assertEquals( 'john@example.com', $unpacked->getEmail() );
		$this->assertTrue( $unpacked->isActive() );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_object_with_inheritance() {
		$packer = new Json_Packer();
		$value = new Test_Admin_User( 'admin', 'admin@example.com', true, [ 'users.manage', 'posts.delete' ] );
		$allowed_classes = [ Test_Admin_User::class, Test_User::class ];

		$packed = $packer->pack( $value, $allowed_classes );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed, true, $allowed_classes );
		$this->assertInstanceOf( Test_Admin_User::class, $unpacked );
		$this->assertEquals( 'admin', $unpacked->getUsername() );
		$this->assertEquals( 'admin@example.com', $unpacked->getEmail() );
		$this->assertTrue( $unpacked->isActive() );
		$this->assertEquals( [ 'users.manage', 'posts.delete' ], $unpacked->getPermissions() );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_nested_objects() {
		$packer = new Json_Packer();
		$address = new Test_Address( '123 Main St', 'New York', 'NY', '10001' );
		$value = new Test_User_With_Address( 'jane_doe', 'jane@example.com', true, $address );
		$allowed_classes = [ Test_User_With_Address::class, Test_User::class, Test_Address::class ];

		$packed = $packer->pack( $value, $allowed_classes );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed, true, $allowed_classes );
		$this->assertInstanceOf( Test_User_With_Address::class, $unpacked );
		$this->assertEquals( 'jane_doe', $unpacked->getUsername() );
		$address = $unpacked->getAddress();
		$this->assertInstanceOf( Test_Address::class, $address );
		$this->assertEquals( '123 Main St', $address->getStreet() );
		$this->assertEquals( 'New York', $address->getCity() );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_pack_and_unpack_array_of_objects() {
		$packer = new Json_Packer();
		$value = [
			new Test_User( 'user1', 'user1@example.com', true ),
			new Test_User( 'user2', 'user2@example.com', false ),
			new Test_User( 'user3', 'user3@example.com', true ),
		];
		$allowed_classes = [ Test_User::class ];

		$packed = $packer->pack( $value, $allowed_classes );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		$unpacked = $packer->unpack( $packed, true, $allowed_classes );
		$this->assertIsArray( $unpacked );
		$this->assertCount( 3, $unpacked );
		foreach ( $unpacked as $i => $user ) {
			$this->assertInstanceOf( Test_User::class, $user );
			$this->assertEquals( $value[ $i ]->getUsername(), $user->getUsername() );
			$this->assertEquals( $value[ $i ]->getEmail(), $user->getEmail() );
			$this->assertEquals( $value[ $i ]->isActive(), $user->isActive() );
		}
	}
	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_handle_circular_references() {
		$packer = new Json_Packer();
		$allowed_classes = [ Test_User_With_Friend::class, Test_User::class ];

		// Create objects with circular reference.
		$user1 = new Test_User_With_Friend( 'user1', 'user1@example.com', true );
		$user2 = new Test_User_With_Friend( 'user2', 'user2@example.com', true );

		$user1->setFriend( $user2 );
		$user2->setFriend( $user1 );

		$packed = $packer->pack( $user1, $allowed_classes );
		$this->assertIsString( $packed );
		$this->assertJson( $packed );

		// Check that the packed JSON contains a reference.
		$this->assertStringContainsString( 'reference', $packed );

		$unpacked = $packer->unpack( $packed, true, $allowed_classes );

		$this->assertInstanceOf( Test_User_With_Friend::class, $unpacked );
		$this->assertEquals( 'user1', $unpacked->getUsername() );

		$friend = $unpacked->getFriend();
		$this->assertInstanceOf( Test_User_With_Friend::class, $friend );
		$this->assertEquals( 'user2', $friend->getUsername() );

		// Check circular reference is preserved.
		$friend_of_friend = $friend->getFriend();
		$this->assertSame( $unpacked, $friend_of_friend );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_handle_missing_class_based_on_fail_on_error_parameter() {
		$packer = new Json_Packer();
		$allowed_classes = [ Test_User::class ];

		// Create and pack an object.
		$user   = new Test_User( 'john_doe', 'john@example.com', true );
		$packed = $packer->pack( $user, $allowed_classes );

		// Replace the class name with a non-existent one.
		$packed_with_missing_class = str_replace(
			'Test_User',
			'Advanced_Test_User',
			$packed
		);

		// Test with fail_on_error = true (default).
		// Note: We're trying to unpack with the wrong class in allowed_classes.
		$result = $packer->unpack( $packed_with_missing_class, true, [ 'Tribe\\tests\\integration\\Json_Packer\\Advanced_Test_User' ] );
		$this->assertNull( $result, 'Should return null when fail_on_error is true and class is missing' );

		// Test with fail_on_error = false.
		$result = $packer->unpack( $packed_with_missing_class, false, [ 'Tribe\\tests\\integration\\Json_Packer\\Advanced_Test_User' ] );
		$this->assertNotNull( $result, 'Should not return null when fail_on_error is false' );
		$this->assertInstanceOf( stdClass::class, $result, 'Should return stdClass when class is missing' );
		$this->assertEquals( 'Tribe\\tests\\integration\\Json_Packer\\Advanced_Test_User', $result->__original_class__ );
		$this->assertEquals( 'john_doe', $result->username );
		$this->assertEquals( 'john@example.com', $result->email );
		$this->assertTrue( $result->active );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_handle_object_with_uninitialized_properties() {
		$packer = new Json_Packer();
		$allowed_classes = [ Test_Object_With_Uninitialized_Property::class ];
		$value = new Test_Object_With_Uninitialized_Property();

		$packed   = $packer->pack( $value, $allowed_classes );
		$unpacked = $packer->unpack( $packed, true, $allowed_classes );

		$this->assertInstanceOf( Test_Object_With_Uninitialized_Property::class, $unpacked );
		$this->assertTrue( $unpacked->hasInitialized() );
		// The uninitialized property should not be set.
		$reflection = new \ReflectionClass( $unpacked );
		$prop       = $reflection->getProperty( 'uninitialized' );
		$prop->setAccessible( true );
		$this->assertFalse( $prop->isInitialized( $unpacked ) );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_handle_deeply_nested_structure() {
		$packer = new Json_Packer();
		$value = [
			'level1' => [
				'level2' => [
					'level3' => [
						'level4' => [
							'level5' => 'deep value',
						],
					],
				],
			],
		];

		$packed   = $packer->pack( $value );
		$unpacked = $packer->unpack( $packed );

		$this->assertEquals( 'deep value', $unpacked['level1']['level2']['level3']['level4']['level5'] );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_handle_mixed_array_with_objects() {
		$packer = new Json_Packer();
		$value = [
			'string' => 'test',
			'number' => 123,
			'object' => new stdClass(),
			'array'  => [ 1, 2, 3 ],
			'null'   => null,
			'bool'   => true,
		];

		$packed   = $packer->pack( $value );
		$unpacked = $packer->unpack( $packed );

		$this->assertEquals( 'test', $unpacked['string'] );
		$this->assertEquals( 123, $unpacked['number'] );
		$this->assertInstanceOf( stdClass::class, $unpacked['object'] );
		$this->assertEquals( [ 1, 2, 3 ], $unpacked['array'] );
		$this->assertNull( $unpacked['null'] );
		$this->assertTrue( $unpacked['bool'] );
	}
	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_handle_objects_with_dynamic_properties() {
		$packer = new Json_Packer();
		$allowed_classes = [ \WP_Post::class ];

		// Test stdClass with dynamic properties.
		$std_obj = new stdClass();
		$std_obj->name = 'Dynamic Test';
		$std_obj->count = 42;
		$std_obj->active = true;
		$std_obj->tags = [ 'test', 'dynamic', 'properties' ];
		$std_obj->metadata = (object) [
			'created' => '2024-01-01',
			'updated' => '2024-01-15',
		];

		$packed_std = $packer->pack( $std_obj );
		$unpacked_std = $packer->unpack( $packed_std );

		$this->assertInstanceOf( stdClass::class, $unpacked_std );
		$this->assertEquals( 'Dynamic Test', $unpacked_std->name );
		$this->assertEquals( 42, $unpacked_std->count );
		$this->assertTrue( $unpacked_std->active );
		$this->assertEquals( [ 'test', 'dynamic', 'properties' ], $unpacked_std->tags );
		$this->assertInstanceOf( stdClass::class, $unpacked_std->metadata );
		$this->assertEquals( '2024-01-01', $unpacked_std->metadata->created );
		$this->assertEquals( '2024-01-15', $unpacked_std->metadata->updated );

		// Test WP_Post with dynamic properties.
		$wp_post = static::factory()->post->create_and_get( [
			'post_title' => 'Test Post',
			'post_content' => 'Test content',
			'post_status' => 'publish',
		] );

		// Add dynamic properties to WP_Post.
		$wp_post->custom_field = 'custom value';
		$wp_post->rating = 4.5;
		$wp_post->is_featured = true;
		$wp_post->related_ids = [ 1, 2, 3 ];

		$packed_post = $packer->pack( $wp_post, $allowed_classes );
		$unpacked_post = $packer->unpack( $packed_post, true, $allowed_classes );

		$this->assertInstanceOf( \WP_Post::class, $unpacked_post );
		$this->assertEquals( 'Test Post', $unpacked_post->post_title );
		$this->assertEquals( 'Test content', $unpacked_post->post_content );
		$this->assertEquals( 'publish', $unpacked_post->post_status );
		// Check dynamic properties.
		$this->assertEquals( 'custom value', $unpacked_post->custom_field );
		$this->assertEquals( 4.5, $unpacked_post->rating );
		$this->assertTrue( $unpacked_post->is_featured );
		$this->assertEquals( [ 1, 2, 3 ], $unpacked_post->related_ids );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_replace_non_allowed_classes_with_stdclass() {
		$packer = new Json_Packer();

		// Test packing without allowed classes - should convert to stdClass.
		$user = new Test_User( 'john_doe', 'john@example.com', true );
		$packed = $packer->pack( $user, [] ); // Empty allowed classes array.
		
		// Verify the packed data uses stdClass instead of Test_User.
		$this->assertStringContainsString( '"type": "stdClass"', $packed );
		$this->assertStringNotContainsString( 'Test_User', $packed );
		
		// Unpack and verify we get stdClass.
		$unpacked = $packer->unpack( $packed );
		$this->assertInstanceOf( stdClass::class, $unpacked );
		$this->assertEquals( 'john_doe', $unpacked->username );
		$this->assertEquals( 'john@example.com', $unpacked->email );
		$this->assertTrue( $unpacked->active );

		// Test with allowed classes that should preserve the type.
		$allowed_user = new Test_User( 'allowed_user', 'allowed@example.com', false );
		$packed_allowed = $packer->pack( $allowed_user, [ Test_User::class ] );
		
		// Verify the packed data uses Test_User since it's allowed.
		$this->assertStringContainsString( 'Test_User', $packed_allowed );
		
		// Unpack and verify we get Test_User back.
		$unpacked_allowed = $packer->unpack( $packed_allowed, true, [ Test_User::class ] );
		$this->assertInstanceOf( Test_User::class, $unpacked_allowed );
		$this->assertEquals( 'allowed_user', $unpacked_allowed->getUsername() );
		$this->assertEquals( 'allowed@example.com', $unpacked_allowed->getEmail() );
		$this->assertFalse( $unpacked_allowed->isActive() );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_return_null_for_invalid_json() {
		$packer = new Json_Packer();

		$result = $packer->unpack( 'invalid json string' );
		$this->assertNull( $result );

		$result = $packer->unpack( '{"incomplete":' );
		$this->assertNull( $result );

		$result = $packer->unpack( '' );
		$this->assertNull( $result );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_allow_datetime_classes_by_default() {
		$packer = new Json_Packer();

		// Test DateTime with empty allowed_classes array.
		$datetime = new DateTime( '2024-03-15 14:30:00', new DateTimeZone( 'UTC' ) );
		$packed = $packer->pack( $datetime, [] ); // Empty allowed classes.
		
		// Should still pack as DateTime, not stdClass.
		$this->assertStringContainsString( 'DateTime', $packed );
		$this->assertStringNotContainsString( '"type": "stdClass"', $packed );
		
		// Should unpack correctly even with empty allowed classes.
		$unpacked = $packer->unpack( $packed, true, [] );
		$this->assertInstanceOf( DateTime::class, $unpacked );
		$this->assertEquals( '2024-03-15 14:30:00', $unpacked->format( 'Y-m-d H:i:s' ) );
		$this->assertEquals( 'UTC', $unpacked->getTimezone()->getName() );

		// Test DateTimeImmutable with empty allowed_classes array.
		$datetime_immutable = new DateTimeImmutable( '2024-06-20 09:15:30', new DateTimeZone( 'America/New_York' ) );
		$packed_immutable = $packer->pack( $datetime_immutable, [] );
		
		// Should pack as DateTimeImmutable.
		$this->assertStringContainsString( 'DateTimeImmutable', $packed_immutable );
		
		// Should unpack correctly.
		$unpacked_immutable = $packer->unpack( $packed_immutable, true, [] );
		$this->assertInstanceOf( DateTimeImmutable::class, $unpacked_immutable );
		$this->assertEquals( '2024-06-20 09:15:30', $unpacked_immutable->format( 'Y-m-d H:i:s' ) );
		$this->assertEquals( 'America/New_York', $unpacked_immutable->getTimezone()->getName() );

		// Test DateTimeZone nested in DateTime.
		$timezone = new DateTimeZone( 'Europe/London' );
		$datetime_with_tz = new DateTime( '2024-12-25 00:00:00', $timezone );
		$packed_with_tz = $packer->pack( $datetime_with_tz, [] );
		
		$unpacked_with_tz = $packer->unpack( $packed_with_tz, true, [] );
		$this->assertInstanceOf( DateTime::class, $unpacked_with_tz );
		$this->assertEquals( 'Europe/London', $unpacked_with_tz->getTimezone()->getName() );

		// Test that these classes work even when mixed with other allowed classes.
		$user = new Test_User( 'john', 'john@example.com', true );
		$complex_data = [
			'user' => $user,
			'created_at' => new DateTime( '2024-01-01 12:00:00' ),
			'updated_at' => new DateTimeImmutable( '2024-01-15 18:30:00' ),
		];
		
		// Pack with only Test_User in allowed classes - DateTime classes should still work.
		$packed_complex = $packer->pack( $complex_data, [ Test_User::class ] );
		$unpacked_complex = $packer->unpack( $packed_complex, true, [ Test_User::class ] );
		
		$this->assertIsArray( $unpacked_complex );
		$this->assertInstanceOf( Test_User::class, $unpacked_complex['user'] );
		$this->assertInstanceOf( DateTime::class, $unpacked_complex['created_at'] );
		$this->assertInstanceOf( DateTimeImmutable::class, $unpacked_complex['updated_at'] );
	}

	/**
	 * @test
	 * @covers \TEC\Common\Json_Packer\Json_Packer::pack
	 * @covers \TEC\Common\Json_Packer\Json_Packer::unpack
	 */
	public function it_should_handle_array_with_mixed_allowed_and_non_allowed_objects() {
		$packer = new Json_Packer();
		
		// Create an array with one allowed object and one non-allowed object.
		$allowed_user = new Test_User( 'allowed_user', 'allowed@example.com', true );
		$admin_user = new Test_Admin_User( 'admin_user', 'admin@example.com', true, ['manage', 'delete'] );
		
		$mixed_array = [
			'allowed' => $allowed_user,
			'not_allowed' => $admin_user,
			'datetime' => new DateTime( '2024-01-01 10:00:00' ), // Always allowed.
			'std' => new stdClass(), // Test stdClass behavior.
		];
		
		// Pack with only Test_User allowed (Test_Admin_User not allowed).
		$allowed_classes = [ Test_User::class ];
		$packed = $packer->pack( $mixed_array, $allowed_classes );
		
		// Verify packing - allowed class should keep its type, non-allowed should become stdClass.
		$this->assertStringContainsString( '"allowed"', $packed );
		$this->assertStringContainsString( 'Test_User', $packed );
		// Admin user should be converted to stdClass during packing.
		$decoded = json_decode( $packed, true );
		$this->assertEquals( 'stdClass', $decoded['value']['not_allowed']['type'] );
		
		// Test with fail_on_error = true (default).
		$unpacked_fail = $packer->unpack( $packed, true, $allowed_classes );
		
		$this->assertIsArray( $unpacked_fail );
		// Allowed object should be properly restored.
		$this->assertInstanceOf( Test_User::class, $unpacked_fail['allowed'] );
		$this->assertEquals( 'allowed_user', $unpacked_fail['allowed']->getUsername() );
		
		// Non-allowed object should be stdClass.
		$this->assertInstanceOf( stdClass::class, $unpacked_fail['not_allowed'] );
		$this->assertEquals( 'admin_user', $unpacked_fail['not_allowed']->username );
		$this->assertEquals( 'admin@example.com', $unpacked_fail['not_allowed']->email );
		$this->assertTrue( $unpacked_fail['not_allowed']->active );
		$this->assertEquals( ['manage', 'delete'], $unpacked_fail['not_allowed']->permissions );
		
		// DateTime should work (always allowed).
		$this->assertInstanceOf( DateTime::class, $unpacked_fail['datetime'] );
		$this->assertEquals( '2024-01-01 10:00:00', $unpacked_fail['datetime']->format( 'Y-m-d H:i:s' ) );
		
		// stdClass should work as expected.
		$this->assertInstanceOf( stdClass::class, $unpacked_fail['std'] );
		
		// Test with fail_on_error = false.
		$unpacked_no_fail = $packer->unpack( $packed, false, $allowed_classes );
		
		// Results should be the same since all conversions happen during packing.
		$this->assertIsArray( $unpacked_no_fail );
		$this->assertInstanceOf( Test_User::class, $unpacked_no_fail['allowed'] );
		$this->assertInstanceOf( stdClass::class, $unpacked_no_fail['not_allowed'] );
		$this->assertInstanceOf( DateTime::class, $unpacked_no_fail['datetime'] );
		$this->assertInstanceOf( stdClass::class, $unpacked_no_fail['std'] );
		
		// Test edge case: pack with all classes allowed, unpack with restricted allowed classes.
		$packed_all_allowed = $packer->pack( $mixed_array, [ Test_User::class, Test_Admin_User::class ] );
		
		// Now unpack with only Test_User allowed.
		// The unpacker should handle this gracefully - converting non-allowed classes to stdClass.
		$unpacked_restricted = $packer->unpack( $packed_all_allowed, true, [ Test_User::class ] );
		
		$this->assertIsArray( $unpacked_restricted );
		$this->assertInstanceOf( Test_User::class, $unpacked_restricted['allowed'] );
		// Test_Admin_User should be converted to stdClass since it's not in allowed_classes.
		$this->assertInstanceOf( stdClass::class, $unpacked_restricted['not_allowed'] );
		$this->assertEquals( 'admin_user', $unpacked_restricted['not_allowed']->username );
		$this->assertEquals( 'admin@example.com', $unpacked_restricted['not_allowed']->email );
		$this->assertTrue( $unpacked_restricted['not_allowed']->active );
		$this->assertEquals( ['manage', 'delete'], $unpacked_restricted['not_allowed']->permissions );
	}
}
