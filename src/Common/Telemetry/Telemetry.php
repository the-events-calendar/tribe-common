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
use Tribe__Container as Container;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Template;

use function ElasticPress\Utils\delete_option;

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
	protected static $plugin_slug  = 'tec';

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
	 * Path to main pugin file
	*
	* @since TBD
	*
	* @var string
	*/
	private static $plugin_path = 'tribe-common.php';

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

		self::$plugin_path = \Tribe__Main::instance()->get_parent_plugin_file();

		// Set the full URL for the Telemetry Server API. Allow overriding reporting server.
		if ( defined('TELEMETRY_SERVER') ) {
            Config::set_server_url( TELEMETRY_SERVER );
        } else {
            Config::set_server_url( 'https://telemetry.stellarwp.com/api/v1' );
        }

		// Set a unique prefix for actions & filters.
		Config::set_hook_prefix( self::$hook_prefix );

		// Set a unique plugin slug.
		Config::set_stellar_slug( self::get_parent_stellar_slug() );

		// Initialize the library.

		Core::instance()->init( self::$plugin_path );

		do_action( 'tec_common_telemetry_loaded', $this );
	}

	public static function get_plugin_slug() {
		return self::$plugin_slug;
	}

	public static function get_hook_prefix() {
		return self::$hook_prefix;
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
			$file = \Tribe__Main::instance()->get_parent_plugin_file();
			self::$parent_plugin = substr( $file, ( strrpos( $file, '/' ) + 1 ), ( strlen( $file ) - ( strrpos( $file, '/' ) + 5 ) ),  );
		}

		return self::$parent_plugin;
	}

	public static function get_parent_stellar_slug() {
		$tec_slugs = self::get_tec_telemetry_slugs();

		foreach( $tec_slugs as $slug => $path ) {
			if ( stripos( self::$plugin_path, $path ) ) {
				return $slug;
			}
		}

		return self::$stellar_slug;
	}

	/**
	 * Get the hook for arguments passed to the opt-in modal.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_optin_arg_hook(): string {
		$slug = self::$hook_prefix;

		return "stellarwp/telemetry/{$slug}/optin_args";
	}

	/**
	 * Get the URL for the permission link.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_permissions_url(): string {
		return esc_url( apply_filters( 'tec_common_telemetry_permissions_url', 'https://evnt.is/1bcl' ) );
	}

	/**
	 * Get the URL for the Terms of Service link
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_terms_url(): string {
		return esc_url( apply_filters( 'tec_common_telemetry_terms_url', 'https://evnt.is/1bcm' ) );
	}

	/**
	 * Get the URL for the Privacy Policy link.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_privacy_url(): string {
		return esc_url( apply_filters( 'tec_common_telemetry_privacy_url', 'https://evnt.is/1bcn' ) );
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
			'plugin_slug'           => self::$plugin_slug,
			'user_name'             => $user_name,
			'permissions_url'       => self::get_permissions_url(),
			'tos_url'               => self::get_terms_url(),
			'privacy_url'           => self::get_privacy_url(),
			'opted_in_plugins_text' => __( 'See which plugins you have opted in to tracking for', 'tribe-common' ),
			'heading'               => __( 'We hope you love TEC Common!', 'tribe-common' ),
			'intro'                 => __( "Hi, {$user_name}! This is an invitation to help our StellarWP community. If you opt-in, some data about your usage of TEC Common and future StellarWP Products will be shared with our teams (so they can work their butts off to improve). We will also share some helpful info on WordPress, and our products from time to time. And if you skip this, that’s okay! Our products still work just fine.", 'tribe-common' ),
		];

		$this->optin_args = apply_filters( 'tec_common_telemetry_optin_args', $optin_args );

		return array_merge( $args, $this->optin_args );
	}

	/**
	 * Filters the exit questionnaire shown during plugin deactivation/uninstall.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $args The default args.
	 *
	 * @return array<string,mixed> $args The modified args.
	 */
	public function filter_exit_interview_args( $args ) {
		$new_args = [
			'plugin_slug'        => self::$plugin_slug,
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
		$plugin_slug = self::$plugin_slug;

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

		do_action( 'stellarwp/telemetry/optin', $plugin_slug );
	}

	/**
	 * Saves the "Opt In Status" setting.
	 *
	 * @return void
	 */
	public function save_opt_in_setting_field(): void {
		// Return early if not saving the Opt In Status field.
		if ( ! isset( $_POST[ 'current-settings-tab' ] ) ) {
			return;
		}

		/**
		 * Filter for the the settings page/tab that the optin control goes on.
		 *
		 * @since TBD
		 *
		 * @param string $tab    The tab slug where the optin control is found.
		 */
		$optin_tab = apply_filters( 'tec_common_telemetry_optin_tab', 'general' );

		$parent = self::get_parent_plugin_slug();

		/**
		 * Parent-specific filter for the the settings page/tab that the optin control goes on.
		 *
		 * @since TBD
		 *
		 * @param string $tab    The tab slug where the optin control is found.
		 */
		$optin_tab = apply_filters( "tec_common_telemetry_{$parent}_optin_tab", $optin_tab );

		if ( $_POST[ 'current-settings-tab' ] !== $optin_tab ) {
			return;
		}

		// Get an instance of the Status class.
		$status = $this->get_status_object();

		// Get the value submitted on the settings page as a boolean.
		$value = ! empty( filter_input( INPUT_POST, 'opt-in-status', FILTER_VALIDATE_BOOLEAN ) );

		$status->set_status( $value, self::$stellar_slug );

		// Gotta catch them all..
		$this->register_tec_telemetry_plugins( $value );

		if ( $value ) {
			// If opting in, blow away the expiration datetime so we send updates on next shutdown.
			delete_option( 'stellarwp_telemetry_last_send' );
		}
	}

	/**
	 * Sugar function to get the status object from the container.
	 *
	 * @since TBD
	 *
	 * @return Status
	 */
	public function get_status_object(): Status {
		return Config::get_container()->get( Status::class );
	}

	/**
	 * The library attempts to set the opt-in status for a site during 'admin_init'. Use the hook with a priority higher
	 * than 10 to make sure you're setting the status after it initializes the option in the options table.
	 */
	function migrate_existing_opt_in(): void {
		$user_has_opted_in_already = get_option( 'fs_accounts' ); // For now.

		if ( $user_has_opted_in_already ) {
			// Get the Opt_In_Subscriber object.
			$Opt_In_Subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
			$Opt_In_Subscriber->opt_in();
		}
	}

	/**
	 * Get the status for *this* plugin, 'cos we don't care about the rest.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public static function get_plugin_status(): bool {
		$hook_prefix = self::$hook_prefix;
		$status_obj  = self::get_status_object();
		$option      = $status_obj->get_option();

		// Avoid checking for missing items
		if ( ! isset( $option['plugins'][self::$plugin_slug]['optin'] ) ) {
			return Status::STATUS_INACTIVE;
		}

		$status = $option['plugins'][self::$plugin_slug]['optin'];

		/**
		 * Filters the opt-in status value.
		 *
		 * @since TBD
		 *
		 * @param boolean $status The opt-in status value.
		 */
		return (bool) apply_filters( "tec_common_telemetry_{$hook_prefix}_optin_status", $status );
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
	public function register_tec_telemetry_plugins( $opted = null ) {
		// Let's reduce the amount this triggers.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$tec_slugs     = self::get_tec_telemetry_slugs();
		$stellar_slugs = Config::get_all_stellar_slugs();

		// We got no other plugins?
		if ( empty( $tec_slugs ) ) {
			return;
		}

		$status = Config::get_container()->get( Status::class );
		$option = $status->get_option();
		if ( NULL === $opted ) {
			$opted = ! empty( $option['plugins'][self::$plugin_slug]['optin'] );
		}

		foreach ( $tec_slugs as $slug => $path ) {
			// Don't re-register plugins (like parent) that are already in there.
			if ( empty( $stellar_slugs[ $slug ] ) ) {
				// Register each plugin with the already instantiated library.
				Config::add_stellar_slug( $slug, $path );
				$status->add_plugin($slug, $opted, $path );

				if ( $opted ) {
					// Don't show the opt-in modal for this plugin.
					update_option( Config::get_container()->get( Opt_In_Template::class )->get_option_name( $slug ), '0' );
				}
			}

			// If we have opted in to one TEC plugin, we're opting in to all other TEC plugins as well - or the reverse.
			$status->set_status( $opted, $slug );
		}
	}
}
