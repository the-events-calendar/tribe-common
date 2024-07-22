<?php

namespace Tribe\tests\eva_integration\Power_Automate\Triggers;

use TEC\Event_Automator\Tests\Traits\Create_events;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use Tribe\Tests\Traits\With_Uopz;

class  OrdersTest extends \Codeception\TestCase\WPTestCase {

	use Create_events;
	use Create_attendees;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear Queue.
		$queue = tribe( Orders::class );
		$queue->set_queue( [] );
	}

	/**
	 * @inheritDoc
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		add_filter( 'tribe_tickets_ticket_object_is_ticket_cache_enabled', '__return_false' );
	}

	/**
	 * @test
	 */
	public function should_not_add_a_post_to_queue() {
		$orders_queue = tribe( Orders::class );
		$this->assertEmpty( $orders_queue->get_queue() );

		wp_insert_post( [
			'post_title'  => 'A test post',
			'post_status' => 'publish',
		] );

		$this->assertEmpty( $orders_queue->get_queue() );
	}

	/**
	 * @test
	 * @skip TC cart does not clear between tests causing, "Trying to access array offset on value of type null".
	 */
	public function should_add_a_tc_order_to_queue() {
		$orders_queue = tribe( Orders::class );
		$event        = $this->generate_event( $this->mock_date_value );
		$order_id_1   = $this->generate_tc_order( $event );

		$queue = $orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $order_id_1, $queue[0] );
	}

	/**
	 * @test
	 * @skip TC cart does not clear between tests causing, "Trying to access array offset on value of type null".
	 */
	public function should_add_multiple_tc_orders_to_queue() {
		$orders_queue = tribe( Orders::class );
		$event        = $this->generate_event( $this->mock_date_value );
		$order_id_1   = $this->generate_tc_order( $event );
		$order_id_2   = $this->generate_tc_order( $event );
		$order_ids    = [ $order_id_1, $order_id_2 ];

		$queue = $orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 2, $queue );
		foreach ( $order_ids as $order_id ) {
			$this->assertContains( $order_id, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_not_add_a_edd_order_with_no_tickets_to_queue() {
		$orders_queue = tribe( Orders::class );
		$this->generate_edd_order_with_no_tickets();

		$queue = $orders_queue->get_queue();
		$this->assertEmpty( $queue );
	}


	/**
	 * @test
	 */
	public function should_add_a_edd_order_to_queue() {
		$orders_queue = tribe( Orders::class );
		$event        = $this->generate_event( $this->mock_date_value );
		$order_id_1   = $this->generate_edd_order( $event );

		$queue = $orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $order_id_1, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_edd_orders_to_queue() {
		$orders_queue = tribe( Orders::class );
		$event        = $this->generate_event( $this->mock_date_value );
		$order_id_1   = $this->generate_edd_order( $event );
		$order_id_2   = $this->generate_edd_order( $event );
		$order_ids    = [ $order_id_1, $order_id_2 ];

		$queue = $orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 2, $queue );
		foreach ( $order_ids as $order_id ) {
			$this->assertContains( $order_id, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_not_add_a_woo_order_with_no_tickets_to_queue() {
		$orders_queue = tribe( Orders::class );
		$this->generate_woo_order_with_no_tickets();

		$queue = $orders_queue->get_queue();
		$this->assertEmpty( $queue );
	}

	/**
	 * @test
	 */
	public function should_add_a_woo_order_to_queue() {
		$orders_queue = tribe( Orders::class );
		$event        = $this->generate_event( $this->mock_date_value );
		$order_id_1   = $this->generate_woo_order( $event );

		$queue = $orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $order_id_1, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_woo_orders_to_queue() {
		$orders_queue = tribe( Orders::class );
		$event        = $this->generate_event( $this->mock_date_value );
		$order_id_1   = $this->generate_woo_order( $event );
		$order_id_2   = $this->generate_woo_order( $event );
		$order_ids    = [ $order_id_1, $order_id_2 ];

		$queue = $orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 2, $queue );
		foreach ( $order_ids as $order_id ) {
			$this->assertContains( $order_id, $queue );
		}
	}

	/**
	 * @test
	 * @skip TC cart does not clear between tests causing, "Trying to access array offset on value of type null".
	 */
	public function should_add_all_providers_orders_to_queue() {
		$orders_queue = tribe( Orders::class );
		$event        = $this->generate_event( $this->mock_date_value );
		$order_id_1   = $this->generate_woo_order( $event );
		$order_id_2   = $this->generate_edd_order( $event );
		$order_id_3   = $this->generate_tc_order( $event );
		$order_ids    = [ $order_id_1, $order_id_2, $order_id_3 ];

		$queue = $orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 2, $queue );
		foreach ( $order_ids as $order_id ) {
			$this->assertContains( $order_id, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_not_add_a_edd_order_to_queue_when_no_access_created() {
		add_filter( 'tec_event_automator_power_automate_enable_add_to_queue', function ( $enable_add_to_queue ) {
			return false;
		}, 11 );

		$orders_queue = tribe( Orders::class );
		$event        = $this->generate_event( $this->mock_date_value );
		$order_id_1   = $this->generate_edd_order( $event );

		$queue = $orders_queue->get_queue();
		$this->assertEmpty( $queue );
	}
}
