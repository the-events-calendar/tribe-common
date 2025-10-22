<?php
/**
 * Handles Telemetry setup and actions.
 *
 * @since 5.1.0
 *
 * @package TEC\Common\Telemetry
 */

namespace TEC\Common\Telemetry;

use TEC\Common\StellarWP\Telemetry\Core;
use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Status;
use Tribe__Container as Container;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Template;
use TEC\Common\StellarWP\Telemetry\Opt_In\Opt_In_Subscriber;

/**
 * Class Telemetry
 *
 * @since 5.1.0

 * @package TEC\Common\Telemetry
 */
final class Telemetry {
	/**
	 * The plugin slug used for identification
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	protected static $plugin_slug = '';

	/**
	 * The custom hook prefix.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	protected static $hook_prefix = 'tec';

	/**
	 * Array to hold the opt-in args.
	 *
	 * @since 5.1.0
	 *
	 * @var array
	 */
	private $optin_args = [];

	/**
	 * The slug for the parent plugin.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	private static $parent_plugin = '';

	/**
	 * The slugs for the base TEC plugins.
	 *
	 * @since 5.1.0
	 *
	 * @var array
	 */
	private static $base_parent_slugs = [
		'the-events-calendar',
		'event-tickets',
	];

	/**
	 * Path to main plugin file
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	private static $plugin_path = '';

	/**
	 * Array for the TEC plugins to add themselves to.
	 *
	 * @since 5.1.0
	 *
	 * @var array<string,string>
	 */
	public static $tec_slugs = [];


	/**
	 * Gentlefolk, start your engines.
	 *
	 * @since 5.1.0
	 *
	 * @return void
	 */
	public function boot(): void {
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

		self::clean_up();

		self::$tec_slugs   = self::get_tec_telemetry_slugs();
		self::$plugin_slug = self::get_parent_plugin_slug();
		self::$plugin_path = \Tribe__Main::instance()->get_parent_plugin_file_path();
		$stellar_slug      = self::get_stellar_slug();

		if ( empty( $stellar_slug ) ) {
			return;
		}

		$telemetry_server = ! defined( 'STELLARWP_TELEMETRY_SERVER' ) ? 'https://telemetry.stellarwp.com/api/v1' : STELLARWP_TELEMETRY_SERVER;

		Config::set_server_url( $telemetry_server );

		// Set a unique prefix for actions & filters.
		Config::set_hook_prefix( self::$hook_prefix );

		// Set a unique plugin slug.
		Config::set_stellar_slug( $stellar_slug );

		if ( empty( self::$plugin_path ) ) {
			return;
		}

		// Initialize the library.
		Core::instance()->init( self::$plugin_path );

		/**
		 * Allow plugins to hook in and add themselves,
		 * running their own actions once Telemetry is initiated,
		 *  but before we register all our plugins.
		 *
		 * @since 5.1.0
		 *
		 * @param self $telemetry The Telemetry instance.
		 */
		do_action( 'tec_common_telemetry_preload', $this );
	}

