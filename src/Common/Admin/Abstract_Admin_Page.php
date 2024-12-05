<?php
/**
 * An abstract admin page to centralize some elements and functionality.
 *
 * @since 7.0.0
 *
 * @package TEC\Admin
 */

namespace TEC\Common\Admin;

use Tribe__Main;

/**
 * Class Admin_Page
 *
 * @since 7.0.0
 *
 * @package TEC\Admin
 */
abstract class Abstract_Admin_Page {

	/**
	 * The slug for the admin menu.
	 *
	 * @since 7.0.0
	 *
	 * @var string
	 */
	public static string $slug = 'tec-admin-page';

	/**
	 * The slug for the admin page
	 *
	 * @since 7.0.0
	 *
	 * @var string
	 */
	public static string $page_slug = '';

	/**
	 * Whether the page has been dismissed.
	 *
	 * @since 7.0.0
	 *
	 * @var bool
	 */
	public static bool $is_dismissed = false;

	/**
	 * Whether the page is dismissible.
	 *
	 * @since 7.0.0
	 *
	 * @var bool
	 */
	public static bool $is_dismissible = false;

	/**
	 * Whether the page has a sidebar.
	 *
	 * @since 7.0.0
	 *
	 * @var bool
	 */
	public static bool $has_sidebar = false;

	/**
	 * Whether the page has a footer.
	 *
	 * @since 7.0.0
	 *
	 * @var bool
	 */
	public static bool $has_footer = false;

	/**
	 * Get the page slug.
	 *
	 * @since 7.0.0
	 */
	public static function get_page_slug(): string {
		if ( ! empty( static::$page_slug ) ) {
			return static::$page_slug;
		}

		static::$page_slug = static::$slug;

		return static::$page_slug;
	}

	/**
	 * Defines wether the current page is the correct page.
	 *
	 * @since 7.0.0
	 */
	public static function is_on_page(): bool {
		$admin_pages = tribe( 'admin.pages' );
		$admin_page  = $admin_pages->get_current_page();
		$page_slug   = static::get_page_slug();

		return ! empty( $admin_page ) && $admin_page === $page_slug;
	}

	/**
	 * Has the page been dismissed?
	 *
	 * @since 7.0.0
	 *
	 * @return bool
	 */
	public static function is_dismissed(): bool {
		if ( ! static::$is_dismissible ) {
			return false;
		}

		return static::$is_dismissed;
	}

	/**
	 * Get the logo source URL.
	 *
	 * @since 7.0.0
	 *
	 * @return string The logo source URL.
	 */
	public function get_logo_source(): string {
		$logo_source = tribe_resource_url( 'images/logo/tec-brand.svg', false, null, Tribe__Main::instance() );

		$admin_page = static::get_page_slug();

		/**
		 * Filter the admin page logo source URL.
		 *
		 * @since 7.0.0
		 *
		 * @param string $logo_source The settings page logo resource URL.
		 * @param string $admin_page The admin page ID.
		 */
		return (string) apply_filters( 'tec_settings_page_logo_source', $logo_source, $admin_page );
	}

	/**
	 * Get the admin page logo.
	 *
	 * @since 7.0.0
	 *
	 * @return void Echos the admin page logo.
	 */
	public function do_page_logo(): void {
		ob_start();
		?>
		<img
			src="<?php echo esc_url( $this->get_logo_source() ); ?>"
			alt=""
			role="presentation"
			id="tec-admin-logo"
		/>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get the page title.
	 *
	 * @since 7.0.0
	 */
	abstract public function get_the_page_title(): string;

	/**
	 * Get the menu title.
	 *
	 * @since 7.0.0
	 */
	abstract public function get_the_menu_title(): string;

	/**
	 * Get the capability required to access the page.
	 *
	 * @since 7.0.0
	 */
	public function required_capability() {
		return 'manage_options';
	}

	/**
	 * Add the settings page.
	 *
	 * @since 7.0.0
	 */
	public function admin_page() {
		if ( static::$is_dismissed ) {
			return;
		}

		add_submenu_page(
			'edit.php?post_type=tribe_events',
			$this->get_the_page_title(),
			$this->get_the_menu_title(),
			$this->required_capability(),
			static::get_page_slug(),
			[ $this, 'admin_page_content' ],
			0
		);
	}

	/**
	 * Get the classes for the content wrapper.
	 *
	 * @since 7.0.0
	 *
	 * @return array<string> The classes for the content wrapper.
	 */
	public function content_wrapper_classes(): array {
		return [ 'tec-admin__content' ];
	}

	/**
	 * Render the admin page content.
	 * Relies on extending classes overriding the admin_page_header,
	 * admin_page_title, and admin_page_main functions.
	 *
	 * @since 7.0.0
	 *
	 * @return void Renders the entire admin page content.
	 */
	public function admin_page_content(): void {
		ob_start();
		?>
		<div id="tec-admin-page" class="tec-admin wrap">
			<?php do_action( 'tec_admin_header_before_header' ); ?>
			<header id="tec-admin__header">
				<?php $this->admin_page_header(); ?>
			</header>
			<?php do_action( 'tec_admin_header_after_header' ); ?>

			<?php do_action( 'tec_admin_header_before_content' ); ?>
			<main id="tec-admin__content" <?php tribe_classes( $this->content_wrapper_classes() ); ?>>
				<?php $this->admin_page_main_content(); ?>
			</main>
			<?php if ( static::$has_sidebar ) : ?>
				<aside id="tec-admin__sidebar" <?php tribe_classes( $this->content_wrapper_classes() ); ?>>
					<?php $this->admin_page_sidebar(); ?>
				</aside>
			<?php endif; ?>
			<?php if ( static::$has_footer ) : ?>
				<footer id="tec-admin__footer">
					<?php $this->admin_page_footer(); ?>
				</footer>
			<?php endif; ?>
			<?php do_action( 'tec_admin_header_after_content' ); ?>
		</div>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render the admin page header.
	 * This will be wrapped in a #tec-admin__header HTML header element.
	 *
	 * @since 7.0.0
	 *
	 * @return void Renders the admin page header.
	 */
	public function admin_page_header(): void {
		ob_start();
		?>
			<?php $this->do_page_logo(); ?>
			<?php do_action( 'tec_admin_header_before_title' ); ?>
			<?php $this->admin_page_title(); ?>
			<?php do_action( 'tec_admin_header_after_title' ); ?>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render the admin page title.
	 * In the header.
	 *
	 * @since 7.0.0
	 *
	 * @return void Renders the admin page title.
	 */
	public function admin_page_title(): void {
		ob_start();
		?>
			<h1 class="tec-admin__header-title"><?php esc_html_e( 'The Events Calendar', 'the-events-calendar' ); ?></h1>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render the admin page main content.
	 * This will be wrapped in a #tec-admin__content HTML section element.
	 *
	 * @since 7.0.0
	 *
	 * @return void Renders the admin page main content.
	 */
	abstract public function admin_page_main_content(): void;

	/**
	 * Render the admin page sidebar content.
	 * This will be wrapped in a #tec-admin__sidebar HTML aside element.
	 *
	 * @since 7.0.0
	 *
	 * @return void Renders the admin page sidebar content.
	 */
	abstract public function admin_page_sidebar(): void;

	/**
	 * Render the admin page footer content.
	 * This will be wrapped in a #tec-admin__footer HTML footer element.
	 *
	 * @since 7.0.0
	 *
	 * @return void Renders the admin page footer content.
	 */
	abstract public function admin_page_footer(): void;
}
