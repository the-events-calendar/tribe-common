<?php

namespace Tribe\tests\eva_integration\Zapier\Triggers;

use TEC\Event_Automator\Tests\Traits\Create_Events;
use TEC\Event_Automator\Zapier\Triggers\New_Events;
use Tribe\Tests\Traits\With_Uopz;

class NewEventTest extends \Codeception\TestCase\WPTestCase {

	use Create_Events;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear Queue.
		$queue = tribe( New_Events::class );
		$queue->set_queue( [] );
		add_filter( 'tec_event_automator_zapier_enable_add_to_queue', '__return_true' );
	}

	/**
	 * @test
	 */
	public function should_not_add_a_post_to_queue() {
		$new_events_queue = tribe( New_Events::class );
		$this->assertEmpty( $new_events_queue->get_queue() );

		wp_insert_post( [
			'post_title'  => 'A test post',
			'post_status' => 'publish',
		] );

		$this->assertEmpty( $new_events_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_not_add_a_draft_event_to_queue() {
		$new_events_queue = tribe( New_Events::class );
		$event            = $this->generate_event( $this->mock_date_value, [ 'status' => 'draft' ] );

		$this->assertEmpty( $new_events_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_not_add_an_event_to_queue_when_run_once_meta_is_found() {
		$new_events_queue = tribe( New_Events::class );
		$event            = $this->generate_event( $this->mock_date_value, [ '_tec_zapier_queue_new_event_run_once' => true, ] );

		$this->assertEmpty( $new_events_queue->get_queue() );
	}

	/**
	 * @test
	 */
	public function should_add_an_event_to_queue() {
		$new_events_queue = tribe( New_Events::class );
		$event            = $this->generate_event( $this->mock_date_value );

		$queue = $new_events_queue->get_queue();
		$this->assertNotEmpty( $queue );
		$this->assertCount( 1, $queue );
		$this->assertEquals( $event->ID, $queue[0] );
	}

	/**
	 * @test
	 */
	public function should_add_multiple_events_to_queue() {
		$new_events_queue = tribe( New_Events::class );
		$events           = $this->generate_multiple_events( $this->mock_date_value );

		$queue = $new_events_queue->get_queue();
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
		add_filter( 'tec_event_automator_zapier_enable_add_to_queue', function ( $enable_add_to_queue ) {
			return false;
		}, 11 );

		$new_events_queue = tribe( New_Events::class );
		$event            = $this->generate_event( $this->mock_date_value );

		$queue = $new_events_queue->get_queue();
		$this->assertEmpty( $queue );
	}
}
