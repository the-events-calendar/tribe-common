<?php

namespace Tribe\tests\eva_integration\Zapier\Triggers;

use TEC\Event_Automator\Tests\Traits\Create_Events;
use TEC\Event_Automator\Tests\Traits\Create_Attendees;
use TEC\Event_Automator\Zapier\Triggers\Attendees;
use Tribe\Tests\Traits\With_Uopz;

class AttendeesTest extends \Codeception\TestCase\WPTestCase {

	use Create_Events;
	use Create_Attendees;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear Queue.
		$queue = tribe( Attendees::class );
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
		$attendees_queue = tribe( Attendees::class );
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
	public function should_not_add_an_rsvp_attendee_to_queue_when_run_once_meta_is_found() {
		$attendees_queue = tribe( Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_rsvp_attendee( $event, [ '_tec_zapier_queue_attendee_run_once' => true, ] );

		$this->assertEmpty( $attendees_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_add_an_rsvp_attendee_to_queue() {
		$attendees_queue = tribe( Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_rsvp_attendee( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_rsvp_attendees_to_queue() {
		$attendees_queue   = tribe( Attendees::class );
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_rsvp_attendees( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $created_attendees as $key => $attendee ) {
			$this->assertContains( $attendee->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_add_an_tc_attendee_to_queue() {
		$attendees_queue = tribe( Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_tc_attendee( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_tc_attendees_to_queue() {
		$attendees_queue   = tribe( Attendees::class );
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_tc_attendees( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $created_attendees as $key => $attendee ) {
			$this->assertContains( $attendee->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_add_an_edd_attendee_to_queue() {
		$attendees_queue = tribe( Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_edd_attendee( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_edd_attendees_to_queue() {
		$attendees_queue   = tribe( Attendees::class );
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_edd_attendees( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $created_attendees as $key => $attendee ) {
			$this->assertContains( $attendee->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_add_an_woo_attendee_to_queue() {
		$attendees_queue = tribe( Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_woo_attendee( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_woo_attendees_to_queue() {
		$attendees_queue   = tribe( Attendees::class );
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_woo_attendees( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $created_attendees as $attendee ) {
			$this->assertContains( $attendee->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_not_add_an_woo_attendee_to_queue_when_no_access_created() {
		add_filter( 'tec_event_automator_zapier_enable_add_to_queue', function ( $enable_add_to_queue ) {
			return false;
		}, 11 );

		$attendees_queue = tribe( Attendees::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_woo_attendee( $event );

		$queue = $attendees_queue->get_queue();
		$this->assertEmpty( $queue );
	}
}
