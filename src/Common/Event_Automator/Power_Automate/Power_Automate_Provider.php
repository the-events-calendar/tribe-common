<?php
/**
 * The Power Automate service provider.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Power_Automate
 */

namespace TEC\Event_Automator\Power_Automate;

use TEC\Common\Contracts\Service_Provider;
use EDD_Payment;
use TEC\Event_Automator\Integrations\Assets;
use TEC\Event_Automator\Power_Automate\Admin\Dashboard;
use TEC\Event_Automator\Power_Automate\Admin\Endpoints_Manager;
use TEC\Event_Automator\Power_Automate\REST\V1\Documentation\Swagger_Documentation;
use TEC\Event_Automator\Integrations\REST\V1\Utilities\Action_Endpoints as Action_Endpoints_Utilities;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Actions\Create_Events;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue\New_Events;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue\Updated_Events;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue\Canceled_Events;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue\Attendees;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue\Updated_Attendees;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue\Checkin;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue\Orders;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue\Refunded_Orders;
use TEC\Event_Automator\Traits\With_Nonce_Routes;
use Tribe__Tickets__Ticket_Object as Ticket_Object;
use WC_Order;
use WP_Post;

/**
 * Class Power_Automate_Provider
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */
class Power_Automate_Provider extends Service_Provider {

	use With_Nonce_Routes;

	/**
	 * The constant to disable the event status coding.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	const DISABLED = 'TEC_POWER_AUTOMATE_DISABLED';

	/**
	 * The constant to enable add to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	const ENABLE_ADD_TO_QUEUE = 'TEC_POWER_AUTOMATE_ENABLE_ADD_TO_QUEUE';

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function register() {
		if ( ! self::is_active() ) {
			return;
		}

		// Register the SP on the container
		$this->container->singleton( 'tec.automator.power.automate.provider', $this );

		$this->add_actions();
		$this->add_filters();

		$this->container->singleton( Api::class );
		$this->container->singleton( Swagger_Documentation::class );

		/**
		 * Allows filtering of the capability required to use the Power Automate integration ajax features.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param string $ajax_capability The capability required to use the ajax features, default manage_options.
		 */
		$ajax_capability = apply_filters( 'tec_event_automator_power_automate_admin_ajax_capability', 'manage_options' );

