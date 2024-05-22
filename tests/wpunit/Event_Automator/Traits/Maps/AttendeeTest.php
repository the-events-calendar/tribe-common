<?php

namespace TEC\Event_Automator\Traits\Maps;

use TEC\Event_Automator\Tests\Traits\Create_events;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use TEC\Event_Automator\Tests\Traits\With_Uopz;
use Tribe\Test\PHPUnit\Traits\With_Post_Remapping;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class AttendeeTest extends \Codeception\TestCase\WPTestCase {

	use SnapshotAssertions;
	use With_Post_Remapping;
	use Create_events;
	use Create_attendees;
	use With_Uopz;
	use Attendees;

	/*
	 * Attendee meta values.
	 *
	 * @since TBD
	 *
	 * @var array<string> The values to use for attendee meta.
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
	public function should_map_attendee_id() {
		$event           = $this->generate_event( $this->mock_date_value );
		$attendee        = $this->generate_rsvp_attendee( $event );

		$next_attendee = $this->get_mapped_attendee( $attendee->ID );
		$this->assertEquals( $attendee->ID, $next_attendee['id'] );
	}

	/**
	 * @test
	 */
	public function should_map_rsvp_attendee_meta() {
		$event           = $this->generate_event( $this->mock_date_value );
		$overrides = [
			'full_name' => 'Meta\'s Attendee',
			'attendee_meta' => $this->field_values,
		];
		$attendee = $this->generate_rsvp_attendee( $event, $overrides, true );
		$next_attendee = $this->get_mapped_attendee( $attendee->ID );

		$this->assertEquals( $attendee->ID, $next_attendee['id'] );
		$this->assertEquals( "Meta's Attendee", $next_attendee['holder_name'] );
		$this->assertEquals( "Meta's Attendee", $next_attendee['purchaser_name'] );
		$this->assertIsInt( $next_attendee['id'] );
		$this->assertIsString( $next_attendee['ticket_id'] );
		$this->assertIsString( $next_attendee['ticket_product_id'] );
		$this->assertIsString( $next_attendee['order_id'] );
		foreach ( $next_attendee['attendee_meta'] as $field ) {
			$this->assertArrayHasKey( 'attendee_meta_id', $field );
			$this->assertArrayHasKey( 'attendee_meta_name', $field );
			$this->assertArrayHasKey( 'attendee_meta_value', $field );
			$this->assertIsArray( $field['attendee_meta_value'] );

			foreach ( $field['attendee_meta_value'] as $value ) {
				$this->assertIsString( $value );
			}
		}
	}

	/**
	 * @test
	 */
	public function should_map_edd_attendee_meta() {
		$event           = $this->generate_event( $this->mock_date_value );
		$overrides = [
			'full_name' => 'Meta\'s Attendee',
			'attendee_meta' => $this->field_values,
		];
		$attendee = $this->generate_edd_attendee( $event, $overrides, true );
		$next_attendee = $this->get_mapped_attendee( $attendee->ID );

		$this->assertEquals( $attendee->ID, $next_attendee['id'] );
		$this->assertEquals( "Meta's Attendee", $next_attendee['holder_name'] );
		$this->assertIsInt( $next_attendee['id'] );
		$this->assertIsString( $next_attendee['ticket_id'] );
		$this->assertIsString( $next_attendee['ticket_product_id'] );
		$this->assertIsString( $next_attendee['order_id'] );
		foreach ( $next_attendee['attendee_meta'] as $field ) {
			$this->assertArrayHasKey( 'attendee_meta_id', $field );
			$this->assertArrayHasKey( 'attendee_meta_name', $field );
			$this->assertArrayHasKey( 'attendee_meta_value', $field );
			$this->assertIsArray( $field['attendee_meta_value'] );

			foreach ( $field['attendee_meta_value'] as $value ) {
				$this->assertIsString( $value );
			}
		}
	}

	/**
	 * @test
	 */
	public function should_map_woo_attendee_meta() {
		$event           = $this->generate_event( $this->mock_date_value );
		$overrides = [
			'full_name' => 'Meta\'s Attendee',
			'attendee_meta' => $this->field_values,
		];
		$attendee = $this->generate_woo_attendee( $event, $overrides, true );
		$next_attendee = $this->get_mapped_attendee( $attendee->ID );

		$this->assertEquals( $attendee->ID, $next_attendee['id'] );
		$this->assertEquals( "Meta's Attendee", $next_attendee['holder_name'] );
		$this->assertEquals( "Meta's Attendee", $next_attendee['purchaser_name'] );
		$this->assertIsInt( $next_attendee['id'] );
		$this->assertIsString( $next_attendee['ticket_id'] );
		$this->assertIsString( $next_attendee['ticket_product_id'] );
		$this->assertIsString( $next_attendee['order_id'] );
		foreach ( $next_attendee['attendee_meta'] as $field ) {
			$this->assertArrayHasKey( 'attendee_meta_id', $field );
			$this->assertArrayHasKey( 'attendee_meta_name', $field );
			$this->assertArrayHasKey( 'attendee_meta_value', $field );
			$this->assertIsArray( $field['attendee_meta_value'] );

			foreach ( $field['attendee_meta_value'] as $value ) {
				$this->assertIsString( $value );
			}
		}
	}
}
