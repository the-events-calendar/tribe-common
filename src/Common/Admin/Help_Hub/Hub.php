<?php
/**
 * Hub class for managing the Help Hub functionality.
 *
 * This class handles rendering the Help Hub page, loading necessary assets,
 * and generating iframes and admin notices related to the Help Hub.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub;

use TEC\Common\Admin\Help_Hub\Resource_Data\Help_Hub_Data_Interface;
use RuntimeException;
use TEC\Common\StellarWP\AdminNotices\AdminNotice;
use TEC\Common\StellarWP\AdminNotices\AdminNotices;
use Tribe__Main;
use Tribe__Template;
use TEC\Common\Configuration\Configuration;

/**
 * Class Hub
 *
 * Manages the Help Hub functionality, including rendering templates,
 * loading assets, and managing iframe content and notices.
 *
 * @package TEC\Common\Admin\Help_Hub
 */
class Hub {

	/**
	 * Data object implementing Help_Hub_Data_Interface, providing necessary Help Hub resources.
	 *
	 * @since TBD
	 *
	 * @var Help_Hub_Data_Interface
	 */
	protected Help_Hub_Data_Interface $data;

	/**
	 * The template class.
	 *
	 * @since TBD
	 *
	 * @var Tribe__Template
	 */
	protected Tribe__Template $template;

	/**
	 * The configuration object.
	 *
	 * @since TBD
	 *
	 * @var Configuration
	 */
	protected Configuration $config;

	/**
	 * Constructor.
	 *
	 * @since TBD
	 *
	 * @param Help_Hub_Data_Interface $data     The data class instance containing Help Hub resources.
	 * @param Configuration           $config   The Zendesk support key.
	 * @param Tribe__Template         $template The template class.
	 */
	public function __construct( Help_Hub_Data_Interface $data, Configuration $config, Tribe__Template $template ) {
		$this->config   = $config;
		$this->template = $template;
		$this->data     = $data;
		$this->register_hooks();
	}

	/**
	 * Retrieves the data object used by the Help Hub.
	 *
	 * @since TBD
	 *
	 * @return Help_Hub_Data_Interface The data object containing Help Hub resources.
	 */
	public function get_data(): Help_Hub_Data_Interface {
		return $this->data;
	}

	/**
	 * Registers the hooks and filters needed for Help Hub functionality.
	 *
	 * Sets up actions and filters for initializing iframe content,
	 * loading assets, and adding custom body classes for Help Hub pages.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		add_action( 'admin_init', [ $this, 'generate_iframe_content' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ], 1 );
		add_filter( 'admin_body_class', [ $this, 'add_help_page_body_class' ] );
	}

	/**
	 * Ensures that the Help Hub data object is set.
	 *
	 * Verifies that the $data property has been set. Throws a RuntimeException
	 * if the data has not been set using the setup method.
	 *
	 * @since TBD
	 *
	 * @return void
	 * @throws RuntimeException If data has not been set using setup.
	 */
	protected function ensure_data_is_set(): void {
		if ( empty( $this->data ) ) {
			throw new RuntimeException( 'The HelpHub data must be set using the setup method before calling this function.' );
		}
	}

	/**
	 * Renders the Help Hub page.
	 *
	 * Generates necessary notices, retrieves data, and renders the appropriate Help Hub template.
	 *
	 * @since TBD
	 *
	 * @return void
	 * @throws RuntimeException If data is not set using the setup method before rendering.
	 */
	public function render(): void {
		/**
		 * Fires before the Help Hub page is rendered.
		 *
		 * Use this hook to modify data or enqueue additional assets before the Help Hub template is generated.
		 *
		 * @since TBD
		 *
		 * @param Hub $this The Hub instance.
		 */
		do_action( 'tec_help_hub_before_render', $this );

		$this->ensure_data_is_set();

		$notice_html      = $this->generate_notice_html();
		$status           = $this->data->get_license_and_opt_in_status();
		$template_variant = self::get_template_variant( $status['has_valid_license'], $status['is_opted_in'] );

		$this->render_template(
			'help-hub',
			[
				'notice'            => $notice_html,
				'template_variant'  => $template_variant,
				'resource_sections' => $this->handle_resource_sections(),
			]
		);

		/**
		 * Fires after the Help Hub page is rendered.
		 *
		 * Use this hook to perform actions or cleanup tasks after the Help Hub template is generated and displayed.
		 *
		 * @since TBD
		 *
		 * @param Hub $this The Hub instance.
		 */
		do_action( 'tec_help_hub_after_render', $this );
	}