	/**
	 * Initializes the plugins and triggers the "loaded" action.
	 *
	 * @since 5.1.0
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register_tec_telemetry_plugins();

		/**
		 * Allow plugins to hook in and add themselves,
		 * running their own actions once Telemetry is initiated.
		 *
		 * @since 5.1.0
		 *
		 * @param self $telemetry The Telemetry instance.
		 */
		do_action( 'tec_common_telemetry_loaded', $this );
	}

	/**
	 * Clean up some old data.
	 * If the "tec" plugin exists, and it has no wp_slug, remove it.
	 * This prevents a fatal with the Telemetry library when we call get_opted_in_plugins().
	 *
	 * @since 5.1.1.1
	 *
	 * @return void
	 */
	public static function clean_up(): void {
		$status = self::get_status_object();
		$option = $status->get_option();
		if ( ! empty( $option['plugins']['tec'] ) && empty( $option['plugins']['tec']['wp_slug'] ) ) {
			$status->remove_plugin( 'tec' );
		}
	}

	/**
	 * Get the slug of the plugin.
	 *
	 * @since 5.1.0
	 */
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
	 * @since 5.1.0
	 *
	 * @return string
	 */
	public static function get_parent_plugin_slug(): string {
		if ( empty( self::$parent_plugin ) ) {
			$file                = \Tribe__Main::instance()->get_parent_plugin_file_path();
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
	 * @since 5.1.0
	 */
	public static function get_stellar_slug(): string {
		$tec_slugs = self::get_tec_telemetry_slugs();

		foreach ( $tec_slugs as $slug => $path ) {
			if ( stripos( self::$plugin_path, $path ) ) {
				return $slug;
			}
		}

		return '';
	}

	/**
	 * Filters the default optin modal args.
	 *
	 * @since 5.1.0
	 *
	 * @param array<string|mixed> $args The current optin modal args.
	 * @param ?string             $slug The Stellar slug being used for Telemetry.
	 *
	 * @return array<string|mixed>
	 */
	public function filter_optin_args( $args, $slug = null ): array {
		// Sanity check for slug mismatch.
		if ( ! in_array( $slug, self::$base_parent_slugs, true ) ) {
			return $args;
		}

		$user_name  = esc_html( wp_get_current_user()->display_name );
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
			'intro'                 => sprintf(
				/* Translators: %s is the current user's display name. */
				__( 'Hi, %1$s! This is an invitation to help our StellarWP community. If you opt-in, some data about your usage of TEC Common and future StellarWP Products will be shared with our teams (so they can work their butts off to improve). We will also share some helpful info on WordPress, and our products from time to time. And if you skip this, that\'s okay! Our products still work just fine.', 'tribe-common' ),
				$user_name
			),
		];

		/**
		 * Allows overriding the modal optin args.
		 *
		 * @since 5.1.0
		 *
		 * @param array<string,mixed> $optin_args The modal arguments to filter.
		 */
		$this->optin_args = apply_filters( 'tec_common_telemetry_optin_args', $optin_args );

		return array_merge( $args, $this->optin_args );
	}

	/**
	 * Get the URL for the permission link in the optin modal.
	 *
	 * @since 5.1.0
	 *
	 * @return string
	 */
	public static function get_permissions_url(): string {
		/**
		 * Allow overriding the permissions URL.
		 *
		 * @since 5.1.0
		 *
		 * @param string $url The URL to the permissions page.
		 */
		return esc_url( apply_filters( 'tec_common_telemetry_permissions_url', 'https://evnt.is/1bcl' ) );
	}

	/**
	 * Get the URL for the Terms of Service link in the optin modal.
	 *
	 * @since 5.1.0
	 *
	 * @return string
	 */
	public static function get_terms_url(): string {
		/**
		 * Allow overriding the Terms of Service URL.
		 *
		 * @since 5.1.0
		 *
		 * @param string $url The URL to the Terms of Service page.
		 */
		return esc_url( apply_filters( 'tec_common_telemetry_terms_url', 'https://evnt.is/1bcm' ) );
	}

	/**
	 * Get the URL for the Privacy Policy link in the optin modal.
	 *
	 * @since 5.1.0
	 *
	 * @return string
	 */
	public static function get_privacy_url(): string {
		/**
		 * Allow overriding the Privacy Policy URL.
		 *
		 * @since 5.1.0
		 *
		 * @param string $url The URL to the Privacy Policy page.
		 */
		return esc_url( apply_filters( 'tec_common_telemetry_privacy_url', 'https://evnt.is/1bcn' ) );
	}

	/**
	 * Filters the exit questionnaire shown during plugin deactivation/uninstall.
	 *
	 * @since 5.1.0
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
			'heading'            => __( 'We\'re sorry to see you go.', 'tribe-common' ),
			'intro'              => __( 'We\'d love to know why you\'re leaving so we can improve our plugin.', 'tribe-common' ),
			'uninstall_reasons'  => [
				[
					'uninstall_reason_id' => 'confusing',
					'uninstall_reason'    => __( 'I couldn\'t understand how to make it work.', 'tribe-common' ),
				],
				[
					'uninstall_reason_id' => 'better-plugin',
					'uninstall_reason'    => __( 'I found a better plugin.', 'tribe-common' ),
					'show_comment'        => true,
				],
				[
					'uninstall_reason_id' => 'no-feature',
					'uninstall_reason'    => __( 'I need a specific feature it doesn\'t provide.', 'tribe-common' ),
					'show_comment'        => true,
				],
				[
					'uninstall_reason_id' => 'broken',
					'uninstall_reason'    => __( 'The plugin doesn\'t work.', 'tribe-common' ),
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
	 * @since 5.1.0
	 *
	 * @param string $slug The plugin slug for Telemetry.
	 *
	 * @return void
	 */
	public function show_optin_modal( $slug ): void {
		/**
		 * Filter allowing disabling of the optin modal.
		 * Returning boolean false will disable the modal
		 *
		 * @since 5.1.0
		 *
		 * @param bool $show Whether to show the modal or not.
		 */
		$show = (bool) apply_filters( 'tec_common_telemetry_show_optin_modal', true, $slug );

		if ( ! $show ) {
			return;
		}

		/**
		 * Telemetry uses this to determine when/where the optin modal should be shown.
		 * i.e. the modal is shown when we run this.
		 *
		 * @since 5.1.0
		 *
		 * @param string $plugin_slug The slug of the plugin showing the modal.
		 */
		do_action( 'stellarwp/telemetry/optin', $slug ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
	}

	/**
	 * Sugar function to get the status object from the container.
	 *
	 * @since 5.1.0
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
	 * @since 5.1.0
	 *
	 * @return array<string,string> An array of plugins in the format ['plugin_slug' => 'plugin_path']
	 */
	public static function get_tec_telemetry_slugs() {
		/**
		 * Filter for plugins to hooked into Telemetry and add themselves.
		 * This acts a Telemetry "registry" for all TEC plugins.
		 * Used to ensure TEC plugins get (de)activated as a group.
		 *
		 * @since 5.1.0
		 *
		 * @param array<string,string> $slugs An array of plugins in the format ['plugin_slug' => 'plugin_path']
		 */
		return apply_filters( 'tec_telemetry_slugs', [] );
	}

	/**
	 * Register and opt in/out the plugins that are hooked into `tec_telemetry_slugs`.
	 * This keeps all TEC plugins in sync and only requires one optin modal response.
	 *
	 * @since 5.1.0
	 *
	 * @param bool|null $opted Whether to opt in or out. If null, will calculate based on existing status.
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

		global $pagenow;

		// Only run on the plugins page, or when we're manually setting an opt-in!
		if ( $pagenow !== 'plugins.php' && is_null( $opted ) ) {
			return;
		}

		$tec_slugs = self::get_tec_telemetry_slugs();

		// We've got no other plugins?
		if ( empty( $tec_slugs ) ) {
			return;
		}

		// Check for cached slugs.
		$cached_slugs = tribe( 'cache' )['tec_telemetry_slugs'] ?? null;

		// We have already run and the slug list hasn't changed since then. Or we are manually running.
		if ( is_null( $opted ) && ! empty( $cached_slugs ) && $cached_slugs == $tec_slugs ) {
			return;
		}

		// No cached slugs, or the list has changed, or we're running manually - so (re)set the cached value.
		tribe( 'cache' )['tec_telemetry_slugs'] = $tec_slugs;

		// In case we're not specifically passed a status...
		$new_opted = $this->calculate_optin_status( $opted );
		$status    = Config::get_container()->get( Status::class );

		foreach ( $tec_slugs as $slug => $path ) {
			// Register each plugin with the already instantiated library.
			Config::add_stellar_slug( $slug, $path );
			$status->add_plugin( $slug, $new_opted, $path );

			if ( $new_opted ) {
				$status->set_status( $new_opted, $slug );
			}

			// If we're manually opting in/out, don't show the modal(s).
			if ( ! is_null( $opted ) ) {
				/*
				 * If we originally opted out, there will be no registration token,
				 * so we have to do this to get Telemetry to *register* the site -
				 * else it will never send updates!
				 */
				$status = Config::get_container()->get( Status::class );
				if ( empty( $status->get_token() ) && ! empty( $opted ) ) {
					$opt_in_subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
					$opt_in_subscriber->initialize_optin_option();

					$this->normalize_optin_status();
				}
			}

			$show_modal = self::calculate_modal_status();

			self::disable_modal( $slug, $show_modal );
		}
	}

	/**
	 * This ensures all our entries are the same.
	 * Note - this immediately sets the option to true/false even if it has not yet been set.
	 * DO NOT use this to check the value, use `calculate_optin_status` instead.
	 *
	 * @since 5.1.8.1
	 */
	public function normalize_optin_status(): void {
		// If they have opted in to one plugin, opt them in to all TEC ones.
		$status_obj = self::get_status_object();
		$stati      = [];
		$status     = $this->calculate_optin_status();
		$stati      = array_filter( $stati );

		foreach ( self::$base_parent_slugs as $slug ) {
			if ( $status_obj->plugin_exists( $slug ) ) {
				$status_obj->set_status( (bool) $status, $slug );
			}
		}

		tribe_update_option( 'opt-in-status', $status );
	}

	/**
	 * Calculate the optin status for the TEC plugins from various sources.
	 * Note: if a null value is returned it will be converted to false.
	 *
	 * @since 6.1.0
	 *
	 * @param bool $opted Whether to opt in or out. If null, will calculate based on existing status.
	 *
	 * @return bool $opted
	 */
	public function calculate_optin_status( $opted = null ) {
		if ( null !== $opted ) {
			return $opted;
		}

		// If they have opted in to one plugin, opt them in to all TEC ones.
		$status_obj = self::get_status_object();
		$stati      = [];
		$option     = $status_obj->get_option();

		foreach ( self::$base_parent_slugs as $slug ) {
			if ( $status_obj->plugin_exists( $slug ) ) {
				$stati[ $slug ] = $option['plugins'][ $slug ]['optin'];
			}
		}

		$status = array_filter( $stati );

		return (bool) array_pop( $status );
	}

	/**
	 * Calculate the optin status for the TEC plugins from various sources.
	 *
	 * @since 5.1.1.1
	 * @since 6.9.6 Change option check to prevent false negatives when the option is `false` (user has opted out).
	 *
	 * @return bool $show If the modal should show
	 */
	public static function calculate_modal_status(): bool {
		// If we've already opted in, don't show the modal.
		$option = tribe_get_option( 'opt-in-status', null );
		if ( null !== $option ) {
			return false;
		}

		// If they have already interacted with a modal, find out.
		$shows = array_flip( self::$base_parent_slugs );
		$optin = Config::get_container()->get( Opt_In_Template::class );

		foreach ( self::$base_parent_slugs as $slug ) {
			$show = get_option( $optin->get_option_name( $slug ), null );
			// Remove unset entries from the array.
			if ( is_null( $show ) ) {
				unset( $shows[ $slug ] );
				continue;
			}

			$shows[ $slug ] = (int) $show;
		}

		// No entries - show modal.
		if ( count( $shows ) < 1 ) {
			return true;
		}

		$shows = array_filter(
			$shows,
			function ( $val ) {
				// remove all the truthy values from the array.
				return ! tribe_is_truthy( $val );
			}
		);

		// If we have interacted with any modals, don't show this one.
		return empty( $shows );
	}

	/**
	 * Sugar function to disable (or enable) the optin modal.
	 *
	 * @since 6.1.0
	 *
	 * @param string      $slug   The plugin slug for Telemetry.
	 * @param boolean|int $enable Opt out (0|false) or in (1|true).
	 */
	public static function disable_modal( $slug, $enable = false ) {
		// Ensure we have a integer representation of a boolean value.
		$enable = tec_bool_to_int( tribe_is_truthy( $enable ) );

		$option_slug = Config::get_container()->get( Opt_In_Template::class )->get_option_name( $slug );
		update_option( $option_slug, $enable );
	}
}
