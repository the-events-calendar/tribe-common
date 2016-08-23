<?php
namespace Tribe\functions;

require_once dirname( __FILE__ ) . '/../../../../src/functions/utils.php';


class utilsTest extends \Codeception\Test\Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	/**
	 * merging two arrays will use positions as keys
	 */
	public function test_merging_two_arrays_will_use_positions_as_keys() {
		$one = [ 'foo', 'bar' ];
		$two = [ 'bar', 'baz' ];
		$this->assertEquals( [ 'bar', 'baz' ], tribe_array_merge_recursive( $one, $two ) );
	}

	/**
	 * merging two arrays will keep different keys
	 */
	public function test_merging_two_arrays_will_keep_different_keys() {
		$one = [ 'foo', 'bar' ];
		$two = [ 2 => 'bar', 3 => 'baz' ];
		$this->assertEquals( [ 'foo', 'bar', 'bar', 'baz' ], tribe_array_merge_recursive( $one, $two ) );
	}

	/**
	 * mergin associative flat arrays will work as array merge
	 */
	public function test_mergin_associative_flat_arrays_will_work_as_array_merge() {
		$one = [ 'one' => 1, 'two' => 2 ];
		$two = [ 'foo' => 'bar', 'one' => 'baz' ];
		$this->assertEquals( array_merge( $one, $two ), tribe_array_merge_recursive( $one, $two ) );
	}

	/**
	 * merging associative and non associative array will preserve keys
	 */
	public function test_merging_associative_and_non_associative_array_will_preserve_keys() {
		$one = [ 'one' => 1, 'two' => 2 ];
		$two = [ 'bar', 'baz' ];
		$this->assertEquals( [ 0 => 'bar', 1 => 'baz', 'one' => 1, 'two' => 2 ], tribe_array_merge_recursive( $one, $two ) );
	}

	/**
	 * merging multidimensional arrays
	 */
	public function test_merging_multidimensional_arrays() {
		$one = [
			'one' => 1,
			1     => 'one',
			'two' => [
				'some' => 'key',
				'more' => [
					'nesting' => 'foo'
				]
			]
		];

		$two      = [
			'bar' => 'baz',
			'baz' => [
				'even' => [
					'more' => 'values',
					'and'  => [ 'dimensions' => 'here' ]
				]
			],
			'two' => [
				'overriding' => 'values'
			],
			5     => 'five'
		];
		$expected = [
			'one' => 1,
			1     => 'one',
			'two' => [
				'overriding' => 'values',
				'some'       => 'key',
				'more'       => [
					'nesting' => 'foo'
				]
			],
			'bar' => 'baz',
			'baz' => [
				'even' => [
					'more' => 'values',
					'and'  => [ 'dimensions' => 'here' ]
				]
			],
			5     => 'five'
		];

		$this->assertEquals( $expected, tribe_array_merge_recursive( $one, $two ) );
	}
}