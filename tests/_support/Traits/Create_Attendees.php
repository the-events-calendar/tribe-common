<?php

namespace TEC\Event_Automator\Tests\Traits;

use TEC\Tickets\Commerce\Cart;
use TEC\Tickets\Commerce\Gateways\PayPal\Gateway;
use TEC\Tickets\Commerce\Module;
use TEC\Tickets\Commerce\Order;
use TEC\Tickets\Commerce\Status\Pending;
use TEC\Tickets\Commerce\Status\Refunded;
use Tribe\Tickets\Test\Commerce\RSVP\Ticket_Maker as RSVP_Ticket_Maker;
use Tribe\Tickets\Test\Commerce\TicketsCommerce\Ticket_Maker as TC_Ticket_Maker;
use Tribe\Tickets\Test\Commerce\PayPal\Order_Maker as TC_Order_Maker;
use TEC\Tickets\Commerce\Attendee as TC_Attendee;
use Tribe\Tickets_Plus\Test\Commerce\WooCommerce\Ticket_Maker as Woo_Ticket_Maker;
use Tribe\Tickets_Plus\Test\Commerce\WooCommerce\Order_Maker as Woo_Order_Maker;
use Tribe\Tickets\Test\Commerce\Attendee_Maker;
use WC_Order_Refund;
use WC_Product_Simple;
use WP_Post;

trait Create_Attendees {
	use RSVP_Ticket_Maker;
	use TC_Ticket_Maker;
	use TC_Order_Maker;
	use Woo_Ticket_Maker;
	use Woo_Order_Maker;
	use Attendee_Maker;

