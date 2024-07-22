<?php
/**
 * Provides methods map attendee details.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps;
 */

namespace TEC\Event_Automator\Traits\Maps;

use WP_Post;
use \Tribe__Timezones as Timezones;

/**
 * Trait Event
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps;
 */
trait Attendees {

	/**
	 * Get the event details mapped for 3rd party services.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @since 6.0.0 Migrated to Common from Event Automator - Add encode arrays.
	 * @since 6.0.0 Migrated to Common from Event Automator - Add add_updated_id, and service_id parameters.
	 *
	 * @param int     $post_id        The post id for an attendee.
	 * @param boolean $add_updated_id Whether to add a updated id, Zapier only updates events per
	 *                                Zap once with the same id, this enables multiple updates.
	 * @param string  $service_id     The service id used to modify the mapped event details.
	 *
	 * @return array<string|mixed> An array of attendee details or false if not a post object.
	 */
	protected function get_mapped_attendee( int $post_id, bool $add_updated_id = false, string $service_id = '' ) {
		if ( ! function_exists( 'tribe_tickets_get_attendees' ) ) {
			return [];
		}
		$attendee_arr = tribe_tickets_get_attendees( $post_id );
		$attendee     = array_shift( $attendee_arr );
		if ( empty( $attendee['provider'] ) ) {
			return [];
		}

		$attendee_id = (int) $attendee['attendee_id'] ?? '';

		// Setup updated ID for checkins and updated attendees.
		if ( $add_updated_id ) {
			$post_modified = get_post_field( 'post_modified', $post_id );
			$updated_id    = '|' . strtotime( $post_modified );


			/** @var Tribe__Tickets__Data_API $data_api */
			$data_api = tribe( 'tickets.data_api' );
			$service_provider = $data_api->get_ticket_provider( $attendee_id );
			if (
				! empty( static::$endpoint_id )
				&& static::$endpoint_id === 'checkin'
				&& ! empty( $service_provider->checkin_key )
			) {
				$checkin_details = get_post_meta( $attendee_id, $service_provider->checkin_key . '_details', true );
				$updated_id = isset( $checkin_details['date'] ) ? '|' . strtotime( $checkin_details['date'] ) : '';
			}

			$attendee_id = $attendee_id . $updated_id;
		}

		$next_attendee = [
			// Attendee.
			'id'                => $attendee_id,
			'holder_name'       => html_entity_decode( $attendee['holder_name'] ?? '' ),
			'holder_email'      => $attendee['holder_email'] ?? '',
			'ticket_id'         => strval( $attendee['ticket_id'] ) ?? '',
			'security_code'     => $attendee['security_code'] ?? '',
			'attendee_meta'     => $this->get_attendee_meta( $attendee ),
			'check_in'          => $attendee['check_in'] ?? '',
			'optout'            => $attendee['optout'] ?? '',
			'user_id'           => $attendee['user_id'] ?? '',
			'is_subscribed'     => $attendee['is_subscribed'] ?? '',

			// Ticket & Order.
			'is_purchaser'      => $attendee['is_purchaser'] ?? '',
			'purchaser_name'    => html_entity_decode( $attendee['purchaser_name'] ?? '' ),
			'purchaser_email'   => $attendee['purchaser_email'] ?? '',
			'provider'          => $attendee['provider_slug'] ?? '',
			'ticket'            => $attendee['ticket'] ?? '',
			'ticket_product_id' => strval( $attendee['product_id'] ) ?? '',
			'order_id'          => strval( $attendee['order_id'] ) ?? '',
			'order_status'      => $attendee['order_status'] ?? '',

			// Event.
			'event_id'          => $attendee['event_id'] ?? '',
			'event_title'       => html_entity_decode( get_the_title( $attendee['event_id'] ) ),
		];

		/**
		 * Filters the attendee details sent to a 3rd party.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 * @since 6.0.0 Migrated to Common from Event Automator - Add Service ID.
		 *
		 * @param array<string|mixed> $next_attendee An array of attendee mapped details.
		 * @param array<string|mixed> $attendee      An array of raw attendee details.
		 * @param string              $service_id    The service id used to modify the mapped event details.
		 */
		$next_attendee = apply_filters( 'tec_automator_map_attendee_details', $next_attendee, $attendee, $service_id );
		// Zapier only requires an id field, if that is empty send a generic invalid message.
		if ( empty( $next_attendee['id'] ) ) {
			return [ 'id' => 'invalid-attendee-id.' ];
		}

		return $next_attendee;
	}

	/**
	 * Get Attendee Meta.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|mixed> $attendee An array of attendee details.
	 *
	 * @return array<string|mixed> $meta_array  Meta attendee array.
	 */
	public function get_attendee_meta( $attendee ) {
		if ( empty( $attendee['attendee_meta'] ) ) {
			return [];
		}

		$meta_array = [];
		foreach ( $attendee['attendee_meta'] as $key => $item ) {
			if ( empty( $item['value'] ) ) {
				continue;
			}
			$value = $item['value'];

			// If value is an array, convert each item to a string and remove the keys.
			if ( is_array( $value ) ) {
				$value = array_values( array_map( 'strval', $value ) );
			} else {
				$value = [ (string) $value ];
			}

			$meta_array[] = [
				'attendee_meta_id'    => $item['slug'],
				'attendee_meta_name'  => $item['label'],
				'attendee_meta_value' => $value,
			];
		}

		return $meta_array;
	}
}
