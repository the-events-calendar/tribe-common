<?php
/**
 * Class for managing technical support components
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__Support' ) ) {

	class Tribe__Support {

		public static $support;
		public $rewrite_rules_purged = false;

		/**
		 * @var Tribe__Support__Obfuscator
		 */
		protected $obfuscator;

		/**
		 * Fields listed here contain HTML and should be escaped before being
		 * printed.
		 *
		 * @var array
		 */
		protected $must_escape = array(
			'tribeEventsAfterHTML',
			'tribeEventsBeforeHTML',
		);

		/**
		 * Field prefixes here should be partially obfuscated before being printed.
		 *
		 * @var array
		 */
		protected $must_obfuscate_prefixes = array(
			'pue_install_key_',
		);

		private function __construct() {
			$this->must_escape = (array) apply_filters( 'tribe_help_must_escape_fields', $this->must_escape );
			add_action( 'tribe_help_pre_get_sections', array( $this, 'append_system_info' ), 10 );
			add_action( 'delete_option_rewrite_rules', array( $this, 'log_rewrite_rule_purge' ) );
		}

		/**
		 * Display help tab info in events settings
		 * @param Tribe__Admin__Help_Page $help The Help Page Instance
		 */
		public function append_system_info( Tribe__Admin__Help_Page $help ) {
			$help->add_section_content( 'system-info', $this->formattedSupportStats(), 10 );
		}

		/**
		 * Collect system information for support
		 *
		 * @return array of system data for support
		 */
		public function getSupportStats() {
			$user = wp_get_current_user();

			$plugins = array();
			if ( function_exists( 'get_plugin_data' ) ) {
				$plugins_raw = wp_get_active_and_valid_plugins();
				foreach ( $plugins_raw as $k => $v ) {
					$plugin_details = get_plugin_data( $v );
					$plugin         = $plugin_details['Name'];
					if ( ! empty( $plugin_details['Version'] ) ) {
						$plugin .= sprintf( ' version %s', $plugin_details['Version'] );
					}
					if ( ! empty( $plugin_details['Author'] ) ) {
						$plugin .= sprintf( ' by %s', $plugin_details['Author'] );
					}
					if ( ! empty( $plugin_details['AuthorURI'] ) ) {
						$plugin .= sprintf( '(%s)', $plugin_details['AuthorURI'] );
					}
					$plugins[] = $plugin;
				}
			}

			$network_plugins = array();
			if ( is_multisite() && function_exists( 'get_plugin_data' ) ) {
				$plugins_raw = wp_get_active_network_plugins();
				foreach ( $plugins_raw as $k => $v ) {
					$plugin_details = get_plugin_data( $v );
					$plugin         = $plugin_details['Name'];
					if ( ! empty( $plugin_details['Version'] ) ) {
						$plugin .= sprintf( ' version %s', $plugin_details['Version'] );
					}
					if ( ! empty( $plugin_details['Author'] ) ) {
						$plugin .= sprintf( ' by %s', $plugin_details['Author'] );
					}
					if ( ! empty( $plugin_details['AuthorURI'] ) ) {
						$plugin .= sprintf( '(%s)', $plugin_details['AuthorURI'] );
					}
					$network_plugins[] = $plugin;
				}
			}

			$mu_plugins = array();
			if ( function_exists( 'get_mu_plugins' ) ) {
				$mu_plugins_raw = get_mu_plugins();
				foreach ( $mu_plugins_raw as $k => $v ) {
					$plugin = $v['Name'];
					if ( ! empty( $v['Version'] ) ) {
						$plugin .= sprintf( ' version %s', $v['Version'] );
					}
					if ( ! empty( $v['Author'] ) ) {
						$plugin .= sprintf( ' by %s', $v['Author'] );
					}
					if ( ! empty( $v['AuthorURI'] ) ) {
						$plugin .= sprintf( '(%s)', $v['AuthorURI'] );
					}
					$mu_plugins[] = $plugin;
				}
			}

			$keys = apply_filters( 'tribe-pue-install-keys', array() );

			$systeminfo = array(
				'Home URL'           => get_home_url(),
				'Site URL'           => get_site_url(),
				'name'               => $user->display_name,
				'email'              => $user->user_email,
				'install keys'       => $keys,
				'WordPress version'  => get_bloginfo( 'version' ),
				'PHP version'        => phpversion(),
				'plugins'            => $plugins,
				'network plugins'    => $network_plugins,
				'mu plugins'         => $mu_plugins,
				'theme'              => wp_get_theme()->get( 'Name' ),
				'multisite'          => is_multisite(),
				'settings'           => Tribe__Settings_Manager::get_options(),
				'WordPress timezone' => get_option( 'timezone_string', esc_html__( 'Unknown or not set', 'tribe-common' ) ),
				'server timezone'    => date_default_timezone_get(),
				'common library dir' => $GLOBALS['tribe-common-info']['dir'],
				'common library version' => $GLOBALS['tribe-common-info']['version'],
			);

			if ( $this->rewrite_rules_purged ) {
				$systeminfo['rewrite rules purged'] = esc_html__( 'Rewrite rules were purged on load of this help page. Chances are there is a rewrite rule flush occurring in a plugin or theme!', 'tribe-common' );
			}

			$systeminfo = apply_filters( 'tribe-events-pro-support', $systeminfo );

			return $systeminfo;
		}

		/**
		 * Render system information into a pretty output
		 *
		 * @return string pretty HTML
		 */
		public function formattedSupportStats() {
			$systeminfo = $this->getSupportStats();
			$output     = '';
			$output .= '<dl class="support-stats">';
			foreach ( $systeminfo as $k => $v ) {

				switch ( $k ) {
					case 'name' :
					case 'email' :
						continue 2;
						break;
					case 'url' :
						$v = sprintf( '<a href="%s">%s</a>', $v, $v );
						break;
				}

				if ( is_array( $v ) ) {
					$keys             = array_keys( $v );
					$key              = array_shift( $keys );
					$is_numeric_array = is_numeric( $key );
					unset( $keys );
					unset( $key );
				}

				$output .= sprintf( '<dt>%s</dt>', $k );
				if ( empty( $v ) ) {
					$output .= '<dd class="support-stats-null">-</dd>';
				} elseif ( is_bool( $v ) ) {
					$output .= sprintf( '<dd class="support-stats-bool">%s</dd>', $v );
				} elseif ( is_string( $v ) ) {
					$output .= sprintf( '<dd class="support-stats-string">%s</dd>', $v );
				} elseif ( is_array( $v ) && $is_numeric_array ) {
					$output .= sprintf( '<dd class="support-stats-array"><ul><li>%s</li></ul></dd>', join( '</li><li>', $v ) );
				} else {
					$formatted_v = array();
					foreach ( $v as $obj_key => $obj_val ) {
						if ( in_array( $obj_key, $this->must_escape ) ) {
							$obj_val = esc_html( $obj_val );
						}

						$obj_val = $this->obfuscator->obfuscate( $obj_key, $obj_val );

						if ( is_array( $obj_val ) ) {
							$formatted_v[] = sprintf( '<li>%s = <pre>%s</pre></li>', $obj_key, print_r( $obj_val, true ) );
						} else {
							$formatted_v[] = sprintf( '<li>%s = %s</li>', $obj_key, $obj_val );
						}
					}
					$v = join( "\n", $formatted_v );
					$output .= sprintf( '<dd class="support-stats-object"><ul>%s</ul></dd>', print_r( $v, true ) );
				}
			}
			$output .= '</dl>';

			return $output;
		}

		/**
		 * Logs the occurence of rewrite rule purging
		 */
		public function log_rewrite_rule_purge() {
			$this->rewrite_rules_purged = true;
		}//end log_rewrite_rule_purge

		/**
		 * Sets the obfuscator to be used.
		 *
		 * @param Tribe__Support__Obfuscator $obfuscator
		 */
		public function set_obfuscator( Tribe__Support__Obfuscator $obfuscator ) {
			$this->obfuscator = $obfuscator;
		}

		/****************** SINGLETON GUTS ******************/

		/**
		 * Enforce Singleton Pattern
		 */
		private static $instance;


		public static function getInstance() {
			if ( null == self::$instance ) {
				$instance       = new self;
				$instance->set_obfuscator( new Tribe__Support__Obfuscator( $instance->must_obfuscate_prefixes ) );
				self::$instance = $instance;
			}

			return self::$instance;
		}
	}

}