		$this->route_admin_by_nonce( $this->admin_routes(), $ajax_capability );
	}

	/**
	 * Returns whether the event status should register, thus activate, or not.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return bool Whether the event status should register or not.
	 */
	public static function is_active() {
		if ( defined( self::DISABLED ) && constant( self::DISABLED ) ) {
			// The disable constant is defined and it's truthy.
			return false;
		}

		if ( getenv( self::DISABLED ) ) {
			// The disable env var is defined and it's truthy.
			return false;
		}

		/**
		 * Allows filtering whether the event status should be activated or not.
		 *
		 * Note: this filter will only apply if the disable constant or env var
		 * are not set or are set to falsy values.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param bool $activate Defaults to `true`.
		 */
		return (bool) apply_filters( 'tec_event_automator_power_automate_enabled', true );
	}

	/**
	 * Provides the routes that should be used to handle Power Automate Integration requests.
	 *
	 * The map returned by this method will be used by the `TEC\Event_Automator\Traits\With_Nonce_Routes` trait.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string,callable> A map from the nonce actions to the corresponding handlers.
	 */
	public function admin_routes() {
		$actions = tribe( Actions::class );

		return [
			$actions::$add_connection    => $this->container->callback( Api::class, 'ajax_add_connection' ),
			$actions::$create_access     => $this->container->callback( Api::class, 'ajax_create_connection_access' ),
			$actions::$delete_connection => $this->container->callback( Api::class, 'ajax_delete_connection' ),
			$actions::$clear_action      => $this->container->callback( Endpoints_Manager::class, 'ajax_clear' ),
			$actions::$disable_action    => $this->container->callback( Endpoints_Manager::class, 'ajax_disable' ),
			$actions::$enable_action     => $this->container->callback( Endpoints_Manager::class, 'ajax_enable' ),
		];
	}

	/**
	 * Adds the actions required for event status.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	protected function add_actions() {
		add_action( 'admin_init', [ $this, 'register_admin_assets' ] );
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );

		// Wait until plugins are loaded and then add queues for our various plugins.
		add_action( 'init', [ $this, 'setup_add_to_queues' ], 20 );
	}

	/**
	 * Adds the actions to add to the queues.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @since 6.0.0.1 Split the method in sub-methods for each plugin.
	 */
	public function setup_add_to_queues() {
		$this->add_tec_setup();
		$this->add_et_setup();
	}

	/**
	 * Adds the actions required for The Events Calendar.
	 *
	 * @since 6.0.0.1
	 *
	 * @return void
	 */
	protected function add_tec_setup(): void {
		if ( ! did_action( 'tec_events_pro_init' ) ) {
			return;
		}

		// New Events.
		add_action( 'wp_insert_post', [ $this, 'add_to_queue' ], 10, 3 );

		// Updated Events.
		add_action( 'post_updated', [ $this, 'add_updated_to_queue' ], 10, 3 );

		// Canceled Events.
		add_action( 'tribe_events_event_status_update_post_meta', [ $this, 'add_canceled_to_queue' ], 10, 2 );
	}

	/**
	 * Adds the actions required for Event Tickets.
	 *
	 * @since 6.0.0.1
	 *
	 * @return void
	 */
	protected function add_et_setup(): void {
		if ( ! did_action( 'tec_tickets_plus_attendee_bind_implementations' ) ) {
			return;
		}

		// Attendees.
		add_action( 'event_tickets_rsvp_attendee_created', [ $this, 'add_rsvp_attendee_to_queue' ], 10, 4 );
		add_action( 'tec_tickets_commerce_attendee_after_create', [ $this, 'add_tc_attendee_to_queue' ], 10, 4 );
		add_action( 'event_ticket_edd_attendee_created', [ $this, 'add_edd_attendee_to_queue' ], 10, 4 );
		add_action( 'event_ticket_woo_attendee_created', [ $this, 'add_woo_attendee_to_queue' ], 10, 4 );

		//Updated Attendees
		add_action( 'post_updated', [ $this, 'add_updated_attendee_to_queue' ], 10, 3 );

		// Checkin.
		add_action( 'rsvp_checkin', [ $this, 'add_checkin_to_queue' ], 10, 2 );
		add_action( 'event_tickets_checkin', [ $this, 'add_checkin_to_queue' ], 10, 2 );
		add_action( 'eddtickets_checkin', [ $this, 'add_checkin_to_queue' ], 10, 2 );
		add_action( 'wootickets_checkin', [ $this, 'add_checkin_to_queue' ], 10, 2 );

		// Ticket Orders.
		add_action( 'tec_tickets_commerce_attendee_after_create', [ $this, 'add_tc_order_to_queue' ], 10, 4 );
		add_action( 'event_tickets_edd_ticket_created', [ $this, 'add_edd_order_to_queue' ], 10, 4 );
		add_action( 'event_tickets_woocommerce_ticket_created', [ $this, 'add_woo_order_to_queue' ], 10, 4 );

		// Refunded Ticket Orders.
		add_action( 'tec_tickets_commerce_order_status_refunded', [ $this, 'add_refunded_tc_order_to_queue' ], 10, 3 );
		add_action( 'edd_refund_order', [ $this, 'add_refunded_edd_order_to_queue' ], 10, 3 );
		add_action( 'woocommerce_order_status_changed', [ $this, 'add_refunded_woo_order_to_queue' ], 10, 4 );
	}

	/**
	 * Adds the filters required by Power Automate.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	protected function add_filters() {
		add_filter( 'tec_event_automator_power-automate_settings_fields', [ $this, 'add_dashboard_fields' ] );
		add_filter( 'tec_event_automator_power-automate_endpoint_details', [ $this, 'filter_create_event_details' ], 10, 2 );
		add_filter( 'tec_event_automator_power-automate_enable_add_to_queues', [ $this, 'filter_enable_add_to_queues' ], 10 );
	}

	/**
	 * Register the Admin Assets for Power Automate.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function register_admin_assets() {
		$this->container->make( Assets::class )->register_admin_assets();
	}

	/**
	 * Registers the REST API endpoints.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @since 6.6.3 - Migrated all but Swagger Documentation endpoint to Event Tickets Plus and Events Calendar Pro
	 */
	public function register_endpoints() {
		$this->container->make( Swagger_Documentation::class )->register();
	}

	/**
	 * Adds the endpoint to the endpoint dashboard filter.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 6.6.2 - Migrated to Event Tickets Plus and Events Calendar Pro
	 */
	public function add_endpoints_to_dashboard() {
		_deprecated_function( __METHOD__, '6.6.2', 'Use Tribe\Events\Pro\Integrations\Event_Automator\Power_Automate_Provider->add_endpoints_to_dashboard or  Tribe\Tickets\Plus\Integrations\Event_Automator\Power_Automate_Provider->add_endpoints_to_dashboard instead.' );

		$this->container->make( New_Events::class )->add_to_dashboard();
		$this->container->make( Updated_Events::class )->add_to_dashboard();
		$this->container->make( Canceled_Events::class )->add_to_dashboard();
		$this->container->make( Attendees::class )->add_to_dashboard();
		$this->container->make( Updated_Attendees::class )->add_to_dashboard();
		$this->container->make( Checkin::class )->add_to_dashboard();
		$this->container->make( Orders::class )->add_to_dashboard();
		$this->container->make( Refunded_Orders::class )->add_to_dashboard();

		$this->container->make( Create_Events::class )->add_to_dashboard();
	}

	/**
	 * Filters the fields in the Events > Settings > Integrations tab to Power Automate settings.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 6.6.2 Migrated to Events Calendar Pro
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function filter_tec_integrations_tab_fields( $fields ) {
		_deprecated_function( __METHOD__, '6.6.2', 'Use Tribe\Events\Pro\Integrations\Event_Automator\Power_Automate_Provider->filter_tec_integrations_tab_fields instead.' );

		if ( ! is_array( $fields ) ) {
			return $fields;
		}

		return tribe( Settings::class )->add_fields_tec( $fields );
	}

	/**
	 * Filters the fields in the Tickets > Settings > Integrations tab to Power Automate settings.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 6.6.2 Migrated to Event Tickets Plus
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function filter_et_integrations_tab_fields( $fields ) {
		_deprecated_function( __METHOD__, '6.6.2', 'Use Tribe\Tickets\Plus\Integrations\Event_Automator\Power_Automate_Provide->filter_et_integrations_tab_fields instead.' );

		if ( ! is_array( $fields ) ) {
			return $fields;
		}

		return tribe( Settings::class )->add_fields_et( $fields );
	}

	/**
	 * Adds the Endpoint dashboard fields after the connection settings.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, with the added endpoint dashboard fields.
	 */
	public function add_dashboard_fields( $fields ) {
		if ( ! is_array( $fields ) ) {
			return $fields;
		}

		return tribe( Dashboard::class )->add_fields( $fields );
	}

	/**
	 * Filters the Power Automate endpoint details.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string,array>    $endpoint     An array of the Power Automate endpoint details.
	 * @param Abstract_REST_Endpoint $endpoint_obj An instance of the endpoint.
	 */
	public function filter_create_event_details( $endpoint, $endpoint_obj ) {
		return tribe( Action_Endpoints_Utilities::class )->filter_details( $endpoint, $endpoint_obj );
	}

	/**
	 * Filter to enable adding to the queues for Power Automate.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param boolean $enable_add_to_queue Whether to enable adding to the queues for Power Automate, default to false.
	 *
	 * @return boolean Whether adding to the queues is enabled Power Automate.
	 */
	public function filter_enable_add_to_queues( $enable_add_to_queue ) {
		if ( defined( self::ENABLE_ADD_TO_QUEUE ) && constant( self::ENABLE_ADD_TO_QUEUE ) ) {
			return true;
		}

		if ( getenv( self::ENABLE_ADD_TO_QUEUE ) ) {
			// The enable env var is defined and it's truthy.
			return true;
		}

		// Setup queues if there is a connection setup.
		$access_keys = get_option( 'tec_power_automate_connections' );
		if ( ! empty( $access_keys ) ) {
			$enable_add_to_queue =  true;
		}

		/**
		 * Allows filtering whether to add items to Power Automate queues.
		 *
		 * Note: this filter will only apply if the enable constant or env var
		 * are not set or are set to true values.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param bool                $enable_add_to_queue Defaults to `false`.
		 * @param array<string|mixed> $access_keys         An array of the Zapier access keys.
		 */
		return (bool) apply_filters( 'tec_event_automator_power_automate_enable_add_to_queue', $enable_add_to_queue, $access_keys );
	}

	/**
	 * Verify token and login user before dispatching the request.
	 * Done on `rest_pre_dispatch` to be able to set current user to pass validation capability checks.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 6.6.2 - Use Tribe\Events\Pro\Integrations\Event_Automator\Power_Automate_Provider->pre_dispatch_verification_for_create_events
	 *
	 * @param mixed           $result  Response to replace the requested version with. Can be anything
	 *                                 a normal endpoint can return, or null to not hijack the request.
	 * @param WP_REST_Server  $server  Server instance.
	 * @param WP_REST_Request $request Request used to generate the response.
	 *
	 * @return null With always return null, failure will happen on the can_create permission check.
	 */
	public function pre_dispatch_verification( $result, $server, $request ) {
		_deprecated_function( __METHOD__, '6.6.2', 'Use Tribe\Events\Pro\Integrations\Event_Automator\Power_Automate_Provider->pre_dispatch_verification_for_create_events instead.' );

		return $this->container->make( Create_Events::class )->pre_dispatch_verification( $result, $server, $request );
	}

	/**
	 * Modifies REST API comma seperated  parameters before validation.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 6.6.2 - Use Tribe\Events\Pro\Integrations\Event_Automator\Power_Automate_Provider->modify_rest_api_params_before_validatio_of_create_events
	 *
	 * @param WP_REST_Response|WP_Error $response Response to replace the requested version with. Can be anything
	 *                                            a normal endpoint can return, or a WP_Error if replacing the
	 *                                            response with an error.
	 * @param WP_REST_Server $handler  ResponseHandler instance (usually WP_REST_Server).
	 * @param WP_REST_Request $request Request used to generate the response.
	 *
	 * @return WP_REST_Response|WP_Error The response.
	 */
	public function modify_rest_api_params_before_validation( $result, $server, $request ) {
		_deprecated_function( __METHOD__, '6.6.2', 'Use Tribe\Events\Pro\Integrations\Event_Automator\Power_Automate_Provider->modify_rest_api_params_before_validatio_of_create_events instead.' );

		return $this->container->make( Create_Events::class )->modify_rest_api_params_before_validation( $result, $server, $request );
	}

	/**
	 * Add a custom post id to a trigger queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int     $post_id A WordPress custom post id.
	 * @param WP_Post $post    A WordPress custom post object.
	 * @param boolean $update  Whether this is an update to a custom post or new. Unreliable and not used.
	 */
	public function add_to_queue( $post_id, $post, $update ) {
		// TEC is not available return to prevent errors.
		if ( ! class_exists('Tribe__Events__Main', false ) ) {
			return;
		}

		$data = [
			'post'   => $post,
			'update' => $update,
		];

		$this->container->make( New_Events::class )->add_to_queue( $post_id, $data );
	}

	/**
	 * Add a custom post id  of an event that has been updated to a trigger queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int     $post_id A WordPress custom post id.
	 * @param WP_Post $post_after   Post object following the update.
	 * @param WP_Post $post_before  Post object before the update.
	 */
	public function add_updated_to_queue( $post_id, $post_after, $post_before ) {
		// TEC is not available return to prevent errors.
		if ( ! class_exists('Tribe__Events__Main', false ) ) {
			return;
		}

		$data = [
			'post'        => $post_after,
			'post_before' => $post_before,
		];

		$this->container->make( Updated_Events::class )->add_to_queue( $post_id, $data );
	}


	/**
	 * Add RSVP attendee to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param integer $attendee_id       An attendee id.
	 * @param integer $post_id           A WordPress custom post id.
	 * @param integer $product_id        A WordPress custom post object.
	 * @param integer $order_attendee_id Whether this is an update to a custom post or new. Unreliable and not used.
	 */
	public function add_rsvp_attendee_to_queue( $attendee_id, $post_id, $product_id, $order_attendee_id ) {
		$data = [
			'post_id'           => $post_id,
			'product_id'        => $product_id,
			'order_attendee_id' => $order_attendee_id,
		];

		$this->container->make( Attendees::class )->add_to_queue( $attendee_id, $data );
	}


	/**
	 * Add Tickets Commerce attendee to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_Post       $attendee Post object for the attendee.
	 * @param WP_Post       $order    Which order generated this attendee.
	 * @param Ticket_Object $ticket   Which ticket generated this Attendee.
	 * @param array         $args     Set of extra arguments used to populate the data for the attendee.
	 */
	public function add_tc_attendee_to_queue( $attendee, $order, $ticket, $args ) {
		$data = [
			'attendee'      => $attendee,
			'order'         => $order,
			'ticket'        => $ticket,
			'attendee_args' => $args,
		];

		$this->container->make( Attendees::class )->add_to_queue( $attendee->ID, $data );
	}

	/**
	 * Add EDD attendee to queue.
	 *
	 * @param int $attendee_id ID of attendee ticket.
	 * @param int $post_id     ID of event.
	 * @param int $order_id    Easy Digital Downloads order ID.
	 * @param int $product_id  Easy Digital Downloads product ID.
	 */
	public function add_edd_attendee_to_queue( $attendee_id, $post_id, $order_id, $product_id ) {
		$data = [
			'post_id'    => $post_id,
			'product_id' => $product_id,
			'order_id'   => $order_id,
		];

		$this->container->make( Attendees::class )->add_to_queue( $attendee_id, $data );
	}

	/**
	 * Add Woo attendee to queue.
	 *
	 * @param int      $attendee_id ID of attendee ticket.
	 * @param int      $post_id     ID of event.
	 * @param WC_Order $order       WooCommerce order.
	 * @param int      $product_id  WooCommerce product ID.
	 */
	public function add_woo_attendee_to_queue( $attendee_id, $post_id, $order, $product_id ) {
		$data = [
			'post_id'    => $post_id,
			'order'      => $order,
			'product_id' => $product_id,
		];

		$this->container->make( Attendees::class )->add_to_queue( $attendee_id, $data );
	}

	/**
	 * Add a custom post id of an attendee that has been updated to a trigger queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int     $post_id     A WordPress custom post id.
	 * @param WP_Post $post_after  Post object following the update.
	 * @param WP_Post $post_before Post object before the update.
	 */
	public function add_updated_attendee_to_queue( $post_id, $post_after, $post_before ) {
		// ET is not available return to prevent errors.
		if ( ! class_exists('Tribe__Tickets__Main', false ) ) {
			return;
		}

		$data = [
			'post'        => $post_after,
			'post_before' => $post_before,
		];

		$this->container->make( Updated_Attendees::class )->add_to_queue( $post_id, $data );
	}

	/**
	 * Add a canceled event post id to a trigger queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int           $post_id ID of the post we're saving.
	 * @param array<string> $data    The meta data we're trying to save.
	 */
	public function add_canceled_to_queue( $post_id, $data ) {
		$this->container->make( Canceled_Events::class )->add_to_queue( $post_id, $data );
	}

	/**
	 * Add checkin to the queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int       $attendee_id   ID of attendee ticket.
	 * @param bool|null $is_qr_checkin True if from QR checkin process.
	 */
	public function add_checkin_to_queue( $attendee_id, $is_qr_checkin ) {
		$data = [
			'is_qr' => boolval( $is_qr_checkin ),
		];

		$this->container->make( Checkin::class )->add_to_queue( $attendee_id, $data );
	}

	/**
	 * Add Tickets Commerce order to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_Post       $attendee Post object for the attendee.
	 * @param WP_Post       $order    Which order generated this attendee.
	 * @param Ticket_Object $ticket   Which ticket generated this Attendee.
	 * @param array         $args     Set of extra arguments used to populate the data for the attendee.
	 */
	public function add_tc_order_to_queue( $attendee, $order, $ticket, $args ) {
		$data = [
			'provider'      => tribe_tickets_get_ticket_provider( $attendee->ID ),
			'order'         => $order,
			'ticket'        => $ticket,
			'attendee_args' => $args,
		];

		$this->container->make( Orders::class )->add_to_queue( $order->ID, $data );
	}

	/**
	 * Add EDD order to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int $attendee_id       ID of attendee ticket.
	 * @param int $order_id          Easy Digital Downloads order ID.
	 * @param int $product_id        Easy Digital Downloads product ID.
	 * @param int $order_attendee_id Attendee # for order.
	 */
	public function add_edd_order_to_queue( $attendee_id, $order_id, $product_id, $order_attendee_id ) {
		$data = [
			'provider'          => tribe_tickets_get_ticket_provider( $attendee_id ),
			'attendee_id'       => $attendee_id,
			'product_id'        => $product_id,
			'order_attendee_id' => $order_attendee_id,
		];

		$this->container->make( Orders::class )->add_to_queue( $order_id, $data );
	}

	/**
	 * Add Woo order to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int $attendee_id       ID of attendee ticket.
	 * @param int $order_id          WooCommerce order ID.
	 * @param int $product_id        WooCommerce product ID.
	 * @param int $order_attendee_id Attendee # for order.
	 */
	public function add_woo_order_to_queue( $attendee_id, $order_id, $product_id, $order_attendee_id ) {
		$data = [
			'provider'          => tribe_tickets_get_ticket_provider( $attendee_id ),
			'attendee_id'       => $attendee_id,
			'product_id'        => $product_id,
			'order_attendee_id' => $order_attendee_id,
		];

		$this->container->make( Orders::class )->add_to_queue( $order_id, $data );
	}

	/**
	 * Add Refunded Tickets Commerce order to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Status_Interface      $new_status New post status.
	 * @param Status_Interface|null $old_status Old post status.
	 * @param \WP_Post              $order      Order Post object.
	 */
	public function add_refunded_tc_order_to_queue( $new_status, $old_status, $order ) {
		$data = [
			'provider'   => tribe_tickets_get_ticket_provider( $order->ID ),
			'order_id'   => $order->ID,
			'order'      => $order,
			'old_status' => $old_status,
			'new_status' => $new_status,
		];

		$this->container->make( Refunded_Orders::class )->add_to_queue( $order->ID, $data );
	}

	/**
	 * Add Refunded EDD order to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int  $order_id     The ID number of the order.
	 * @param int  $refund_id    The ID number of the refund order.
	 * @param bool $all_refunded The status of the order prior to this change.
	 */
	public function add_refunded_edd_order_to_queue( $order_id, $refund_id, $all_refunded ) {
		// EDD does not get the provider by id for EDD 3.0.0
		/** @var \Tribe__Tickets_Plus__Commerce__EDD__Main $commerce_edd */
		$provider   = tribe( 'tickets-plus.commerce.edd' );
		$payment    = new EDD_Payment( $order_id );
		$new_status = $payment->__get( 'status' );

		$data = [
			'provider'   => $provider,
			'order_id'   => $order_id,
			'new_status' => $new_status,
		];

		$this->container->make( Refunded_Orders::class )->add_to_queue( $order_id, $data );
	}

	/**
	 * Add Refunded Woo order to queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int      $order_id   WooCommerce order ID.
	 * @param string   $old_status The status of the order prior to this change.
	 * @param string   $new_status The new order status.
	 * @param WC_Order $order      The instance of the order object.
	 */
	public function add_refunded_woo_order_to_queue( $order_id, $old_status, $new_status, $order ) {
		$data = [
			'provider'   => tribe_tickets_get_ticket_provider( $order_id ),
			'order_id'   => $order_id,
			'old_status' => $old_status,
			'new_status' => $new_status,
		];

		$this->container->make( Refunded_Orders::class )->add_to_queue( $order_id, $data );
	}
}