	/*
	 * Attendee meta fieldset.
	 *
	 * @since 6.0.0
	 *
	 * @var array<string|mixed> The fieldset to use for attendee meta.
	 */
	protected array $fieldset = [
		[
			'id'          => 0,
			'type'        => 'select',
			'required'    => '',
			'label'       => 'Dropdown for Tests',
			'slug'        => 'dropdown-for-tests',
			'extra'       => [
				'options' => [
					'Option 1',
					'2nd Option',
					'Last One',
				],
			],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'text',
			'required'    => '',
			'label'       => 'Text Field',
			'slug'        => 'text-field',
			'extra'       => [],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'radio',
			'required'    => '',
			'label'       => 'Radio Field',
			'slug'        => 'radio-field',
			'extra'       => [
				'options' => [
					'radio1',
					'radio2',
					'radio3',
				],
			],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'checkbox',
			'required'    => '',
			'label'       => 'Checkbox Field',
			'slug'        => 'checkbox-field',
			'extra'       => [
				'options' => [
					'check1',
					'check2',
					'check3',
				],
			],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'select',
			'required'    => '',
			'label'       => 'Dropdown',
			'slug'        => 'dropdown',
			'extra'       => [
				'options' => [
					'drop1',
					'drop2',
					'drop3',
				],
			],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'email',
			'required'    => '',
			'label'       => 'Email Field',
			'slug'        => 'email-field',
			'extra'       => [],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'telephone',
			'required'    => '',
			'label'       => 'Telephone',
			'slug'        => 'telephone',
			'extra'       => [],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'url',
			'required'    => '',
			'label'       => 'URL Field',
			'slug'        => 'url-field',
			'extra'       => [],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'birth',
			'required'    => '',
			'label'       => 'Birthday Field',
			'slug'        => 'birthday-field',
			'extra'       => [],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
		[
			'id'          => 0,
			'type'        => 'datetime',
			'required'    => '',
			'label'       => 'Date Field',
			'slug'        => 'date-field',
			'extra'       => [],
			'classes'     => [],
			'attributes'  => [],
			'placeholder' => '',
			'description' => '',
		],
	];

	/**
	 * Generate RSVP for an Event.
	 *
	 * @since 6.0.0
	 *
	 * @param int $event_id The event id to create a RSVP for.
	 *
	 * @return int The RSVP id.
	 */
	protected function generate_rsvp_for_event( int $event_id ): int {
		update_post_meta( $event_id, '_tribe_default_ticket_provider', 'Tribe__Tickets__RSVP' );

		return $this->create_rsvp_ticket( $event_id );
	}

	/**
	 * Generate RSVP Attendee.
	 *
	 * @since 6.0.0
	 *
	 * @param int           $event_id      The event id to create a RSVP for.
	 * @param array<string> $overrides     An optional array of overrides to generate attendees.
	 * @param bool          $attendee_meta An optional to enable attendee meta fields.
	 *
	 * @return WP_Post|false The new post object or false if unsuccessful.
	 */
	protected function generate_rsvp_attendee( $event, $overrides = [], $attendee_meta = false ) {
		$rsvp_id = $this->create_rsvp_ticket( $event->ID );
		if ( $attendee_meta ) {
			$this->save_attendee_meta_to_ticket( $rsvp_id );
		}

		/** @var \Tribe\Tickets\RSVP\Attendee_Repository $attendees */
		$attendees     = tribe_attendees( 'rsvp' );
		$attendee_data = [
			'full_name' => 'A test attendee',
			'email'     => 'attendee@test.com',
		];

		$explicit_keys        = [
			'status',
		];
		$meta_input_overrides = array_diff_key( $overrides, array_combine( $explicit_keys, $explicit_keys ) );

		$attendee_data = array_merge( $attendee_data, $meta_input_overrides );

		return $attendees->create_attendee_for_ticket( $rsvp_id, $attendee_data );
	}

	/**
	 * Generate Multiple RSVP Attendee.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post $event The event post object to create an RSVP for.
	 *
	 * @return array<int> An array of generated attendee ids.
	 */
	protected function generate_multiple_rsvp_attendees( $event ) {
		$rsvp_id = $this->create_rsvp_ticket( $event->ID );
		/** @var \Tribe\Tickets\RSVP\Attendee_Repository $attendees */
		$attendees = tribe_attendees( 'rsvp' );

		return array_map(
			static function ( $i ) use ( $attendees, $rsvp_id ) {
				return $attendees->create_attendee_for_ticket(
					$rsvp_id,
					[
						'full_name' => 'A test attendee-' . $i,
						'email'     => $i . '-attendee@test.com',
					]
				);
			},
			range( 1, 3 )
		);
	}

	/**
	 * Generate RSVP Attendee and update it.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post       $event     The event post object to create a RSVP for.
	 * @param array<string> $overrides An optional array of overrides to generate attendees.
	 *
	 * @return WP_Post|bool The updated attendee post object or false if no attendee generated.
	 */
	protected function generate_rsvp_attendee_updated_it( $event, $overrides = [] ) {
		$attendee = $this->generate_rsvp_attendee( $event, $overrides );

		if ( ! $attendee instanceof WP_Post ) {
			return false;
		}

		// Update the post date to the past so it can pass the updated attendee queue validation.
		global $wpdb;
		$wpdb->update(
			$wpdb->posts,
			[ 'post_date' => '2023-02-01 12:00:00' ],
			[ 'ID' => $attendee->ID ],
			[ '%s' ],
			[ '%d' ]
		);
		wp_cache_flush();

		$provider      = tribe_tickets_get_ticket_provider( $attendee->ID );
		$attendee_data = [
			'full_name'         => 'updated name',
			'email'             => get_post_meta( $attendee->ID, '_tribe_rsvp_email', true ),
			'attendee_meta'     => [],
			'attendee_source'   => 'admin',
			'attendee_added_by' => get_current_user_id(),
		];

		$provider->update_attendee( $attendee->ID, $attendee_data );

		return $attendee;
	}

	/**
	 * Generate Tickets Commerce Ticket for an Event.
	 *
	 * @since 6.0.0
	 *
	 * @param int $event_id The event id to create a TC Ticket for.
	 *
	 * @return int The TC tickets id.
	 */
	protected function generate_tc_ticket_for_event( int $event_id ): int {
		update_post_meta( $event_id, '_tribe_default_ticket_provider', \TEC\Tickets\Commerce\Module::class );

		return $this->create_tc_ticket( $event_id );
	}

	/**
	 * Generate TC Attendee.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post       $event         The event post object to create an TC Attendee for.
	 * @param array<string> $overrides     An optional array of overrides to generate attendees.
	 * @param bool          $attendee_meta An optional to enable attendee meta fields.
	 *
	 * @return int A generated attendee id.
	 */
	protected function generate_tc_attendee( $event, $overrides = [], $attendee_meta = false ) {
		$ticket_id = $this->create_tc_ticket( $event->ID );
		if ( $attendee_meta ) {
			$this->save_attendee_meta_to_ticket( $ticket_id );
		}

		$ticket    = tribe( Module::class )->get_ticket( $event->ID, $ticket_id );
		$purchaser = [
			'purchaser_user_id'    => 0,
			'purchaser_full_name'  => 'Test Purchaser',
			'purchaser_first_name' => 'Test',
			'purchaser_last_name'  => 'Purchaser',
			'purchaser_email'      => 'test@test.com',
		];
		$order     = tribe( Order::class )->create( tribe( Gateway::class ), $purchaser );
		/** @var \Tribe\Tickets\Commerce\Attendee_Repository $attendees */
		$attendees = tribe( TC_Attendee::class );

		$attendee_data        = [
			'full_name' => 'A test attendee',
			'email'     => 'attendee@test.com',
			'key_name'  => 'tc',
		];
		$explicit_keys        = [
			'status',
		];
		$meta_input_overrides = array_diff_key( $overrides, array_combine( $explicit_keys, $explicit_keys ) );

		$attendee_data = array_merge( $attendee_data, $meta_input_overrides );

		return $attendees->create( $order, $ticket, $attendee_data );
	}

	/**
	 * Generate Multiple TC Attendee.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post $event The event post object to create an TC Attendee for.
	 *
	 * @return array<int> An array of generated attendee ids.
	 */
	protected function generate_multiple_tc_attendees( $event ) {
		$ticket_id = $this->create_tc_ticket( $event->ID );
		$ticket    = tribe( Module::class )->get_ticket( $event->ID, $ticket_id );
		$purchaser = [
			'purchaser_user_id'    => 1,
			'purchaser_full_name'  => 'TC Test Purchaser',
			'purchaser_first_name' => 'TC',
			'purchaser_last_name'  => 'Zapier',
			'purchaser_email'      => 'zapier@test.com',
		];

		$order = tribe( Order::class )->create( tribe( Gateway::class ), $purchaser );
		/** @var \Tribe\Tickets\Commerce\Attendee_Repository $attendees */
		$attendees = tribe( TC_Attendee::class );

		return array_map(
			static function ( $i ) use ( $attendees, $order, $ticket ) {
				return $attendees->create(
					$order,
					$ticket,
					[
						'full_name' => 'A test attendee-' . $i,
						'email'     => $i . '-attendee@test.com',
					]
				);
			},
			range( 1, 3 )
		);
	}

	/**
	 * Generate TC Attendee and updated it.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post       $event     The event post object to create an TC Attendee for.
	 * @param array<string> $overrides An optional array of overrides to generate attendees.
	 *
	 * @return int The updated attendee id.
	 */
	protected function generate_tc_attendee_updated_it( $event, $overrides = [] ) {
		$attendee = $this->generate_tc_attendee( $event, $overrides );

		// Update the post date to the past, so it can pass the updated attendee queue validation.
		global $wpdb;
		$wpdb->update(
			$wpdb->posts,
			[ 'post_date' => '2023-02-01 12:00:00' ],
			[ 'ID' => $attendee->ID ],
			[ '%s' ],
			[ '%d' ]
		);

		wp_cache_flush();

		$provider      = tribe_tickets_get_ticket_provider( $attendee->ID );
		$attendee_data = [
			'full_name'         => 'updated name',
			'email'             => get_post_meta( $attendee->ID, '_tribe_rsvp_email', true ),
			'attendee_meta'     => [],
			'attendee_source'   => 'admin',
			'attendee_added_by' => get_current_user_id(),
		];

		$provider->update_attendee( $attendee->ID, $attendee_data );

		return $attendee;
	}

	/**
	 * Generate TC Order.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post $event The event post object to create a TC Order for.
	 *
	 * @return int The generated order id.
	 */
	protected function generate_tc_order( $event ) {
		$ticket_id = $this->create_tc_ticket( $event->ID, 8 );

		$cart = new Cart();
		$cart->get_repository()->upsert_item( $ticket_id, 5 );

		$purchaser = [
			'purchaser_user_id'    => 0,
			'purchaser_full_name'  => 'Test Purchaser',
			'purchaser_first_name' => 'Test',
			'purchaser_last_name'  => 'Purchaser',
			'purchaser_email'      => 'test@test.com',
		];

		$order     = tribe( Order::class )->create_from_cart( tribe( Gateway::class ), $purchaser );
		$completed = tribe( Order::class )->modify_status( $order->ID, Pending::SLUG );

		return $order->ID;
	}

	/**
	 * Generate TC Order and refund it.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post $event The event post object to create a TC Order for.
	 *
	 * @return int The generated order id.
	 */
	protected function generate_tc_order_and_refund_it( $event ) {
		$order_id = $this->generate_tc_order( $event );

		$refunded = tribe( Order::class )->modify_status( $order_id, Refunded::SLUG );

		return $order_id;
	}

	/**
	 * Generate Woo Ticket for an Event.
	 *
	 * @since 6.0.0
	 *
	 * @param int $event_id The event id to create a Woo Ticket for.
	 *
	 * @return int The Woo tickets id.
	 */
	protected function generate_woo_ticket_for_event( int $event_id ): int {
		update_post_meta( $event_id, '_tribe_default_ticket_provider', 'Tribe__Tickets_Plus__Commerce__WooCommerce__Main' );

		return $this->create_woocommerce_ticket( $event_id, 9 );
	}

	/**
	 * Generate Woo Attendee.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post       $event         The event post object to create a Woo Attendee for.
	 * @param array<string> $overrides     An optional array of overrides to generate attendees.
	 * @param bool          $attendee_meta An optional to enable attendee meta fields.
	 *
	 * @return int A generated attendee id.
	 */
	protected function generate_woo_attendee( $event, $overrides = [], $attendee_meta = false ) {
		$ticket_id = $this->create_woocommerce_ticket( $event->ID, 9 );
		if ( $attendee_meta ) {
			$this->save_attendee_meta_to_ticket( $ticket_id );
		}

		/** @var \Tribe\Tickets\Commerce\Attendee_Repository $attendees */
		$attendees     = tribe_attendees( 'woo' );
		$attendee_data = [
			'full_name' => 'A test attendee',
			'email'     => 'attendee@test.com',
		];

		$explicit_keys        = [
			'status',
		];
		$meta_input_overrides = array_diff_key( $overrides, array_combine( $explicit_keys, $explicit_keys ) );

		$attendee_data = array_merge( $attendee_data, $meta_input_overrides );

		return $attendees->create_attendee_for_ticket( $ticket_id, $attendee_data );
	}

	/**
	 * Generate Multiple Woo Attendee.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post $event The event post object to create a Woo Attendee for.
	 *
	 * @return array<int> An array of generated attendee ids.
	 */
	protected function generate_multiple_woo_attendees( $event ) {
		$ticket_id = $this->create_woocommerce_ticket( $event->ID, 11 );
		/** @var Tribe__Tickets_Plus__Repositories__Attendee__WooCommerce $attendees */
		$attendees = tribe_attendees( 'woo' );

		return array_map(
			static function ( $i ) use ( $attendees, $ticket_id ) {
				return $attendees->create_attendee_for_ticket(
					$ticket_id,
					[
						'full_name' => 'A test attendee-' . $i,
						'email'     => $i . '-attendee@test.com',
					]
				);
			},
			range( 1, 3 )
		);
	}

	/**
	 * Generate Woo Attendee and updated it.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post       $event     The event post object to create a Woo Attendee for.
	 * @param array<string> $overrides An optional array of overrides to generate attendees.
	 *
	 * @return int The updated attendee id.
	 */
	protected function generate_woo_attendee_updated_it( $event, $overrides = [] ) {
		$attendee = $this->generate_woo_attendee( $event, $overrides );

		// Update the post date to the past, so it can pass the updated attendee queue validation.
		global $wpdb;
		$wpdb->update(
			$wpdb->posts,
			[ 'post_date' => '2023-02-01 12:00:00' ],
			[ 'ID' => $attendee->ID ],
			[ '%s' ],
			[ '%d' ]
		);

		wp_cache_flush();

		$provider      = tribe_tickets_get_ticket_provider( $attendee->ID );
		$attendee_data = [
			'full_name'         => 'updated name',
			'email'             => get_post_meta( $attendee->ID, '_tribe_rsvp_email', true ),
			'attendee_meta'     => [],
			'attendee_source'   => 'admin',
			'attendee_added_by' => get_current_user_id(),
		];

		$provider->update_attendee( $attendee->ID, $attendee_data );

		return $attendee;
	}

	/**
	 * Create a Woo product.
	 *
	 * @since 6.0.0
	 *
	 * @return int The created product id.
	 */
	protected function create_woo_product() {
		$product = new WC_Product_Simple();
		$product->set_name( 'Product Title' );
		$product->set_slug( 'no-ticket-product' );
		$product->set_regular_price( 8.00 );
		$product->save();


		return $product->get_id();
	}

	/**
	 * Generate Woo Order.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post $event The event post object to create a Woo Order for.
	 *
	 * @return int The generated order id.
	 */
	protected function generate_woo_order( $event ) {
		$ticket_id = $this->create_woocommerce_ticket( $event->ID, 8 );
		return $this->create_woocommerce_order( $ticket_id, 2 );
	}

	/**
	 * Generate Woo Order with no tickets.
	 *
	 * @since 6.0.0
	 *
	 * @return int The generated order id.
	 */
	protected function generate_woo_order_with_no_tickets() {
		$product_id = $this->create_woo_product();
		// Prevent tickets from being generated.
		add_filter(
			'event_tickets_woo_ticket_generating_order_stati',
			function ( $generation_statuses ) {
				return [];
			}
		);

		return $this->create_woocommerce_order( $product_id, 2 );
	}

	/**
	 * Generate Woo Order and refund it.
	 *
	 * @since 6.0.0
	 *
	 * @param WP_Post $event The event post object to create a Woo Order for.
	 *
	 * @return int The generated order id.
	 */
	protected function generate_woo_order_and_refund_it( $event ) {
		$ticket_id = $this->create_woocommerce_ticket( $event->ID, 8 );
		$refund    = $this->create_refunded_woocommerce_order( $ticket_id, 2 );

		return $refund->get_parent_id();
	}

	/**
	 * Generate Woo Order with no tickets and refund it.
	 *
	 * @since 6.0.0
	 *
	 * @return int The generated order id.
	 */
	protected function generate_woo_order_with_no_tickets_and_refund_it() {
		$product_id = $this->create_woo_product();
		// Prevent tickets from being generated.
		add_filter(
			'event_tickets_woo_ticket_generating_order_stati',
			function ( $generation_statuses ) {
				return [];
			}
		);

		$refund = $this->create_refunded_woocommerce_order( $product_id, 2 );
		$refund = new WC_Order_Refund( $refund->get_id() );

		return $refund->get_parent_id();
	}

	/**
	 * Setup attendee meta fields for a ticket.
	 *
	 * @since 6.0.0
	 *
	 * @param Int $ticket_id A ticket post id.
	 */
	protected function save_attendee_meta_to_ticket( $ticket_id ) {
		update_post_meta( $ticket_id, '_tribe_tickets_meta_enabled', 'yes' );
		update_post_meta( $ticket_id, '_tribe_tickets_meta', $this->fieldset );
	}
}
