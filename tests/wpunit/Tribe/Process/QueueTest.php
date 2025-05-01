<?php

namespace Tribe\Process;

include_once codecept_data_dir( 'classes/Dummy_Queue.php' );

use Codeception\TestCase\WPTestCase;
use Tribe\Common\Tests\Dummy_Queue as Queue;
use Tribe__Process__Queue as Process;

class QueueTest extends WPTestCase {

	function setUp(): void {
		parent::setUp();
		$this->clear_queues();
		add_filter( 'tribe_supports_async_process', '__return_true' );
	}

	protected function clear_queues() {
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%tribe_proces%'" );

	}

	function tearDown(): void {
		$this->clear_queues();
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
	 * @return Queue
	 */
	private function make_instance() {
		$queue = new Queue();
		$queue->set_callback( '__return_false' );

		return $queue;
	}

	/**
	 * It should return the queue id after dispatching it
	 *
	 * @test
	 */
	public function should_return_the_queue_id_after_dispatching_it() {
		$sut = $this->make_instance();
		for ( $i = 0; $i < 3; $i ++ ) {
			$sut->push_to_queue( $i );
		}
		$sut->save()->dispatch();
		$this->assertInternalType( 'string', $sut->get_id() );
	}

	/**
	 * It should throw if trying to fetch the queue id before saving it
	 *
	 * @test
	 */
	public function should_throw_if_trying_to_fetch_the_queue_id_before_saving_it() {
		$sut = $this->make_instance();
		for ( $i = 0; $i < 3; $i ++ ) {
			$sut->push_to_queue( $i );
		}

		$this->expectException( \RuntimeException::class );

		$sut->get_id();
	}

	/**
	 * It should allow stopping a queue by id
	 *
	 * @test
	 */
	public function should_allow_stopping_a_queue_by_id() {
		$queue = $this->make_instance();
		$queue->set_callback( '__return_false' );
		for ( $i = 0; $i < 100; $i ++ ) {
			$queue->push_to_queue( $i );
		}
		$queue->save()->dispatch();

		$queue_id = $queue->get_id();

		$this->assertNotEmpty( get_option( $queue->get_batch_key() ) );

		$this->assertTrue( \Tribe__Process__Queue::stop_queue( $queue_id ) );

		$this->assertEmpty( get_option( $queue->get_batch_key() ) );

		$this->assertFalse( \Tribe__Process__Queue::stop_queue( $queue_id ) );
	}

	/**
	 * It should allow getting the progress of a queue
	 *
	 * @test
	 */
	public function should_allow_getting_the_progress_of_a_queue() {
		$queue = $this->make_instance();
		$queue->set_callback( '__return_false' );

		for ( $i = 0; $i < 10000; $i ++ ) {
			$queue->push_to_queue( $i );
		}
		$queue->save()->dispatch();

		$queue_id = $queue->get_id();

		$this->assertNotEmpty( get_option( $queue_id ) );

		wp_cache_delete( $queue_id, 'options' );

		$progress = \Tribe__Process__Queue::get_status_of( $queue_id );

		$this->assertNotEmpty( $progress );
		$this->assertEquals( $queue_id, $progress['identifier'] );
		$this->assertEquals( 0, $progress['done'] );
		$this->assertEquals( 10000, $progress['total'] );
		$this->assertInternalType( 'int', $progress['fragments'] );

		\Tribe__Process__Queue::stop_queue( $queue_id );
	}

	/**
	 * It should fragment the data if bigger than the max_allowed_packet
	 *
	 * @test
	 */
	public function should_fragment_the_data_if_bigger_than_the_max_allowed_packet() {
		$data      = array_fill( 0, 100, 'foo' );
		$size      = ( strlen( utf8_decode( serialize( $data ) ) ) );
		$half_size = floor( $size / 2 );
		add_filter( 'tribe_db_max_allowed_packet', function () use ( $half_size ) {
			return (int) $half_size;
		} );

		$sut = $this->make_instance();

		foreach ( $data as $item ) {
			$sut->push_to_queue( $item );
		}

		$sut->save();
		$queue_id = $sut->get_id();

		global $wpdb;
        $query = $wpdb->prepare(
            "SELECT option_id FROM {$wpdb->options} WHERE option_name LIKE %s AND autoload = 'off'",
            $wpdb->esc_like($queue_id) . '%'
        );
		$this->assertCount( 4, $wpdb->get_col( $query ) );
	}

	/**
	 * It should allow setting a defined queue id on the queue
	 *
	 * @test
	 */
	public function should_allow_setting_a_defined_queue_id_on_the_queue() {
		$sut = $this->make_instance();
		$sut->set_id( 'unique-q-id' );
		foreach ( range( 1, 5 ) as $i ) {
			$sut->push_to_queue( $i );
		}
		$sut->save();

		$id = $sut->get_id();
		$this->assertEquals( 'tribe_queue_dummy_queue_batch_unique-q-id', $id );
		$q_status = \Tribe__Process__Queue::get_status_of( $id )->to_array();
		$this->assertArrayHasKey( 'last_update', $q_status );
		unset( $q_status['last_update'] );
		$expected = [
			'identifier' => $id,
			'done'       => 0,
			'total'      => 5,
			'fragments'  => 1,
		];
		$this->assertEqualSets( $expected, $q_status );
	}

	/**
	 * It should throw if trying to set ID on queue after saving it
	 *
	 * @test
	 */
	public function should_throw_if_trying_to_set_id_on_queue_after_saving_it() {
		$sut = $this->make_instance();
		foreach ( range( 1, 5 ) as $i ) {
			$sut->push_to_queue( $i );
		}
		$sut->save();

		$this->expectException( \RuntimeException::class );

		$sut->set_id( 'unique-q-id' );
	}

	/**
	 * It should set a last_update timestamp on the queue when updating
	 *
	 * @test
	 */
	public function should_set_a_last_update_timestamp_on_the_queue_when_updating() {
		$sut = $this->make_instance();
		foreach ( range( 1, 5 ) as $i ) {
			$sut->push_to_queue( $i );
		}
		$sut->save();

		$save_status = Queue::get_status_of( $sut->get_id() )->to_array();

		$this->assertArrayHasKey( 'last_update', $save_status );
		$this->assertInternalType( 'int', $save_status['last_update'] );
		$after_save_last_update = $save_status['last_update'];
		$this->assertEquals( time(), $after_save_last_update, 'Last update should be about now', 2 );

		sleep( 1 );

		$sut->update( $sut->get_id(), [] );

		$update_status = Queue::get_status_of( $sut->get_id() )->to_array();

		$this->assertArrayHasKey( 'last_update', $update_status );
		$this->assertInternalType( 'int', $update_status['last_update'] );
		$this->assertGreaterThan( $after_save_last_update, $update_status['last_update'] );
		$this->assertEquals( time(), $update_status['last_update'], 'Last update should be about now', 2 );
	}

	/**
	 * It should correctly detect stuck queues
	 *
	 * @test
	 */
	public function should_correctly_detect_stuck_queues() {
		$sut = $this->make_instance();
		foreach ( range( 1, 5 ) as $i ) {
			$sut->push_to_queue( $i );
		}
		$sut->save();

		$this->assertFalse( Queue::is_stuck( $sut->get_id() ) );

		sleep( 1 );

		add_filter( 'tribe_process_queue_time_limit', '__return_zero' );

		$this->assertTrue( Queue::is_stuck( $sut->get_id() ) );
	}

	/**
	 * It should allow deleting a queue
	 *
	 * @test
	 */
	public function should_allow_deleting_a_queue() {
		$sut = $this->make_instance();
		foreach ( range( 1, 5 ) as $i ) {
			$sut->push_to_queue( $i );
		}
		$sut->save();

		$this->assertNotEmpty( get_transient( $sut->get_meta_key( $sut->get_id() ) ) );
		$this->assertNotEmpty( get_option( $sut->get_id() ) );

		$sut->delete( $sut->get_id() );

		$this->assertEmpty( get_transient( $sut->get_meta_key( $sut->get_id() ) ) );
		wp_cache_flush();
		$this->assertEmpty( get_option( $sut->get_id() ) );
	}

	/**
	 * It should allow deleting queues of a specific action
	 *
	 * @test
	 */
	public function should_allow_deleting_queues_of_a_specific_action() {
		$action = 'dummy_queue';

		$this->assertEquals( 0, Queue::delete_all_queues( $action ) );

		$this->make_instance()->push_to_queue( [ 'foo' => 'bar' ] )->save();

		$this->assertEquals( 1, Queue::delete_all_queues( $action ) );

		$this->make_instance()->push_to_queue( [ 'foo' => 'bar' ] )->save();
		$this->make_instance()->push_to_queue( [ 'foo' => 'bar' ] )->save();
		$this->make_instance()->push_to_queue( [ 'foo' => 'bar' ] )->save();

		$this->assertEquals( 0, Queue::delete_all_queues( 'not-dummy' ) );
		$this->assertEquals( 3, Queue::delete_all_queues( $action ) );
	}

	/**
	 * It should call the complete method on the queue when running on cron fallback
	 *
	 * @test
	 */
	public function should_call_the_complete_method_on_the_queue_when_running_on_cron_fallback() {
		// Let's make sure to say that async processes are not supported.
		add_filter( 'tribe_supports_async_process', '__return_false' );
		global $__test_flag__;
		$__test_flag__ = 'start';

		// Create a queue with a custom completion method.
		$test_queue = new class extends Process {

			public static function action() {
				return 'test';
			}

			protected function task( $item ) {
				global $__test_flag__;
				$__test_flag__ = 'Processing: ' . (int) $item;

				return Process::ITEM_DONE;
			}

			protected function complete() {
				global $__test_flag__;
				$__test_flag__ = 'completed';
			}

		};

		// Fill and save the queue.
		foreach ( range( 1, 5 ) as $i ) {
			$test_queue->push_to_queue( $i );
		}
		$test_queue->save()->dispatch();

		$queue = $test_queue->get_identifier();
		do_action( $queue );

		$this->assertEquals( 'completed', $__test_flag__ );
	}

	/**
	 * It should call the complete method on cron processing even with empty queue
	 *
	 * @test
	 */
	public function should_call_the_complete_method_on_cron_processing_even_with_empty_queue() {
		// Let's make sure to say that async processes are not supported.
		add_filter( 'tribe_supports_async_process', '__return_false' );
		global $__test_flag__;
		$__test_flag__ = 'start';

		// Create a queue with a custom completion method.
		$test_queue = new class extends Process {

			public static function action() {
				return 'test';
			}

			protected function task( $item ) {
				global $__test_flag__;
				$__test_flag__ = 'Processing: ' . (int) $item;

				return Process::ITEM_DONE;
			}

			protected function complete() {
				global $__test_flag__;
				$__test_flag__ = 'completed';
			}

		};

		$test_queue->save()->dispatch();

		$queue = $test_queue->get_identifier();
		do_action( $queue );

		$this->assertEquals( 'completed', $__test_flag__ );
	}

}
