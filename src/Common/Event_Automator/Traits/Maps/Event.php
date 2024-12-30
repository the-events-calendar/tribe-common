<?php
/**
 * Provides a map of single event details.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps;
 */

namespace TEC\Event_Automator\Traits\Maps;

use WP_Post;
use WP_Term;
use WP_Error;
use \Tribe__Timezones as Timezones;
use Tribe__Events__Main as TEC;

/**
 * Trait Event
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps;
 */
trait Event {

	/**
	 * Get the event details mapped for 3rd party services.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @since 6.0.0 Migrated to Common from Event Automator - Service ID.
	 *
	 * @param int     $post_id    The post id for an event.
	 * @param boolean $unique_id  Whether to add a unique id, Zapier only updates events per
	 *                            Zap once with the same id, this enables multiple updates.
	 * @param string  $service_id The service id used to modify the mapped event details.
	 *
	 * @return array<string|mixed> An array of event details or false if not a post object.
	 */
	protected function get_mapped_event( int $post_id, bool $add_unique_id = false, string $service_id = '' ) {
		$event = tribe_get_event( $post_id );
		if ( ! $event instanceof \WP_Post ) {
			return false;
		}

		if ( $event->post_type !== TEC::POSTTYPE ) {
			return false;
		}

		$event_id = $event->ID;
		if ( $add_unique_id ) {
			$post_modified = get_post_field( 'post_modified', $post_id );
			$updated_id    = '|' . strtotime( $post_modified );

			$event_id = $event_id . $updated_id;
		}

		// Default event status is blank as Google recognizes it as scheduled, this sets it to scheduled.
		// This is not translated as it is meant to serve as an attribute along with canceled and postponed.
		$event_status = empty( $event->event_status ) ? 'scheduled' : $event->event_status;

		$author_data  = get_userdata( $event->post_author );
		$author_email = $author_data ? $author_data->user_email : null;

		$next_event = [
			// Detail Fields.
			'id'                 => (string) $event_id,
			'title'              => $event->post_title,
			'description'        => $event->post_content,
			'excerpt'            => $event->excerpt,
			'permalink'          => $event->permalink,
			'author_id'          => (int) $event->post_author,
			'author_email'       => $author_email,
			'event_status'       => $event_status,
			'featured'           => $event->featured,
			'sticky'             => $event->sticky,
			'organizers'         => $this->get_organizers( $event ),
			'venue'              => $this->get_venues( $event ),
			'cost'               => html_entity_decode( $event->cost ),
			'website_url'        => get_post_meta( $event->ID, '_EventURL', true ),
			'featured_image_url' => isset( $event->thumbnail->full->url ) ? $event->thumbnail->full->url : '',
			'category'           => $this->get_taxonomy_terms( TEC::TAXONOMY, $event ),
			'tag'                => $this->get_taxonomy_terms( 'post_tag', $event ),
			'tickets'            => $this->get_tickets( $event ),

			// Time Based Fields.
			'start_date'          => $event->dates->start->format( 'c' ),
			'end_date'            => $event->dates->end->format( 'c' ),
			'timezone'            => $event->timezone,
			'timezone_abbr'       => Timezones::abbr( $event->start_date, $event->timezone ),
			'all_day'             => $event->all_day,
			'multi_day'           => intval( $event->multiday ),
			'is_past'             => $event->is_past,
			'duration'            => intval( $event->duration ),
		];

		/**
		 * Filters the event details sent to a 3rd party.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 * @since 6.0.0 Migrated to Common from Event Automator - Add Service ID.
		 *
		 * @param array<string|mixed> $next_event An array of event details.
		 * @param WP_Post             $event      An instance of the event WP_Post object.
		 * @param string              $service_id The service id used to modify the mapped event details.
		 */
		$next_event = apply_filters( 'tec_automator_map_event_details', $next_event, $event, $service_id );
		// Zapier only requires an id field, if that is empty send a generic invalid message.
		if ( empty( $next_event['id'] ) ) {
			return [ 'id' => 'invalid-event-id' ];
		}

		return $next_event;
	}