	/**
	 * Handles and filters the resource sections for the Help Hub.
	 *
	 * This method centralizes the creation and filtering of resource sections,
	 * allowing for a single point of modification based on the data class.
	 *
	 * @since TBD
	 *
	 * @return array The filtered resource sections.
	 */
	public function handle_resource_sections(): array {
		$sections        = $this->data->create_resource_sections();
		$data_class_name = get_class( $this->data );

		/**
		 * Filter the Help Hub resource sections for a specific data class.
		 *
		 * This dynamic filter allows customization of the Help Hub resource sections specific
		 * to a given data class, enabling more granular control over section customization.
		 *
		 * @since TBD
		 *
		 * @param array                   $sections        The array of resource sections.
		 * @param Help_Hub_Data_Interface $data            The data instance used for generating sections.
		 */
		$sections = apply_filters( "tec_help_hub_resource_sections{$data_class_name}", $sections, $this->data );

		/**
		 * Filter the Help Hub resource sections.
		 *
		 * Allows customization of the Help Hub resource sections by other components.
		 *
		 * @since TBD
		 *
		 * @param array                   $sections        The array of resource sections.
		 * @param Help_Hub_Data_Interface $data            The data instance used for generating sections.
		 * @param string                  $data_class_name The name of the data class.
		 */
		return apply_filters( 'tec_help_hub_resource_sections', $sections, $this->data, $data_class_name );
	}

	/**
	 * Determines the template variant based on license validity and opt-in status.
	 *
	 * @since TBD
	 *
	 * @param bool $has_valid_license Whether the license is valid.
	 * @param bool $is_opted_in      Whether the user has opted into telemetry.
	 *
	 * @return string The template variant.
	 */
	protected static function get_template_variant( bool $has_valid_license, bool $is_opted_in ): string {
		if ( ! $has_valid_license ) {
			return 'no-license';
		}

		return $is_opted_in ? 'has-license-has-consent' : 'has-license-no-consent';
	}

	/**
	 * Checks if the current page is a Help Hub page.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public static function is_current_page(): bool {
		global $current_screen;

		$help_pages = [
			'tribe_events_page_tec-events-help',
			'tribe_events_page_tec-events-help-hub',
		];

		return in_array( $current_screen->id, $help_pages, true );
	}

	/**
	 * Adds custom body classes to the Help Hub page.
	 *
	 * @since TBD
	 *
	 * @param string $classes Space-separated string of classes for the body tag.
	 *
	 * @return string Filtered list of classes.
	 */
	public function add_help_page_body_class( $classes ) {
		if ( ! self::is_current_page() ) {
			return $classes;
		}

		$class_array = array_merge( explode( ' ', $classes ), [ 'tribe-help', 'tec-help', 'tribe_events_page_tec-events-settings' ] );

		return implode( ' ', array_unique( $class_array ) );
	}

	/**
	 * Enqueues assets for the Help Hub page.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function load_assets() {
		if ( ! self::is_current_page() ) {
			return;
		}

		tribe_asset(
			Tribe__Main::instance(),
			'tec-common-help-hub-style',
			'help-hub.css',
			null,
			'admin_enqueue_scripts'
		);

		tribe_asset(
			Tribe__Main::instance(),
			'tribe-admin-help-page',
			'admin/help-page.js',
			[ 'tribe-clipboard', 'tribe-common' ],
			'admin_enqueue_scripts',
			[
				'conditionals' => [ self::class, 'is_current_page' ],
				'localize'     => [
					'name' => 'tribe_system_info',
					'data' => [
						'sysinfo_optin_nonce'        => wp_create_nonce( 'sysinfo_optin_nonce' ),
						'clipboard_btn_text'         => _x( 'Copy to clipboard', 'Copy to clipboard button text.', 'tribe-common' ),
						'clipboard_copied_text'      => _x( 'System info copied', 'Copy to clipboard success message', 'tribe-common' ),
						'clipboard_fail_text'        => _x( 'Press "Cmd + C" to copy', 'Copy to clipboard instructions', 'tribe-common' ),
						'sysinfo_error_message_text' => _x( 'Something has gone wrong!', 'Default error message for system info optin', 'tribe-common' ),
						'sysinfo_error_code_text'    => _x( 'Code:', 'Error code label for system info optin', 'tribe-common' ),
						'sysinfo_error_status_text'  => _x( 'Status:', 'Error status label for system info optin', 'tribe-common' ),
					],
				],
			]
		);

		wp_enqueue_script( 'jquery-ui-accordion' );
	}

	/**
	 * Generates a telemetry opt-in link.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_telemetry_opt_in_link(): string {
		return add_query_arg(
			[
				'page'      => 'tec-events-settings',
				'tab'       => 'general-debugging-tab',
				'post_type' => 'tribe_events',
			],
			admin_url( 'edit.php' )
		);
	}

	/**
	 * Generates and outputs iframe content when appropriate.
	 *
	 * @since TBD
	 *
	 * @return void
	 * @throws RuntimeException If data has not been set using setup.
	 */
	public function generate_iframe_content(): void {
		$this->ensure_data_is_set();
		$page   = tribe_get_request_var( 'page' );
		$iframe = tribe_get_request_var( 'embedded_content' );

		if ( empty( $page ) || 'tec-events-help-hub' !== $page || empty( $iframe ) ) {
			return;
		}

		/**
		 * Fires before the Help Hub iframe content is rendered.
		 *
		 * Use this hook to enqueue additional assets, modify iframe-specific content,
		 * or take other actions just before the iframe content is generated.
		 *
		 * @since TBD
		 *
		 * @param Hub $this The Hub instance.
		 */
		do_action( 'tec_help_hub_before_iframe_render', $this );

		$this->register_iframe_hooks();

		// phpcs:ignore WordPressVIPMinimum.UserExperience.AdminBarRemoval.RemovalDetected
		show_admin_bar( false );
		$this->render_template( 'help-hub/support/iframe-content' );

		/**
		 * Fires after the Help Hub iframe content has been rendered.
		 *
		 * Use this hook to perform actions or cleanup tasks after the iframe content
		 * has been generated and displayed.
		 *
		 * @since TBD
		 *
		 * @param Hub $this The Hub instance.
		 */
		do_action( 'tec_help_hub_after_iframe_render', $this );

		exit;
	}

