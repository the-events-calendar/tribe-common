<?php

namespace Tribe\tests\eva_integration\Traits\Maps\Commerce;

use TEC\Event_Automator\Tests\Traits\Create_Events;
use TEC\Event_Automator\Tests\Traits\Create_Attendees;
use TEC\Event_Automator\Traits\Maps\Commerce\Tickets_Commerce;
use Tribe\Tests\Traits\With_Uopz;
use Tribe\Test\PHPUnit\Traits\With_Post_Remapping;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class TicketsCommerceTest extends \Codeception\TestCase\WPTestCase {

	use SnapshotAssertions;
	use With_Post_Remapping;
	use Create_Events;
	use Create_Attendees;
	use With_Uopz;
	use Tickets_Commerce;

	public function setUp() {
		parent::setUp();

		// To support taxonomy term creation and assignment.
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		tribe( 'cache' )->reset();
	}

	/**
	 * @test
	 */
	public function should_map_tc_order_id() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_tc_order( $event );

		$next_order = $this->get_tc_order_by_id( $order_id );
		$this->assertEquals( $order_id, $next_order['order_id'] );
		$this->assertIsString( $next_order['id'] );
	}

	/**
	 * @test
	 */
	public function should_map_tc_types() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_tc_order( $event );

		$next_order = $this->get_tc_order_by_id( $order_id );

		$this->assertIsString( $next_order['order_id'] );
		$this->assertIsString( $next_order['order_number'] );
		$this->assertIsFloat( $next_order['order_total'] );
		$this->assertIsInt( $next_order['customer_id'] );
		$this->assertIsInt( $next_order['customer_user'] );
	}

	/**
	 * @test
	 */
	public function should_map_tc_order_items() {
		$event    = $this->generate_event( $this->mock_date_value );
		$order_id = $this->generate_tc_order( $event );

		$next_order = $this->get_tc_order_by_id( $order_id );
		$this->assertIsArray( $next_order['items'] );

		$first_item = reset( $next_order['items'] );

		$this->assertArrayHasKey( 'price', $first_item );
		$this->assertIsFloat( $first_item['price'] );
		$this->assertIsInt( $first_item['quantity'] );
		$this->assertIsFloat( $first_item['subtotal'] );
		$this->assertIsArray( $first_item['meta'] );
	}
}
