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

		$this->assertEquals( Worker::$done, $sut->get_work_status( $work_id ) );
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

		$this->assertEquals( Worker::$working, $sut->get_work_status( $work_id ) );

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::$done, $sut->get_work_status( $work_id ) );
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

		$this->assertEquals( Worker::$working, $sut->get_work_status( $work_id ) );
		$this->assertEquals( 'a/b/c/', get_option( 'foo' ) );

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::$done, $sut->get_work_status( $work_id ) );
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

		$this->assertEquals( Worker::$working, $sut->get_work_status( $work_id ) );
		$this->assertEquals( 'a/b/c/', get_option( 'foo' ) );

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::$done, $sut->get_work_status( $work_id ) );
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

		$this->assertEquals( Worker::$working, $sut->get_work_status( $work_id ) );
		$this->assertEquals( $targets, $sut->get_work( $work_id )->get_remaining() );

		$sut->work_on( $work_id );

		$this->assertEquals( Worker::$working, $sut->get_work_status( $work_id ) );
		$this->assertEquals( $targets, $sut->get_work( $work_id )->get_remaining() );
	}

	/**
	 * @test
	 * it should run scheduled works on action hook
	 */
	public function it_should_run_scheduled_works_on_action_hook() {
		tribe_register( 'foo', $this );
		$setup_factory = $this->make_instance();
		$work_one      = $setup_factory->queue_work( [ 'a', 'b', 'c', 'd', 'e', 'f' ], [ __CLASS__, 'callback_two' ] )
		                               ->set_batch_size( 3 )
		                               ->save();
		$work_two      = $setup_factory->queue_work( [ 'foo', 'baz', 'bar' ], [ __CLASS__, 'callback_two' ] )
		                               ->set_batch_size( 2 )
		                               ->save();
		$the_beatles   = [ 'john', 'paul', 'george', 'ringo' ];
		$work_three    = $setup_factory->queue_work( $the_beatles, [ 'tribe', 'foo', 'callback_one' ] )
		                               ->set_batch_size( 2 )
		                               ->save();

		add_action( 'some_action', [ 'Tribe__Queue', 'work' ] );

		$check_factory = $this->make_instance();

		do_action( 'some_action' );

		$this->assertEquals( Worker::$working, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::$queued, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::$queued, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/', get_option( 'foo' ) );
		$expected_list = [ $work_one => Worker::$working, $work_two => Worker::$queued, $work_three => Worker::$queued ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::$queued, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::$queued, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/', get_option( 'foo' ) );
		$expected_list = [ $work_two => Worker::$queued, $work_three => Worker::$queued ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::$working, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::$queued, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/foo/baz/', get_option( 'foo' ) );
		$expected_list = [ $work_two => Worker::$working, $work_three => Worker::$queued ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::$queued, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/foo/baz/bar/', get_option( 'foo' ) );
		$expected_list = [ $work_three => Worker::$queued ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::$working, $check_factory->get_work_status( $work_three ) );
		$this->assertEquals( 'a/b/c/d/e/f/foo/baz/bar/john/paul/', get_option( 'foo' ) );
		$expected_list = [ $work_three => Worker::$working ];
		$this->assertEquals( $expected_list, $check_factory->get_work_list() );

		do_action( 'some_action' );

		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_one ) );
		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_two ) );
		$this->assertEquals( Worker::$done, $check_factory->get_work_status( $work_three ) );
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
	 * it should return self::$not_found when trying to get status of non existing job
	 */
	public function it_should_return_not_found_when_trying_to_get_status_of_non_existing_job() {
		$sut = $this->make_instance();

		$this->assertEquals( Worker::$not_found, $sut->get_work_status( 'bada.boom' ) );
	}

	/**
	 * @test
	 * it should return empty array if list option is not set
	 */
	public function it_should_return_empty_array_if_list_option_is_not_set() {
		delete_option( Queue::$works_option );

		$sut = $this->make_instance();

		$this->assertEquals( [], $sut->get_work_list() );
	}

	/**
	 * @test
	 * it should sort works by priority when getting the list
	 */
	public function it_should_sort_works_by_priority_when_getting_the_list() {
		$sut = $this->make_instance();

		$work_one   = $sut->queue_work( [ 'a', 'b', 'c', 'd' ], [ __CLASS__, 'callback_one' ] )->set_priority( 5 )->save();
		$work_two   = $sut->queue_work( [ 'a', 'b', 'c', 'e' ], [ __CLASS__, 'callback_one' ] )->set_priority( 1 )->save();
		$work_three = $sut->queue_work( [ 'a', 'b', 'c', 'f' ], [ __CLASS__, 'callback_one' ] )->set_priority( 10 )->save();

		$expected = [ $work_two => Worker::$queued, $work_one => Worker::$queued, $work_three => Worker::$queued ];
		$list     = $sut->get_work_list();
		$this->assertSame( $expected, $list );
	}

	/**
	 * It should start works immediately when using the start_work method
	 *
	 * @test
	 */
	public function start_works_immediately_when_using_the_start_work_method() {
		$sut = $this->make_instance();

		$targets = [ 'a', 'b', 'c', 'd' ];
		$work    = $sut->start_work( $targets, [ __CLASS__, 'callback_one' ] );
		$work_id = $work->get_id();

		$list = $sut->get_work_list( false );
		$this->assertArrayHasKey( $work_id, $list );
		$this->assertEmpty( $work->get_remaining() );
	}

	/**
	 * It should process specified number of targets when using the start_work method
	 *
	 * @test
	 */
	public function process_specified_number_of_targets_when_using_the_start_work_method() {
		$sut = $this->make_instance();

		$targets = [ 'a', 'b', 'c', 'd' ];
		$work    = $sut->start_work( $targets, [ __CLASS__, 'callback_four' ], null, 2 );

		$this->assertCount( 2, get_option( '__callback_four_processed' ) );
		$this->assertCount( 2, $work->get_remaining() );
	}

	/**
	 * It should allow removing jobs from a group
	 *
	 * @test
	 */
	public function should_allow_removing_jobs_from_a_group() {
		$sut = $this->make_instance();

		$foo_one    = $sut->queue_work( [ 'one' ], '__return_true', null, 'foo' )->save();
		$foo_two    = $sut->queue_work( [ 'one', 'two' ], '__return_true', null, 'foo' )->save();
		$foo_three  = $sut->queue_work( [ 'two', 'three' ], '__return_true', null, 'foo' )->save();
		$bar_one    = $sut->queue_work( [ 'one' ], '__return_true', null, 'bar' )->save();
		$bar_two    = $sut->queue_work( [ 'one', 'two' ], '__return_true', null, 'bar' )->save();
		$bar_three  = $sut->queue_work( [ 'two', 'three' ], '__return_true', null, 'bar' )->save();
		$sut->queue_work( [ 'two', 'three' ], '__return_true', null )->save();

		$this->assertCount( 7, $sut->get_work_list() );

		$this->assertEqualSets( [ $foo_one, $foo_two, $foo_three ], $sut->remove_work_group_from_list( 'foo' ) );

		$this->assertCount( 4, $sut->get_work_list() );

		$this->assertEqualSets( [ $bar_one, $bar_two, $bar_three ], $sut->remove_work_group_from_list( 'bar' ) );

		$this->assertCount( 1, $sut->get_work_list() );
	}

	/**
	 * It should correctly parse similarly named groups when removing a group
	 *
	 * @test
	 */
	public function should_correctly_parse_similarly_named_groups_when_removing_a_group() {
		$sut = $this->make_instance();

		$foo_one    = $sut->queue_work( [ 'one' ], '__return_true', null, 'foo' )->save();
		$foo_two    = $sut->queue_work( [ 'one', 'two' ], '__return_true', null, 'foo' )->save();
		$foo_three  = $sut->queue_work( [ 'two', 'three' ], '__return_true', null, 'foo' )->save();
		$foobar_one    = $sut->queue_work( [ 'one' ], '__return_true', null, 'foobar' )->save();
		$foobar_two    = $sut->queue_work( [ 'one', 'two' ], '__return_true', null, 'foobar' )->save();
		$foobar_three  = $sut->queue_work( [ 'two', 'three' ], '__return_true', null, 'foobar' )->save();
		$sut->queue_work( [ 'two', 'three' ], '__return_true', null )->save();

		$this->assertCount( 7, $sut->get_work_list() );

		$this->assertEqualSets( [ $foo_one, $foo_two, $foo_three ], $sut->remove_work_group_from_list( 'foo' ) );

		$this->assertCount( 4, $sut->get_work_list() );

		$this->assertEqualSets( [ $foobar_one, $foobar_two, $foobar_three ], $sut->remove_work_group_from_list( 'foobar' ) );

		$this->assertCount( 1, $sut->get_work_list() );
	}

	/**
	 * It should block any further work when removing a work
	 *
	 * @test
	 */
	public function should_block_any_further_work_when_removing_a_work() {
		$targets = [ 1, 2, 3, 4, 5, 6 ];

		$sut = $this->make_instance();

		$work = $sut->start_work( $targets, [ __CLASS__, 'callback_five' ], null, 3 );

		$this->assertEqualSets( [ 1, 2, 3 ], get_option( 'callback_five' ) );

		$sut->remove_work_from_list( $work, 0 );

		$sut->work_on( $work );

		$this->assertEqualSets( [ 1, 2, 3 ], get_option( 'callback_five' ) );
	}

	/**
	 * It should stop working immediately on a group work when removed
	 *
	 * @test
	 */
	public function should_stop_working_immediately_on_a_group_work_when_removed() {
		$sut = $this->make_instance();

		$work_one   = $sut->start_work( [ 1, 2, 3, 4 ], [ __CLASS__, 'callback_five' ], null, 1, 'foo' );
		$work_two   = $sut->start_work( [ 5, 6, 7, 8 ], [ __CLASS__, 'callback_five' ], null, 1, 'foo' );
		$work_three = $sut->start_work( [ 9, 10, 11, 12 ], [ __CLASS__, 'callback_five' ], null, 1, 'foo' );

		$expected = [ 1, 5, 9 ];

		$this->assertEqualSets( $expected, get_option( 'callback_five' ) );

		$sut->remove_work_group_from_list( 'foo', 0 );

		$sut->work_on( $work_one );
		$sut->work_on( $work_two );
		$sut->work_on( $work_three );

		$this->assertEqualSets( $expected, get_option( 'callback_five' ) );
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

	public function callback_four( $target ) {
		$option = '__callback_four_processed';
		$stored = get_option( $option );

		$stored = empty( $stored ) ? [ $target ] : array_merge( $stored, [ $target ] );

		update_option( $option, $stored );

		return true;
	}

	public function callback_five( $target ) {
		$list = get_option( 'callback_five' );
		if ( empty( $list ) ) {
			$list = [ $target ];
		} else {
			$list[] = $target;
		}

		return update_option( 'callback_five', $list );
	}
}