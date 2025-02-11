<?php
/**
 * Plugin Update Engine Class
 *
 * This is a direct port to Tribe Commons of the PUE classes contained
 * in The Events Calendar.
 *
 * @todo switch all plugins over to use the PUE utilities here in Commons
 */

use TEC\Common\StellarWP\Uplink\Config;

use function TEC\Common\StellarWP\Uplink\get_resource;

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// phpcs:disable PEAR.NamingConventions.ValidClassName.Invalid
// phpcs:disable TEC.Classes.ValidClassName.NotSnakeCase

if ( ! class_exists( 'Tribe__PUE__Checker' ) ) {
	/**
	 * A custom plugin update checker.
	 *
	 * @since  1.7
	 */
	class Tribe__PUE__Checker {

		/**
		 * Plugin filename relative to the plugins' directory.
		 *
		 * @var string
		 */
		private string $plugin_file = '';

		/**
		 * Used to hold the plugin_name as set by the constructor.
		 *
		 * @var string
		 */
		private string $plugin_name = '';

		/**
		 * The plugin slug (without the .php extension)
		 *
		 * @var string
		 */
		protected string $plugin_slug;

		/**
		 * Plugin slug. (with .php extension)
		 *
		 * @var string
		 */
		private string $slug = '';

		/**
		 * Current domain.
		 *
		 * @var string
		 */
		private static string $domain = '';

		/**
		 * Used to hold the query variables for download checks
		 *
		 * @var array
		 */
		private array $download_query = [];

		/**
		 * The context in which this license key is used. May be 'component'
		 * in the case of a downloadable set of files such as a plugin or
		 * theme or else 'service' if the license key is used to utilize a
		 * remote SaaS platform.
		 *
		 * @var string
		 */
		private string $context = 'component';

		/**
		 * How often to check for updates (in hours).
		 *
		 * @var int
		 */
		public int $check_period = 12;

		/**
		 * Where to store the update info.
		 *
		 * @var string
		 */
		public string $pue_option_name = '';

		/**
		 * Where to store the temporary status info.
		 *
		 * @since 4.14.14
		 *
		 * @var string
		 */
		public string $pue_key_status_transient_name;

		/**
		 * Where to store the temporary status info.
		 *
		 * @since 4.14.9
		 *
		 * @var string
		 */
		public string $pue_key_status_option_name;

		/**
		 * Used to hold the install_key if set (included here for addons that will extend PUE to use install key checks).
		 *
		 * @var bool
		 */
		public bool $install_key = false;

		/**
		 * For setting the dismiss upgrade option (per plugin).
		 *
		 * @var string
		 */
		public string $dismiss_upgrade;

		/**
		 * We'll customize this later so each plugin can have its own install key!
		 *
		 * @var string
		 */
		public string $pue_install_key;

		/**
		 * Storing any `plugin_info` data that gets returned so we can display an admin notice.
		 *
		 * @var Tribe__PUE__Plugin_Info|array|null
		 */
		public $plugin_info;

		/**
		 * Storing the `plugin_notice` messages.
		 *
		 * @var array
		 */
		public array $plugin_notice;

		/**
		 * Stats
		 *
		 * @var array
		 */
		private static array $stats = [];

		/**
		 * Full Stats
		 *
		 * @var array
		 */
		private static array $stats_full = [];

		/**
		 * Validation array
		 *
		 * @since 5.1.4
		 *
		 * @var array
		 */
		private array $validate_query = [];

		/**
		 * A unique list of instances of the PUE Checker that has been initialized.
		 *
		 * @since 6.3.2
		 *
		 * @var Tribe__PUE__Checker[] Instances of checkers that have been registered.
		 */
		protected static array $instances = [];

		/**
		 * @var string The transient key.
		 */
		public const IS_ANY_LICENSE_VALID_TRANSIENT_KEY = 'TEC_IS_ANY_LICENSE_VALID_TRANSIENT';

		/**
		 * Class constructor.
		 *
		 * @param string $pue_update_url          Deprecated. The URL of the plugin's metadata file.
		 * @param string $slug                    The plugin's 'slug'.
		 * @param array  $options                 {
		 *                                        Contains any options that need to be set in the class initialization for construct.
		 *
		 * @type integer $check_period            How often to check for updates (in hours). Defaults to checking every
		 *                                        12 hours. Set to 0 to disable automatic update checks.
		 * @type string  $pue_option_name         Where to store book-keeping info about update checks. Defaults to
		 *                                        'external_updates-$slug'.
		 * @type string  $apikey                  Used to authorize download updates from developer server
		 * @type string  $context                 Defaults to 'component' which is expected for plugins (or themes).
		 *                                        If set to 'service' it will not hook into WP update checks.
		 * @type string  $plugin_name             The plugin name, defaults to the name in the plugin file itself.
		 *                                        }
		 *
		 * @param string $plugin_file             Fully qualified path to the main plugin file.
		 */
		public function __construct( $pue_update_url, string $slug = '', $options = [], $plugin_file = '' ) {
			$this->set_slug( $slug );
			$this->set_plugin_file( $plugin_file );
			$this->set_options( $options );
			$this->hooks();
			$this->set_key_status_name();
			$this->init( $slug );
			// So we can reference our "registered" instances later.
			self::$instances[ $slug ] ??= $this;
		}

		/**
		 * Initializes the PUE checker and fires the appropriate action if not already initialized.
		 *
		 * This method ensures that the `tec_pue_checker_init` action is fired only once per unique slug.
		 *
		 * @since 6.4.2
		 *
		 * @param string $slug The unique slug for the plugin being initialized.
		 */
		public function init( string $slug ) {
			if ( isset( self::$instances[ $slug ] ) ) {
				return;
			}

			/**
			 * Fires when initializing the PUE checker.
			 *
			 * @since 6.4.2
			 *
			 * @param Tribe__PUE__Checker $checker An instance of the PUE Checker being initialized.
			 */
			do_action( 'tec_pue_checker_init', $this );
		}

		/**
		 * Gets whether the license key is valid or not.
		 *
		 * @since 4.14.9
		 * @since 6.4.1 Added uplink resource check.
		 * @since 6.4.2 Added check for valid plugin.
		 */
		public function is_key_valid() {
			$uplink_resource = $this->get_uplink_resource( $this->get_slug() );

			if ( $uplink_resource ) {
				$uplink_status = $uplink_resource->has_valid_license();
				$this->update_any_license_valid_transient( $this->get_slug(), tribe_is_truthy( $uplink_status ) );

				return $uplink_status;
			}

			$status = get_transient( $this->pue_key_status_transient_name );

			if ( empty( $status ) ) {
				$status = get_option( $this->pue_key_status_option_name, 'invalid' );
			}

			$this->update_any_license_valid_transient( $this->get_slug(), 'valid' === $status );

			return 'valid' === $status;
		}

		/**
		 * Helper function to check the transient structure and if any plugin is valid.
		 *
		 * @since 6.4.2
		 *
		 * @param array|null $transient_value The current transient value.
		 *
		 * @return bool True if a valid license is found, otherwise false.
		 */
		protected static function transient_contains_valid_license( ?array $transient_value ): bool {
			if ( ! is_array( $transient_value ) || ! isset( $transient_value['plugins'] ) ) {
				return false;
			}

			return in_array( true, $transient_value['plugins'] );
		}

		/**
		 * Updates the license status in the global transient.
		 *
		 * @since 6.4.2
		 *
		 * @param string $plugin_slug The slug of the plugin being updated.
		 * @param bool   $status      The license status.
		 */
		protected static function update_any_license_valid_transient( string $plugin_slug, bool $status ): void {
			$transient_value = get_transient( self::IS_ANY_LICENSE_VALID_TRANSIENT_KEY ) ?: [ 'plugins' => [] ];

			// If the transient value is false or a string, initialize it as an empty array with the 'plugins' key.
			if ( ! is_array( $transient_value ) ) {
				$transient_value = [ 'plugins' => [] ];
			}

			$transient_value['plugins'][ $plugin_slug ] = $status;

			set_transient( self::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, $transient_value, HOUR_IN_SECONDS );
		}

		/**
		 * Iterate on all the registered PUE Product Licenses we have and find if any are valid.
		 * Will revalidate the licenses if none are found to be valid.
		 *
		 * @since 6.3.2
		 * @since 6.4.2 Refactored logic to account for the transient structure.
		 *
		 * @return bool
		 */
		public static function is_any_license_valid(): bool {
			$transient_value = get_transient( self::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

			if ( ! is_array( $transient_value ) ) {
				$transient_value = [];
			}

			// Check if the transient has a valid license.
			if ( self::transient_contains_valid_license( $transient_value ) ) {
				return true;
			}

			// Ensure instances exist.
			if ( empty( self::$instances ) ) {
				set_transient( self::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, [ 'plugins' => [] ], HOUR_IN_SECONDS );

				return false;
			}

			// Revalidate licenses.
			foreach ( self::$instances as $plugin_slug => $checker ) {
				// First, check if the key is already valid.
				if ( $checker->is_key_valid() ) {
					self::update_any_license_valid_transient( $plugin_slug, true );

					return true;
				}

				// If not valid, attempt to revalidate the license.
				$license  = get_option( $checker->get_license_option_key() );
				$response = $checker->validate_key( $license );

				if ( ! empty( $response['status'] ) ) {
					self::update_any_license_valid_transient( $plugin_slug, true );

					return true;
				} else {
					self::update_any_license_valid_transient( $plugin_slug, false );
				}
			}

			return false;
		}

		/**
		 * Gets whether the PUE key validation check is expired.
		 *
		 * @since 4.14.9
		 * @since 6.5.1 Updated `$option_expiration` logic.
		 */
		public function is_key_validation_expired(): bool {
			// Check if a transient exists; if so, the key is not expired.
			if ( get_transient( $this->pue_key_status_transient_name ) ) {
				return false;
			}

			// Retrieve the timeout value from the options table.
			$option_expiration = get_option( "{$this->pue_key_status_option_name}_timeout", null );

			// Expired if the timeout is missing or the current time exceeds the stored expiration.
			return empty( $option_expiration ) || ( time() > $option_expiration );
		}

		/**
		 * Set the PUE key status property names.
		 *
		 * @since 4.14.9
		 */
		public function set_key_status_name(): void {
			$this->pue_key_status_option_name = 'pue_key_status_' . $this->get_slug() . '_' . $this->get_site_domain();

			$this->pue_key_status_transient_name = md5( $this->get_slug() . $this->get_site_domain() );
		}

		/**
		 * Sets the key status based on the key validation check results.
		 *
		 * @since 4.14.9
		 * @since 6.4.2 Clear `self::IS_ANY_LICENSE_VALID_TRANSIENT_KEY` when a key is checked.
		 * @since 6.5.1 Updated the value of `{$this->pue_key_status_option_name}_timeout` to be the expiration date of when we should recheck the license.
		 *
		 * @param int $valid 0 for invalid, 1 or 2 for valid.
		 */
		public function set_key_status( $valid ): void {
			$status     = tribe_is_truthy( $valid ) ? 'valid' : 'invalid';
			$expiration = time() + ( $this->check_period * HOUR_IN_SECONDS );
			update_option( $this->pue_key_status_option_name, $status );
			update_option( "{$this->pue_key_status_option_name}_timeout", $expiration );

			// We set a transient in addition to an option for compatibility reasons.
			set_transient( $this->pue_key_status_transient_name, $status, $this->check_period * HOUR_IN_SECONDS );

			// Update the global license transient.
			self::update_any_license_valid_transient( $this->get_slug(), tribe_is_truthy( $valid ) );
		}

		/**
		 * Install the hooks required to run periodic update checks and inject update info
		 * into WP data structures.
		 * Also, other hooks related to the automatic updates (such as checking against API and what not (@from Darren)
		 *
		 * @since 6.5.1 Added `initialize_license_check` action.
		 * @since 6.5.1.1 Moved `monitor_active_plugins` and `initialize_license_check` to `setup_pue_license_hooks`, and run on `admin_init`.
		 */
		public function hooks(): void {
			// Override requests for plugin information.
			add_filter( 'plugins_api', [ $this, 'inject_info' ], 10, 3 );

			// Check for updates when the WP updates are checked and inject our update if needed.
			// Only add filter if the TRIBE_DISABLE_PUE constant is not set as true and where
			// the context is not 'service'.
			if ( ( ! defined( 'TRIBE_DISABLE_PUE' ) || true !== TRIBE_DISABLE_PUE ) && 'service' !== $this->context ) {
				add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_for_updates' ] );
			}

			add_filter( 'tribe_licensable_addons', [ $this, 'build_addon_list' ] );
			add_filter( 'tribe_license_fields', [ $this, 'do_license_key_fields' ] );
			add_action( 'tribe_settings_after_content_tab_licenses', [ $this, 'do_license_key_javascript' ] );
			add_action( 'tribe_settings_success_message', [ $this, 'do_license_key_success_message' ], 10, 2 );
			add_action( 'load-plugins.php', [ $this, 'remove_default_inline_update_msg' ], 50 );

			// Key validation.
			add_action( 'tribe_settings_save_field', [ $this, 'check_for_api_key_error_on_action' ], 10, 2 );
			add_action( 'wp_ajax_pue-validate-key_' . $this->get_slug(), [ $this, 'ajax_validate_key' ] );
			add_filter( 'tribe-pue-install-keys', [ $this, 'return_install_key' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'maybe_display_json_error_on_plugins_page' ], 1 );

			// Package name.
			add_filter( 'upgrader_pre_download', [ Tribe__PUE__Package_Handler::instance(), 'filter_upgrader_pre_download' ], 5, 3 );

			add_action( 'admin_init', [ $this, 'setup_admin_init_pue_license_hooks' ] );
		}

		/**
		 * Initializes and registers the PUE license check and active plugin monitoring.
		 * This method is triggered on `admin_init`.
		 *
		 * @since 6.5.1.1
		 * @return void
		 */
		public function setup_admin_init_pue_license_hooks() {
			self::monitor_active_plugins( $this );
			$this->general_notifications();
			$this->initialize_license_check( $this );
			$this->monitor_uplink_actions();
		}

		/********************** Getter / Setter Functions **********************/

		/**
		 * Get the slug.
		 *
		 * @return string The slug for the plugin.
		 */
		public function get_slug(): string {
			/**
			 * Filters the slug for the plugin.
			 *
			 * @since 5.2.0
			 *
			 * @param string $slug The default slug for the plugin.
			 */
			return (string) apply_filters( 'pue_get_slug', $this->slug );
		}

		/**
		 * Set the slug.
		 *
		 * @param string $slug The plugin slug.
		 */
		private function set_slug( string $slug = '' ): void {
			$this->slug            = $slug;
			$clean_slug            = str_replace( '-', '_', $this->slug );
			$this->dismiss_upgrade = 'pu_dismissed_upgrade_' . $clean_slug;
			$this->pue_install_key = 'pue_install_key_' . $clean_slug;
		}

		/**
		 * Get the PUE update API endpoint url.
		 *
		 * @return string
		 */
		public function get_pue_update_url(): string {
			$pue_update_url = 'https://pue.theeventscalendar.com';

			if ( defined( 'PUE_UPDATE_URL' ) ) {
				$pue_update_url = PUE_UPDATE_URL;
			}

			/**
			 * Filters the PUE update URL.
			 *
			 * This filter allows developers to modify the PUE update URL dynamically based on the slug or other context.
			 *
			 * @since 5.2.0
			 *
			 * @param string $pue_update_url The default PUE update URL.
			 * @param string $slug           The slug of the plugin being updated.
			 */
			$pue_update_url = (string) apply_filters( 'pue_get_update_url', $pue_update_url, $this->get_slug() );

			$pue_update_url = untrailingslashit( $pue_update_url );

			return $pue_update_url;
		}

		/**
		 * Get the plugin file path.
		 *
		 * @return string
		 */
		public function get_plugin_file(): string {
			/**
			 * Filters the plugin file path for the current plugin.
			 *
			 * @since 5.2.0
			 *
			 * @param string $plugin_file The default plugin file path.
			 * @param string $slug        The slug of the current plugin.
			 */
			return (string) apply_filters( 'pue_get_plugin_file', $this->plugin_file, $this->get_slug() );
		}

		/**
		 * Set the plugin file path.
		 *
		 * @param string $plugin_file The plugin file path.
		 */
		private function set_plugin_file( string $plugin_file = '' ): void {
			if ( ! empty( $plugin_file ) ) {
				$this->plugin_file = $plugin_file;

				return;
			}

			$slug = $this->get_slug();
			if ( ! empty( $slug ) ) {
				$this->plugin_file = $slug . '/' . $slug . '.php';
			}
		}

		/**
		 * Set the plugin name.
		 *
		 * @since 6.5.1 Switched to early bail.
		 *
		 * @param string $plugin_name The plugin name.
		 */
		private function set_plugin_name( string $plugin_name = '' ): void {
			if ( ! empty( $plugin_name ) ) {
				$this->plugin_name = $plugin_name;

				return;
			}

			// Get name from plugin file itself.
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			// Prevents get_plugins from throwing a weird notice.
			if ( ! file_exists( WP_PLUGIN_DIR . '/' . $this->get_plugin_file() ) ) {
				return;
			}

			$plugin_details    = explode( '/', $this->get_plugin_file() );
			$plugin_folder     = get_plugins( '/' . $plugin_details[0] );
			$this->plugin_name = isset( $plugin_details[1] ) && isset( $plugin_folder[ $plugin_details[1] ] ) ? $plugin_folder[ $plugin_details[1] ]['Name'] : '';
		}

		/**
		 * Get the plugin name.
		 *
		 * @return string
		 */
		public function get_plugin_name(): string {
			if ( empty( $this->plugin_name ) ) {
				$this->set_plugin_name();
			}

			/**
			 * Filters the plugin name.
			 *
			 * @since 5.2.0
			 *
			 * @param string $plugin_name The current plugin name.
			 * @param string $slug        The slug of the plugin.
			 */
			return (string) apply_filters( 'pue_get_plugin_name', $this->plugin_name, $this->get_slug() );
		}

		/**
		 * Set all the PUE instantiation options.
		 *
		 * @param array $options Passed instantiation options.
		 */
		private function set_options( array $options = [] ): void {
			$options = wp_parse_args(
				$options,
				[
					'pue_option_name' => 'external_updates-' . $this->get_slug(),
					'apikey'          => '',
					'check_period'    => 12,
					'context'         => 'component',
					'plugin_name'     => '',
				]
			);

			$this->pue_option_name = $options['pue_option_name'];
			$this->check_period    = (int) $options['check_period'];
			$this->context         = $options['context'];
			$this->plugin_name     = $options['plugin_name'];
		}

		/**
		 * Set all the download query array
		 *
		 * @param array $download_query Passed download query.
		 */
		private function set_download_query( array $download_query = [] ): void {
			if ( ! empty( $download_query ) ) {
				$this->download_query = $download_query;

				return;
			}

			// Plugin slug.
			$this->download_query['plugin'] = sanitize_text_field( $this->get_slug() );

			// Include current version.
			$this->download_query['installed_version'] = sanitize_text_field( $this->get_installed_version() );

			$this->download_query['domain'] = sanitize_text_field( $this->get_domain() );

			// Get general stats.
			$stats = $this->get_stats();

			$this->download_query['multisite']         = $stats['network']['multisite'];
			$this->download_query['network_activated'] = $stats['network']['network_activated'];
			$this->download_query['active_sites']      = $stats['network']['active_sites'];
			$this->download_query['wp_version']        = $stats['versions']['wp'];

			// The following is for install key inclusion (will apply later with PUE addons).
			$this->download_query['key'] = sanitize_text_field( $this->get_key() );
			$this->download_query['dk']  = sanitize_text_field( $this->get_key( 'default' ) );
			$this->download_query['o']   = sanitize_text_field( $this->get_key( 'any', 'origin' ) );
		}

		/**
		 * Get the download_query args
		 *
		 * @return array
		 */
		public function get_download_query(): array {
			if ( empty( $this->download_query ) ) {
				$this->set_download_query();
			}

			/**
			 * Filters the download query arguments.
			 *
			 * @since 5.2.0
			 *
			 * @param array  $download_query The current download query arguments.
			 * @param string $slug           The slug of the plugin.
			 */
			return (array) apply_filters( 'pue_get_download_query', $this->download_query, $this->get_slug() );
		}

		/**
		 * Set all the validate query array.
		 *
		 * @param array $validate_query Passed validate query params.
		 */
		private function set_validate_query( array $validate_query = [] ): void {
			if ( ! empty( $validate_query ) ) {
				$this->validate_query = $validate_query;

				return;
			}

			// The following is for install key inclusion (will apply later with PUE addons).
			$this->validate_query['key'] = sanitize_text_field( $this->get_key() );

			// Include default key.
			$this->validate_query['default_key'] = sanitize_text_field( $this->get_key( 'default' ) );

			// Include license origin.
			$this->validate_query['license_origin'] = sanitize_text_field( $this->get_key( 'any', 'origin' ) );

			// Plugin slug.
			$this->validate_query['plugin'] = sanitize_text_field( $this->get_slug() );

			// Include current version.
			$this->validate_query['version'] = sanitize_text_field( $this->get_installed_version() );

			// Include current domain.
			$this->validate_query['domain'] = sanitize_text_field( $this->get_domain() );

			// Include plugin stats.
			$this->validate_query['stats'] = $this->get_stats();
		}

		/**
		 * Get the validate_query args.
		 *
		 * @return array
		 */
		public function get_validate_query() {
			if ( empty( $this->validate_query ) ) {
				$this->set_validate_query();
			}

			/**
			 * Filters the validate query arguments.
			 *
			 * @since 5.2.0
			 *
			 * @param array  $validate_query The current validate query arguments.
			 * @param string $slug           The slug of the plugin.
			 */
			return (array) apply_filters( 'pue_get_validate_query', $this->validate_query, $this->get_slug() );
		}

		/**
		 * Get current domain.
		 *
		 * @return string
		 */
		public function get_domain(): string {
			$domain = self::$domain;

			if ( empty( $domain ) ) {
				$url = wp_parse_url( get_option( 'siteurl' ) );
				if ( ! empty( $url ) && isset( $url['host'] ) ) {
					$domain = $url['host'];
				} elseif ( isset( $_SERVER['SERVER_NAME'] ) ) {
					$domain = $_SERVER['SERVER_NAME'];
				}

				if ( is_multisite() ) {
					// For multisite, return the network-level siteurl.
					$domain = $this->get_network_domain();
				}

				self::$domain = $domain;
			}

			return $domain;
		}


		/********************** General Functions **********************/

		/**
		 * Compile a list of addons.
		 *
		 * @param array $addons List of addons.
		 *
		 * @return array List of addons.
		 */
		public function build_addon_list( array $addons = [] ): array {
			$addons[] = $this->get_plugin_name();

			return $addons;
		}

		/**
		 * Inserts license key fields on license key page
		 *
		 * @param array $fields List of fields.
		 *
		 * @return array Modified list of fields.
		 */
		public function do_license_key_fields( array $fields ): array {
			// Common fields whether licenses should be hidden or not.
			$to_insert = [
				$this->pue_install_key . '-heading' => [
					'type'  => 'heading',
					'class' => [
						'tec-settings-form__section-header',
						'tec-settings-form__section-header--sub',
					],
					'label' => $this->get_plugin_name(),
				],
			];

			$no_license_tooltip = esc_html__( 'A valid license key is required for support and updates', 'tribe-common' );
			if ( 'event-aggregator' === $this->get_slug() ) {
				$no_license_tooltip = sprintf(
				/* Translators: %1$s and %2$s are opening and closing <a> tags, respectively. */
					esc_html__( '%1$sBuy a license%2$s for the Event Aggregator service to access additional import features.', 'tribe-common' ),
					'<a href="https://evnt.is/196y" target="_blank">',
					'</a>'
				);
			}

			// We want to inject the following license settings at the end of the licenses tab.
			if ( $this->should_show_network_editable_license() ) {
				$to_insert[ $this->pue_install_key ] = [
					'type'            => 'license_key',
					'size'            => 'large',
					'validation_type' => 'license_key',
					'label'           => sprintf( esc_attr__( 'License Key', 'tribe-common' ) ),
					'default'         => $this->get_key( 'default' ),
					'tooltip'         => $no_license_tooltip,
					'parent_option'   => false,
					'network_option'  => true,
				];
			} elseif ( $this->should_show_subsite_editable_license() ) {
				$to_insert[ $this->pue_install_key ] = [
					'type'            => 'license_key',
					'size'            => 'large',
					'validation_type' => 'license_key',
					'label'           => sprintf( esc_attr__( 'License Key', 'tribe-common' ) ),
					'default'         => $this->get_key( 'default' ),
					'tooltip'         => $no_license_tooltip,
					'parent_option'   => false,
					'network_option'  => false,
				];
			} elseif ( $this->should_show_overrideable_license() ) {
				$to_insert[ $this->pue_install_key . '-state' ] = [
					'type'             => 'html',
					'label'            => sprintf( esc_attr__( 'License Key Status:', 'tribe-common' ) ),
					'label_attributes' => [ 'style' => 'width:auto;' ],
					'html'             => sprintf( '<p>%s</p>', $this->get_network_license_state_string() ),
				];

				$override_id = $this->pue_install_key . '-override';

				$to_insert[ $override_id ] = [
					'type'            => 'checkbox_bool',
					'label'           => esc_html__( 'Override network license key', 'tribe-common' ),
					'tooltip'         => esc_html__( 'Check this box if you wish to override the network license key with your own', 'tribe-common' ),
					'default'         => false,
					'validation_type' => 'boolean',
					'parent_option'   => false,
					'attributes'      => [ 'id' => $override_id . '-field' ],
				];

				$to_insert[ $this->pue_install_key ] = [
					'type'                => 'license_key',
					'size'                => 'large',
					'validation_type'     => 'license_key',
					'label'               => sprintf( esc_attr__( 'Site License Key', 'tribe-common' ) ),
					'tooltip'             => $no_license_tooltip,
					'parent_option'       => false,
					'network_option'      => false,
					'class'               => 'tribe-dependent',
					'fieldset_attributes' => [
						'data-depends'           => '#' . $override_id . '-field',
						'data-condition-checked' => true,
					],
				];
			} else {
				$to_insert[ $this->pue_install_key . '-state' ] = [
					'type'             => 'html',
					'label'            => sprintf( esc_attr__( 'License Key Status:', 'tribe-common' ) ),
					'label_attributes' => [ 'style' => 'width:auto;' ],
					'html'             => sprintf( '<p>%s</p>', $this->get_network_license_state_string() ),
				];
			}

			$to_insert = tribe( 'settings' )->wrap_section_content( $this->pue_install_key, $to_insert );

			$fields = self::array_insert_after_key( 'tribe-form-content-start', $fields, $to_insert );

			return $fields;
		}

		/**
		 * Inserts the javascript that makes the ajax checking
		 * work on the license key page
		 */
		public function do_license_key_javascript(): void {
			?>
			<script>
				jQuery( document ).ready( function ( $ ) {
					$( '.tribe-field-license_key' ).each( function () {
						var $el = $( this );
						var $field = $el.find( 'input' );

						if ( '' === $field.val().trim() ) {
							$el.find( '.license-test-results' ).hide();
						}
					} );

					$( '#tribe-field-<?php echo esc_attr( $this->pue_install_key ); ?>' ).change( function () {
						<?php echo sanitize_html_class( $this->pue_install_key ); ?>_validateKey();
					} );
					<?php echo sanitize_html_class( $this->pue_install_key ); ?>_validateKey();
				} );

				function <?php echo sanitize_html_class( $this->pue_install_key ); ?>_validateKey() {
					var this_id = '#tribe-field-<?php echo esc_attr( $this->pue_install_key ); ?>';
					var $validity_msg = jQuery( this_id + ' .key-validity' );

					if ( jQuery( this_id + ' input' ).val() != '' ) {
						jQuery( this_id + ' .license-test-results' ).show();
						jQuery( this_id + ' .tooltip' ).hide();
						jQuery( this_id + ' .ajax-loading-license' ).show();
						$validity_msg.hide();

						// Strip whitespace from key
						var <?php echo sanitize_html_class( $this->pue_install_key ); ?>_license_key = jQuery( this_id + ' input' )
							.val()
							.replace(
								/^\s+|\s+$/g,
								""
							);
						jQuery( this_id + ' input' ).val( <?php echo sanitize_html_class( $this->pue_install_key ); ?>_license_key );

						var data = {
							action: 'pue-validate-key_<?php echo esc_attr( $this->get_slug() ); ?>',
							key: <?php echo sanitize_html_class( $this->pue_install_key ); ?>_license_key,
							_wpnonce: '<?php echo esc_attr( wp_create_nonce( 'pue-validate-key_' . $this->get_slug() ) ); ?>'
						};
						jQuery.post(
							ajaxurl,
							data,
							function ( response ) {
								var data = jQuery.parseJSON( response );

								jQuery( this_id + ' .ajax-loading-license' ).hide();
								$validity_msg.show();
								$validity_msg.html( data.message );

								switch ( data.status ) {
									case 1:
										$validity_msg.addClass( 'valid-key' ).removeClass( 'invalid-key' );
										break;
									case 2:
										$validity_msg.addClass( 'valid-key service-msg' );
										break;
									default:
										$validity_msg.addClass( 'invalid-key' ).removeClass( 'valid-key' );
										break;
								}
							}
						);
					}
				}
			</script>
			<?php
		}

		/**
		 * Filter the success message on license key page.
		 *
		 * @param string $message Success message.
		 * @param string $tab     Tab name.
		 *
		 * @return string
		 */
		public function do_license_key_success_message( string $message, string $tab ): string {
			if ( 'licenses' !== $tab ) {
				return $message;
			}

			return '<div id="message" class="updated"><p><strong>' . esc_html__( 'License key(s) updated.', 'tribe-common' ) . '</strong></p></div>';
		}

		/**
		 * Build stats for endpoints.
		 *
		 * @return array
		 */
		public function build_stats() {
			global $wpdb;

			$stats = [
				'versions' => [
					'wp' => sanitize_text_field( $GLOBALS['wp_version'] ),
				],
				'network'  => [
					'multisite'         => 0,
					'network_activated' => 0,
					'active_sites'      => 1,
				],
			];

			if ( is_multisite() ) {
				$sql_count = "
					SELECT COUNT( `blog_id` )
					FROM `{$wpdb->blogs}`
					WHERE
						`public` = '1'
						AND `archived` = '0'
						AND `spam` = '0'
						AND `deleted` = '0'
				";

				$stats['network']['multisite']         = 1;
				$stats['network']['network_activated'] = (int) $this->is_plugin_active_for_network();
				$stats['network']['active_sites']      = (int) $wpdb->get_var( $sql_count );
			}

			self::$stats = $stats;

			return $stats;
		}

		/**
		 * Build full stats for endpoints.
		 *
		 * @param array $stats Initial stats.
		 *
		 * @return array
		 */
		public function build_full_stats( array $stats ): array {
			global $wpdb;

			$theme = wp_get_theme();

			$current_offset = (int) get_option( 'gmt_offset', 0 );
			$tzstring       = get_option( 'timezone_string' );

			// Remove old Etc mappings. Fallback to gmt_offset.
			if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
				$timezone = '';
			}

			// Create a UTC+- zone if no timezone string exists.
			if ( empty( $tzstring ) ) {
				if ( 0 === $current_offset ) {
					$timezone = 'UTC+0';
				} elseif ( $current_offset < 0 ) {
					$timezone = 'UTC' . $current_offset;
				} else {
					$timezone = 'UTC+' . $current_offset;
				}
			}

			$stats['versions'] = [
				'wp'    => sanitize_text_field( $GLOBALS['wp_version'] ),
				'php'   => sanitize_text_field( phpversion() ),
				'mysql' => sanitize_text_field( $wpdb->db_version() ),
			];

			$stats['theme'] = [
				'name'       => sanitize_text_field( $theme->get( 'Name' ) ),
				'version'    => sanitize_text_field( $theme->get( 'Version' ) ),
				'stylesheet' => sanitize_text_field( $theme->get_stylesheet() ),
				'template'   => sanitize_text_field( $theme->get_template() ),
			];

			$stats['site_language'] = sanitize_text_field( get_locale() );
			$stats['user_language'] = sanitize_text_field( get_user_locale() );
			$stats['is_public']     = (int) get_option( 'blog_public', 0 );
			$stats['wp_debug']      = (int) ( defined( 'WP_DEBUG' ) && WP_DEBUG );
			$stats['site_timezone'] = sanitize_text_field( $timezone );

			$stats['totals'] = [
				'all_post_types'   => (int) $wpdb->get_var( "SELECT COUNT(*) FROM `{$wpdb->posts}`" ),
				'events'           => (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `{$wpdb->posts}` WHERE post_type = %s", 'tribe_events' ) ),
				'venues'           => (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `{$wpdb->posts}` WHERE post_type = %s", 'tribe_venue' ) ),
				'organizers'       => (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `{$wpdb->posts}` WHERE post_type = %s", 'tribe_organizer' ) ),
				'event_categories' => (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `{$wpdb->term_taxonomy}` WHERE taxonomy = %s", 'tribe_events_cat' ) ),
			];

			self::$stats_full = $stats;

			return $stats;
		}

		/**
		 * Build and get the stats.
		 *
		 * @return array
		 */
		public function get_stats(): array {
			$stats = self::$stats;

			if ( empty( $stats ) ) {
				$stats = $this->build_stats();
			}

			/**
			 * Allow full stats data to be built and sent.
			 *
			 * @since 4.5.1
			 *
			 * @param boolean $use_full_stats Whether to send full stats.
			 */
			$use_full_stats = (bool) apply_filters( 'pue_use_full_stats', false );

			if ( $use_full_stats ) {
				$stats_full = self::$stats_full;

				if ( empty( $stats_full ) ) {
					$stats = $this->build_full_stats( $stats );
				}
			}

			/**
			 * Filter stats and allow plugins to add their own stats
			 * for tracking specific points of data.
			 *
			 * @since 4.5.1
			 *
			 * @param array                $stats          Stats gathered by PUE Checker class.
			 * @param boolean              $use_full_stats Whether to send full stats.
			 * @param Tribe__PUE__Checker $checker        PUE Checker class object.
			 */
			$stats = (array) apply_filters( 'pue_stats', $stats, $use_full_stats, $this );

			return $stats;
		}

		/**
		 * Get current license key, optionally of a specific type.
		 *
		 * @param string $type        The type of key to get (any, network, local, default).
		 * @param string $return_type The type of data to return (key, origin).
		 *
		 * @return string
		 */
		public function get_key( string $type = 'any', string $return_type = 'key' ): string {
			$resource    = $this->get_uplink_resource( $this->get_slug() );
			$license_key = $resource ? $resource->get_license_key( $type ) : false;
			if ( $license_key ) {
				return $license_key;
			}

			$license_key    = '';
			$license_origin = 'm';

			/*
			 * Even if we have a network key if the plugin is not active on the network then it should
			 * not be used.
			 */
			if (
				( 'network' === $type || 'any' === $type )
				&& is_multisite()
				&& $this->is_plugin_active_for_network()
			) {
				$license_key = get_network_option( null, $this->pue_install_key, '' );
			}

			if ( ( 'local' === $type || 'any' === $type ) && empty( $license_key ) ) {
				$license_key = get_option( $this->pue_install_key, '' );
			}

			if ( empty( $license_key ) && ( 'default' === $type || 'any' === $type ) ) {
				$autoloader = Tribe__Autoloader::instance();

				$class_name = $autoloader->get_prefix_by_slug( $this->get_slug() );

				if ( $class_name ) {
					$is_namespaced = false !== strpos( $class_name, '\\' );

					if ( $is_namespaced ) {
						// Handle class prefixes like Tribe\Plugin\.
						$class_name .= 'PUE\Helper';
					} else {
						// Handle class prefixes like Tribe__Plugin__.
						$class_name .= 'PUE__Helper';
					}

					if ( constant( $class_name . '::DATA' ) ) {
						$license_key = constant( $class_name . '::DATA' );

						$license_origin = 'e';
					}
				}
			}

			if ( 'origin' === $return_type ) {
				if ( 'm' === $license_origin ) {
					$default_key = $this->get_key( 'default' );

					if ( $license_key !== $default_key ) {
						$license_origin = 'o';
					}
				}

				return $license_origin;
			}

			return $license_key;
		}

		/**
		 * Update license key for specific type of license.
		 *
		 * @param string $license_key The new license key value.
		 * @param string $type        The type of key to update (network or local).
		 */
		public function update_key( string $license_key, string $type = 'local' ): void {
			if ( 'network' === $type && is_multisite() ) {
				update_network_option( null, $this->pue_install_key, sanitize_text_field( $license_key ) );
			} elseif ( 'local' === $type ) {
				update_option( $this->pue_install_key, sanitize_text_field( $license_key ) );
			}
		}

		/**
		 * Checks for the license key status with MT servers.
		 *
		 * @since 6.5.1.1 Bail early if no license key is found.
		 *
		 * @param string $key     The license key to check.
		 * @param bool   $network Whether the key to check for is a network one or not.
		 *
		 * @return array An associative array containing the license status response.
		 */
		public function validate_key( string $key, bool $network = false ): array {
			$response           = [];
			$response['status'] = 0;

			if ( empty( $key ) ) {
				return [];
			}

			$uplink_resource = $this->get_uplink_resource( $this->get_slug() );

			if ( $uplink_resource ) {
				$key = $uplink_resource->get_license_key();
			}

			if ( ! $key ) {
				$response['message'] = sprintf(
				/* Translators: %1$s and %2$s are opening and closing <a> tags, respectively. */
					esc_html__(
						'Hmmm... something\'s wrong with this validator. Please contact %1$ssupport%2$s.',
						'tribe-common'
					),
					'<a href="https://evnt.is/1u">',
					'</a>'
				);

				return $response;
			}

			$query_args = $this->get_validate_query();

			$query_args['key'] = sanitize_text_field( $key );

			// This method is primarily used during when validating keys by ajax, before they are
			// formally committed or saved by the user: for that reason we call request_info()
			// rather than license_key_status() as at this stage invalid or missing keys should
			// not result in admin notices being generated.
			$plugin_info = $this->request_info( $query_args );
			$expiration  = $plugin_info->expiration ?? __( 'unknown date', 'tribe-common' );

			$pue_notices = tribe( 'pue.notices' );
			$plugin_name = $this->get_plugin_name();

			if ( empty( $plugin_info ) ) {
				$response['message'] = __( 'Sorry, key validation server is not available.', 'tribe-common' );
			} elseif ( isset( $plugin_info->api_expired ) && 1 === (int) $plugin_info->api_expired ) {
				$response['message']     = $this->get_license_expired_message();
				$response['api_expired'] = true;
			} elseif ( isset( $plugin_info->api_upgrade ) && 1 === (int) $plugin_info->api_upgrade ) {
				$response['message']     = $this->get_api_message( $plugin_info );
				$response['api_upgrade'] = true;
			} elseif ( isset( $plugin_info->api_invalid ) && 1 === (int) $plugin_info->api_invalid ) {
				$response['message']     = $this->get_api_message( $plugin_info );
				$response['api_invalid'] = true;
			} else {
				$key_type = 'local';

				if ( $network ) {
					$key_type = 'network';
				}

				$current_install_key = $this->get_key( $key_type );
				$replacement_key     = $query_args['key'];

				if ( ! empty( $plugin_info->replacement_key ) ) {
					// The PUE service might send over a new key upon validation.
					$replacement_key = $plugin_info->replacement_key;
				}

				if ( $current_install_key && $current_install_key === $replacement_key ) {
					$default_success_msg = esc_html(
						sprintf(
						/* Translators: %s is the expiration date. */
							__( 'Valid Key! Expires on %s', 'tribe-common' ),
							$expiration
						)
					);
				} else {
					// Set the key.
					$this->update_key( $replacement_key, $key_type );

					$default_success_msg = esc_html(
						sprintf(
						/* Translators: %s is the expiration date. */
							__(
								'Thanks for setting up a valid key. It will expire on %s',
								'tribe-common'
							),
							$expiration
						)
					);

					// Set system info key on TEC.com after successful validation of license.
					$optin_key = get_option( 'tribe_systeminfo_optin' );
					if ( $optin_key ) {
						Tribe__Support::send_sysinfo_key( $optin_key, $query_args['domain'], false, true );
					}
				}

				$pue_notices->clear_notices( $plugin_name );

				$response['status']     = isset( $plugin_info->api_message ) ? 2 : 1;
				$response['message']    = $plugin_info->api_message ?? $default_success_msg;
				$response['expiration'] = esc_html( $expiration );

				if ( isset( $plugin_info->daily_limit ) ) {
					$response['daily_limit'] = intval( $plugin_info->daily_limit );
				}
			}

			$response['message'] = wp_kses( $response['message'], 'data' );

			$this->set_key_status( $response['status'] );

			return $response;
		}

		/**
		 * Get the message for an expired license.
		 */
		public function get_license_expired_message(): string {
			return '<a href="https://evnt.is/195y" target="_blank" class="button button-primary">'
				. __( 'Renew Your License Now', 'tribe-common' )
				. '<span class="screen-reader-text">'
				. __( ' (opens in a new window)', 'tribe-common' )
				. '</span></a>';
		}

		/**
		 * Echo JSON results for key validation
		 */
		public function ajax_validate_key(): void {
			$key   = isset( $_POST['key'] ) ? wp_unslash( $_POST['key'] ) : null;
			$nonce = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : null;

			if (
				empty( $nonce )
				|| false === wp_verify_nonce( $nonce, 'pue-validate-key_' . $this->get_slug() )
			) {
				$response = [
					'status'  => 0,
					'message' => __( 'Please refresh the page and try your request again.', 'tribe-common' ),
				];
			} else {
				$response = $this->validate_key( $key );
			}

			echo json_encode( $response );
			tribe_exit();
		}

		/**
		 * Processes variable substitutions for server-side API message.
		 *
		 * @param Tribe__PUE__Plugin_Info $info An object containing plugin information, such as the version
		 *                                      and API inline invalid message.
		 *
		 * @return string The processed API message with placeholders replaced by dynamic values.
		 */
		private function get_api_message( $info ) {
			// this default message should never show, but is here as a fallback just in case.
			$message = sprintf(
			/* Translators: %1$s is the plugin name. %2$s and %3$s are opening and closing <a> tags, respectively. */
				esc_html__(
					'There is an update for %1$s. You\'ll need to %2$scheck your license%3$s to have access to updates, downloads, and support.',
					'tribe-common'
				),
				$this->get_plugin_name(),
				'<a href="https://theeventscalendar.com/license-keys/">',
				'</a>'
			);

			if ( ! empty( $info->api_inline_invalid_message ) ) {
				$message = wp_kses( $info->api_inline_invalid_message, 'post' );
			}

			$message = str_replace( '%plugin_name%', $this->get_plugin_name(), $message );
			$message = str_replace( '%plugin_slug%', $this->get_slug(), $message );
			$message = str_replace( '%update_url%', $this->get_pue_update_url() . '/', $message );
			$message = str_replace( '%version%', $info->version, $message );
			$message = str_replace( '%changelog%', '<a class="thickbox" title="' . $this->get_plugin_name() . '" href="plugin-install.php?tab=plugin-information&plugin=' . $this->get_slug() . '&TB_iframe=true&width=640&height=808">what\'s new</a>', $message );

			return $message;
		}

		/**
		 * Whether the plugin is network activated and licensed or not.
		 *
		 * @return bool
		 */
		public function is_network_licensed(): bool {
			$is_network_licensed = false;

			if ( ! is_network_admin() && $this->is_plugin_active_for_network() ) {
				$network_key = $this->get_key( 'network' );
				$local_key   = $this->get_key( 'local' );

				// Check whether the network is licensed and NOT overridden by local license.
				if ( $network_key && ( empty( $local_key ) || $local_key === $network_key ) ) {
					$is_network_licensed = true;
				}
			}

			return $is_network_licensed;
		}

		/**
		 * Returns tet name of the option that stores the license key.
		 *
		 * @return string
		 */
		public function get_license_option_key(): string {
			return $this->pue_install_key;
		}

		/**
		 * Get the API update message.
		 */
		private function get_api_update_message() {
			$plugin_info = $this->plugin_info;

			if ( ! isset( $plugin_info->api_invalid_message ) ) {
				return false;
			}

			return sprintf(
			/* Translators: %1$s is the plugin name. %2$s and %3$s are opening and closing <a> tags, respectively. */
				esc_html__(
					'There is an update for %1$s. %2$sRenew your license%3$s to get access to bug fixes, security updates, and new features.',
					'tribe-common'
				),
				$this->get_plugin_name(),
				'<a href="https://theeventscalendar.com/license-keys/">',
				'</a>'
			);
		}

		/**
		 * Displays a PUE message on the page if it is relevant
		 *
		 * @param string $page The current page.
		 */
		public function maybe_display_json_error_on_plugins_page( $page ) {
			if ( 'plugins.php' !== $page ) {
				return;
			}

			$state            = $this->get_state();
			$messages         = [];
			$plugin_updates   = get_plugin_updates();
			$update_available = isset( $plugin_updates[ $this->plugin_file ] );

			// Check to see if there is an licensing error or update message we should show.
			if ( ! empty( $state->update->license_error ) ) {
				$messages[] = $state->update->license_error;
			} elseif ( $update_available && current_user_can( 'update_plugins' ) ) {
				// A plugin update is available.
				$update_now = sprintf(
				/* Translators: %s is the plugin version number. */
					esc_html__(
						'Update now to version %s.',
						'tribe-common'
					),
					$state->update->version
				);

				$update_now_link = sprintf(
					' <a href="%1$s" class="update-link">%2$s</a>',
					wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->plugin_file, 'upgrade-plugin_' . $this->plugin_file ),
					$update_now
				);

				$update_message = sprintf(
				/* Translators: %1$s is the plugin name. %2$s is the update now link. */
					esc_html__( 'There is a new version of %1$s available. %2$s', 'tribe-common' ),
					$this->plugin_name,
					$update_now_link
				);

				$messages[] = sprintf(
					'<p>%s</p>',
					$update_message
				);
			}

			if ( empty( $messages ) ) {
				return;
			}

			$message_row_html = '';

			foreach ( $messages as $message ) {
				$message_row_html .= sprintf(
					'<div class="update-message notice inline notice-warning notice-alt">%s</div>',
					$message
				);
			}

			$message_row_html = sprintf(
				'<tr class="plugin-update-tr active"><td colspan="3" class="plugin-update">%s</td></tr>',
				$message_row_html
			);

			$this->plugin_notice = [
				'slug'             => $this->plugin_file,
				'message_row_html' => $message_row_html,
			];

			add_filter( 'tribe_plugin_notices', [ $this, 'add_notice_to_plugin_notices' ] );
		}

		/**
		 * Adds the PUE message to the plugin notices array.
		 *
		 * @param array $notices The current plugin notices.
		 *
		 * @return array
		 */
		public function add_notice_to_plugin_notices( array $notices ): array {
			if ( ! $this->plugin_notice || $this->is_network_licensed() ) {
				return $notices;
			}

			$notices[ $this->plugin_notice['slug'] ] = $this->plugin_notice;

			return $notices;
		}

		/**
		 * Returns plugin/license key data based on the provided query arguments.
		 *
		 * Calling this method will also take care of setting up admin notices for any
		 * keys that are invalid or have expired, etc.
		 *
		 * @see Tribe__PUE__Checker::request_info()
		 *
		 * @param array $query_args Additional query arguments to append to the request.
		 *
		 * @return Tribe__PUE__Plugin_Info|null
		 */
		public function license_key_status( array $query_args ) {
			$pue_notices = tribe( 'pue.notices' );
			$plugin_info = $this->request_info( $query_args );
			$plugin_name = empty( $this->plugin_name ) ? $this->get_plugin_name() : $this->plugin_name;

			if ( empty( $plugin_name ) ) {
				return $plugin_info;
			}

			$install_key = $this->get_key();

			// Check for expired keys.
			if ( empty( $install_key ) ) {
				// Return null on empty license keys.
				return null;
			} elseif ( ! empty( $plugin_info->api_expired ) ) {
				$pue_notices->add_notice( Tribe__PUE__Notices::EXPIRED_KEY, $plugin_name );
			} elseif ( ! empty( $plugin_info->api_upgrade ) ) {
				// Check for keys that are out of installs (*must* happen before the api_invalid test).
				$pue_notices->add_notice( Tribe__PUE__Notices::UPGRADE_KEY, $plugin_name );
			} elseif (
				// Check for invalid keys last of all (upgrades/empty keys will be flagged as invalid).
				! empty( $plugin_info->api_invalid )
				&& (
					'component' === $this->context
					|| (
						'service' === $this->context
						&& $install_key
					)
				)
			) {
				$pue_notices->add_notice( Tribe__PUE__Notices::INVALID_KEY, $plugin_name );
			} else {
				// If none of the above were satisfied we can assume the key is valid.
				$pue_notices->clear_notices( $plugin_name );
			}

			return $plugin_info;
		}

		/**
		 * Sets up and manages those license key notifications which don't depend on communicating with a remote
		 * PUE server, etc.
		 */
		public function general_notifications(): void {
			$plugin_name = empty( $this->plugin_name ) ? $this->get_plugin_name() : $this->plugin_name;

			// Register our plugin name for use in messages (thus if we're deactivated, any previously
			// added persistent messaging can be cleared).
			tribe( 'pue.notices' )->register_name( $plugin_name );

			// Detect and setup notices for missing keys.
			$install_key = $this->get_key();

			if ( empty( $install_key ) && 'service' !== $this->context ) {
				tribe( 'pue.notices' )->add_notice( Tribe__PUE__Notices::INVALID_KEY, $plugin_name );
			}
		}

		/**
		 * Retrieve plugin info from the configured API endpoint.
		 *
		 * In general, this method should not be called directly and it is preferable to call
		 * the license_key_status() method instead. That method returns the same result, but
		 * also analyses each response to set up appropriate license key notifications in the
		 * admin environment.
		 *
		 * @see  Tribe__PUE__Checker::license_key_status()
		 *
		 * @param array $query_args Additional query arguments to append to the request. Optional.
		 *
		 * @return Tribe__PUE__Plugin_Info|string|null $plugin_info
		 * @uses wp_remote_get()
		 */
		public function request_info( $query_args = [] ) {
			/**
			 * Filters the query arguments used to request plugin info from the API endpoint.
			 *
			 * @since 5.2.0
			 *
			 * @param array  $query_args The current query arguments for the request.
			 */
			$query_args = (array) apply_filters( 'tribe_puc_request_info_query_args-' . $this->get_slug(), $query_args ); //phpcs:ignore WordPress.NamingConventions.ValidHookName

			// Cache the API call so it only needs to be made once per plugin per page load.
			static $plugin_info_cache;

			// Sort parameter keys.
			$hash_data = $query_args;

			ksort( $hash_data );

			// Flatten hashed data.
			$hash_data = json_encode( $hash_data );

			// Generate unique hash.
			$key = hash( 'sha256', $hash_data );

			if ( isset( $plugin_info_cache[ $key ] ) ) {
				return $plugin_info_cache[ $key ];
			}

			// Various options for the wp_remote_get() call. Plugins can filter these, too.
			$options = [
				'body'    => $query_args,
				'timeout' => 15, // In seconds.
				'headers' => [
					'Accept' => 'application/json',
				],
			];

			/**
			 * Filters the options used in the wp_remote_get() call to request plugin info.
			 *
			 * @since 5.2.0
			 *
			 * @param array  $options The options for the wp_remote_get() call.
			 */
			$options = (array) apply_filters( 'tribe_puc_request_info_options-' . $this->get_slug(), $options ); //phpcs:ignore WordPress.NamingConventions.ValidHookName

			$url = sprintf( '%s/api/plugins/v2/license/validate', $this->get_pue_update_url() );

			$result = wp_remote_post(
				$url,
				$options
			);

			// Try to parse the response.
			$plugin_info = null;

			if (
				! is_wp_error( $result )
				&& isset( $result['response']['code'] )
				&& ( 200 === (int) $result['response']['code'] )
				&& ! empty( $result['body'] )
			) {
				$plugin_info = Tribe__PUE__Plugin_Info::from_json( $result['body'] );
			}

			/**
			 * Filters the plugin info retrieved from the API request.
			 *
			 * @since 5.2.0
			 *
			 * @param Tribe__PUE__Plugin_Info|string|null $plugin_info The plugin info object or null if unavailable.
			 * @param array                                $result      The result of the wp_remote_get() call.
			 */
			$plugin_info = apply_filters( 'tribe_puc_request_info_result-' . $this->get_slug(), $plugin_info, $result ); //phpcs:ignore WordPress.NamingConventions.ValidHookName

			$plugin_info_cache[ $key ] = $plugin_info;

			return $plugin_info;
		}

		/**
		 * Returns the domain contained in the network's siteurl option (not the full URL).
		 *
		 * @return string
		 */
		public function get_network_domain(): string {
			$site_url = wp_parse_url( get_site_option( 'siteurl' ) );
			if ( ! $site_url || ! isset( $site_url['host'] ) ) {
				return '';
			} else {
				return strtolower( $site_url['host'] );
			}
		}

		/**
		 * Retrieve the latest update (if any) from the configured API endpoint.
		 *
		 * @return ?Tribe__PUE__Utility An instance of Tribe__PUE__Utility, or NULL when no updates are available.
		 * @uses Tribe__PUE__Checker::get_validate_query()
		 *
		 */
		public function request_update(): ?Tribe__PUE__Utility {
			// For the sake of simplicity, this function just calls get_validate_query()
			// and transforms the result accordingly.
			$query_args = $this->get_validate_query();

			if ( ! empty( $_POST['key'] ) ) {
				$query_args['key'] = sanitize_text_field( $_POST['key'] );
			} elseif ( ! empty( $_POST[ $this->pue_install_key ] ) ) {
				$query_args['key'] = sanitize_text_field( $_POST[ $this->pue_install_key ] );
			}

			$this->plugin_info = $plugin_info = $this->license_key_status( $query_args );

			if ( null === $plugin_info ) {
				return null;
			}

			// admin display for if the update check reveals that there is a new version but the API key isn't valid.
			if ( isset( $plugin_info->api_invalid ) ) {
				$plugin_info                = Tribe__PUE__Utility::from_plugin_info( $plugin_info );
				$plugin_info->license_error = $this->get_api_message( $plugin_info );

				return $plugin_info;
			}

			if ( ! empty( $plugin_info->new_install_key ) ) {
				$this->update_key( $plugin_info->new_install_key );
			}

			// Need to correct the download url so it contains the custom user data (e.g. api and any other parameters).
			$download_query = $this->get_download_query();

			if ( ! empty( $download_query ) ) {
				$plugin_info->download_url = esc_url_raw( add_query_arg( $download_query, $plugin_info->download_url ) );
			}

			// Add plugin dirname/file (this will be expected by WordPress when it builds the plugin list table).
			$plugin_info->plugin = $this->get_plugin_file();

			return Tribe__PUE__Utility::from_plugin_info( $plugin_info );
		}

		/**
		 * Display a changelog when the api key is missing.
		 */
		public function display_changelog() {
			// Contents of changelog display page when api-key is invalid or missing.  It will ONLY show the changelog (hook into existing thickbox?).
		}

		/**
		 * Get the currently installed version of the plugin.
		 *
		 * @since 6.5.1 Refactored logic to better catch different scenarios.
		 *
		 * @return string Version number.
		 */
		public function get_installed_version(): string {
			if ( ! function_exists( 'get_plugins' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if ( ! function_exists( 'get_plugins' ) ) {
				return '';
			}

			$all_plugins = get_plugins();
			$plugin_file = $this->get_plugin_file();

			if ( ! array_key_exists( $plugin_file, $all_plugins ) ) {
				return '';
			}

			$plugin_data = $all_plugins[ $plugin_file ];

			if ( ! array_key_exists( 'Version', $plugin_data ) ) {
				return '';
			}

			return $plugin_data['Version'];
		}

		/**
		 * Get plugin update state.
		 *
		 * @param boolean $force_recheck Whether to force a recheck of the update status.
		 *
		 * @return object
		 */
		public function get_state( $force_recheck = false ) {
			$state = null;

			if ( ! $force_recheck ) {
				$state = get_site_option( $this->pue_option_name, false );
			}

			if ( empty( $state ) ) {
				$state                 = new stdClass();
				$state->lastCheck      = 0;
				$state->checkedVersion = '';
				$state->update         = null;
			}

			return $state;
		}

		/**
		 * Updates the plugin's update state in the database.
		 *
		 * This method stores the provided value as the plugin's update state
		 * using the site-wide options system.
		 *
		 * @param object $value The value representing the plugin's update state,
		 *                      typically containing data about updates or related plugin information.
		 *
		 * @return void
		 */
		public function update_state( $value ): void {
			update_site_option( $this->pue_option_name, $value );
		}

		/**
		 * Check for plugin updates.
		 * The results are stored in the DB option specified in $pue_option_name.
		 *
		 * @since 6.5.1.1 Bail early if no license key is found.
		 *
		 * @param array|object $updates       Existing updates.
		 * @param boolean      $force_recheck Whether to force a recheck of the update status.
		 *
		 * @return array|object
		 */
		public function check_for_updates( $updates = [], bool $force_recheck = false ) {
			if ( empty( $this->get_key() ) ) {
				return $updates;
			}

			$state = $this->get_state( $force_recheck );

			$state->lastCheck      = time();
			$state->checkedVersion = $this->get_installed_version();

			// Save before checking in case something goes wrong.
			$this->update_state( $state );

			$state->update = $this->request_update();

			// If a null update was returned, skip to the end of the function.
			if ( null !== $state->update ) {
				// We treat this like an object throughout so let's convert it here.
				$updates = (object) $updates;

				// Is there an update to insert?
				if ( version_compare( $state->update->version, $this->get_installed_version(), '>' ) ) {
					if ( ! isset( $updates->response ) ) {
						$updates->response = [];
					}

					$plugin_file = $this->get_plugin_file();

					$updates->response[ $plugin_file ] = $state->update->to_wp_format( $plugin_file );

					// Clear the no_update property if it exists.
					if ( isset( $updates->no_update[ $plugin_file ] ) ) {
						unset( $updates->no_update[ $plugin_file ] );
					}

					// If the key has expired we should register an appropriate admin notice.
					if ( $this->plugin_info->api_expired ) {
						tribe( 'pue.notices' )->add_notice( Tribe__PUE__Notices::EXPIRED_KEY, $this->plugin_name );
					}
				} else {
					$plugin_file = $this->get_plugin_file();

					// Clean up any stale update info.
					if ( isset( $updates->response[ $plugin_file ] ) ) {
						unset( $updates->response[ $plugin_file ] );
					}

					/**
					 * If the plugin is up to date, we need to add it to the `no_update` property so that enable auto updates can appear correctly in the UI.
					 *
					 * See this post for more information:
					 *
					 * @link https://make.wordpress.org/core/2020/07/30/recommended-usage-of-the-updates-api-to-support-the-auto-updates-ui-for-plugins-and-themes-in-wordpress-5-5/
					 */
					/** @var \stdClass $transient */
					if ( ! isset( $updates->no_update ) ) {
						$updates->no_update = [];
					}

					$updates->no_update[ $plugin_file ] = $state->update->to_wp_format( $plugin_file );
				}
			}

			$this->update_state( $state );

			return $updates;
		}

		/**
		 * Clears out the site external site option and re-checks the license key
		 *
		 * @since 6.5.1
		 *
		 * @param string|null $field_id        The ID of the field.
		 * @param mixed       $value           The value of the option.
		 *
		 * @return void
		 * @internal
		 */
		public function check_for_api_key_error_on_action( ?string $field_id, $value ): void {
			// Only hook into our option.
			if ( $this->pue_install_key !== $field_id ) {
				return;
			}

			if ( 'service' !== $this->context ) {
				$this->check_for_updates( [], true );
			}

			// if we are saving this PUE key, we need to make sure we update the license key notices
			// appropriately. Otherwise, we could have an invalid license key in place but the notices
			// aren't being thrown globally.

			$query_args = $this->get_validate_query();

			$query_args['key'] = sanitize_text_field( $value );

			$this->license_key_status( $query_args );
		}

		/**
		 * Clears out the site external site option and re-checks the license key
		 *
		 * @param mixed  $value           The value of the option.
		 * @param string $field_id        The ID of the field.
		 * @param object $validated_field The validated field.
		 *
		 * @return mixed returns $value
		 * @deprecated 6.5.1 Updated to be passthrough method to `check_for_api_key_error_on_action`.
		 *
		 * @internal
		 */
		public function check_for_api_key_error( $value, string $field_id, object $validated_field ) {
			_deprecated_function( __METHOD__, '6.5.1', 'check_for_api_key_error_on_action' );
			$this->check_for_api_key_error_on_action( $field_id, $value );

			return $value;
		}

		/**
		 * Intercept plugins_api() calls that request information about our plugin and
		 * use the configured API endpoint to satisfy them.
		 *
		 * @see plugins_api()
		 *
		 * @param mixed        $result The result object or NULL.
		 * @param string|null  $action The type of information being requested from the Plugin Install API.
		 * @param array|object $args   Arguments used to query for installer pages from the Plugin Install API.
		 *
		 * @return mixed
		 */
		public function inject_info( $result, ?string $action = null, $args = null ) {
			$relevant = ( 'plugin_information' === $action ) && isset( $args->slug ) && ( $args->slug === $this->slug );
			if ( ! $relevant ) {
				return $result;
			}

			$query_args = $this->get_validate_query();

			$plugin_info = $this->license_key_status( $query_args );

			if ( $plugin_info ) {
				return $plugin_info->to_wp_format();
			}

			return $result;
		}

		/**
		 * Register a callback for filtering query arguments.
		 *
		 * The callback function should take one argument - an associative array of query arguments.
		 * It should return a modified array of query arguments.
		 *
		 * @param callback $callback The callback function.
		 *
		 * @deprecated 6.5.1 This method is deprecated and will be removed in a future release. Use the 'tribe_puc_request_info_query_args-<slug>' filter directly.
		 */
		public function add_query_arg_filter( $callback ) {
			_deprecated_function( __METHOD__, '6.5.1', 'add_filter(\'tribe_puc_request_info_query_args-<slug>\', $callback);' );

			/**
			 * Filters the query arguments passed to the API request.
			 *
			 * Plugins can hook into this filter to modify the query parameters sent to the API.
			 *
			 * @since 5.2.0
			 *
			 * @param array $callback The query arguments to be sent to the API.
			 */
			add_filter( 'tribe_puc_request_info_query_args-' . $this->get_slug(), $callback );
		}

		/**
		 * Register a callback for filtering arguments passed to wp_remote_get().
		 *
		 * The callback function should take one argument - an associative array of arguments -
		 * and return a modified array or arguments. See the WP documentation on wp_remote_get()
		 * for details on what arguments are available and how they work.
		 *
		 * @param callback $callback The callback function.
		 *
		 * @uses       add_filter() This method is a convenience wrapper for add_filter().
		 * @deprecated 6.5.1 This method is deprecated and will be removed in a future release. Use the 'tribe_puc_request_info_options-<slug>' filter directly.
		 */
		public function add_http_request_arg_filter( $callback ) {
			_deprecated_function( __METHOD__, '6.5.1', 'add_filter(\'tribe_puc_request_info_options-<slug>\', $callback);' );

			add_filter( 'tribe_puc_request_info_options-' . $this->get_slug(), $callback );
		}

		/**
		 * Register a callback for filtering the plugin info retrieved from the external API.
		 *
		 * The callback function should take two arguments. If the plugin info was retrieved
		 * successfully, the first argument passed will be an instance of Tribe__PUE__Plugin_Info. Otherwise,
		 * it will be NULL. The second argument will be the corresponding return value of
		 * wp_remote_get (see WP docs for details).
		 *
		 * The callback function should return a new or modified instance of Tribe__PUE__Plugin_Info or NULL.
		 *
		 * @param callback $callback The callback function.
		 *
		 * @uses       add_filter() This method is a convenience wrapper for add_filter().
		 * @deprecated 6.5.1 This method is deprecated and will be removed in a future release. Use the 'tribe_puc_request_info_result-<slug>' filter directly.
		 */
		public function add_result_filter( $callback ) {
			_deprecated_function( __METHOD__, '6.5.1', 'add_filter(\'tribe_puc_request_info_result-<slug>\', $callback, 10, 2);' );

			add_filter( 'tribe_puc_request_info_result-' . $this->get_slug(), $callback, 10, 2 );
		}

		/**
		 * Insert an array after a specified key within another array.
		 *
		 * @param string $key          The key to insert after.
		 * @param array  $source_array The array to insert into.
		 * @param array  $insert_array The array to insert.
		 *
		 * @return array
		 */
		public static function array_insert_after_key( string $key, array $source_array, array $insert_array ): array {
			if ( array_key_exists( $key, $source_array ) ) {
				$position     = array_search( $key, array_keys( $source_array ), true ) + 1;
				$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
			} else {
				// If no key is found, then add it to the end of the array.
				$source_array += $insert_array;
			}

			return $source_array;
		}

		/**
		 * Add this plugin key to the list of keys.
		 *
		 * @param array $keys The list of keys.
		 *
		 * @return array $keys
		 */
		public function return_install_key( array $keys = [] ): array {
			$key = $this->get_key();

			if ( ! empty( $key ) ) {
				$keys[ $this->get_slug() ] = $key;
			}

			return $keys;
		}

		/**
		 * Prevent the default inline update-available messages from appearing, as we
		 * have implemented our own.
		 *
		 * @see resources/js/pue-notices.js
		 */
		public function remove_default_inline_update_msg() {
			remove_action( "after_plugin_row_{$this->plugin_file}", 'wp_plugin_update_row' );
		}

		/**
		 * Returns the domain of the single site installation.
		 *
		 * Will try to read it from the $_SERVER['SERVER_NAME'] variable
		 * and fall back on the one contained in the siteurl option.
		 *
		 * @return string
		 */
		protected function get_site_domain(): string {
			if ( isset( $_SERVER['SERVER_NAME'] ) ) {
				return $_SERVER['SERVER_NAME'];
			}
			$site_url = wp_parse_url( get_option( 'siteurl' ) );
			if ( ! $site_url || ! isset( $site_url['host'] ) ) {
				return '';
			} else {
				return strtolower( $site_url['host'] );
			}
		}

		/**
		 * Check whether the current plugin is active for the network or not.
		 *
		 * @return boolean Whether the plugin is network activated.
		 */
		protected function is_plugin_active_for_network(): bool {
			if ( ! is_multisite() ) {
				return false;
			}

			$map = [
				'event-aggregator/event-aggregator.php' => 'the-events-calendar/the-events-calendar.php',
			];

			$plugin_file = $this->get_plugin_file();

			if ( isset( $map[ $this->plugin_file ] ) ) {
				$plugin_file = $map[ $this->plugin_file ];
			}

			if ( function_exists( 'is_plugin_active_for_network' ) ) {
				// If is_plugin_active_for_network() is available, let's use it!
				return is_plugin_active_for_network( $plugin_file );
			} else {
				// When this method is called sufficiently early in the request,
				// is_plugin_active_for_network() may not be available (#115826).
				$plugins = get_site_option( 'active_sitewide_plugins' );

				return isset( $plugins[ $plugin_file ] );
			}
		}

		/**
		 * Returns the localized string for a plugin or component license state.
		 *
		 * @return string The localized state string.
		 */
		protected function get_network_license_state_string(): string {
			$states = [
				'licensed'     => esc_html__( 'A valid license has been entered by your network administrator.', 'tribe-common' ),
				'not-licensed' => esc_html__( 'No license entered. Consult your network administrator.', 'tribe-common' ),
				'expired'      => esc_html__( 'Expired license. Consult your network administrator.', 'tribe-common' ),
			];

			$response = $this->validate_key( $this->get_key( 'network' ), true );

			if ( isset( $response['status'] ) && 1 === (int) $response['status'] ) {
				$state = 'licensed';
			} elseif ( isset( $response['api_expired'] ) && true === (bool) $response['api_expired'] ) {
				$state = 'expired';
			} else {
				$state = 'not-licensed';
			}

			return $states[ $state ];
		}

		/**
		 * Whether the user should be shown the fully editable subsite license field or not.
		 *
		 * This check will happen in the context of the plugin administration area; checks on the user
		 * capability to edit the plugin settings have been made before.
		 *
		 * @return bool
		 */
		public function should_show_subsite_editable_license(): bool {
			if ( ! is_multisite() ) {
				return true;
			}

			if ( is_network_admin() ) {
				return false;
			}

			if ( $this->is_plugin_active_for_network() && ! is_super_admin() ) {
				return false;
			}

			return true;
		}

		/**
		 * Whether the user should be shown the override control to override the network license key or not.
		 *
		 * This check will happen in the context of the plugin administration area; checks on the user
		 * capability to edit the plugin settings have been made before.
		 *
		 * @return bool
		 */
		public function should_show_overrideable_license(): bool {
			if ( is_network_admin() ) {
				return false;
			}

			if ( is_super_admin() ) {
				return false;
			}

			if ( ! $this->is_plugin_active_for_network() ) {
				return false;
			}

			return true;
		}

		/**
		 * Whether the user should be shown the fully editable network license field or not.
		 *
		 * This check will happen in the context of the network plugin administration area; checks on the user
		 * capability to edit the network plugin settings have been made before.
		 *
		 * @return bool
		 */
		public function should_show_network_editable_license(): bool {
			return is_network_admin() && is_super_admin();
		}

		/**
		 * Determines if the value on the DB is the correct format.
		 *
		 * @since 4.15.0
		 *
		 * @return bool
		 */
		public function is_valid_key_format(): bool {
			$license_opt = (string) get_option( $this->get_license_option_key() );
			if ( empty( $license_opt ) ) {
				return false;
			}

			if ( ! preg_match( '/([0-9a-z]+)/i', $license_opt, $matches ) ) {
				return false;
			}

			// Pull the matching string into a variable.
			$license = $matches[1];

			if ( 40 !== strlen( $license ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Hooks into the Uplink plugin's 'connected' action for the current plugin.
		 *
		 * This method registers a callback for the 'stellarwp/uplink/{slug}/connected' action.
		 * When the action is triggered, it updates the license validity transient for the plugin.
		 *
		 * @since 6.4.2
		 *
		 * @return void
		 */
		public static function monitor_uplink_actions(): void {
			// Hook into the existing 'connected' action for the specific plugin slug.
			add_action(
				'stellarwp/uplink/' . Config::get_hook_prefix() . '/connected',
				function ( $plugin ) {
					self::update_any_license_valid_transient( $plugin->get_slug(), true );
				},
			);
		}

		/**
		 * Monitor active plugins and validate the transient for the given slug.
		 *
		 * @since 6.4.2
		 *
		 * @param Tribe__PUE__Checker $checker An instance of the PUE Checker.
		 */
		public static function monitor_active_plugins( Tribe__PUE__Checker $checker ): void {
			$current_plugin_list = array_unique( array_merge( array_keys( self::$instances ), [ $checker->get_slug() ] ) );

			// Retrieve or initialize transient data.
			$transient_data            = get_transient( self::IS_ANY_LICENSE_VALID_TRANSIENT_KEY ) ?: [ 'plugins' => [] ];
			$transient_data['plugins'] = is_array( $transient_data['plugins'] ) ? $transient_data['plugins'] : [];

			// Check if the missing plugins are valid, which adds the transients automatically.
			foreach ( $current_plugin_list as $plugin_slug ) {
				if ( ! isset( $transient_data['plugins'][ $plugin_slug ] ) ) {
					$transient_data['plugins'][ $plugin_slug ] = $checker->is_key_valid();
				}
			}
		}

		/**
		 * Initializes a license check during PUE Checker initialization.
		 *
		 * @since 6.5.1.1 Bail early if no license key is found.
		 *
		 * @param Tribe__PUE__Checker $checker An instance of the PUE Checker.
		 */
		public function initialize_license_check( Tribe__PUE__Checker $checker ): void {
			if ( ! is_admin() ) {
				return;
			}
			// Check Transient.
			$pue_transient_status = get_transient( $this->pue_key_status_transient_name );
			if ( ! empty( $pue_transient_status ) ) {
				return;
			}

			// Check Option and Timeout.
			$pue_option_status = get_option( $this->pue_key_status_option_name );
			$option_expiration = get_option( "{$this->pue_key_status_option_name}_timeout", null );

			if ( ! empty( $pue_option_status ) && $option_expiration && time() < $option_expiration ) {
				// Option exists, create transient for tracking.
				set_transient( $this->pue_key_status_transient_name, $pue_option_status, HOUR_IN_SECONDS );
				return;
			}

			// Retrieve the license.
			$license = get_option( $checker->get_license_option_key() );
			if ( empty( $license ) ) {
				// An empty license doesn't always mean an invalid plugin. So skip it.
				return;
			}

			// Validate the license.
			$response = $checker->validate_key( $license );

			// Fallback to invalid if validation fails.
			// 1 for valid, 0 for invalid.
			$status = tribe_is_truthy( $response['status'] ) ? 1 : 0;

			$this->set_key_status( $status );
		}

		/**
		 * Retrieves the uplink resource for the given slug, if available.
		 *
		 * Ensures the `get_resource()` function exists before calling it.
		 *
		 * @param string $slug The slug of the resource to retrieve.
		 *
		 * @return mixed The resource object if available, or `null` if the function does not exist or fails to retrieve the resource.
		 */
		public function get_uplink_resource( string $slug ) {
			try {
				$resource = get_resource( $slug );
			} catch ( Throwable $e ) {
				return null;
			}

			return $resource;
		}
	}
}
