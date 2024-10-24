<?php
/**
 * Hub class for managing the Help Hub functionality.
 *
 * This class handles rendering the Help Hub page, loading necessary assets,
 * and generating iframes and admin notices related to the Help Hub.
 *
 * @since   TBD
 * @package TEC\Common\Help_Hub
 */

namespace TEC\Common\Help_Hub;

use TEC\Common\Configuration\Configuration;
use TEC\Common\StellarWP\AdminNotices\AdminNotice;
use TEC\Common\StellarWP\AdminNotices\AdminNotices;
use TEC\Common\Telemetry\Telemetry;
use Tribe__Main;
use Tribe__PUE__Checker;
use Tribe__Template;

/**
 * Class Hub
 *
 * @package TEC\Common\Help_Hub
 */
class Hub {

	/**
	 * @since TBD
	 *
	 * @var Configuration The configuration object.
	 */
	protected Configuration $config;

	/**
	 * Initialize any required vars.
	 */
	public function __construct() {
		$this->config = tribe( Configuration::class );

		if ( ! defined( 'DOCSBOT_SUPPORT_KEY' ) ) {
			// @todo Need key
			define( 'DOCSBOT_SUPPORT_KEY', '' );
		}
		if ( ! defined( 'ZENDESK_CHAT_KEY' ) ) {
			define( 'ZENDESK_CHAT_KEY', '' );
		}
	}

	/**
	 * Render the Help Hub page.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render(): void {
		// Generate the admin notice HTML.
		$notice_html = $this->generate_notice_html();

		$status           = $this->get_license_and_opt_in_status();
		$template_variant = $this->get_template_variant( $status['is_license_valid'], $status['is_opted_in'] );

		// Render the help page template.
		$this->render_template(
			'help-hub',
			[
				'notice'           => $notice_html,
				'template_variant' => $template_variant,
			]
		);
	}

	/**
	 * Get the license validity and telemetry opt-in status.
	 *
	 * @return array Contains 'is_license_valid' and 'is_opted_in' status.
	 */
	protected function get_license_and_opt_in_status(): array {
		$is_license_valid = Tribe__PUE__Checker::is_any_license_valid();
		$common_telemetry = tribe( Telemetry::class );
		$is_opted_in      = $common_telemetry->calculate_optin_status();

		return [
			'is_license_valid' => $is_license_valid,
			'is_opted_in'      => $is_opted_in,
		];
	}

	/**
	 * Determine the template variant based on the license and opt-in status.
	 *
	 * @param bool $is_license_valid Whether the license is valid.
	 * @param bool $is_opted_in Whether the user has opted into telemetry.
	 *
	 * @return string The template variant.
	 */
	protected function get_template_variant( bool $is_license_valid, bool $is_opted_in ): string {
		if ( $is_license_valid && $is_opted_in ) {
			return 'has-license-has-consent';
		} elseif ( $is_license_valid && ! $is_opted_in ) {
			return 'has-license-no-consent';
		}

		return 'no-license';
	}

	/**
	 * Checks if the current page is the Help one
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_current_page(): bool {
		global $current_screen;

		$help_pages = [
			'tribe_events_page_tec-events-help',
			'tribe_events_page_tec-events-help-hub',
		];

		return in_array( $current_screen->id, $help_pages );
	}

	/**
	 * Adds custom body classes to the admin for the help page.
	 *
	 * @since TBD
	 *
	 * @param string $classes Space-separated string of classes for the body tag.
	 *
	 * @return string Filtered list of classes.
	 */
	public function add_help_page_body_class( $classes ) {
		// Early bail if we're not on the current help page.
		if ( ! $this->is_current_page() ) {
			return $classes;
		}

		// Convert string of classes to an array.
		$class_array = explode( ' ', $classes );

		// Add custom classes.
		$class_array = array_merge( $class_array, [ 'tribe-help', 'tec-help', 'tribe_events_page_tec-events-settings' ] );

		// Return the final class list as a space-separated string.
		return implode( ' ', array_unique( $class_array ) );
	}

