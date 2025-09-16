<?php
/**
 * Provides methods map ticket details.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps;
 */

namespace TEC\Event_Automator\Traits\Maps;

/**
 * Trait Event
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps;
 */
trait Ticket {

	use Attendees;

	/**
	 * Get the ticket details mapped for 3rd party services.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int    $post_id    The post id for an ticket.
	 * @param string $service_id The service id used to modify the mapped event details.
	 *
	 * @return array<string|mixed> An array of ticket details or false if not a post object.
	 */
	protected function get_mapped_ticket( int $post_id, string $service_id = '' ) {
		if ( ! function_exists( 'tribe_tickets' ) ) {
			return [];
		}

		$repository = tribe( 'tickets.rest-v1.repository' );
		$ticket     = $repository->get_ticket_data( $post_id );
		if ( empty( $ticket['provider'] ) ) {
			return [];
		}

		$event       = tribe_events_get_ticket_event( $ticket['id'] );
		$event_id    = 0;
		$event_title = '';
		if ( isset( $event->ID ) ) {
			$event_id    = $event->ID;
			$event_title = html_entity_decode( get_the_title( $event->ID ) );
		}

		$next_ticket = [
			// Ticket.
			'id'                            => (int) $ticket['id'],
			'title'                         => html_entity_decode( $ticket['title'] ?? '' ),
			'description'                   => $ticket['description'] ?? '',
			'capacity'                      => (int) $ticket['capacity'] ?? 0,
			'capacity_details'              => $ticket['capacity_details'] ?? [],
			'provider'                      => $ticket['provider'] ?? '',
			'type'                          => $ticket['type'] ?? '',
			'status'                        => $ticket['status'] ?? '',
			'author'                        => (int) $ticket['author'] ?? 0,
			'image'                         => strval( $ticket['author'] ?? '' ),
			'is_available'                  => (bool) $ticket['is_available'] ?? false,
			'available_from'                => $ticket['available_from'] ?? '',
			'available_from_details'        => $ticket['available_from_details'] ?? [],
			'available_until'               => $ticket['available_until'] ?? '',
			'available_until_details'       => $ticket['available_until_details'] ?? [],
			'price_suffix'                  => $ticket['price_suffix'] ?? '',
			'cost'                          => strval( $ticket['cost'] ?? '' ),
			'cost_details'                  => $ticket['cost_details'] ?? [],
			'checkin'                       => $ticket['checkin'] ?? [],
			'attendees'                     => $this->get_mapped_attendees( $ticket ),
			'iac'                           => $ticket['iac'] ?? '',
			'supports_attendee_information' => (bool) $ticket['supports_attendee_information'] ?? false,
			'requires_attendee_information' => (bool) $ticket['requires_attendee_information'] ?? false,
			'attendee_information_fields'   => $this->get_ticket_information_fields( $ticket ),

			// Event.
			'event_id'                      => (int) $event_id,
			'event_title'                   => $event_title,
		];

		/**
		 * Filters the ticket details sent to a 3rd party.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string|mixed> $next_ticket An array of ticket mapped details.
		 * @param array<string|mixed> $ticket      An array of raw ticket details.
		 * @param string              $service_id    The service id used to modify the mapped event details.
		 */
		$next_ticket = apply_filters( 'tec_automator_map_ticket_details', $next_ticket, $ticket, $service_id );
		// Zapier only requires an id field, if that is empty send a generic invalid message.
		if ( empty( $next_ticket['id'] ) ) {
			return [ 'id' => 'invalid-ticket-id.' ];
		}

		return $next_ticket;
	}

	/**
	 * Get Ticket Information Fields.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|mixed> $ticket An array of ticket details.
	 *
	 * @return array<string|mixed> $meta_array  Meta attendee array.
	 */
	public function get_mapped_attendees( $ticket ) {
		if ( empty( $ticket['attendees'] ) ) {
			return [];
		}

		$attendees = [];
		foreach ( $ticket['attendees'] as $attendee ) {
			if ( empty( $attendee['id'] ) ) {
				continue;
			}

			$next_attendee = $this->get_mapped_attendee( $attendee['id'] );
			if ( empty( $next_attendee['id'] ) ) {
				continue;
			}

			$attendees[] = $next_attendee;
		}

		return $attendees;
	}

	/**
	 * Get Ticket Information Fields.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|mixed> $ticket An array of ticket details.
	 *
	 * @return array<string|mixed> $meta_array  Meta attendee array.
	 */
	public function get_ticket_information_fields( $ticket ) {
		if ( empty( $ticket['attendee_information_fields'] ) ) {
			return [];
		}

		$information_array = [];
		foreach ( $ticket['attendee_information_fields'] as $item ) {
			if ( empty( $item['slug'] ) ) {
				continue;
			}

			$information_array[] = [
				'attendee_information_slug'     => $item['slug'],
				'attendee_information_type'     => $item['type'],
				'attendee_information_required' => (bool) $item['required'] ?? false,
				'attendee_information_label'    => $item['label'],
				'attendee_information_extra'    => (array) $item['extra'] ?? [],
			];
		}

		return $information_array;
	}
}
