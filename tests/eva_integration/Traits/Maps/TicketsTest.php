<?php

namespace Tribe\tests\eva_integration\Traits\Maps;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Tests\Traits\Create_Attendees;
use TEC\Event_Automator\Tests\Traits\Create_Events;
use TEC\Event_Automator\Traits\Maps\Ticket;
use Tribe\Tests\Traits\With_Uopz;
use Tribe\Test\PHPUnit\Traits\With_Post_Remapping;

class TicketsTest extends \Codeception\TestCase\WPTestCase {

	use SnapshotAssertions;
	use With_Post_Remapping;
	use Create_Events;
	use Create_Attendees;
	use With_Uopz;
	use Ticket;

	/*
	 * Ticket meta values.
	 *
	 * @since 6.0.0
	 *
	 * @var array<string> The values to use for ticket meta.
	 */
	protected array $field_values = [
		'dropdown-for-tests'                              => '2nd Option',
		'text-field'                                      => 'Test',
		'radio-field'                                     => 'radio2',
		'checkbox-field_3f268a535d76578d48af2d98a9b5aed7' => 'check1',
		'checkbox-field_e5058a61e22656b980153c4e10b46fa6' => 'check3',
		'dropdown'                                        => 'drop1',
		'email-field'                                     => 'support@tec.com',
		'telephone'                                       => '2020222222',
		'url-field'                                       => 'https:tec.com',
		'birthday-field'                                  => '2013-02-15',
		'date-field'                                      => '2023-07-26',
	];

	public function setUp() {
		parent::setUp();

		// To support taxonomy term creation and assignment.
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		tribe( 'cache' )->reset();
	}

	/**
	 * @test
	 */
	public function should_map_ticket_id() {
		$event     = $this->generate_event( $this->mock_date_value );
		$ticket_id = $this->generate_rsvp_for_event( $event->ID );

		$next_ticket = $this->get_mapped_ticket( $ticket_id );
		$this->assertEquals( $ticket_id, $next_ticket['id'] );
	}

	/**
	 * @test
	 */
	public function should_map_rsvp_ticket_data() {
		$event     = $this->generate_event( $this->mock_date_value );
		$ticket_id = $this->generate_rsvp_for_event( $event->ID );
		$this->save_attendee_meta_to_ticket( $ticket_id );
		$next_ticket = $this->get_mapped_ticket( $ticket_id );

		$this->assertEquals( $ticket_id, $next_ticket['id'] );
		$this->assertEquals( $event->ID, $next_ticket['event_id'] );
		$this->assertEquals( 'rsvp', $next_ticket['provider'] );
		$this->assertEquals( true, $next_ticket['supports_attendee_information'] );
	}

	/**
	 * @test
	 */
	public function should_map_tc_ticket_meta() {
		$event     = $this->generate_event( $this->mock_date_value );
		$ticket_id = $this->generate_tc_ticket_for_event( $event->ID );
		$next_ticket = $this->get_mapped_ticket( $ticket_id );

		$this->assertEquals( $ticket_id, $next_ticket['id'] );
		$this->assertEquals( $event->ID, $next_ticket['event_id'] );
		$this->assertEquals( 'tc', $next_ticket['provider'] );
		$this->assertEquals( false, $next_ticket['supports_attendee_information'] );
	}

	/**
	 * @test
	 */
	public function should_map_edd_ticket_meta() {
		$event     = $this->generate_event( $this->mock_date_value );
		$ticket_id = $this->generate_edd_ticket_for_event( $event->ID );
		$this->save_attendee_meta_to_ticket( $ticket_id );
		$next_ticket = $this->get_mapped_ticket( $ticket_id );

		$this->assertEquals( $ticket_id, $next_ticket['id'] );
		$this->assertEquals( $event->ID, $next_ticket['event_id'] );
		$this->assertEquals( 'edd', $next_ticket['provider'] );
		$this->assertEquals( true, $next_ticket['supports_attendee_information'] );
	}

	/**
	 * @test
	 */
	public function should_map_woo_ticket_meta() {
		$event     = $this->generate_event( $this->mock_date_value );
		$ticket_id = $this->generate_woo_ticket_for_event( $event->ID );
		$next_ticket = $this->get_mapped_ticket( $ticket_id );

		$this->assertEquals( $ticket_id, $next_ticket['id'] );
		$this->assertEquals( $event->ID, $next_ticket['event_id'] );
		$this->assertEquals( 'woo', $next_ticket['provider'] );
		$this->assertEquals( false, $next_ticket['supports_attendee_information'] );
	}
}
