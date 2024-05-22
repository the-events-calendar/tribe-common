<?php

namespace Tribe\Utils;

use PHPUnit\Framework\AssertionFailedError;

class Lazy_CollectionTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$collection = new Lazy_Collection( '__return_empty_array' );
		$this->assertInstanceOf( Lazy_Collection::class, $collection );
	}

	/**
	 * It should not call the callback on creation
	 *
	 * @test
	 */
	public function should_not_call_the_callback_on_creation() {
		$callback = static function () {
			throw new AssertionFailedError( 'This should not be called on __construct.' );
		};

		new Lazy_Collection( $callback );
	}

	/**
	 * It should call the callback when first accessing the collection and no more
	 *
	 * @test
	 */
	public function should_call_the_callback_when_first_accessing_the_collection_and_no_more() {
		$called   = false;
		$callback = static function () use ( &$called ) {
			if ( $called ) {
				throw new AssertionFailedError( 'This should not be called a second time.' );
			}

			$called = true;

			return [ 'foo', 'bar', 'baz' ];
		};

		$collection = new Lazy_Collection( $callback );

		$this->assertEquals( [ 'foo', 'bar', 'baz' ], $collection->all() );
		$this->assertEquals( [ 'foo', 'bar', 'baz' ], $collection->all() );
		$this->assertEquals( [ 'foo', 'bar', 'baz' ], $collection->all() );
	}

	/**
	 * It should allow running methods on the collection
	 *
	 * @test
	 */
	public function should_allow_running_methods_on_the_collection() {
		$callback = static function () {
			return [ 'foo', 'bar', 'baz' ];
		};

		$collection = new Lazy_Collection( $callback );

		$this->assertEquals( [ 'foo', 'bar', 'baz' ], $collection->all() );
		$this->assertEquals( 'foo', $collection->first() );
		$this->assertEquals( 'foo', $collection->nth( 1 ) );
		$this->assertEquals( 'baz', $collection->last() );
		$this->assertEquals( 'baz', $collection->nth( 3 ) );
		$this->assertEquals( 'bar', $collection->nth( 2 ) );
	}

	/**
	 * It should behave like an array
	 *
	 * @test
	 */
	public function should_behave_like_an_array() {
		$callback = static function () {
			return [ 'foo', 'bar', 'baz' ];
		};

		$collection = new Lazy_Collection( $callback );

		$got = [];
		foreach ( $collection as $item ) {
			$got[] = $item;
		}

		$this->assertEquals( $collection->all(), $got );
	}

	/**
	 * It should call callback on serialization
	 *
	 * @test
	 */
	public function should_call_callback_on_serialization() {
		$called   = false;
		$callback = static function () use ( &$called ) {
			$called = true;

			return [ 'foo', 'bar', 'baz' ];
		};

		$collection = new Lazy_Collection( $callback );
		$serialized = $collection->serialize();

		$this->assertTrue( $called );

		$this->assertEquals( [ 'foo', 'bar', 'baz' ], unserialize( $serialized ) );
	}

	/**
	 * It should allow accessing the collection methods as properties
	 *
	 * @test
	 */
	public function should_allow_accessing_the_collection_methods_as_properties() {
		$callback = static function () {
			return [ 'foo', 'bar', 'baz' ];
		};

		$collection = new Lazy_Collection( $callback );

		$this->assertEquals( 'foo', $collection->first );
		$this->assertEquals( 'baz', $collection->last );
		$this->assertEquals( [ 'foo', 'bar', 'baz' ], $collection->all );
		$this->assertEquals( 3, $collection->count );
		$this->assertEquals( 'bar', $collection->nth( 2 ) );
	}

	/**
	 * It should correctly expand when json_encoded
	 *
	 * @test
	 */
	public function should_correctly_expand_when_json_encoded() {
		$callback = static function () {
			return [ 'foo', 'bar', 'baz' ];
		};

		$collection = new Lazy_Collection( $callback );

		$this->assertEquals(
			json_encode( [ 'foo', 'bar', 'baz' ] ),
			json_encode( $collection )
		);
	}
}
