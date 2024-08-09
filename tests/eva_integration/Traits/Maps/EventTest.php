<?php

namespace Tribe\tests\eva_integration\Traits\Maps;

use TEC\Common\StellarWP\Models\Repositories\Repository;
use TEC\Event_Automator\Tests\Traits\Create_events;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use TEC\Event_Automator\Traits\Maps\Event;
use Tribe\Tests\Traits\With_Uopz;
use Tribe\Events\Event_Status\Admin_Template;
use Tribe\Events\Event_Status\Classic_Editor;
use Tribe__Events__Main as TEC_Main;
use Tribe\Events\Models\Post_Types\Venue as Venue_Model;
use Tribe\Events\Models\Post_Types\Organizer as Organizer_Model;
use Tribe\Test\PHPUnit\Traits\With_Post_Remapping;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class EventTest extends \Codeception\TestCase\WPTestCase {

	use SnapshotAssertions;
	use With_Post_Remapping;
	use Create_events;
	use Create_attendees;
	use With_Uopz;
	use Event;

	public function setUp() {
		parent::setUp();

		// To support taxonomy term creation and assignment.
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		tribe( 'cache' )->reset();
	}

	/**
	 * @test
	 */
	public function should_map_event_id() {
		$event      = $this->generate_event( $this->mock_date_value );
		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
	}

	/**
	 * @test
	 */
	public function should_map_event_id_with_timestamp() {
		global $wpdb;

		$timestamp = '1674560040';
		$event      = $this->generate_event( $this->mock_date_value );

		$formatted_date = date( 'Y-m-d H:i:s', $timestamp );
		$formatted_date_gmt = gmdate( 'Y-m-d H:i:s', $timestamp );

		// Manually update post modified date.
		$wpdb->update(
			$wpdb->posts,
			[
			  'post_modified' => $formatted_date,
			  'post_modified_gmt' => $formatted_date_gmt
			],
			['ID' => $event->ID],
			['%s', '%s'],
			['%d']
		);

		wp_cache_flush();

		$next_event = $this->get_mapped_event( $event->ID, true );
		$event_id   = explode( '|', $next_event['id'] );

		$this->assertEquals( $event->ID, $event_id[0] );
		$this->assertEquals( '1674560040', $event_id[1] );
	}

	/**
	 * @test
	 */
	public function should_map_error_message_when_missing_id() {
		$event = $this->generate_event( $this->mock_date_value );

		// Filter the mapped event and remove the id.
		add_filter( 'tec_automator_map_event_details', function ( $next_event, $event ) {
			unset( $next_event['id'] );

			return $next_event;
		}, 10, 2 );

		wp_cache_flush();
		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( 'invalid-event-id', $next_event['id'] );
		$this->assertFalse( isset( $next_event['title'] ) );
	}

	/**
	 * @test
	 */
	public function should_map_event_as_scheduled() {
		$event      = $this->generate_event( $this->mock_date_value );
		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( 'scheduled', $next_event['event_status'] );
	}

	/**
	 * @test
	 */
	public function should_map_event_as_canceled() {
		$event  = $this->generate_event( $this->mock_date_value );
		$data   = [
			'status'        => 'canceled',
			'status-reason' => 'Because Test',
		];
		$editor = new Classic_Editor( new Admin_Template(), null );
		$editor->update_fields( $event->ID, $data );

		wp_cache_flush();
		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( 'canceled', $next_event['event_status'] );
	}

	/**
	 * @test
	 */
	public function should_map_event_as_postponed() {
		$event  = $this->generate_event( $this->mock_date_value );
		$data   = [
			'status'        => 'postponed',
			'status-reason' => 'Because Test',
		];
		$editor = new Classic_Editor( new Admin_Template(), null );
		$editor->update_fields( $event->ID, $data );

		wp_cache_flush();
		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( 'postponed', $next_event['event_status'] );
	}

	/**
	 * @test
	 */
	public function should_map_event_website() {
		$website_url = 'https://twitter.com/';
		$overrides   = [
			'_EventURL' => $website_url,
		];
		$event       = $this->generate_event( $this->mock_date_value, $overrides );

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( $website_url, $next_event['website_url'] );
	}

	/**
	 * @test
	 */
	public function should_map_eventcost() {
		$event_cost = '$10.01';
		$overrides   = [
			'_EventCost' => $event_cost,
		];
		$event       = $this->generate_event( $this->mock_date_value, $overrides );

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( $event_cost, $next_event['cost'] );
	}

	/**
	 * @test
	 */
	public function should_map_event_featured_img() {
		$image          = codecept_data_dir( 'images/featured-image.jpg' );
		$attachment_id  = $this->factory()->attachment->create_upload_object( $image );
		$attachment_url = wp_get_attachment_url( $attachment_id );

		$event = $this->generate_event( $this->mock_date_value );
		set_post_thumbnail( $event->ID, $attachment_id );

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( $attachment_url, $next_event['featured_image_url'] );
	}

	/**
	 * @test
	 */
	public function should_map_event_categories() {
		$cat        = $this->factory()->term->create( [ 'taxonomy' => TEC_Main::TAXONOMY, 'slug' => 'cat-1', 'name' => 'test-1', 'description' => 'description-test-1' ] );
		$cat_parent = $this->factory()->term->create( [ 'taxonomy' => TEC_Main::TAXONOMY, 'slug' => 'cat-parent', 'name' => 'test-parent', 'description' => 'description-test-parent' ] );
		$cat_2      = $this->factory()->term->create( [ 'taxonomy' => TEC_Main::TAXONOMY, 'slug' => 'cat-2', 'name' => 'test-2', 'description' => 'description-test-2', 'parent' => $cat_parent ] );

		$overrides  = [
			'category' => [ $cat, $cat_2 ],
		];
		$event      = $this->generate_event( $this->mock_date_value, $overrides );
		$next_event = $this->get_mapped_event( $event->ID );

		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( [ $cat, $cat_2 ], wp_get_object_terms( $event->ID, TEC_Main::TAXONOMY, [ 'fields' => 'ids' ] ) );
		$this->assertMatchesJsonSnapshot( json_encode( $next_event['category'], JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_map_event_tags() {
		$tag   = $this->factory()->tag->create( [ 'slug' => 'tag-1', 'name' => 'test-1', 'description' => 'description-test-1' ] );
		$tag_2 = $this->factory()->tag->create( [ 'slug' => 'tag-2', 'name' => 'test-2', 'description' => 'description-test-2' ] );

		$overrides  = [
			'tag' => [ $tag, $tag_2 ],
		];
		$event      = $this->generate_event( $this->mock_date_value, $overrides );
		$next_event = $this->get_mapped_event( $event->ID );

		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( [ $tag, $tag_2 ], wp_get_object_terms( $event->ID, 'post_tag', [ 'fields' => 'ids' ] ) );
		$this->assertMatchesJsonSnapshot( json_encode( $next_event['tag'], JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_map_event_venues() {
		$mock       = $this->get_mock_venue( 'venues/1.json' );
		$venue      = Venue_Model::from_post( $mock )->to_post();
		$args       = [
			'start_date' => '2018-01-01 09:00:00',
			'end_date'   => '2018-01-01 11:00:00',
			'timezone'   => 'Europe/Paris',
			'title'      => 'A test event',
			'venue'      => $venue->ID,
		];
		$event      = tribe_events()->set_args( $args )->create();
		$next_event = $this->get_mapped_event( $event->ID );

		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertMatchesJsonSnapshot( json_encode( $next_event['venue'], JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 * @skip The decorator is failing with multiple ones added to the same property. Need to investigate.
	 */
	public function should_map_event_organizers() {
		$mock       = $this->get_mock_organizer( 'organizers/1.json' );
		$organizer  = Organizer_Model::from_post( $mock )->to_post();
		$args       = [
			'start_date' => '2018-01-01 09:00:00',
			'end_date'   => '2018-01-01 11:00:00',
			'timezone'   => 'Europe/Paris',
			'title'      => 'A test event',
			'organizer'  => $organizer,
		];
		$event      = tribe_events()->set_args( $args )->create();
		$next_event = $this->get_mapped_event( $event->ID );

		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertMatchesJsonSnapshot( json_encode( $next_event['organizers'], JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_map_multiple_event_organizers() {
		$mock_1      = $this->get_mock_organizer( 'organizers/1.json' );
		$organizer_1 = Organizer_Model::from_post( $mock_1 )->to_post();

		$mock_2      = $this->get_mock_organizer( 'organizers/2.json' );
		$organizer_2 = Organizer_Model::from_post( $mock_2 )->to_post();
		$args        = [
			'start_date' => '2018-01-01 09:00:00',
			'end_date'   => '2018-01-01 11:00:00',
			'timezone'   => 'Europe/Paris',
			'title'      => 'A test event',
			'organizer'  => [ $organizer_1->ID, $organizer_2->ID ],
		];
		$event       = tribe_events()->set_args( $args )->create();
		$next_event  = $this->get_mapped_event( $event->ID );

		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertMatchesJsonSnapshot( json_encode( $next_event['organizers'], JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 * @skip Tickets and RSVP are generated but not included in the event object.
	 */
	public function should_map_event_with_edd_tickets() {
		$event  = $this->generate_event( $this->mock_date_value );
		$edd_id = $this->generate_edd_ticket_for_event( $event->ID );

		wp_cache_flush();

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( true, $next_event['tickets']['has_ticket'] );
		$this->assertEquals( false, $next_event['tickets']['has_rsvp'] );
		$this->assertEquals( true, $next_event['tickets']['in_date_range'] );
		$this->assertEquals( false, $next_event['tickets']['sold_out'] );
		$this->assertEquals( $edd_id, $next_event['tickets']['tickets'][0]['id'] );
	}

	/**
	 * @test
	 * @skip Tickets and RSVP are generated but not included in the event object.
	 */
	public function should_map_event_with_tc_tickets() {
		$event  = $this->generate_event( $this->mock_date_value );
		$woo_id = $this->generate_tc_ticket_for_event( $event->ID );

		wp_cache_flush();

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( true, $next_event['tickets']['has_ticket'] );
		$this->assertEquals( false, $next_event['tickets']['has_rsvp'] );
		$this->assertEquals( true, $next_event['tickets']['in_date_range'] );
		$this->assertEquals( false, $next_event['tickets']['sold_out'] );
		$this->assertEquals( $woo_id, $next_event['tickets']['tickets'][0]['id'] );
	}

	/**
	 * @test
	 * @skip Tickets and RSVP are generated but not included in the event object.
	 */
	public function should_map_event_with_woo_tickets() {
		$event  = $this->generate_event( $this->mock_date_value );
		$woo_id = $this->generate_woo_ticket_for_event( $event->ID );

		wp_cache_flush();

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( true, $next_event['tickets']['has_ticket'] );
		$this->assertEquals( false, $next_event['tickets']['has_rsvp'] );
		$this->assertEquals( true, $next_event['tickets']['in_date_range'] );
		$this->assertEquals( false, $next_event['tickets']['sold_out'] );
		$this->assertEquals( $woo_id, $next_event['tickets']['tickets'][0]['id'] );
	}

	/**
	 * @test
	 * @skip Tickets and RSVP are generated but not included in the event object.
	 */
	public function should_map_event_with_multiple_woo_tickets() {
		$event  = $this->generate_event( $this->mock_date_value );
		$woo_id = $this->generate_woo_ticket_for_event( $event->ID );
		$woo_id_2 = $this->generate_woo_ticket_for_event( $event->ID );

		wp_cache_flush();

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( true, $next_event['tickets']['has_ticket'] );
		$this->assertEquals( false, $next_event['tickets']['has_rsvp'] );
		$this->assertEquals( true, $next_event['tickets']['in_date_range'] );
		$this->assertEquals( false, $next_event['tickets']['sold_out'] );
		$this->assertEquals( $woo_id, $next_event['tickets']['tickets'][0]['id'] );
		$this->assertEquals( $woo_id_2, $next_event['tickets']['tickets'][1]['id'] );
	}

	/**
	 * @test
	 * @skip Tickets and RSVP are generated but not included in the event object.
	 */
	public function should_map_event_with_rsvps() {
		$event   = $this->generate_event( $this->mock_date_value );
		$rsvp_id = $this->generate_rsvp_for_event( $event->ID );

		wp_cache_flush();
		//@todo troubleshoot the event object not including any tickets or RSVP
		///$repository = tribe( \Tribe__Events__Repositories__Event::class );
		//$repository->flush();
		//$tickets = \Tribe__Tickets__Tickets::get_event_tickets( $event->ID );

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( false, $next_event['tickets']['has_ticket'] );
		$this->assertEquals( true, $next_event['tickets']['has_rsvp'] );
		$this->assertEquals( true, $next_event['tickets']['in_date_range'] );
		$this->assertEquals( false, $next_event['tickets']['sold_out'] );
		$this->assertEquals( $rsvp_id, $next_event['tickets']['rsvps'][0]['id'] );
	}

	/**
	 * @test
	 * @skip Tickets and RSVP are generated but not included in the event object.
	 */
	public function should_map_event_with_rsvps_and_tickets() {
		$event   = $this->generate_event( $this->mock_date_value );
		$rsvp_id = $this->generate_rsvp_for_event( $event->ID );
		$woo_id  = $this->generate_woo_ticket_for_event( $event->ID );

		wp_cache_flush();

		$next_event = $this->get_mapped_event( $event->ID );
		$this->assertEquals( $event->ID, $next_event['id'] );
		$this->assertEquals( true, $next_event['tickets']['has_ticket'] );
		$this->assertEquals( true, $next_event['tickets']['has_rsvp'] );
		$this->assertEquals( true, $next_event['tickets']['in_date_range'] );
		$this->assertEquals( false, $next_event['tickets']['sold_out'] );
		$this->assertEquals( $woo_id, $next_event['tickets']['tickets'][0]['id'] );
		$this->assertEquals( $rsvp_id, $next_event['tickets']['rsvps'][0]['id'] );
	}

	/**
	 * @test
	 * @skip Tickets and RSVP are generated but not included in the event object.
	 */
	public function should_map_event_with_correct_type() {
		$event      = $this->generate_event( $this->mock_date_value );
		$next_event = $this->get_mapped_event( $event->ID );

		$this->assertIsString( $next_event['id'] );
		$this->assertIsInt( $next_event['multi_day'] );
		$this->assertIsInt( $next_event['duration'] );
	}
}
