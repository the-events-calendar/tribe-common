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
use TEC\Common\StellarWP\Telemetry\Opt_In\Status;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Subscriber;
use Tribe__Container as Container;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Template;

/**
 * Class Telemetry
 *
 * @since   TBD

 * @package TEC\Common\Telemetry
 */
final class Telemetry {
	/**
	 * The plugin slug used for identification
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static $plugin_slug  = '';

	/**
	 * The stellar slug used for identification
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static $stellar_slug  = 'tec';

	/**
	 * The custom hook prefix.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static $hook_prefix = 'tec';

	/**
	 * Array to hold the opt-in args.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private $optin_args = [];

	/**
	 * The slug for the parent plugin.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private static $parent_plugin = '';

	/**
	 * The slugs for the base TEC plugins.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private static $base_parent_slugs = [
		'the-events-calendar',
		'event-tickets'
	];

	/**
	 * Path to main pugin file
	*
	* @since TBD
	*
	* @var string
	*/
	private static $plugin_path = 'tribe-common.php';

	/**
	 * Array for the TEC plugins to add themselves to.
	 *
	 * @since TBD
	 *
	 * @var array<string,string>
	 */
	public static $tec_slugs = [];

	/**
	 * Gentlefolk, start your engines.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function init(): void {
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
		$container = Container::init();
		Config::set_container( $container );

		self::$tec_slugs    = self::get_tec_telemetry_slugs();
		self::$plugin_path  = \Tribe__Main::instance()->get_parent_plugin_file_path();
		self::$stellar_slug = self::get_stellar_slug();
		$telemetry_server   = ! defined('TELEMETRY_SERVER') ? 'https://telemetry.stellarwp.com/api/v1': TELEMETRY_SERVER;

		Config::set_server_url( $telemetry_server );

		// Set a unique prefix for actions & filters.
		Config::set_hook_prefix( self::$hook_prefix );

		// Set a unique plugin slug.
		Config::set_stellar_slug( self::$stellar_slug );

		// Initialize the library.
		Core::instance()->init( self::$plugin_path );

		if ( is_admin() ) {
			$this->register_tec_telemetry_plugins();
		}

		/**
		 * Allow plugins to hook in and add themselves,
		 * running their own actions once Telemetry is initiated.
		 *
		 * @since TBD
		 *
		 * @param self $telemetry The Telemetry instance.
		 */
		do_action( 'tec_common_telemetry_loaded', $this );
	}

	public static function get_plugin_slug() {
		if ( empty( self::$plugin_slug ) ) {
			self::$plugin_slug = self::get_parent_plugin_slug();
		}

		return self::$plugin_slug;
	}

	/**
	 * Get the slug of the parent plugin.
	 * Hydrated lazily.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_parent_plugin_slug(): string {
		if ( empty( self::$parent_plugin ) ) {
			$file = \Tribe__Main::instance()->get_parent_plugin_file_path();
			self::$parent_plugin = substr(
				$file,
				( strrpos( $file, '/' ) + 1 ),
				( strlen( $file ) - ( strrpos( $file, '/' ) + 5 ) )
			);
		}

		return self::$parent_plugin;
	}

	/**
	 * Get the stellar slug based on the parent plugin.
	 *
	 * @since TBD
	 */
	public static function get_stellar_slug(): string {
		$tec_slugs = self::get_tec_telemetry_slugs();

		foreach( $tec_slugs as $slug => $path ) {
			if ( stripos( self::$plugin_path, $path ) ) {
				return $slug;
			}
		}

		return self::$stellar_slug;
	}

	/**
	 * Filters the default optin modal args.
	 *
	 * @since TBD
	 *
	 * @param array<string|mixed> $args The current optin modal args.
	 *
	 * @return array<string|mixed>
	 */
	public function filter_optin_args( $args ): array {
		$user_name   = esc_html( wp_get_current_user()->display_name );

		/*
		if ET only change logo, name to Event Tickets
		if TEC only change logo
		If both, use The Events Calendar
		*/

		$optin_args = [
			'plugin_logo'           => tribe_resource_url( 'images/logo/tec-brand.svg', false, null, \Tribe__Main::instance() ),
			'plugin_logo_width'     => 'auto',
			'plugin_logo_height'    => 42,
			'plugin_logo_alt'       => 'TEC Common Logo',
			'plugin_name'           => 'TEC Common',
			'plugin_slug'           => self::get_plugin_slug(),
			'user_name'             => $user_name,
			'permissions_url'       => self::get_permissions_url(),
			'tos_url'               => self::get_terms_url(),
			'privacy_url'           => self::get_privacy_url(),
			'opted_in_plugins_text' => __( 'See which plugins you have opted in to tracking for', 'tribe-common' ),
			'heading'               => __( 'We hope you love TEC Common!', 'tribe-common' ),
			'intro'                 => __( "Hi, {$user_name}! This is an invitation to help our StellarWP community. If you opt-in, some data about your usage of TEC Common and future StellarWP Products will be shared with our teams (so they can work their butts off to improve). We will also share some helpful info on WordPress, and our products from time to time. And if you skip this, that’s okay! Our products still work just fine.", 'tribe-common' ),
		];

		/**
		 * Allows overriding the modal optin args.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $optin_args The modal arguments to filter.
		 */
		$this->optin_args = apply_filters( 'tec_common_telemetry_optin_args', $optin_args );

		return array_merge( $args, $this->optin_args );
	}

	/**
	 * Get the URL for the permission link in the optin modal.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_permissions_url(): string {
		/**
		 * Allow overriding the permissions URL.
		 *
		 * @since TBD
		 *
		 * @param string $url The URL to the permissions page.
		 */
		return esc_url( apply_filters( 'tec_common_telemetry_permissions_url', 'https://evnt.is/1bcl' ) );
	}

	/**
	 * Get the URL for the Terms of Service link in the optin modal.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_terms_url(): string {
		/**
		 * Allow overriding the Terms of Service URL.
		 *
		 * @since TBD
		 *
		 * @param string $url The URL to the Terms of Service page.
		 */
		return esc_url( apply_filters( 'tec_common_telemetry_terms_url', 'https://evnt.is/1bcm' ) );
	}

	/**
	 * Get the URL for the Privacy Policy link in the optin modal.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_privacy_url(): string {
		/**
		 * Allow overriding the Privacy Policy URL.
		 *
		 * @since TBD
		 *
		 * @param string $url The URL to the Privacy Policy page.
		 */
		return esc_url( apply_filters( 'tec_common_telemetry_privacy_url', 'https://evnt.is/1bcn' ) );
	}

	/**
	 * Filters the exit questionnaire shown during plugin deactivation/uninstall.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $args The current args.
	 *
	 * @return array<string,mixed> $args The modified args.
	 */
	public function filter_exit_interview_args( $args ) {
		$new_args = [
			'plugin_logo'        => tribe_resource_url( 'images/logo/tec-brand.svg', false, null, \Tribe__Main::instance() ),
			'plugin_logo_width'  => 'auto',
			'plugin_logo_height' => 32,
			'plugin_logo_alt'    => 'TEC Common Logo',
			'heading'            => __( 'We’re sorry to see you go.', 'tribe-common' ),
			'intro'              => __( 'We’d love to know why you’re leaving so we can improve our plugin.', 'tribe-common' ),
			'uninstall_reasons'  => [
				[
					'uninstall_reason_id' => 'confusing',
					'uninstall_reason'    => __( 'I couldn’t understand how to make it work.', 'tribe-common' ),
				],
				[
					'uninstall_reason_id' => 'better-plugin',
					'uninstall_reason'    => __( 'I found a better plugin.', 'tribe-common' ),
					'show_comment'        => true,
				],
				[
					'uninstall_reason_id' => 'no-feature',
					'uninstall_reason'    => __( 'I need a specific feature it doesn’t provide.', 'tribe-common' ),
					'show_comment'        => true,
				],
				[
					'uninstall_reason_id' => 'broken',
					'uninstall_reason'    => __( 'The plugin doesn’t work.', 'tribe-common' ),
					'show_comment'        => true,
				],
				[
					'uninstall_reason_id' => 'other',
					'uninstall_reason'    => __( 'Other', 'tribe-common' ),
					'show_comment'        => true,
				],
			],
		];

		return array_merge( $args, $new_args );
	}

	/**
	 * Triggers Telemetry's opt-in modal with our parameters.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function show_optin_modal(): void {
		$plugin_slug = self::get_plugin_slug();

		/**
		 * Filter allowing disabling of the optin modal.
		 * Returning boolean false will disable the modal
		 *
		 * @since TBD
		 *
		 * @param bool $show Whether to show the modal or not.
		 */
		$show = (bool) apply_filters( 'tec_common_telemetry_show_optin_modal', true );

		if ( ! $show ) {
			return;
		}

		/**
		 * Telemetry uses this to determine when/where the optin modal should be shown.
		 * i.e. the modal is shown when we run this.
		 *
		 * @since TBD
		 *
		 * @param string $plugin_slug The slug of the plugin showing the modal.
		 */
		do_action( 'stellarwp/telemetry/optin', $plugin_slug );
	}

	/**
	 * Sugar function to get the status object from the container.
	 *
	 * @since TBD
	 *
	 * @return Status
	 */
	public static function get_status_object(): Status {
		return Config::get_container()->get( Status::class );
	}

	/**
	 * Allows out plugins to hook in and add themselves,
	 * automating a lot of the registration and opt in/out process.
	 *
	 * @since TBD
	 *
	 * @return array<string,string> An array of plugins in the format [ 'plugin_slug' => 'plugin_path' ]
	 */
	public static function get_tec_telemetry_slugs() {
		/**
		 * Filter for plugins to hooked into Telemetry and add themselves.
		 * This acts a Telemetry "registry" for all TEC plugins.
		 * Used to ensure TEC plugins get (de)activated as a group.
		 *
		 * @since TBD
		 *
		 * @param array<string,string> $slugs An array of plugins in the format [ 'plugin_slug' => 'plugin_path' ]
		 */
		return apply_filters( 'tec_telemetry_slugs', [] );
	}

	/**
	 * Register and opt in/out the plugins that are hooked into `tec_telemetry_slugs`.
	 * This keeps all TEC plugins in sync and only requires one optin modal response.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_tec_telemetry_plugins( $opted = NULL ) {
		$new_opted = $opted;
		// Let's reduce the amount this triggers.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$tec_slugs = self::get_tec_telemetry_slugs();

		// We got no other plugins?
		if ( empty( $tec_slugs ) ) {
			return;
		}

		// If we're not specifically passing a status...
		if ( NULL === $opted ) {
			$status = Config::get_container()->get( Status::class );
			// If they have opted in to one plugin, opt them in to all TEC ones.
			// @todo: @camwyn this needs a more sane way to check for StellarWP plugins specifically -
			// other than having to hardcode all the slugs and check them.
			// This will _have to change_ once Telemetry gets used by a non-StellarWP plugin.
			if( is_admin() ) {
				$new_opted = count( $status->get_opted_in_plugins() ) > 0;
			}

			// Finally, if we have manually changed things, use that.
			$tec_option = tribe_get_option( 'opt-in-status', NULL );
			if ( ! is_null( $tec_option ) ) {
				$new_opted = $tec_option;
			}

			// If we still have nothing, opt out by default
			if ( is_null( $new_opted ) ) {
				$new_opted = false;
			}
		}

		foreach ( $tec_slugs as $slug => $path ) {
			// Register each plugin with the already instantiated library.
			Config::add_stellar_slug( $slug, $path );
			$status->add_plugin( $slug, $new_opted, $path );
			$opt_in_subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
			$opt_in_subscriber->initialize_optin_option();
			$opt_in_subscriber->opt_in( $slug );

			// If we have opted in to one TEC plugin, we're opting in to all other TEC plugins as well - or the reverse.
			$status->set_status( $new_opted, $slug );

			if ( $opted ) {
				// Don't show the opt-in modal for this plugin.
				update_option( Config::get_container()->get( Opt_In_Template::class )->get_option_name( $slug ), '0' );
			}
		}
	}
}
