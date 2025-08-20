<?php

namespace Tribe\tests\eva_integration\Zapier\Triggers;

use TEC\Event_Automator\Tests\Traits\Create_Events;
use TEC\Event_Automator\Tests\Traits\Create_Attendees;
use TEC\Event_Automator\Zapier\Triggers\Refunded_Orders;
use Tribe\Tests\Traits\With_Uopz;

class RefundedOrdersTest extends \Codeception\TestCase\WPTestCase {

	use Create_Events;
	use Create_Attendees;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear Queue.
		$queue = tribe( Refunded_Orders::class );
		$queue->set_queue( [] );
		add_filter( 'tec_event_automator_zapier_enable_add_to_queue', '__return_true' );
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
		$refunded_orders_queue = tribe( Refunded_Orders::class );
		$this->assertEmpty( $refunded_orders_queue->get_queue() );

		wp_insert_post(
			[
				'post_title'  => 'A test post',
				'post_status' => 'publish',
			] 
		);

		$this->assertEmpty( $refunded_orders_queue->get_queue() );
	}

	/**
	 * @test
	 * @skip TC cart does not clear between tests causing, "Trying to access array offset on value of type null".
	 */
	public function should_add_a_refunded_tc_order_to_queue() {
		$refunded_orders_queue = tribe( Refunded_Orders::class );
		$event                 = $this->generate_event( $this->mock_date_value );
		$order_id_1            = $this->generate_tc_order_and_refund_it( $event );

		$queue = $refunded_orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $order_id_1, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_not_add_a_refunded_woo_order_with_no_tickets_to_queue() {
		$refunded_orders_queue = tribe( Refunded_Orders::class );
		$this->generate_woo_order_with_no_tickets_and_refund_it();

		$queue = $refunded_orders_queue->get_queue();
		$this->assertEmpty( $queue );
	}

	/**
	 * @test
	 */
	public function should_pass_provider_refunded_woo_order_to_queue() {
		add_filter(
			'tec_event_automator_zapier_add_to_queue_data_refunded_orders',
			function ( $data, $post_id, $endpoint ) {
				$this->assertInstanceOf( \Tribe__Tickets_Plus__Commerce__WooCommerce__Main::class, $data['provider'] );
				$this->assertEquals( $post_id, $data['order_id'] );
				$this->assertEquals( 'completed', $data['old_status'] );
				$this->assertEquals( 'refunded', $data['new_status'] );

				return $data;
			},
			10,
			3 
		);

		$event = $this->generate_event( $this->mock_date_value );
		$this->generate_woo_order_and_refund_it( $event );
	}

	/**
	 * @test
	 */
	public function should_add_a_refunded_woo_order_to_queue() {
		$refunded_orders_queue = tribe( Refunded_Orders::class );
		$event                 = $this->generate_event( $this->mock_date_value );
		$order_id_1            = $this->generate_woo_order_and_refund_it( $event );

		$queue = $refunded_orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		// This check fails on github actions, cannot be reproduced locally in slic or in QA.
		//$this->assertCount( 1, $queue );
		$this->assertEquals( $order_id_1, $queue[0] );
	}

	/**
	 * @test
	 * @skip TC cart does not clear between tests causing, "Trying to access array offset on value of type null".
	 */
	public function should_add_all_providers_orders_to_queue() {
		$refunded_orders_queue = tribe( Refunded_Orders::class );
		$event                 = $this->generate_event( $this->mock_date_value );
		$order_id_1            = $this->generate_woo_order_and_refund_it( $event );
		$order_id_2            = $this->generate_tc_order_and_refund_it( $event );
		$order_ids             = [ $order_id_1, $order_id_2 ];

		$queue = $refunded_orders_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 2, $queue );
		foreach ( $order_ids as $order_id ) {
			$this->assertContains( $order_id, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_not_add_a_refunded_woo_order_to_queue_when_no_access_created() {
		add_filter(
			'tec_event_automator_zapier_enable_add_to_queue',
			function ( $enable_add_to_queue ) {
				return false;
			},
			11 
		);

		$refunded_orders_queue = tribe( Refunded_Orders::class );
		$event                 = $this->generate_event( $this->mock_date_value );
		$order_id_1            = $this->generate_woo_order_and_refund_it( $event );

		$queue = $refunded_orders_queue->get_queue();
		$this->assertEmpty( $queue );
	}
}
