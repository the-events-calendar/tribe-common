<?php

namespace Tribe;

use Tribe__Data as Data;

class DataTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Data::class, $this->make_instance() );
	}

	/**
	 * @return Data
	 */
	protected function make_instance() {
		return new Data( [] );
	}

	/**
	 * Test data setting in constructor
	 *
	 * @test
	 */
	public function test_data_setting_in_constructor() {
		$data = new Data( [ 'foo' => 'bar', 'baz' => 23 ], 'wooz' );

		$this->assertEquals( 'bar', $data['foo'] );
		$this->assertEquals( 23, $data['baz'] );
		$this->assertEquals( 'wooz', $data['nope'] );

		$this->assertEqualSets( [ 'foo' => 'bar', 'baz' => 23 ], $data->get_data() );
	}

	/**
	 * Test data setting after construction
	 *
	 * @test
	 */
	public function test_data_setting_after_construction() {
		$data = $this->make_instance();

		$data->set_data( [ 'foo' => 'bar', 'baz' => 23 ] );
		$data->set_default( 'wooz' );

		$this->assertEquals( 'bar', $data['foo'] );
		$this->assertEquals( 23, $data['baz'] );
		$this->assertEquals( 'wooz', $data['nope'] );

		$this->assertEqualSets( [ 'foo' => 'bar', 'baz' => 23 ], $data->get_data() );
	}

	/**
	 * Test iteration
	 *
	 * @test
	 */
	public function test_iteration() {
		$data_set = [ 'foo' => 'bar', 'baz' => 23 ];
		$data = new Data( $data_set, 'wooz' );

		$iterated = [];
		foreach ( $data as $key => $value ) {
			$iterated[ $key ] = $value;
		}

		$this->assertEqualSets( $data_set, $iterated );
	}

	/**
	 * Test iteration on empty data
	 *
	 * @test
	 */
	public function test_iteration_on_empty_data() {
		$data = new Data();

		$iterated = [];
		foreach ( $data as $key => $value ) {
			$iterated[ $key ] = $value;
		}

		$this->assertEquals( [], $iterated );
	}

	/**
	 * Test casting to array of object data set
	 *
	 * @test
	 */
	public function test_casting_to_array_of_object_data_set() {
		$data_set = [ 'foo' => 'bar', 'baz' => 23 ];
		$data = new Data( (object) $data_set, 'wooz' );

		$iterated = [];
		foreach ( $data as $key => $value ) {
			$iterated[ $key ] = $value;
		}

		$this->assertEqualSets( $data_set, $iterated );
	}
}