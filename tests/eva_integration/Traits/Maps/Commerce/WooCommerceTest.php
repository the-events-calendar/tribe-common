<?php

namespace Tribe\tests\eva_integration\Traits\Maps\Commerce;

use TEC\Event_Automator\Tests\Traits\Create_Events;
use TEC\Event_Automator\Tests\Traits\Create_Attendees;
use TEC\Event_Automator\Traits\Maps\Commerce\WooCommerce;
use Tribe\Tests\Traits\With_Uopz;
use Tribe\Test\PHPUnit\Traits\With_Post_Remapping;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Codeception\TestCase\WPTestCase;

class WooCommerceTest extends WPTestCase {

	use SnapshotAssertions;
	use With_Post_Remapping;
	use Create_Events;
	use Create_Attendees;
	use With_Uopz;
	use WooCommerce;

	public function setUp(): void {
		parent::setUp();

		// To support taxonomy term creation and assignment.
		wp_set_current_user( 1 );

		tribe( 'cache' )->reset();
	}

	/**
	 * @test
	 */
	public function should_map_woo_order_id() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_woo_order( $event );

		$next_order = $this->get_woo_order_by_id( $order_id );
		$this->assertEquals( $order_id, $next_order['order_id'] );
		$this->assertIsString( $next_order['id'] );
	}

	/**
	 * @test
	 */
	public function should_map_woo_types() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_woo_order( $event );

		$next_order = $this->get_woo_order_by_id( $order_id );

		$this->assertIsFloat( $next_order['tax_total'] );
		$this->assertIsFloat( $next_order['discount_total'] );
		$this->assertIsFloat( $next_order['order_total'] );
		$this->assertIsInt( $next_order['customer_id'] );
		$this->assertIsInt( $next_order['customer_user'] );
	}

	/**
	 * @test
	 */
	public function should_map_woo_customer_notes() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_woo_order( $event );

		$order = wc_get_order( $order_id );
		$order->add_order_note( 'Test note for mapping', true, true );
		$order->add_order_note( 'Test note for automated test', true, true );
		$order->add_order_note( 'Hidden in automated test', false, true );

		$next_order = $this->get_woo_order_by_id( $order_id );

		$this->assertIsArray( $next_order['customer_note'] );
		$this->assertCount( 2, $next_order['customer_note'] );

		$customer_note = reset( $next_order['customer_note'] );

		$this->assertArrayHasKey( 'order_note_content', $customer_note );
		$this->assertArrayHasKey( 'order_note_object_type', $customer_note );
		$this->assertArrayHasKey( 'order_note_date_created', $customer_note );
	}

	/**
	 * @test
	 */
	public function should_map_woo_order_items() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_woo_order( $event );

		$next_order = $this->get_woo_order_by_id( $order_id );
		$this->assertIsArray( $next_order['items'] );

		$first_item = reset( $next_order['items'] );

		$this->assertArrayHasKey( 'price', $first_item );
		$this->assertIsFloat( $first_item['price'] );
		$this->assertIsInt( $first_item['quantity'] );
		$this->assertIsFloat( $first_item['subtotal'] );
		$this->assertIsFloat( $first_item['total'] );
		$this->assertIsFloat( $first_item['tax'] );
		$this->assertIsArray( $first_item['meta'] );

		$first_meta = reset( $first_item['meta'] );

		$this->assertArrayHasKey( 'ticket_meta_id', $first_meta );
		$this->assertArrayHasKey( 'ticket_meta_name', $first_meta );
		$this->assertArrayHasKey( 'ticket_meta_value', $first_meta );
		$this->assertIsArray( $first_meta['ticket_meta_value'] );
	}
}
