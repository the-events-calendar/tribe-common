<?php
/**
 * Plugin Update Engine Class
 *
 * This is a direct port to Tribe Commons of the PUE classes contained
 * in The Events Calendar.
 *
 * @todo switch all plugins over to use the PUE utilities here in Commons
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__PUE__Checker' ) ) {
	/**
	 * A custom plugin update checker.
	 * @since  1.7
	 */
	class Tribe__PUE__Checker {

		// The URL of the plugin's metadata file.
		private $pue_update_url = '';

		// Plugin filename relative to the plugins directory.
		private $plugin_file = '';

		// variable used to hold the plugin_name as set by the constructor.
		private $plugin_name = '';

		// Plugin slug. (with .php extension)
		private $slug = '';

		// used to hold the query variables for download checks;
		private $download_query = array();

		// How often to check for updates (in hours).
		public $check_period = 12;

		// Where to store the update info.
		public $pue_option_name = '';

		// used to hold the user API.  If not set then nothing will work!
		public $api_secret_key = '';

		// used to hold the install_key if set (included here for addons that will extend PUE to use install key checks)
		public $install_key = false;

		// for setting the dismiss upgrade option (per plugin).
		public $dismiss_upgrade;

		/**
		 * We'll customize this later so each plugin can have it's own install key!
		 * @var string
		 */
		public $pue_install_key;

		/**
		 * Storing any `json_error` data that get's returned so we can display an admin notice.
		 * For backwards compatibility this will be kept in the code for 2 versions
		 *
		 * @var array|null
		 *
		 * @deprecated
		 * @todo  remove on 4.5
		 */
		public $json_error;

		/**
		 * Storing any `plugin_info` data that get's returned so we can display an admin notice.
		 * @var array|null
		 */
		public $plugin_info;

		public $plugin_notice;

		/**
		 * Class constructor.
		 *
		 * @param string $pue_update_url The URL of the plugin's metadata file.
		 * @param string $slug           The plugin's 'slug'.
		 * @param array  $options        Contains any options that need to be set in the class initialization for construct.  These are the keys:
		 *
		 * @key integer $check_period How often to check for updates (in hours). Defaults to checking every 12 hours. Set to 0 to disable automatic update checks.
		 * @key string $pue_option_name Where to store book-keeping info about update checks. Defaults to 'external_updates-$slug'.
		 * @key string $apikey used to authorize download updates from developer server
		 *
		 * @param string $plugin_file    fully qualified path to the main plugin file.
		 */
		public function __construct( $pue_update_url, $slug = '', $options = array(), $plugin_file = '' ) {

			$this->set_slug( $slug );
			$this->set_pue_update_url( $pue_update_url );
			$this->set_plugin_file( $plugin_file );
			$this->set_options( $options );
			$this->hooks();

		}

		/**
		 * Install the hooks required to run periodic update checks and inject update info
		 * into WP data structures.
		 * Also other hooks related to the automatic updates (such as checking agains API and what not (@from Darren)
		 */
		public function hooks() {
			// Override requests for plugin information
			add_filter( 'plugins_api', array( $this, 'inject_info' ), 10, 3 );

			// Check for updates when the WP updates are checked and inject our update if needed.
			// Only add filter if the TRIBE_DISABLE_PUE constant is not set as true.
			if ( ! defined( 'TRIBE_DISABLE_PUE' ) || TRIBE_DISABLE_PUE !== true ) {
				add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_updates' ) );
			}

			add_filter( 'tribe_licensable_addons', array( $this, 'build_addon_list' ) );
			add_action( 'tribe_license_fields', array( $this, 'do_license_key_fields' ) );
			add_action( 'tribe_settings_after_content_tab_licenses', array( $this, 'do_license_key_javascript' ) );
			add_action( 'tribe_settings_success_message', array( $this, 'do_license_key_success_message' ), 10, 2 );

			add_action( 'update_option_' . $this->pue_install_key, array( $this, 'check_for_api_key_error' ), 10, 2 );

			// Key validation
			add_action( 'wp_ajax_pue-validate-key_' . $this->get_slug(), array( $this, 'ajax_validate_key' ) );

			// Dashboard message "dismiss upgrade" link
			add_action( 'wp_ajax_' . $this->dismiss_upgrade, array( $this, 'dashboard_dismiss_upgrade' ) );

			add_filter( 'tribe-pue-install-keys', array( $this, 'return_install_key' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'maybe_display_json_error_on_plugins_page' ), 1 );

			tribe_notice( 'pue-validation', array( $this, 'display_license_error_message' ), 'dismiss=1&type=warning' );
		}

		/********************** Getter / Setter Functions **********************/

		/**
		 * Get the slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return apply_filters( 'pue_get_slug', $this->slug );
		}

		/**
		 * Set the slug
		 *
		 * @param string $slug
		 */
		private function set_slug( $slug = '' ) {
			$this->slug            = $slug;
			$clean_slug            = str_replace( '-', '_', $this->slug );
			$this->dismiss_upgrade = 'pu_dismissed_upgrade_' . $clean_slug;
			$this->pue_install_key = 'pue_install_key_' . $clean_slug;
		}

		/**
		 * Get the PUE update API endpoint url
		 *
		 * @return string
		 */
		public function get_pue_update_url() {
			return apply_filters( 'pue_get_update_url', $this->pue_update_url, $this->get_slug() );
		}

		/**
		 * Set the PUE update URL
		 *
		 * This can be overridden using the global constant 'PUE_UPDATE_URL'
		 *
		 * @param string $pue_update_url
		 */
		private function set_pue_update_url( $pue_update_url ) {
			$this->pue_update_url = ( defined( 'PUE_UPDATE_URL' ) ) ? trailingslashit( PUE_UPDATE_URL ) : trailingslashit( $pue_update_url );
		}

		/**
		 * Get the plugin file path
		 *
		 * @return string
		 */
		public function get_plugin_file() {
			return apply_filters( 'pue_get_plugin_file', $this->plugin_file, $this->get_slug() );
		}

		/**
		 * Set the plugin file path
		 *
		 * @param string $plugin_file
		 */
		private function set_plugin_file( $plugin_file = '' ) {

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
		 * Set the plugin name
		 *
		 * @param string $plugin_name
		 */
		private function set_plugin_name( $plugin_name = '' ) {
			if ( ! empty( $plugin_name ) ) {
				$this->plugin_name = $plugin_name;
			} else {
				//get name from plugin file itself
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}

				$plugin_details    = explode( '/', $this->get_plugin_file() );
				$plugin_folder     = get_plugins( '/' . $plugin_details[0] );
				$this->plugin_name = isset( $plugin_details[1] ) && isset( $plugin_folder[ $plugin_details[1] ] ) ? $plugin_folder[ $plugin_details[1] ]['Name'] : null;
			}
		}

		/**
		 * Get the plugin name
		 *
		 * @return string
		 */
		public function get_plugin_name() {
			if ( empty( $this->plugin_name ) ) {
				$this->set_plugin_name();
			}

			return apply_filters( 'pue_get_plugin_name', $this->plugin_name, $this->get_slug() );
		}

		/**
		 * Set all the PUE instantiation options
		 *
		 * @param array $options
		 */
		private function set_options( $options = array() ) {

			$options = wp_parse_args(
				$options, array(
					'pue_option_name' => 'external_updates-' . $this->get_slug(),
					'apikey'          => '',
					'installkey'      => false,
					'check_period'    => 12,
				)
			);

			$this->pue_option_name = $options['pue_option_name'];
			$this->check_period    = (int) $options['check_period'];
			$this->api_secret_key  = $options['apikey'];
			if ( isset( $options['installkey'] ) && $options['installkey'] ) {
				$this->install_key = trim( $options['installkey'] );
			} else {
				$this->install_key = trim( $this->get_option( $this->pue_install_key ) );
			}

		}

		/**
		 * Set all the download query array
		 *
		 * @param array $download_query
		 */
		private function set_download_query( $download_query = array() ) {

			if ( ! empty( $download_query ) ) {
				$this->download_query = $download_query;

				return;
			}

			//download query flag
			$this->download_query['pu_get_download'] = 1;

			//include current version
			if ( $version = $this->get_installed_version() ) {
				$this->download_query['pue_active_version'] = $version;
			}

			//the following is for install key inclusion (will apply later with PUE addons.)
			if ( isset( $this->install_key ) ) {
				$this->download_query['pu_install_key'] = $this->install_key;
			}

			if ( ! empty( $this->api_secret_key ) ) {
				$this->download_query['pu_plugin_api'] = $this->api_secret_key;
			}

		}

		/**
		 * Get the download_query args
		 *
		 * @return array
		 */
		public function get_download_query() {
			if ( empty( $this->download_query ) ) {
				$this->set_download_query();
			}

			return apply_filters( 'pue_get_download_query', $this->download_query, $this->get_slug() );
		}


		/********************** General Functions **********************/

		/**
		 * Compile  a list of addons
		 *
		 * @param array $addons list of addons
		 *
		 * @return array list of addons
		 */
		public function build_addon_list( $addons = array() ) {
			$addons[] = $this->get_plugin_name();

			return $addons;
		}

		/**
		 * Inserts license key fields on license key page
		 *
		 * @param array $fields List of fields
		 *
		 * @return array Modified list of fields.
		 */
		public function do_license_key_fields( $fields ) {

			// we want to inject the following license settings at the end of the licenses tab
			$fields = self::array_insert_after_key( 'tribe-form-content-start', $fields, array(
					$this->pue_install_key . '-heading' => array(
						'type'  => 'heading',
						'label' => $this->get_plugin_name(),
					),
					$this->pue_install_key => array(
						'type'            => 'license_key',
						'size'            => 'large',
						'validation_type' => 'license_key',
						'label'           => sprintf( esc_attr__( 'License Key', 'tribe-common' ) ),
						'tooltip'         => esc_html__( 'A valid license key is required for support and updates', 'tribe-common' ),
						'parent_option'   => false,
						'network_option'  => true,
					),
				)
			);

			return $fields;
		}

		/**
		 * Inserts the javascript that makes the ajax checking
		 * work on the license key page
		 *
		 */
		public function do_license_key_javascript() {
			?>
			<script>
				jQuery(document).ready(function ($) {
					$('#tribe-field-<?php echo $this->pue_install_key ?>').change(function () {
						<?php echo $this->pue_install_key ?>_validateKey();
					});
					<?php echo $this->pue_install_key ?>_validateKey();
				});
				function <?php echo $this->pue_install_key ?>_validateKey() {
					var this_id       = '#tribe-field-<?php echo $this->pue_install_key ?>';
					var $validity_msg = jQuery(this_id + ' .key-validity');

					if (jQuery(this_id + ' input').val() != '') {
						jQuery(this_id + ' .tooltip').hide();
						jQuery(this_id + ' .ajax-loading-license').show();
						$validity_msg.hide();

						// Strip whitespace from key
						var <?php echo $this->pue_install_key ?>_license_key = jQuery(this_id + ' input').val().replace(/^\s+|\s+$/g, "");
						jQuery(this_id + ' input').val(<?php echo $this->pue_install_key ?>_license_key);

						var data = { action: 'pue-validate-key_<?php echo $this->get_slug(); ?>', key: <?php echo $this->pue_install_key ?>_license_key };
						jQuery.post(ajaxurl, data, function (response) {
							var data          = jQuery.parseJSON(response);

							jQuery(this_id + ' .ajax-loading-license').hide();
							$validity_msg.show();
							$validity_msg.html(data.message);

							switch ( data.status ) {
								case 1: $validity_msg.addClass( 'valid-key' ); break;
								case 2: $validity_msg.addClass( 'valid-key service-msg' ); break;
								default: $validity_msg.addClass( 'invalid-key' ); break;
							}
						});
					}
				}
			</script>
			<?php
		}

		/**
		 * Filter the success message on license key page
		 *
		 * @param string $message
		 * @param string $tab
		 *
		 * @return string
		 */
		public function do_license_key_success_message( $message, $tab ) {

			if ( $tab != 'licenses' ) {
				return $message;
			}

			return '<div id="message" class="updated"><p><strong>' . esc_html__( 'License key(s) updated.', 'tribe-common' ) . '</strong></p></div>';

		}

		public function validate_key( $key ) {
			$response           = array();
			$response['status'] = 0;

			if ( ! $key ) {
				$response['message'] = sprintf( esc_html__( 'Hmmm... something\'s wrong with this validator. Please contact %ssupport%s.', 'tribe-common' ), '<a href="http://m.tri.be/1u">', '</a>' );
				return $response;
			}

			$queryArgs = array(
				'pu_install_key'          => trim( $key ),
				'pu_checking_for_updates' => '1',
			);

			//include version info
			$queryArgs['pue_active_version'] = $this->get_installed_version();

			global $wp_version;
			$queryArgs['wp_version'] = $wp_version;

			// For multisite, return the network-level siteurl ... in
			// all other cases return the actual URL being serviced
			$queryArgs['domain'] = is_multisite() ? $this->get_network_domain() : $_SERVER['SERVER_NAME'];

			if ( is_multisite() ) {
				$queryArgs['multisite']         = 1;
				$queryArgs['network_activated'] = is_plugin_active_for_network( $this->get_plugin_file() );
				global $wpdb;
				$queryArgs['active_sites'] = $wpdb->get_var( "SELECT count(blog_id) FROM $wpdb->blogs WHERE public = '1' AND archived = '0' AND spam = '0' AND deleted = '0'" );
			} else {
				$queryArgs['multisite']         = 0;
				$queryArgs['network_activated'] = 0;
				$queryArgs['active_sites']      = 1;
			}

			$plugin_info = $this->request_info( $queryArgs );
			$expiration = isset( $plugin_info->expiration ) ? $plugin_info->expiration : esc_html__( 'unknown date', 'tribe-common' );

			if ( empty( $plugin_info ) ) {
				$response['message'] = esc_html__( 'Sorry, key validation server is not available.', 'tribe-common' );
			} elseif ( isset( $plugin_info->api_expired ) && $plugin_info->api_expired == 1 ) {
				$response['message'] = $this->get_license_expired_message();
			} elseif ( isset( $plugin_info->api_upgrade ) && $plugin_info->api_upgrade == 1 ) {
				$response['message'] = $this->get_api_message( $plugin_info );
			} elseif ( isset( $plugin_info->api_invalid ) && $plugin_info->api_invalid == 1 ) {
				$response['message'] = $this->get_api_message( $plugin_info );
			} else {
				$api_secret_key = get_option( $this->pue_install_key );
				if ( $api_secret_key && $api_secret_key === $queryArgs['pu_install_key'] ){
					$default_success_msg = sprintf( esc_html__( 'Valid Key! Expires on %s', 'tribe-common' ), $expiration );
				} else {
					// Set the key
					update_option( $this->pue_install_key, $queryArgs['pu_install_key'] );

					$default_success_msg = sprintf( esc_html__( 'Thanks for setting up a valid key, it will expire on %s', 'tribe-common' ), $expiration );

					//Set SysInfo Key on Tec.com After Successful Validation of License
					$optin_key = get_option( 'tribe_systeminfo_optin' );
					if ( $optin_key ) {
						Tribe__Support::send_sysinfo_key( $optin_key, $queryArgs['domain'], false, true );
					}
				}

				$response['status']     = isset( $plugin_info->api_message ) ? 2 : 1;
				$response['message']    = isset( $plugin_info->api_message ) ? wp_kses( $plugin_info->api_message, 'data' ) : $default_success_msg;
				$response['expiration'] = $expiration;
			}

			return $response;
		}

		/**
		 * Echo JSON results for key validation
		 */
		public function ajax_validate_key() {
			$key = isset( $_POST['key'] ) ? $_POST['key'] : null;

			$response = $this->validate_key( $key );

			echo json_encode( $response );
			exit;
		}

		/**
		 * processes variable substitutions for server-side API message
		 */
		private function get_api_message( $info ) {
			// this default message should never show, but is here as a fallback just in case.
			$message = sprintf(
				esc_html__( 'Sorry, there is a problem with your license key. You\'ll need to %scheck your license%s to have access to updates, downloads, and support.', 'tribe-common' ),
				'<a href="https://theeventscalendar.com/license-keys/">',
				'</a>'
			);

			if ( ! empty( $info->api_invalid_message ) ) {
				$message = wp_kses( $info->api_invalid_message, 'post' );
			}

			$message = str_replace( '%plugin_name%', '<b>' . $this->get_plugin_name() . '</b>', $message );
			$message = str_replace( '%plugin_slug%', $this->get_slug(), $message );
			$message = str_replace( '%update_url%', $this->get_pue_update_url(), $message );
			$message = str_replace( '%version%', $info->version, $message );

			return $message;
		}

		/**
		 * Displays an error notice if a premium plugin is activated and the license is expired
		 *
		 * @since 4.3
		 *
		 * @return bool|string
		 */
		public function display_license_error_message() {
			$plugin_info = $this->plugin_info;

			if ( ! current_user_can( 'install_plugins' ) ) {
				return false;
			}

			if ( ! isset( $plugin_info->api_invalid ) ) {
				return false;
			}

			$expired_license_msg     = $this->get_api_message( $plugin_info );
			$expired_license_message = str_replace( '%plugin_name%', '<strong>' . $this->get_plugin_name() . '</strong>', $expired_license_msg );

			$html[] = '<img class="tribe-spirit-animal" src="' . esc_url( Tribe__Main::instance()->plugin_url . 'src/resources/images/spirit-animal.png' ) . '">';
			$html[] = '<p>' . wp_kses( $expired_license_message, 'post' ) . '</p>';

			if ( isset( $plugin_info->api_expired ) ) {
				$html[] = '<p>' . $this->get_license_expired_message() . '</p>';
			} else {
				$license_tab = admin_url( 'edit.php?page=tribe-common&tab=licenses&post_type=tribe_events' );
				$license_tab_link = sprintf( '<a href="' . $license_tab . '">%s</a>', esc_html__( 'Add your license key', 'tribe-common' ) );
				$tec_link = '<a href="https://theeventscalendar.com" target="_blank">' . esc_html__( 'theeventscalendar.com', 'tribe-common' ) . '<span class="screen-reader-text">' .  esc_html__( 'opens in a new window', 'tribe-common' ) . '</span></a>';
				$link   = '<a href="http://m.tri.be/195d" target="_blank">' . esc_html__( 'license keys', 'tribe-common' ) . '<span class="screen-reader-text">' .  esc_html__( 'opens in a new window', 'tribe-common' ) . '</span></a>';
				$html[] = '<p>' . sprintf( __( '%s so that you can always have access to the latest versions including bug fixes, security updates, and new features.', 'tribe-common' ), $license_tab_link ) . '</p>';
				$html[] = '<p>' . sprintf( __( 'You can find your %1$s in your account on %2$s.', 'tribe-common' ), $link, $tec_link ) . '</p>';
			}
			return Tribe__Admin__Notices::instance()->render( 'pue-validation', implode( "\r\n", $html ) );
		}

		public function get_license_expired_message() {
			$helper_text = '<span class="screen-reader-text">' . __( ' (opens in a new window)', 'tribe-common' ) . '</span>';
			return sprintf(
				__(
					'This license key is expired! You need to %1$srenew your license %2$s %3$s to get access to the latest versions including bug fixes, security updates, and new features.',
					'tribe-common'
				),
				'<a href="http://m.tri.be/195y" target="_blank">',
				$helper_text,
				'</a>'
			);
		}

		/**
		 * Displays a PUE message on the page if it is relevant
		 */
		public function maybe_display_json_error_on_plugins_page( $page ) {
			if ( 'plugins.php' !== $page ) {
				return;
			}

			$state = $this->get_option( $this->pue_option_name, false, false );

			if ( empty( $state->update->license_error ) ) {
				return;
			}

			$this->plugin_notice = array(
				'slug' => $this->get_slug(),
				'message' => $state->update->license_error,
			);
			add_filter( 'tribe_plugin_notices', array( $this, 'add_notice_to_plugin_notices' ) );
		}

		public function add_notice_to_plugin_notices( $notices ) {
			if ( ! $this->plugin_notice ) {
				return $notices;
			}

			$notices[ $this->plugin_notice['slug'] ] = $this->plugin_notice;

			return $notices;
		}

		/**
		 * Retrieve plugin info from the configured API endpoint.
		 *
		 * @param array $queryArgs Additional query arguments to append to the request. Optional.
		 *
		 * @uses wp_remote_get()
		 * @return string $plugin_info
		 */
		public function request_info( $queryArgs = array() ) {
			//Query args to append to the URL. Plugins can add their own by using a filter callback (see add_query_arg_filter()).
			$queryArgs['installed_version'] = $this->get_installed_version();
			$queryArgs['pu_request_plugin'] = $this->get_slug();

			if ( empty( $queryArgs['pu_plugin_api'] ) && ! empty( $this->api_secret_key ) ) {
				$queryArgs['pu_plugin_api'] = $this->api_secret_key;
			}

			if ( empty( $queryArgs['pu_install_key'] ) && ! empty( $this->install_key ) ) {
				$queryArgs['pu_install_key'] = $this->install_key;
			}

			//include version info
			$queryArgs['pue_active_version'] = $this->get_installed_version();

			global $wp_version;
			$queryArgs['wp_version'] = $wp_version;

			//include domain and multisite stats
			$queryArgs['domain'] = is_multisite() ? $this->get_network_domain() : $_SERVER['SERVER_NAME'];

			if ( is_multisite() ) {
				$queryArgs['multisite']         = 1;
				$queryArgs['network_activated'] = is_plugin_active_for_network( $this->get_plugin_file() );
				global $wpdb;
				$queryArgs['active_sites'] = $wpdb->get_var( "SELECT count(blog_id) FROM $wpdb->blogs WHERE public = '1' AND archived = '0' AND spam = '0' AND deleted = '0'" );

			} else {
				$queryArgs['multisite']         = 0;
				$queryArgs['network_activated'] = 0;
				$queryArgs['active_sites']      = 1;
			}

			$queryArgs = apply_filters( 'tribe_puc_request_info_query_args-' . $this->get_slug(), $queryArgs );

			//Various options for the wp_remote_get() call. Plugins can filter these, too.
			$options = array(
				'timeout' => 15, //seconds
				'headers' => array(
					'Accept' => 'application/json',
				),
			);
			$options = apply_filters( 'tribe_puc_request_info_options-' . $this->get_slug(), $options );

			$url = $this->get_pue_update_url();
			if ( ! empty( $queryArgs ) ) {
				$url = esc_url_raw( add_query_arg( $queryArgs, $url ) );
			}

			// Cache the API call so it only needs to be made once per plugin per page load.
			static $plugin_info_cache;
			$key = crc32( implode( '', $queryArgs ) );
			if ( isset( $plugin_info_cache[ $key ] ) ) {
				return $plugin_info_cache[ $key ];
			}

			$result = wp_remote_get(
				$url,
				$options
			);

			//Try to parse the response
			$plugin_info = null;
			if ( ! is_wp_error( $result ) && isset( $result['response']['code'] ) && ( $result['response']['code'] == 200 ) && ! empty( $result['body'] ) ) {
				$plugin_info = Tribe__PUE__Plugin_Info::from_json( $result['body'] );
			}
			$plugin_info = apply_filters( 'tribe_puc_request_info_result-' . $this->get_slug(), $plugin_info, $result );

			$plugin_info_cache[ $key ] = $plugin_info;

			return $plugin_info;
		}

		/**
		 * Returns the domain contained in the network's siteurl option (not the full URL).
		 *
		 * @return string
		 */
		public function get_network_domain() {
			$site_url = parse_url( get_site_option( 'siteurl' ) );
			if ( ! $site_url || ! isset( $site_url['host'] ) ) {
				return '';
			} else {
				return strtolower( $site_url['host'] );
			}
		}

		/**
		 * Retrieve the latest update (if any) from the configured API endpoint.
		 *
		 * @uses Tribe__PUE__Checker::request_info()
		 *
		 * @return Tribe__PUE__Utility An instance of Tribe__PUE__Utility, or NULL when no updates are available.
		 */
		public function request_update() {
			// For the sake of simplicity, this function just calls request_info()
			// and transforms the result accordingly.
			$args = array(
				'pu_checking_for_updates' => 1,
			);

			if ( ! empty( $_POST['key'] ) ) {
				$args['pu_install_key'] = $_POST['key'];
			}

			$this->plugin_info = $plugin_info = $this->request_info( $args );

			if ( null === $plugin_info ) {
				return null;
			}

			// admin display for if the update check reveals that there is a new version but the API key isn't valid.
			if ( isset( $plugin_info->api_invalid ) ) {
				//we have json_error returned let's display a message
				$this->json_error = $this->plugin_info;
				add_action( 'admin_notices', array( &$this, 'maybe_display_json_error_on_plugins_page' ) );

				$plugin_info = Tribe__PUE__Utility::from_plugin_info( $plugin_info );
				$plugin_info->license_error = $this->get_api_message( $plugin_info );
				return $plugin_info;
			}

			if ( isset( $plugin_info->new_install_key ) ) {
				$this->update_option( $this->pue_install_key, $plugin_info->new_install_key );
			}

			//need to correct the download url so it contains the custom user data (i.e. api and any other paramaters)

			$download_query = $this->get_download_query();
			if ( ! empty( $download_query ) ) {
				$plugin_info->download_url = esc_url_raw( add_query_arg( $download_query, $plugin_info->download_url ) );
			}

			// Add plugin dirname/file (this will be expected by WordPress when it builds the plugin list table)
			$plugin_info->plugin = $this->get_plugin_file();

			return Tribe__PUE__Utility::from_plugin_info( $plugin_info );
		}


		/**
		 * Display the upgrade message in the plugin list under the plugin.
		 *
		 * @param $plugin_data
		 */
		public function in_plugin_update_message( $plugin_data ) {
			$plugin_info = $this->plugin_info;

			//only display messages if there is a new version of the plugin.
			if ( is_object( $plugin_info ) && version_compare( $plugin_info->version, $this->get_installed_version(), '>' ) ) {
				if ( $plugin_info->api_invalid ) {
					$msg = str_replace( '%plugin_name%', '<strong>' . $this->get_plugin_name() . '</strong>', $plugin_info->api_inline_invalid_message );
					$msg = str_replace( '%plugin_slug%', $this->get_slug(), $msg );
					$msg = str_replace( '%update_url%', $this->get_pue_update_url(), $msg );
					$msg = str_replace( '%version%', $plugin_info->version, $msg );
					$msg = str_replace( '%changelog%', '<a class="thickbox" title="' . $this->get_plugin_name() . '" href="plugin-install.php?tab=plugin-information&plugin=' . $this->get_slug() . '&TB_iframe=true&width=640&height=808">what\'s new</a>', $msg );
					echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $msg . '</div></td>';
				}
			}
		}


		/**
		 * Display a changelog when the api key is missing.
		 */
		public function display_changelog() {
			//contents of changelog display page when api-key is invalid or missing.  It will ONLY show the changelog (hook into existing thickbox?)
		}

		/**
		 * Update option to dismiss the upgrade notice.
		 */
		public function dashboard_dismiss_upgrade() {
			$os_ary = $this->get_option( $this->dismiss_upgrade );
			if ( ! is_array( $os_ary ) ) {
				$os_ary = array();
			}

			$os_ary[] = $_POST['version'];
			$this->update_option( $this->dismiss_upgrade, $os_ary );
		}

		/**
		 * Get the currently installed version of the plugin.
		 *
		 * @return string Version number.
		 */
		public function get_installed_version() {
			if ( function_exists( 'get_plugins' ) ) {
				$allPlugins = get_plugins();
				if ( array_key_exists( $this->get_plugin_file(), $allPlugins ) && array_key_exists( 'Version', $allPlugins[ $this->get_plugin_file() ] ) ) {
					return $allPlugins[ $this->get_plugin_file() ]['Version'];
				}
			}
		}

		/**
		 * Get MU compatible options.
		 *
		 * @param string     $option_key
		 * @param bool|mixed $default
		 * @param bool       $use_cache
		 *
		 * @return null|mixed
		 */
		public function get_option( $option_key, $default = false, $use_cache = true ) {
			return get_site_option( $option_key, $default, $use_cache );
		}

		/**
		 * Update MU compatible options.
		 *
		 * @param mixed $option_key
		 * @param mixed $value
		 */
		public function update_option( $option_key, $value ) {
			update_site_option( $option_key, $value );
		}

		/**
		 * Check for plugin updates.
		 *
		 * The results are stored in the DB option specified in $pue_option_name.
		 *
		 * @param array $updates
		 *
		 */
		public function check_for_updates( $updates = array() ) {
			$state = $this->get_option( $this->pue_option_name, false, false );
			if ( empty( $state ) ) {
				$state                 = new StdClass;
				$state->lastCheck      = 0;
				$state->checkedVersion = '';
				$state->update         = null;
			}

			$state->lastCheck      = time();
			$state->checkedVersion = $this->get_installed_version();
			$this->update_option( $this->pue_option_name, $state ); //Save before checking in case something goes wrong

			$state->update = $this->request_update();

			// If a null update was returned, skip the end of the function.
			if ( $state->update == null ) {
				$this->update_option( $this->pue_option_name, $state );
				return $updates;
			}

			//Is there an update to insert?
			if ( version_compare( $state->update->version, $this->get_installed_version(), '>' ) ) {
				if ( empty( $updates ) ) {
					$updates = array( 'response' => array() );
					$updates = (object) $updates;
				}
				$updates->response[ $this->get_plugin_file() ] = $state->update->to_wp_format();
			}

			$this->update_option( $this->pue_option_name, $state );
			add_action( 'after_plugin_row_' . $this->get_plugin_file(), array( &$this, 'in_plugin_update_message' ) );

			return $updates;
		}

		/**
		 * Clears out the site external site option and re-checks the license key
		 */
		public function check_for_api_key_error( $old_value, $value ) {
			delete_site_option( $this->pue_option_name );

			$this->check_for_updates();
		}

		/**
		 * Intercept plugins_api() calls that request information about our plugin and
		 * use the configured API endpoint to satisfy them.
		 *
		 * @see plugins_api()
		 *
		 * @param mixed        $result
		 * @param string       $action
		 * @param array|object $args
		 *
		 * @return mixed
		 */
		public function inject_info( $result, $action = null, $args = null ) {
			$relevant = ( $action == 'plugin_information' ) && isset( $args->slug ) && ( $args->slug == $this->slug );
			if ( ! $relevant ) {
				return $result;
			}

			$plugin_info = $this->request_info( array( 'pu_checking_for_updates' => '1' ) );
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
		 * @uses add_filter() This method is a convenience wrapper for add_filter().
		 *
		 * @param callback $callback
		 *
		 */
		public function add_query_arg_filter( $callback ) {
			add_filter( 'tribe_puc_request_info_query_args-' . $this->get_slug(), $callback );
		}

		/**
		 * Register a callback for filtering arguments passed to wp_remote_get().
		 *
		 * The callback function should take one argument - an associative array of arguments -
		 * and return a modified array or arguments. See the WP documentation on wp_remote_get()
		 * for details on what arguments are available and how they work.
		 *
		 * @uses add_filter() This method is a convenience wrapper for add_filter().
		 *
		 * @param callback $callback
		 *
		 */
		public function add_http_request_arg_filter( $callback ) {
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
		 * @uses add_filter() This method is a convenience wrapper for add_filter().
		 *
		 * @param callback $callback
		 *
		 */
		public function add_result_filter( $callback ) {
			add_filter( 'tribe_puc_request_info_result-' . $this->get_slug(), $callback, 10, 2 );
		}

		/**
		 * Insert an array after a specified key within another array.
		 *
		 * @param $key
		 * @param $source_array
		 * @param $insert_array
		 *
		 * @return array
		 *
		 */
		public static function array_insert_after_key( $key, $source_array, $insert_array ) {
			if ( array_key_exists( $key, $source_array ) ) {
				$position     = array_search( $key, array_keys( $source_array ) ) + 1;
				$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
			} else {
				// If no key is found, then add it to the end of the array.
				$source_array += $insert_array;
			}

			return $source_array;
		}

		/**
		 * Add this plugin key to the list of keys
		 *
		 * @param array $keys
		 *
		 * @return array $keys
		 *
		 */
		public function return_install_key( $keys = array() ) {
			if ( ! empty( $this->install_key ) ) {
				$keys[ $this->get_slug() ] = $this->install_key;
			}

			return $keys;
		}
	}
}
