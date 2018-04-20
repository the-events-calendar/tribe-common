<?php

namespace Tribe\Process;

include_once codecept_data_dir( 'classes/Dummy_Queue.php' );

use Codeception\TestCase\WPTestCase;
use Tribe\Common\Tests\Dummy_Queue as Queue;

class QueueTest extends WPTestCase {

	function setUp() {
		parent::setUp();
		$this->clear_queues();
	}

	protected function clear_queues() {
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%tribe_proces%'" );

	}

	function tearDown() {
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

		$this->assertNotEmpty( get_option( $queue_id ) );

		$this->assertTrue( \Tribe__Process__Queue::stop_queue( $queue_id ) );

		$this->assertEmpty( get_option( $queue_id ) );

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
		$query = $wpdb->prepare( "SELECT option_id FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( $queue_id ) . '%' );
		$this->assertCount( 4, $wpdb->get_col( $query ) );
	}
}
