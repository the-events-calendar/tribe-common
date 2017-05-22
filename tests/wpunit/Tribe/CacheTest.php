<?php

namespace Tribe;

use Tribe__Cache as Cache;

class CacheTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Cache::class, $this->make_instance() );
	}

	/**
	 * @return Cache
	 */
	protected function make_instance() {
		return new Cache();
	}

	/**
	 * It should allow storing value using ArrayAccess API
	 *
	 * @test
	 */
	public function it_should_allow_storing_value_using_array_access_api() {
		$cache = $this->make_instance();

		$this->assertFalse( isset( $cache['foo'] ) );

		$cache['foo'] = 'bar';

		$this->assertTrue( isset( $cache['foo'] ) );
		$this->assertEquals( 'bar', $cache['foo'] );
	}

	/**
	 * It should correctly fabricate keys
	 *
	 * @test
	 */
	public function it_should_correctly_fabricate_keys() {
		$components_1 = [ __FILE__, [ 23, 89 ], [ 'foo' => 'bar', 'bar' => 'baz' ] ];
		$components_2 = [ __FILE__, [ 23, 89 ], [ 'bar' => 'baz', 'foo' => 'bar' ] ];

		$cache = $this->make_instance();

		$this->assertEquals( $cache->make_key( $components_1 ), $cache->make_key( $components_2 ) );
		$this->assertEquals( $cache->make_key( $components_1, 'pre' ), $cache->make_key( $components_2, 'pre' ) );
		$this->assertNotEquals( $cache->make_key( $components_1, 'foo' ), $cache->make_key( $components_2, 'pre' ) );
		$this->assertNotEquals( $cache->make_key( $components_1, '', false ), $cache->make_key( $components_2, '', false ) );
	}

	/**
	 * It should correctly handle long keys
	 *
	 * @test
	 */
	public function it_should_correctly_handle_long_keys() {
		$components = [ __FILE__, [ 23, 89 ], [ 'foo' => 'bar', 'bar' => 'baz' ] ];

		$cache = $this->make_instance();

		$long_prefix = 'some very long prefix that should trigger some kind of minification on the key creation or so I hope';
		$key = $cache->make_key( $components, $long_prefix );
		$cache[ $key ] = 'bar';

		$this->assertTrue( isset( $cache[ $key ] ) );
		$this->assertEquals('bar',$cache[$key]);
	}
}