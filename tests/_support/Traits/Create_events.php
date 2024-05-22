<?php

namespace TEC\Event_Automator\Tests\Traits;

use Tribe\Events\Event_Status\Admin_Template;
use Tribe\Events\Event_Status\Classic_Editor;
use Tribe__Utils__Array as Arr;

trait Create_events {

	protected function generate_event( $mock_date, $overrides = [] ) {
		$timezone_string = 'America/New_York';
		$timezone        = new \DateTimeZone( $timezone_string );
		update_option( 'timezone_string', $timezone_string );
		$now = new \DateTimeImmutable( $mock_date, $timezone );
		$status = esc_attr( Arr::get( $overrides, 'status', 'publish' ) );

		$args = [
			'start_date' => $now->setTime( 10, 0 ),
			'timezone'   => $timezone,
			'duration'   => 3 * HOUR_IN_SECONDS,
			'title'      => 'Test Event',
			'status'     => $status,
		];

		$explicit_keys = [
			'status',
		];
		$meta_input_overrides = array_diff_key( $overrides, array_combine( $explicit_keys, $explicit_keys ) );

		$args = array_merge( $args, $meta_input_overrides );

		return tribe_events()->set_args( $args )->create();
	}

	protected function generate_multiple_events( $mock_date ) {
		$timezone_string = 'America/New_York';
		$timezone        = new \DateTimeZone( $timezone_string );
		update_option( 'timezone_string', $timezone_string );
		$now = new \DateTimeImmutable( $mock_date, $timezone );
		return array_map(
			static function ( $i ) use ( $now, $timezone ) {
				return tribe_events()->set_args(
					[
						'start_date' => $now->setTime( 10 + $i, 0 ),
						'timezone'   => $timezone,
						'duration'   => 3 * HOUR_IN_SECONDS,
						'title'      => 'Test Event - ' . $i,
						'status'     => 'publish',
					]
				)->create();
			},
			range( 1, 3 )
		);
	}

	/**
	 * Generate an event and set update event status.
	 *
	 * @since TBD
	 *
	 * @param string        $mock_date A date string to use to create an event.
	 * @param array<string> $overrides An optional array of overrides to generate events.
	 * @param string        $status    An event status to set the event to, default is canceled.
	 *
	 * @return \WP_Post An array of event post objects, as decorated by the `tribe_get_event` function.
	 */
	protected function generate_event_and_update_event_status( $mock_date, $overrides = [], $status = 'canceled' ) {
		$event  = $this->generate_event( $mock_date, $overrides );
		$data   = [
			'status'        => $status,
			'status-reason' => 'Because Test',
		];
		$editor = new Classic_Editor( new Admin_Template(), null );
		$editor->update_fields( $event->ID, $data );

		return $event;
	}

	/**
	 * Generate multiple events and set update event status.
	 *
	 * @since TBD
	 *
	 * @param string        $mock_date A date string to use to create an event.
	 * @param array<string> $overrides An optional array of overrides to generate events.
	 * @param string        $status    An event status to set the event to, default is canceled.
	 *
	 * @return array<\WP_Post> An array of event post objects, as decorated by the `tribe_get_event` function.
	 */
	protected function generate_multiple_events_and_update_event_status( $mock_date, $overrides = [], $status = 'canceled' ) {
		$events = $this->generate_multiple_events( $mock_date, $overrides );
		$data   = [
			'status'        => $status,
			'status-reason' => 'Because Test',
		];
		$editor = new Classic_Editor( new Admin_Template(), null );
		foreach ( $events as $key => $event ) {
			$editor->update_fields( $event->ID, $data );
		}

		return $events;
	}

	/**
	 * Get the details to update an event.
	 *
	 * @since TBD
	 *
	 * @return array<\DateTime> An array of datetime objects.
	 */
	protected function get_update_event_details(): array {
		$start                = '2018-01-15 10:00:00';
		$start_date           = new \DateTime( $start, new \DateTimeZone( 'America/New_York' ) );
		$three_hours_interval = new \DateInterval( 'PT1H' );
		$end_date             = clone $start_date;
		$end_date->add( $three_hours_interval );

		return array( $start_date, $end_date );
	}

	/**
	 * Generate an event and update it so it is added to the update queue.
	 *
	 * @since TBD
	 *
	 * @param string        $mock_date A date string to use to create an event.
	 * @param array<string> $overrides An optional array of overrides to generate events.
	 * @param array<string> $updates An optional array of updates to generate events.
	 *
	 * @return \WP_Post An array of event post objects, as decorated by the `tribe_get_event` function.
	 */
	protected function generate_event_and_update_it( $mock_date, $overrides = [], $updates = [] ) {
		$event = $this->generate_event( $mock_date, $overrides );
		list( $start_date, $end_date ) = $this->get_update_event_details();

		// Move the start forward 1 hour and the end by 2.
		tribe_events()
			->where( 'post__in', [ $event->ID ] )
			->set( 'start_date', $start_date->add( new \DateInterval( 'PT1H' ) )->format( 'Y-m-d H:i:s' ) )
			->set( 'end_date', $end_date->add( new \DateInterval( 'PT2H' ) )->format( 'Y-m-d H:i:s' ) )
			->save();

		return $event;
	}

	/**
	 * Generate multiple events and update them to add to the update queue.
	 *
	 * @since TBD
	 *
	 * @param string        $mock_date A date string to use to create an event.
	 * @param array<string> $overrides An optional array of overrides to generate events.
	 * @param string        $status    An event status to set the event to, default is canceled.
	 *
	 * @return array<\WP_Post> An array of event post objects, as decorated by the `tribe_get_event` function.
	 */
	protected function generate_multiple_events_and_update_them( $mock_date, $overrides = [], $status = 'canceled' ) {
		$events = $this->generate_multiple_events( $mock_date, $overrides );
		list( $start_date, $end_date ) = $this->get_update_event_details();

		foreach ( $events as $key => $event ) {
			// Move the start forward 1 hour and the end by 2.
			tribe_events()
				->where( 'post__in', [ $event->ID ] )
				->set( 'start_date', $start_date->add( new \DateInterval( 'PT1H' ) )->format( 'Y-m-d H:i:s' ) )
				->set( 'end_date', $end_date->add( new \DateInterval( 'PT2H' ) )->format( 'Y-m-d H:i:s' ) )
				->save();
		}

		return $events;
	}
}