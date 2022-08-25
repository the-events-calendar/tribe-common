<?php
/**
 * The Zapier service provider.
 *
 * @since   TBD
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

use TEC\Common\Traits\With_Nonce_Routes;
use TEC\Common\Zapier\REST\V1\Endpoints\Authorize;
use TEC\Common\Zapier\REST\V1\Endpoints\Swagger_Documentation;

/**
 * Class Event_Status_Provider
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */
class Zapier_Provider extends \tad_DI52_ServiceProvider {
	use With_Nonce_Routes;

	/**
	 * The constant to disable the event status coding.
	 *
	 * @since TBD
	 */
	const DISABLED = 'TEC_ZAPIER_DISABLED';

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		if ( ! self::is_active() ) {
			return;
		}

		// Register the SP on the container
		$this->container->singleton( 'common.zapier.provider', $this );

		$this->add_actions();
		$this->add_filters();

		$this->container->singleton( Authorize::class );
		//$this->container->singleton( Swagger_Documentation::class, new Swagger_Documentation( tribe( 'tickets.rest-v1.main' )->get_semantic_version( ) ) );
		$this->container->singleton( Swagger_Documentation::class, new Swagger_Documentation( '1.0.0' ) );

		/**
		 * Allows filtering of the capability required to use the Zapier integration ajax features.
		 *
		 * @since TBD
		 *
		 * @param string $ajax_capability The capability required to use the ajax features, default manage_options.
		 */
		$ajax_capability = apply_filters( 'tec_common_zapier_admin_ajax_capability', 'manage_options' );

		$this->route_admin_by_nonce( $this->admin_routes(), $ajax_capability );
	}

	/**
	 * Returns whether the event status should register, thus activate, or not.
	 *
	 * @since TBD
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
		 * @since TBD
		 *
		 * @param bool $activate Defaults to `true`.
		 */
		return (bool) apply_filters( 'tec_common_zapier_enabled', true );
	}

	/**
	 * Adds the actions required for event status.
	 *
	 * @since TBD
	 */
	protected function add_actions() {
		add_action( 'tribe_plugins_loaded', [ $this, 'register_admin_assets' ] );
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * Register the Admin Assets for Zapier.
	 *
	 * @since TBD
	 */
	public function register_admin_assets() {
		$this->container->make( Assets::class )->register_admin_assets();
	}

	/**
	 * Adds the filters required by Zapier.
	 *
	 * @since TBD
	 */
	protected function add_filters() {
		add_filter( 'tribe_addons_tab_fields', [ $this, 'filter_addons_tab_fields' ] );
	}

	/**
	 * Filters the fields in the Events > Settings > APIs tab to add the ones provided by the extension.
	 *
	 * @since TBD
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function filter_addons_tab_fields( $fields ) {
		if ( ! is_array( $fields ) ) {
			return $fields;
		}

		return tribe( Settings::class )->add_fields( $fields );
	}

	/**
	 * Registers the REST API endpoints for Zapier
	 *
	 * @since TBD
	 */
	public function register_endpoints() {
		$this->container->make( Authorize::class )->register();
	}

	/**
	 * Provides the routes that should be used to handle Zapier Integration requests.
	 *
	 * The map returned by this method will be used by the `TEC\Common\Traits\With_Nonce_Routes` trait.
	 *
	 * @since TBD
	 *
	 * @return array<string,callable> A map from the nonce actions to the corresponding handlers.
	 */
	public function admin_routes() {
		$actions = tribe( Actions::class );

		return [
			$actions::$add_aki_key_action => $this->container->callback( Api::class, 'ajax_add_api_key' ),
			$actions::$generate_action    => $this->container->callback( Api::class, 'ajax_generate_api_key_pair' ),
			$actions::$revoke_action      => $this->container->callback( Api::class, 'ajax_revoke' ),
		];
	}
}
