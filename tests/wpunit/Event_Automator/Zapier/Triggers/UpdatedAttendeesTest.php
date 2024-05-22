<?php

namespace TEC\Event_Automator\Zapier\Triggers;

use TEC\Event_Automator\Tests\Traits\Create_events;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use TEC\Event_Automator\Tests\Traits\With_Uopz;

class UpdatedAttendeesTest extends \Codeception\TestCase\WPTestCase {

	use Create_events;
	use Create_attendees;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear Queue.
		$queue = tribe( Updated_Attendees::class );
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
		$attendees_queue = tribe( Updated_Attendees::class );
		$this->assertEmpty( $attendees_queue->get_queue() );

		wp_insert_post( [
			'post_title'  => 'A test post',
			'post_status' => 'publish',
		] );

		$this->assertEmpty( $attendees_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_not_add_an_rsvp_attendee_to_queue_when_created() {
		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_rsvp_attendee( $event );

		$this->assertEmpty( $attendees_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_add_an_updated_rsvp_attendee_to_queue() {
		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_rsvp_attendee_updated_it( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_not_add_an_tc_attendee_to_queue_when_created() {
		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_tc_attendee( $event );

		$this->assertEmpty( $attendees_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_add_an_updated_tc_attendee_to_queue() {
		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_tc_attendee_updated_it( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_not_add_an_edd_attendee_to_queue_when_created() {
		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_edd_attendee( $event );

		$this->assertEmpty( $attendees_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_add_an_updated_edd_attendee_to_queue() {
		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_edd_attendee_updated_it( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_not_add_an_woo_attendee_to_queue_when_created() {
		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_woo_attendee( $event );

		$this->assertEmpty( $attendees_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_add_an_updated_woo_attendee_to_queue() {
		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_woo_attendee_updated_it( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_not_add_an_updated_edd_attendee_to_queue_when_no_access_created() {
		add_filter( 'tec_event_automator_zapier_enable_add_to_queue', function ( $enable_add_to_queue ) {
			return false;
		}, 11 );

		$attendees_queue = tribe( Updated_Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_edd_attendee_updated_it( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertEmpty( $queue );
	}

}
