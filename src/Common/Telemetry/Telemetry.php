<?php
/**
 * Handles Telemetry setup and actions.
 *
 * @since   TBD
 *
 * @package TEC\Common\Telemetry
 */
namespace TEC\Common\Telemetry;

use TEC\Common\StellarWP\Telemetry\Core;
use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Subscriber;
use TEC\Common\StellarWP\Telemetry\Opt_In\Status;
use TEC\Common\Container;

/**
 * Class Telemetry
 *
 * @since   TBD

 * @package TEC\Common\Telemetry
 */
class Telemetry {
	/**
	 * The plugin slug used for identification
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $plugin_slug  = 'tec-common';

	/**
	 * The custom hook prefix.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $hook_prefix = 'tec';

	/**
	 * Array to hold the optin args.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private $optin_args = [];

	function init() {
		/**
		 * Configure the container.
		 *
		 * The container must be compatible with stellarwp/container-contract.
		 * See here: https://github.com/stellarwp/container-contract#usage.
		 *
		 * If you do not have a container, we recommend https://github.com/lucatume/di52
		 * and the corresponding wrapper:
		 * https://github.com/stellarwp/container-contract/blob/main/examples/di52/Container.php
		 */
		$container = new Container();
		Config::set_container( $container );

		// Set the full URL for the Telemetry Server API.
		Config::set_server_url( 'https://telemetry-api.moderntribe.qa/api/v1' );

		// Set a unique prefix for actions & filters.
		Config::set_hook_prefix( static::$hook_prefix );

		// Set a unique plugin slug.
		Config::set_stellar_slug( static::$plugin_slug );

		// Initialize the library.
		$path = \Tribe__Main::instance()->plugin_path . 'tribe-common.php';
		error_log($path);
		Core::instance()->init( \Tribe__Main::instance()->plugin_path . 'tribe-common.php' );
	}

	public function filter_optin_args( $args ) {
		$user_name   = esc_html( wp_get_current_user()->display_name );

		$this->optin_args = [
			'plugin_logo'           => tribe_resource_url( 'images/logo/tec-brand.svg', false, null, \Tribe__Main::instance() ),
			'plugin_logo_width'     => 'auto',
			'plugin_logo_height'    => 42,
			'plugin_logo_alt'       => 'The Events Calendar Logo',
			'plugin_name'           => 'The Events Calendar',
			'plugin_slug'           => static::$plugin_slug,
			'user_name'             => $user_name,
			'permissions_url'       => '#',
			'tos_url'               => '#',
			'privacy_url'           => '#',
			'opted_in_plugins_text' => __( 'See which plugins you have opted in to tracking for', 'the-events-calendar' ),
			'heading'               => __( 'We hope you love The Events Calendar.', 'the-events-calendar' ),
			'intro'                 => __( "Hi, {$user_name}! This is an invitation to help our StellarWP community. If you opt-in, some data about your usage of The Events Calendar and future StellarWP Products will be shared with our teams (so they can work their butts off to improve). We will also share some helpful info on WordPress, and our products from time to time. And if you skip this, that’s okay! Our products still work just fine.", 'the-events-calendar' ),
		];

		return array_merge( $args, $this->optin_args );
	}

	/**
	 * Triggers Telemetry's opt-in modal with our parameters.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function do_optin_modal() {
		$plugin_slug = static::$plugin_slug;

		do_action( "stellarwp/telemetry/{$plugin_slug}/optin" );
	}

	/**
	 * Saves the "Opt In Status" setting.
	 *
	 * @return void
	 */
	public function save_opt_in_setting_field() {
		// Return early if not saving the Opt In Status field.
		if ( ! isset( $_POST[ 'opt-in-status' ] ) ) {
			return;
		}

		// Get an instance of the Status class.
		$Status = Config::get_container()->get( Status::class );

		// Get the value submitted on the settings page as a boolean.
		$value = filter_input( INPUT_POST, 'opt-in-status', FILTER_VALIDATE_BOOL );

		$Status->set_status( $value );
	}

	/**
	 * The library attempts to set the opt-in status for a site during 'admin_init'. Use the hook with a priority higher
	 * than 10 to make sure you're setting the status after it initializes the option in the options table.
	 */
	function migrate_existing_opt_in() {
		$user_has_opted_in_already = get_option( 'fs_accounts' ); // For now.

		if ( $user_has_opted_in_already ) {
			// Get the Opt_In_Subscriber object.
			$Opt_In_Subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
			$Opt_In_Subscriber->opt_in();
		}
	}
}