	/**
	 * Enqueue the Help page assets.
	 *
	 * @since TBD
	 */
	public function load_assets() {
		if ( ! $this->is_current_page() ) {
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
				'conditionals' => [ $this, 'is_current_page' ],
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

		// Add the built-in accordion.
		wp_enqueue_script( 'jquery-ui-accordion' );
	}

	/**
	 * Get the telemetry opt in link.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_telemetry_opt_in_link(): string {
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
	 * Generates and outputs the iframe content if the correct parameters are provided.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function generate_iframe_content(): void {
		$page   = tribe_get_request_var( 'page' );
		$iframe = tribe_get_request_var( 'embedded_content' );

		// Return early if $page is empty or not 'tec-events-help-hub', or if $iframe is empty.
		if ( empty( $page ) || 'tec-events-help-hub' !== $page || empty( $iframe ) ) {
			return;
		}

		// Enqueue our assets for the Iframe.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_help_page_iframe_assets' ] );

		// Disable the admin bar for iframe requests.
		// phpcs:ignore WordPressVIPMinimum.UserExperience.AdminBarRemoval.RemovalDetected
		show_admin_bar( false );
		// Render the iframe content.
		$this->render_template(
			'help-hub/support/iframe-content'
		);

		exit;
	}

	/**
	 * Enqueues the necessary scripts for the help page and dequeues all theme styles.
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
						'docsbot_key'    => $this->config->get( 'DOCSBOT_SUPPORT_KEY' ),
						'zendeskChatKey' => $this->config->get( 'ZENDESK_CHAT_KEY' ),
					],
				],
			]
		);
		$this->dequeue_theme_styles();
	}

	/**
	 * Dequeues theme-related styles to avoid conflicts within the iframe.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function dequeue_theme_styles(): void {
		global $wp_styles;
		$theme_directory = get_template_directory_uri();

		// Dequeue only styles from the current theme.
		foreach ( $wp_styles->queue as $handle ) {
			$src = $wp_styles->registered[ $handle ]->src;
			if ( strpos( $src, $theme_directory ) !== false ) {
				wp_dequeue_style( $handle );
			}
		}
	}

	/**
	 * Generates the admin notice HTML.
	 *
	 * @since TBD
	 *
	 * @return string The HTML for the admin notice.
	 */
	private function generate_notice_html(): string {
		$notice_slug    = 'tec-common-help-chatbot-notice';
		$notice_content = sprintf(
		// translators: 1: the opening tag to the chatbot link, 2: the closing tag.
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
			->dismissible()
			->withWrapper();

		return AdminNotices::render( $notice_admin, false );
	}

	/**
	 * Renders the help page or iframe template.
	 *
	 * @since TBD
	 *
	 * @param string $template_name The template file name (without .php).
	 * @param array  $extra_values  Extra values to pass to the template.
	 *
	 * @return void
	 */
	private function render_template( $template_name, array $extra_values = [] ): void {
		$main             = Tribe__Main::instance();
		$template         = new Tribe__Template();
		$opt_in_link      = $this->get_telemetry_opt_in_link();
		$status           = $this->get_license_and_opt_in_status();
		$is_opted_in      = $status['is_opted_in'];
		$is_license_valid = $status['is_license_valid'];
		$zendesk_chat_key = $this->config->get( 'ZENDESK_CHAT_KEY' );
		$dotbot_chat_key  = $this->config->get( 'DOCSBOT_SUPPORT_KEY' );

		$tec_icon_url     = tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, $main );
		$ea_icon_url      = tribe_resource_url( 'images/logo/event-aggregator.svg', false, null, $main );
		$fbar_icon_url    = tribe_resource_url( 'images/logo/filterbar.svg', false, null, $main );
		$article_icon_url = tribe_resource_url( 'images/icons/file-text1.svg', false, null, $main );
		$stars_icon_url   = tribe_resource_url( 'images/icons/stars.svg', false, null, $main );

		// Merge core values with extra values passed to the method.
		$template_values = array_merge(
			[
				'main'              => $main,
				'is_opted_in'       => $is_opted_in,
				'is_license_valid'  => $is_license_valid,
				'zendesk_chat_key'  => $zendesk_chat_key,
				'docblock_chat_key' => $dotbot_chat_key,
				'opt_in_link'       => $opt_in_link,
				'tec_icon_url'     => $tec_icon_url,
				'ea_icon_url'      => $ea_icon_url,
				'fbar_icon_url'    => $fbar_icon_url,
				'article_icon_url' => $article_icon_url,
				'stars_icon_url'   => $stars_icon_url,
			],
			$extra_values
		);

		// Setup template values and render the template.
		$template->set_values( $template_values );
		$template->set_template_origin( $main );
		$template->set_template_folder( 'src/admin-views' );
		$template->set_template_context_extract( true );
		$template->set_template_folder_lookup( false );
		$template->template( $template_name );
	}
}
