<?php

namespace Tribe\Queue;

use Tribe__Queue__Worker as Worker;
use function GuzzleHttp\json_encode;

class WorkerTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 * it should throw if adding non callable callback
	 */
	public function it_should_throw_if_adding_non_callable_callback() {
		$this->expectException( \InvalidArgumentException::class );

		new Worker( [], [], 'foo' );
	}

	/**
	 * @test
	 * it should throw if passing non well formed tribe container callback
	 */
	public function it_should_throw_if_passing_non_well_formed_tribe_container_callback() {
		$this->expectException( \InvalidArgumentException::class );

		new Worker( [], [], [ 'tribe', 'foo' ] );
	}

	/**
	 * @test
	 * it should save its state in a transient
	 */
	public function it_should_save_its_state_in_a_transient() {
		$worker = new Worker( [ 'a', 'b', 'c' ], [ 'a', 'b' ], 'trailingslashit', [ 'foo' => 'bar' ], Worker::DONE );

		$worker->save();

		$expected = json_decode( json_encode( [
			'targets'    => [ 'a', 'b', 'c' ],
			'remaining'  => [ 'a', 'b' ],
			'callback'   => 'trailingslashit',
			'data'       => [ 'foo' => 'bar' ],
			'status'     => Worker::DONE,
			'batch_size' => 10,
			'priority' => 10,
		] ) );
		$this->assertEquals( $expected, $worker->read() );
	}

	/**
	 * @test
	 * it should work on all targets if batch size is bigger then the count
	 */
	public function it_should_work_on_all_targets_if_batch_size_is_bigger_then_the_count() {
		$worker = new Worker( [ 'a', 'b', 'c', 'd' ], [ 'a', 'b', 'c', 'd' ], 'trailingslashit' );
		$worker->set_batch_size( 10 );
		$worker->work();

		$expected = [
			'targets'    => [ 'a', 'b', 'c', 'd' ],
			'remaining'  => [],
			'callback'   => 'trailingslashit',
			'data'       => '',
			'status'     => Worker::DONE,
			'batch_size' => 10,
			'priority'   => 10,
		];
		$this->assertEquals( $expected, $worker->to_array() );
	}

	/**
	 * @test
	 * it should work on a subset of targets if count is larger than batch size
	 */
	public function it_should_work_on_a_subset_of_targets_if_count_is_larger_than_batch_size() {
		$worker = new Worker( [ 'a', 'b', 'c', 'd' ], [ 'a', 'b', 'c', 'd' ], 'trailingslashit' );
		$worker->set_batch_size( 2 );

		$worker->work();

		$expected = [
			'targets'    => [ 'a', 'b', 'c', 'd' ],
			'remaining'  => [ 'c', 'd' ],
			'callback'   => 'trailingslashit',
			'data'       => '',
			'status'     => Worker::WORKING,
			'batch_size' => 2,
			'priority'   => 10,
		];
		$this->assertEquals( $expected, $worker->to_array() );

		$worker->work();

		$expected = [
			'targets'    => [ 'a', 'b', 'c', 'd' ],
			'remaining'  => [],
			'callback'   => 'trailingslashit',
			'data'       => '',
			'status'     => Worker::DONE,
			'batch_size' => 2,
			'priority'   => 10,
		];
		$this->assertEquals( $expected, $worker->to_array() );
	}

	/**
	 * @test
	 * it should move failed at the end of the queue
	 */
	public function it_should_move_failed_at_the_end_of_the_queue() {
		$targets = [ 'a', 'b', 'c', 'd', 'e' ];
		$worker = new Worker( $targets, $targets, array( __CLASS__, 'callback_one' ) );
		$worker->set_batch_size( 3 );

		$worker->work();

		$expected = [
			'targets'    => $targets,
			'remaining'  => [ 'd', 'e', 'c' ],
			'callback'   => array( __CLASS__, 'callback_one' ),
			'data'       => '',
			'status'     => Worker::WORKING,
			'batch_size' => 3,
			'priority'   => 10,
		];
		$this->assertEquals( $expected, $worker->to_array() );

		$worker->work();

		$expected = [
			'targets'    => $targets,
			'remaining'  => [ 'c' ],
			'callback'   => array( __CLASS__, 'callback_one' ),
			'data'       => '',
			'status'     => Worker::WORKING,
			'batch_size' => 3,
			'priority'   => 10,
		];
		$this->assertEquals( $expected, $worker->to_array() );
	}

	/**
	 * @test
	 * it should move exception raising at the end of the queue
	 */
	public function it_should_move_exception_raising_at_the_end_of_the_queue() {
		$targets = [ 'a', 'b', 'c', 'd', 'e' ];
		$worker = new Worker( $targets, $targets, array( __CLASS__, 'callback_two' ) );
		$worker->set_batch_size( 3 );

		$worker->work();

		$expected = [
			'targets'    => $targets,
			'remaining'  => [ 'd', 'e', 'c' ],
			'callback'   => array( __CLASS__, 'callback_two' ),
			'data'       => '',
			'status'     => Worker::WORKING,
			'batch_size' => 3,
			'priority'   => 10,
		];
		$this->assertEquals( $expected, $worker->to_array() );

		$worker->work();

		$expected = [
			'targets'    => $targets,
			'remaining'  => [ 'c' ],
			'callback'   => array( __CLASS__, 'callback_two' ),
			'data'       => '',
			'status'     => Worker::WORKING,
			'batch_size' => 3,
			'priority'   => 10,
		];
		$this->assertEquals( $expected, $worker->to_array() );
	}

	/**
	 * @test
	 * it should set the default priority of a work to 10
	 */
	public function it_should_set_the_default_priority_of_a_work_to_10() {
		$targets = [ 'a', 'b', 'c', 'd', 'e' ];
		$worker = new Worker( $targets, $targets, array( __CLASS__, 'callback_two' ) );

		$this->assertEquals( 10, $worker->get_priority() );

	}

	/**
	 * @test
	 * it should throw if trying to set the priority of a work to non integer value
	 */
	public function it_should_throw_if_trying_to_set_the_priority_of_a_work_to_non_integer_value() {
		$this->expectException( \InvalidArgumentException::class );

		$targets = [ 'a', 'b', 'c', 'd', 'e' ];
		$worker = new Worker( $targets, $targets, array( __CLASS__, 'callback_two' ) );
		$worker->set_batch_size( 3 );
		$worker->set_priority( 'foo' );
	}

	/**
	 * @test
	 * it should allow setting the priority for the work
	 */
	public function it_should_allow_setting_the_priority_for_the_work() {
		$targets = [ 'a', 'b', 'c', 'd', 'e' ];
		$worker = new Worker( $targets, $targets, array( __CLASS__, 'callback_two' ) );
		$worker->set_priority( '23' );

		$this->assertEquals( 23, $worker->get_priority() );
	}

	public static function callback_one( $_, $index ) {
		return 2 === $index ? false : true;
	}

	public static function callback_two( $_, $index ) {
		if ( 2 === $index ) {
			throw new \RuntimeException();
		}

		return true;
	}
}