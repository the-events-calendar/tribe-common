<?php

namespace Tribe;

use Tribe__Queue as Queue;
use Tribe__Queue__Worker as Worker;

class QueueTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Queue::class, $sut );
	}

	/**
	 * @test
	 * it should mark a job with no targets as done
	 */
	public function it_should_mark_a_job_with_no_targets_as_done() {
		$sut = $this->make_instance();

		$work_id = $sut->queue_work( [], 'trailingslashit' )->save();

		$this->assertEquals( Worker::DONE, $sut->get_work_status( $work_id ) );
	}

	/**
	 * @test
	 * it should allow setting the batch size on a per-work base
	 */
	public function it_should_allow_setting_the_batch_size_on_a_per_work_base() {
		$sut = $this->make_instance();

		$work_id = $sut->queue_work( [ 'a', 'b', 'c', 'd', 'e', 'f' ], 'trailingslashit' )
		               ->set_batch_size( 3 )
		               ->save();

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::WORKING, $sut->get_work_status( $work_id ) );

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::DONE, $sut->get_work_status( $work_id ) );
	}

	/**
	 * @test
	 * it should allow setting a static method as callback
	 */
	public function it_should_allow_setting_a_static_method_as_callback() {
		$sut = $this->make_instance();

		$work_id = $sut->queue_work( [ 'a', 'b', 'c', 'd', 'e', 'f' ], [ __CLASS__, 'callback_two' ] )
		               ->set_batch_size( 3 )
		               ->save();

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::WORKING, $sut->get_work_status( $work_id ) );
		$this->assertEquals( 'a/b/c/', get_option( 'foo' ) );

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::DONE, $sut->get_work_status( $work_id ) );
		$this->assertEquals( 'a/b/c/d/e/f/', get_option( 'foo' ) );
	}

	/**
	 * @test
	 * it should allow setting a container alias and method as callback
	 */
	public function it_should_allow_setting_a_container_alias_and_method_as_callback() {
		$sut = $this->make_instance();

		tribe_register( 'foo', $this );

		$work_id = $sut->queue_work( [ 'a', 'b', 'c', 'd', 'e', 'f' ], [ 'tribe', 'foo', 'callback_one' ] )
		               ->set_batch_size( 3 )
		               ->save();

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::WORKING, $sut->get_work_status( $work_id ) );
		$this->assertEquals( 'a/b/c/', get_option( 'foo' ) );

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::DONE, $sut->get_work_status( $work_id ) );
		$this->assertEquals( 'a/b/c/d/e/f/', get_option( 'foo' ) );
	}

	/**
	 * @test
	 * it should mark callbacks raising exceptions as not successful
	 */
	public function it_should_mark_callbacks_raising_exceptions_as_not_successful() {
		$sut = $this->make_instance();

		$targets = [ 'a', 'b', 'c', 'd', 'e', 'f' ];
		$work_id = $sut->queue_work( $targets, [ __CLASS__, 'callback_three' ] )
		               ->set_batch_size( 10 )
		               ->save();

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::WORKING, $sut->get_work_status( $work_id ) );
		$this->assertEquals( $targets, $sut->get_work( $work_id )->get_remaining() );

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::WORKING, $sut->get_work_status( $work_id ) );
		$this->assertEquals( $targets, $sut->get_work( $work_id )->get_remaining() );
	}

	/**
	 * @test
	 * it should run scheduled works on action hook
	 */
	public function it_should_run_scheduled_works_on_action_hook() {
		tribe_register( 'foo', $this );
		$setup_factory = $this->make_instance();
		$work_one = $setup_factory->queue_work( [ 'a', 'b', 'c', 'd', 'e', 'f' ], [ __CLASS__, 'callback_two' ] )
		                          ->set_batch_size( 3 )
		                          ->save();
		$work_two = $setup_factory->queue_work( [ 'foo', 'baz', 'bar' ], [ __CLASS__, 'callback_two' ] )
		                          ->set_batch_size( 2 )
		                          ->save();
		$the_beatles = [ 'john', 'paul', 'george', 'ringo' ];
		$work_three = $setup_factory->queue_work( $the_beatles, [ 'tribe', 'foo', 'callback_one' ] )
		                            ->set_batch_size( 2 )
		                            ->save();

		add_action( 'some_action', [ 'Tribe__Queue', 'work' ] );

		$check_factory = $this->make_instance();

		do_action( 'some_action' );

		$this->assertEquals( Worker::WORKING, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::QUEUED, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::QUEUED, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/', get_option( 'foo' ) );
		$expected_list = [ $work_one => Worker::WORKING, $work_two => Worker::QUEUED, $work_three => Worker::QUEUED ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::QUEUED, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::QUEUED, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/', get_option( 'foo' ) );
		$expected_list = [ $work_two => Worker::QUEUED, $work_three => Worker::QUEUED ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::WORKING, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::QUEUED, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/foo/baz/', get_option( 'foo' ) );
		$expected_list = [ $work_two => Worker::WORKING, $work_three => Worker::QUEUED ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::QUEUED, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/foo/baz/bar/', get_option( 'foo' ) );
		$expected_list = [ $work_three => Worker::QUEUED ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::WORKING, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/foo/baz/bar/john/paul/', get_option( 'foo' ) );
		$expected_list = [ $work_three => Worker::WORKING ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::DONE, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/foo/baz/bar/john/paul/george/ringo/', get_option( 'foo' ) );
		$expected_list = [];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );
	}

	/**
	 * @test
	 * it should return false if trying to work on non existing work
	 */
	public function it_should_return_false_if_trying_to_work_on_non_existing_work() {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->work_on( 'bada.boom' ) );
	}

	/**
	 * @test
	 * it should return NOT_FOUND when trying to get status of non existing job
	 */
	public function it_should_return_not_found_when_trying_to_get_status_of_non_existing_job() {
		$sut = $this->make_instance();

		$this->assertEquals( Worker::NOT_FOUND, $sut->get_work_status( 'bada.boom' ) );
	}

	/**
	 * @test
	 * it should return empty array if list option is not set
	 */
	public function it_should_return_empty_array_if_list_option_is_not_set() {
		delete_option( Queue::WORKS_OPTION );

		$sut = $this->make_instance();

		$this->assertEquals( [], $sut->get_work_list() );
	}

	/**
	 * @return Queue
	 */
	private function make_instance() {
		return new Queue();
	}

	public function callback_one( $string ) {
		return update_option( 'foo', get_option( 'foo' ) . trailingslashit( $string ) );
	}

	public static function callback_two( $string ) {
		return update_option( 'foo', get_option( 'foo' ) . trailingslashit( $string ) );
	}

	public function callback_three() {
		throw new \RuntimeException();
	}
}