<?php

namespace Tribe\tests\eva_integration\Power_Automate\Triggers;

use TEC\Event_Automator\Power_Automate\Triggers\Checkin;
use TEC\Event_Automator\Tests\Traits\Create_events;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use Tribe\Tests\Traits\With_Uopz;
use TEC\Tickets\Commerce\Module;
use Tribe__Tickets_Plus__Commerce__EDD__Main as EDD_Tickets;
use Tribe__Tickets_Plus__Commerce__WooCommerce__Main as Woo_Tickets;

class CheckinTest extends \Codeception\TestCase\WPTestCase {

	use Create_Events;
	use Create_Attendees;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear Queue.
		$queue = tribe( Checkin::class );
		$queue->set_queue( [] );
		add_filter( 'tec_event_automator_power_automate_enable_add_to_queue', '__return_true' );
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
		$checkin_queue = tribe( Checkin::class );
		$this->assertEmpty( $checkin_queue->get_queue() );

		wp_insert_post( [
			'post_title'  => 'A test post',
			'post_status' => 'publish',
		] );

		$this->assertEmpty( $checkin_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_add_an_rsvp_attendee_to_queue() {
		$checkin_queue = tribe( Checkin::class );
		$event         = $this->generate_event( $this->mock_date_value );
		$attendee      = $this->generate_rsvp_attendee( $event );
		tribe( 'tickets.rsvp' )->checkin( $attendee->ID, true );

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_rsvp_checkin_to_queue() {
		$checkin_queue   = tribe( Checkin::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$created_checkin = $this->generate_multiple_rsvp_attendees( $event );
		foreach ( $created_checkin as $attendee ) {
			tribe( 'tickets.rsvp' )->checkin( $attendee->ID, true );
		}

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $created_checkin as $attendee ) {
			$this->assertContains( $attendee->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_add_an_tc_attendee_to_queue() {
		$checkin_queue = tribe( Checkin::class );
		$event         = $this->generate_event( $this->mock_date_value );
		$attendee      = $this->generate_tc_attendee( $event );
		tribe( Module::class )->checkin( $attendee->ID );

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_tc_checkin_to_queue() {
		$checkin_queue   = tribe( Checkin::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$created_checkin = $this->generate_multiple_tc_attendees( $event );
		foreach ( $created_checkin as $attendee ) {
			tribe( Module::class )->checkin( $attendee->ID );
		}

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $created_checkin as $attendee ) {
			$this->assertContains( $attendee->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_add_an_edd_attendee_to_queue() {
		$checkin_queue = tribe( Checkin::class );
		$event         = $this->generate_event( $this->mock_date_value );
		$attendee      = $this->generate_edd_attendee( $event );
		tribe( EDD_Tickets::class )->checkin( $attendee->ID );

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_edd_checkin_to_queue() {
		$checkin_queue   = tribe( Checkin::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$created_checkin = $this->generate_multiple_edd_attendees( $event );
		foreach ( $created_checkin as $attendee ) {
			tribe( EDD_Tickets::class )->checkin( $attendee->ID );
		}

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $created_checkin as $attendee ) {
			$this->assertContains( $attendee->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_add_an_woo_attendee_to_queue() {
		$checkin_queue = tribe( Checkin::class );
		$event         = $this->generate_event( $this->mock_date_value );
		$attendee      = $this->generate_woo_attendee( $event );
		tribe( Woo_Tickets::class )->checkin( $attendee->ID );

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_woo_checkin_to_queue() {
		$checkin_queue   = tribe( Checkin::class );
		$event           = $this->generate_event( $this->mock_date_value );
		$created_checkin = $this->generate_multiple_woo_attendees( $event );
		foreach ( $created_checkin as $attendee ) {
			tribe( Woo_Tickets::class )->checkin( $attendee->ID );
		}

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 3, $queue );
		foreach ( $created_checkin as $attendee ) {
			$this->assertContains( $attendee->ID, $queue );
		}
	}

	/**
	 * @test
	 */
	public function should_add_an_woo_attendee_to_queue_once_per_load() {
		$checkin_queue = tribe( Checkin::class );
		$event         = $this->generate_event( $this->mock_date_value );
		$attendee      = $this->generate_woo_attendee( $event );
		tribe( Woo_Tickets::class )->checkin( $attendee->ID );
		tribe( Woo_Tickets::class )->checkin( $attendee->ID );

		$queue = $checkin_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $attendee->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_not_add_an_edd_attendee_to_queue_when_no_access_created() {
		add_filter( 'tec_event_automator_power_automate_enable_add_to_queue', function ( $enable_add_to_queue ) {
			return false;
		}, 11 );

		$checkin_queue = tribe( Checkin::class );
		$event         = $this->generate_event( $this->mock_date_value );
		$attendee      = $this->generate_edd_attendee( $event );
		tribe( EDD_Tickets::class )->checkin( $attendee->ID );

		$queue = $checkin_queue->get_queue();
		$this->assertEmpty( $queue );
	}
}
