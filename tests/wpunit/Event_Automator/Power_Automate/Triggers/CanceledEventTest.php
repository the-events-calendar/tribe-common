<?php

namespace TEC\Event_Automator\Power_Automate\Triggers;

use TEC\Event_Automator\Tests\Traits\Create_events;
use Tribe\Tests\Traits\With_Uopz;

class CanceledEventTest extends \Codeception\TestCase\WPTestCase {

	use Create_events;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear Queue.
		$queue = tribe( Canceled_Events::class );
		$queue->set_queue( [] );
	}

	/**
	 * @test
	 */
	public function should_not_add_a_draft_event_to_queue() {
		$canceled_events_queue = tribe( Canceled_Events::class );
		$this->generate_event_and_update_event_status( $this->mock_date_value, [ 'status' => 'draft' ] );

		$this->assertEmpty( $canceled_events_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_not_add_postponed_event_to_queue() {
		$canceled_events_queue = tribe( Canceled_Events::class );
		$this->generate_event_and_update_event_status( $this->mock_date_value, [], 'postponed' );

		$this->assertEmpty( $canceled_events_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_not_add_scheduled_event_to_queue() {
		$canceled_events_queue = tribe( Canceled_Events::class );
		$this->generate_event_and_update_event_status( $this->mock_date_value, [], 'scheduled' );

		$this->assertEmpty( $canceled_events_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_add_an_event_to_queue() {
		$canceled_events_queue = tribe( Canceled_Events::class );
		$event                 = $this->generate_event_and_update_event_status( $this->mock_date_value );

		$queue = $canceled_events_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $event->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_events_to_queue() {
		$canceled_events_queue = tribe( Canceled_Events::class );
		$events                = $this->generate_multiple_events_and_update_event_status( $this->mock_date_value );

		$queue = $canceled_events_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $events as $event ) {
			$this->assertContains( $event->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_not_add_an_event_to_queue_when_no_access_created() {
		add_filter( 'tec_event_automator_power_automate_enable_add_to_queue', function ( $enable_add_to_queue ) {
			return false;
		}, 11 );

		$canceled_events_queue = tribe( Canceled_Events::class );
		$event                 = $this->generate_event_and_update_event_status( $this->mock_date_value );

		$queue = $canceled_events_queue->get_queue();
		$this->assertEmpty( $queue );
	}
}