	/**
	 * Registers the hooks and filters needed for Help Hub Iframe functionality.
	 *
	 * Sets up actions and filters for initializing iframe content,
	 * loading assets, and adding custom body classes for Help Hub pages.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_iframe_hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_help_page_iframe_assets' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'dequeue_theme_styles' ] );
	}

	/**
	 * Enqueues assets specific to the iframe content and removes theme styles.
	 *
	 * @since TBD
	 * @return void
	 */
	public function enqueue_help_page_iframe_assets(): void {
		define( 'IFRAME_REQUEST', true );

		tribe_asset(
			Tribe__Main::instance(),
			'help-hub-iframe-style',
			'help-hub-iframe.css',
			null,
			'wp_enqueue_scripts'
		);

		tribe_asset(
			Tribe__Main::instance(),
			'help-hub-iframe-js',
			'admin/help-hub-iframe.js',
			null,
			'wp_enqueue_scripts',
			[
				'localize' => [
					'name' => 'helpHubSettings',
					'data' => [
						'docsbot_key'    => $this->config->get( 'TEC_HELP_HUB_CHAT_DOCSBOT_SUPPORT_KEY' ),
						'zendeskChatKey' => $this->config->get( 'TEC_HELP_HUB_CHAT_ZENDESK_CHAT_KEY' ),
					],
				],
			]
		);
	}

	/**
	 * Removes theme-related styles to avoid iframe conflicts.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public static function dequeue_theme_styles(): void {
		global $wp_styles;
		$theme_directory = get_template_directory_uri();

		foreach ( $wp_styles->queue as $handle ) {
			$src = $wp_styles->registered[ $handle ]->src;
			if ( strpos( $src, $theme_directory ) !== false ) {
				wp_dequeue_style( $handle );
			}
		}
	}

	/**
	 * Generates HTML for the admin notice.
	 *
	 * @since TBD
	 *
	 * @return string The HTML for the admin notice.
	 */
	private function generate_notice_html(): string {
		$notice_slug    = 'tec-common-help-chatbot-notice';
		$notice_content = sprintf(
			// translators: Placeholders are for the `a` tag that displays a link.
			_x(
				'To find the answer to all your questions use the %1$sTEC Chatbot%2$s',
				'The callout notice to try the chatbot with a link to the page',
				'tribe-common'
			),
			'<a data-tab-target="tec-help-tab" href="#">',
			'</a>'
		);

		$notice_admin = ( new AdminNotice( $notice_slug, "<p>$notice_content</p>" ) )
			->urgency( 'info' )
			->inline()
			->dismissible( false )
			->withWrapper();

		return AdminNotices::render( $notice_admin, false );
	}

	/**
	 * Renders the specified template with provided variables.
	 *
	 * @since TBD
	 *
	 * @param string $template_name The template file name (without .php).
	 * @param array  $extra_values  Extra values to pass to the template.
	 *
	 * @return void
	 */
	private function render_template( string $template_name, array $extra_values = [] ): void {
		$main     = Tribe__Main::instance();
		$template = $this->template;

		$template_values = wp_parse_args(
			$extra_values,
			[
				'main'          => $main,
				'status_values' => $this->data->get_license_and_opt_in_status(),
				'keys'          => $this->get_chat_keys(),
				'icons'         => $this->data->get_icon_urls( $main ),
				'links'         => self::get_links(),
			]
		);

		$template->set_values( $template_values );
		$template->set_template_origin( $main );
		$template->set_template_folder( 'src/admin-views' );
		$template->set_template_context_extract( true );
		$template->set_template_folder_lookup( false );
		$template->template( $template_name );
	}

	/**
	 * Retrieves chat keys from the configuration.
	 *
	 * @since TBD
	 *
	 * @return array Associative array containing chat keys.
	 */
	protected function get_chat_keys(): array {
		return [
			'zendesk_chat_key' => $this->config->get( 'TEC_HELP_HUB_CHAT_ZENDESK_CHAT_KEY' ),
			'docsbot_chat_key' => $this->config->get( 'TEC_HELP_HUB_CHAT_DOCSBOT_SUPPORT_KEY' ),
		];
	}

	/**
	 * Retrieves relevant template links, including the telemetry opt-in link.
	 *
	 * @since TBD
	 *
	 * @return array Associative array containing template links.
	 */
	protected static function get_links(): array {
		return [
			'opt_in_link' => self::get_telemetry_opt_in_link(),
		];
	}
}
