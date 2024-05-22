<?php

namespace TEC\Event_Automator\Traits\Maps\Commerce;

use TEC\Event_Automator\Tests\Traits\Create_events;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use TEC\Event_Automator\Tests\Traits\With_Uopz;
use Tribe\Test\PHPUnit\Traits\With_Post_Remapping;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class EDDTest extends \Codeception\TestCase\WPTestCase {

	use SnapshotAssertions;
	use With_Post_Remapping;
	use Create_events;
	use Create_attendees;
	use With_Uopz;
	use EDD;

	public function setUp() {
		parent::setUp();

		// To support taxonomy term creation and assignment.
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		tribe( 'cache' )->reset();

		add_action( 'edd_complete_purchase', [ $this, 'disable_email' ], 998, 1 );
	}

	public function disable_email() {
		// This disables customer purchase receipts
		remove_action( 'edd_complete_purchase', 'edd_trigger_purchase_receipt', 999, 1 );

		// This disables email notices to admins
		remove_action( 'edd_admin_sale_notice', 'edd_admin_email_notice', 10, 2 );
	}

	/**
	 * @test
	 */
	public function should_map_edd_order_id() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_edd_order( $event );

		$next_order = $this->get_edd_order_by_id( $order_id );
		$this->assertEquals( $order_id, $next_order['order_id'] );
		$this->assertIsString( $next_order['id'] );
	}

	/**
	 * @test
	 */
	public function should_map_edd_types() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_edd_order( $event );

		$next_order = $this->get_edd_order_by_id( $order_id );

		$this->assertIsFloat( $next_order['tax_total'] );
		$this->assertIsFloat( $next_order['discount_total'] );
		$this->assertIsFloat( $next_order['order_total'] );
		$this->assertIsInt( $next_order['customer_id'] );
		$this->assertIsInt( $next_order['customer_user'] );
	}

	/**
	 * @test
	 */
	public function should_map_edd_customer_notes() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_edd_order( $event );

		$next_order = $this->get_edd_order_by_id( $order_id );

		$this->assertIsArray( $next_order['customer_note'] );
		$this->assertCount( 1, $next_order['customer_note'] );

		$customer_note = reset( $next_order['customer_note'] );

		$this->assertArrayHasKey( 'order_note_content', $customer_note );
		$this->assertArrayHasKey( 'order_note_object_type', $customer_note );
		$this->assertArrayHasKey( 'order_note_date_created', $customer_note );
	}

	/**
	 * @test
	 */
	public function should_map_edd_order_items() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_edd_order( $event );

		$next_order = $this->get_edd_order_by_id( $order_id );
		$this->assertIsArray( $next_order['items'] );

		$first_item = reset( $next_order['items'] );

		$this->assertIsInt( $first_item['ticket_id'] );
		$this->assertIsFloat( $first_item['price'] );
		$this->assertIsInt( $first_item['quantity'] );
		$this->assertIsFloat( $first_item['subtotal'] );
		$this->assertIsFloat( $first_item['total'] );
		$this->assertIsFloat( $first_item['tax'] );
	}
}