	/**
	 * Get all the taxonomy terms for an event.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string  $taxonomy_name The event taxonomy name.
	 * @param WP_Post $event         An instance of the event WP_Post object.
	 *
	 * @return array<string|mixed> An array of terms for the mapped event.
	 */
	protected function get_taxonomy_terms( string $taxonomy_name, WP_Post $event ) : array {
		$terms = wp_get_object_terms( $event->ID, $taxonomy_name );

		if ( empty( $terms ) || ! is_array( $terms ) ) {
			return [];
		}

		$mapped_terms = [];
		foreach ( $terms as $term ) {
			$formatted_term = $this->get_term_formatted_for_map( $term );

			if ( ! empty( $term->parent ) ) {
				$parent_term              = get_term( $term->parent, TEC::TAXONOMY );
				$formatted_term['parent'] = $this->get_term_formatted_for_map( $parent_term );
			}

			if ( empty( $formatted_term ) ) {
				continue;
			}

			$mapped_terms[] = $formatted_term;
		}

		return $mapped_terms;
	}

	/**
	 * Get the term formatted for the event map for 3rd party services.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_Term|WP_Error $term A term object or WP_Error instance.
	 *
	 * @return array<string|string> An array of term values for the mapped event.
	 */
	protected function get_term_formatted_for_map( $term ) : array {
		if ( is_wp_error( $term ) ) {
			return [];
		}
		if ( ! isset( $term->name ) ) {
			return [];
		}

		return [
			'name'        => $term->name,
			'slug'        => $term->slug,
			'description' => $term->description,
		];
	}

	/**
	 * Get all the organizer details for an event.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_Post $event The instance of WP_Post for an event.
	 *
	 * @return array<string|mixed> An array of organizer details or empty array.
	 */
	protected function get_organizers( WP_Post $event ) : array {
		if ( ! $event instanceof \WP_Post ) {
			return [];
		}

		if ( $event->post_type !== TEC::POSTTYPE ) {
			return [];
		}

		$organizers = $event->organizers->all();
		if ( empty( $organizers ) ) {
			return [];
		}

		$all_organizers = [];
		foreach ( $organizers as $organizer ) {
			$all_organizers[] = $this->get_mapped_organizer( $organizer );
		}

		/**
		 * Filters all the organizer details sent to a 3rd party.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string|mixed> An array of organizers with mapped details.
		 * @param array<WP_Post> An array of instances of the organizer WP_Post object.
		 */
		$all_organizers = apply_filters( 'tec_automator_map_all_organizers', $all_organizers, $organizers );

		return $all_organizers;
	}

	/**
	 * Get the organizer details mapped for 3rd party services.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_Post $organizer The post for a organizer.
	 *
	 * @return boolean|array An array of organizer details or false if not a post object.
	 */
	protected function get_mapped_organizer( WP_Post $organizer ) {
		if ( ! $organizer instanceof \WP_Post ) {
			return false;
		}

		if ( $organizer->post_type !== TEC::ORGANIZER_POST_TYPE ) {
			return false;
		}

		$image_url = get_the_post_thumbnail_url( $organizer->ID, 'full' );

		$next_organizer = [
			// Detail Fields.
			'id'                 => $organizer->ID,
			'title'              => $organizer->post_title,
			'description'        => $organizer->post_content,
			'excerpt'            => $organizer->excerpt,
			'permalink'          => $organizer->permalink,
			'featured_image_url' => empty( $image_url ) ? '' : $image_url,
			'phone'              => $organizer->phone,
			'website'            => $organizer->website,
			'email'            	 => $organizer->email,
		];

		/**
		 * Filters the organizer details sent to a 3rd party.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string|mixed> An array of organizer details.
		 * @param WP_Post An instance of the organizer WP_Post object.
		 */
		$next_organizer = apply_filters( 'tec_automator_map_organizer_details', $next_organizer, $organizer );

		return $next_organizer;
	}

	/**
	 * Get all the venue details for an event.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_Post $event The instance of WP_Post for an event.
	 *
	 * @return array<string|mixed> An array of venue details or empty array.
	 */
	protected function get_venues( WP_Post $event ) : array {
		if ( ! $event instanceof \WP_Post ) {
			return [];
		}

		if ( $event->post_type !== TEC::POSTTYPE ) {
			return [];
		}

		$venues = $event->venues->all();
		if ( empty( $venues ) ) {
			return [];
		}

		$all_venues = [];
		foreach( $venues as $venue ) {
			$all_venues[] = $this->get_mapped_venue( $venue );
		}

		/**
		 * Filters all the venue details sent to a 3rd party.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string|mixed> An array of venues with mapped details.
		 * @param array<WP_Post> An array of instances of the venue WP_Post object.
		 */
		$all_venues = apply_filters( 'tec_automator_map_all_venues', $all_venues, $venues );

		return $all_venues;
	}

	/**
	 * Get the venue details mapped for 3rd party services.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_Post     $venue   The post for a venue.
	 *
	 * @return boolean|array An array of venue details or false if not a post object.
	 */
	protected function get_mapped_venue( WP_Post $venue ) {
		if ( ! $venue instanceof \WP_Post ) {
			return false;
		}

		if ( $venue->post_type !== TEC::VENUE_POST_TYPE ) {
			return false;
		}

		$image_url = get_the_post_thumbnail_url( $venue->ID, 'full' );

		$next_venue = [
			// Detail Fields.
			'id'                 => $venue->ID,
			'title'              => $venue->post_title,
			'description'        => $venue->post_content,
			'excerpt'            => $venue->excerpt,
			'permalink'          => $venue->permalink,
			'featured_image_url' => empty( $image_url ) ? '' : $image_url,
			'phone'              => $venue->phone,
			'website'            => $venue->website,
			'address'            => $venue->address,
			'country'            => $venue->country,
			'city'               => $venue->city,
			'state_province'     => $venue->state_province,
			'state'              => $venue->state,
			'zip'                => $venue->zip,
			'directions_link'    => $venue->directions_link,
			'geolocation'        => (object) $venue->geolocation,
		];

		/**
		 * Filters the venue details sent to a 3rd party.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string|mixed> An array of venue details.
		 * @param WP_Post An instance of the venue WP_Post object.
		 */
		$next_venue = apply_filters( 'tec_automator_map_venue_details', $next_venue, $venue );

		return $next_venue;
	}

	/**
	 * Get all tickets|rsvps for an Event.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_Post $event The instance of WP_Post for an event.
	 *
	 * @return array<string|mixed> An array of ticket details for the event.
	 */
	protected function get_tickets( WP_Post $event ) : array {
		$base_ticket_data = [
			'has_ticket'    => false,
			'has_rsvp'      => false,
			'in_date_range' => false,
			'sold_out'      => false,
			'tickets'       => [],
			'rsvps'         => [],
		];

		if ( ! $event instanceof \WP_Post ) {
			return $base_ticket_data;
		}

		if ( $event->post_type !== TEC::POSTTYPE ) {
			return $base_ticket_data;
		}

		if ( empty( $event->tickets ) ) {
			return $base_ticket_data;
		}

		if ( empty( $event->tickets->exist() ) ) {
			return $base_ticket_data;
		}

		$tickets_data = \Tribe__Tickets__Tickets::get_all_event_tickets( $event->ID );
		$all_tickets = [];
		$all_rsvps = [];
		foreach( $tickets_data as $ticket ) {
			$ticket_data = [
				'id'             => $ticket->ID,
				'name'           => $ticket->name,
				'provider_class' => $ticket->provider_class,
			];

			if ( $ticket->provider_class === 'Tribe__Tickets__RSVP' ) {
				$all_rsvps[] = $ticket_data;
				continue;
			}

			$all_tickets[] = $ticket_data;
		}

		$base_ticket_data['has_rsvp']      = ! empty( $all_rsvps );
		$base_ticket_data['has_ticket']    = ! empty( $all_tickets );
		$base_ticket_data['in_date_range'] = $event->tickets->in_date_range();
		$base_ticket_data['sold_out']      = $event->tickets->sold_out();
		$base_ticket_data['tickets']       = $all_tickets;
		$base_ticket_data['rsvps']         = $all_rsvps;

		/**
		 * Filters all the venue details sent to a 3rd party.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string|mixed> An array of venues with mapped details.
		 * @param WP_Post $event The instance of WP_Post for an event.
		 */
		$base_ticket_data = apply_filters( 'tec_automator_map_all_venues', $base_ticket_data, $event );

		return $base_ticket_data;
	}
}
