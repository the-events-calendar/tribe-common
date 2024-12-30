<?php
/**
 * An abstract admin page to centralize some elements and functionality.
 *
 * @since 6.4.1
 *
 * @package TEC\Admin
 */

namespace TEC\Common\Admin;

use Tribe__Main;

/**
 * Class Admin_Page
 *
 * @since 6.4.1
 *
 * @package TEC\Admin
 */
abstract class Abstract_Admin_Page {

	/**
	 * The slug for the admin menu.
	 *
	 * @since 6.4.1
	 *
	 * @var string
	 */
	public static string $slug = 'tec-admin-page';

	/**
	 * The slug for the admin page
	 *
	 * @since 6.4.1
	 *
	 * @var string
	 */
	public static string $page_slug = '';

	/**
	 * Whether the page has been dismissed.
	 *
	 * @since 6.4.1
	 *
	 * @var bool
	 */
	public static bool $is_dismissed = false;

	/**
	 * Whether the page is dismissible.
	 *
	 * @since 6.4.1
	 *
	 * @var bool
	 */
	public static bool $is_dismissible = false;

	/**
	 * Whether the page has a header.
	 *
	 * @since 6.4.1
	 *
	 * @var bool
	 */
	public static bool $has_header = true;

	/**
	 * Whether the page has a sidebar.
	 *
	 * @since 6.4.1
	 *
	 * @var bool
	 */
	public static bool $has_sidebar = false;

	/**
	 * Whether the page has a footer.
	 *
	 * @since 6.4.1
	 *
	 * @var bool
	 */
	public static bool $has_footer = false;

	/**
	 * Add the settings page.
	 *
	 * @since 6.4.1
	 */
	public function admin_page() {
		if ( static::is_dismissed() ) {
			return;
		}

		$parent_slug = $this->get_parent_page_slug();

		if ( ! empty( $parent_slug ) ) {
			add_submenu_page(
				$parent_slug,
				$this->get_the_page_title(),
				$this->get_the_menu_title(),
				$this->required_capability(),
				static::get_page_slug(),
				[ $this, 'admin_page_content' ],
				$this->get_position()
			);
		} else {
			add_menu_page(
				$this->get_the_page_title(),
				$this->get_the_menu_title(),
				$this->required_capability(),
				static::get_page_slug(),
				[ $this, 'admin_page_content' ],
				$this->get_page_icon_url(),
				$this->get_position()
			);
		}
	}

	/**
	 * Get the page slug.
	 *
	 * @since 6.4.1
	 */
	public static function get_page_slug(): string {
		if ( ! empty( static::$page_slug ) ) {
			return static::$page_slug;
		}

		static::$page_slug = static::$slug;

		return static::$page_slug;
	}

	/**
	 * Get the page type.
	 *
	 * @since 6.4.1
	 */
	public function get_page_type(): string {
		// Defined in the traits, or redefined in an extending class.
		return static::$page_type;
	}

	/**
	 * Defines wether the current page is the correct page.
	 *
	 * @since 6.4.1
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
	 * @since 6.4.1
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
	 * @since 6.4.1
	 *
	 * @return string The logo source URL.
	 */
	public function get_logo_source(): string {
		$logo_source = tribe_resource_url( 'images/logo/tec-brand.svg', false, null, Tribe__Main::instance() );

		$admin_page = static::get_page_slug();

		/**
		 * Filter the admin page logo source URL.
		 *
		 * @since 6.4.1
		 *
		 * @param string $logo_source The settings page logo resource URL.
		 * @param string $admin_page The admin page ID.
		 */
		return (string) apply_filters( 'tec_settings_page_logo_source', $logo_source, $admin_page );
	}

	/**
	 * Get the admin page logo.
	 *
	 * @since 6.4.1
	 *
	 * @return void Echos the admin page logo.
	 */
	public function do_page_logo(): void {
		// Only run once to avoid duplicating IDs.
		if ( did_action( 'tribe_admin_page_after_logo' ) ) {
			return;
		}

		?>
		<img
			src="<?php echo esc_url( $this->get_logo_source() ); ?>"
			alt=""
			role="presentation"
			id="tec-admin-page-logo"
			<?php tribe_classes( $this->logo_classes() ); ?>
		/>
		<?php

		do_action( 'tribe_admin_page_after_logo' );
	}

	/**
	 * Get the page title.
	 *
	 * @since 6.4.1
	 */
	abstract public function get_the_page_title(): string;

	/**
	 * Get the menu title.
	 *
	 * @since 6.4.1
	 */
	abstract public function get_the_menu_title(): string;

	/**
	 * Get the capability required to access the page.
	 *
	 * @since 6.4.1
	 */
	public function required_capability() {
		return 'manage_options';
	}

	/**
	 * Get the parent page slug.
	 *
	 * @since 6.4.1
	 */
	abstract public function get_parent_page_slug(): string;

	/**
	 * Get the icon url for the menu.
	 * Can be a URL to a custom file or a dashicon class.
	 *
	 * @since 6.4.1
	 */
	public function get_page_icon_url(): ?string {
		return '';
	}

	/**
	 * Get the menu position of the page.
	 *
	 * @since 6.4.1
	 */
	public function get_position(): ?int {
		return $this->menu_position ?? null;
	}

	/**
	 * Get the classes for the header.
	 *
	 * @since 6.4.1
	 *
	 * @return array<string> The classes for the header.
	 */
	public function header_classes(): array {
		$classes = [ 'tec-admin-page__header' ];

		return (array) apply_filters( 'tec_admin_page_header_classes', $classes );
	}

	/**
	 * Get the classes for the logo.
	 *
	 * @since 6.4.1
	 *
	 * @return array<string> The classes for the logo.
	 */
	public function logo_classes(): array {
		$classes = [ 'tec-admin-page__logo' ];

		return (array) apply_filters( 'tec_admin_page_logo_classes', $classes );
	}

	/**
	 * Get the classes for the content wrapper.
	 *
	 * @since 6.4.1
	 *
	 * @return array<string> The classes for the content wrapper.
	 */
	public function content_classes(): array {
		$classes = [ 'tec-admin-page__content' ];

		return (array) apply_filters( 'tec_admin_page_content_classes', $classes );
	}

	/**
	 * Get the classes for the sidebar.
	 *
	 * @since 6.4.1
	 *
	 * @return array<string> The classes for the sidebar.
	 */
	public function sidebar_classes(): array {
		$classes = [ 'tec-admin-page__sidebar' ];

		return (array) apply_filters( 'tec_admin_page_sidebar_classes', $classes );
	}

	/**
	 * Get the classes for the footer.
	 *
	 * @since 6.4.1
	 *
	 * @return array<string> The classes for the footer.
	 */
	public function footer_classes(): array {
		$classes = [ 'tec-admin-page__footer' ];

		return (array) apply_filters( 'tec_admin_page_footer_classes', $classes );
	}

	/**
	 * Get the classes for the wrapper.
	 *
	 * @since 6.4.1
	 *
	 * @return array
	 */
	public function wrapper_classes(): array {
		$classes = [ 'tec-admin-page', 'tec-admin', 'wrap' ];

		if ( static::$has_header ) {
			$classes[] = 'tec-admin-page--header';
		}

		if ( static::$has_sidebar ) {
			$classes[] = 'tec-admin-page--sidebar';
		}

		if ( static::$has_footer ) {
			$classes[] = 'tec-admin-page--footer';
		}

		return (array) apply_filters( 'tec_admin_page_wrapper_classes', $classes );
	}

	/**
	 * Render the admin page content.
	 * Relies on extending classes overriding the admin_page_header,
	 * admin_page_title, and admin_page_main functions.
	 *
	 * HTML wrapper are used to layout of the page.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the entire admin page content.
	 */
	public function admin_page_content(): void {
		do_action( 'tec_admin_page_before_wrap_start' );
		?>

		<div id="tec-admin-page" <?php tribe_classes( $this->wrapper_classes() ); ?> >
			<?php do_action( 'tec_admin_page_after_wrap_start' ); ?>

			<?php $this->admin_page_header(); ?>

			<?php $this->admin_page_main_content_wrapper(); ?>

			<?php do_action( 'tec_admin_page_after_content' ); ?>

			<?php $this->admin_page_sidebar_wrapper(); ?>

			<?php $this->admin_page_footer_wrapper(); ?>

			<?php do_action( 'tec_admin_page_before_wrap_end' ); ?>
		</div>

		<?php
		do_action( 'tec_admin_page_after_wrap_end' );
	}

	/**
	 * Render the admin page header.
	 * This will be wrapped in a #tec-admin__header HTML header element.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the admin page header.
	 */
	public function admin_page_header(): void {
		if ( ! static::$has_header ) {
			return;
		}

		?>
			<header id="tec-admin-page-header" <?php tribe_classes( $this->header_classes() ); ?>>
				<?php $this->do_page_logo(); ?>
				<?php do_action( 'tec_admin_header_before_title' ); ?>
				<?php $this->admin_page_title(); ?>
				<?php do_action( 'tec_admin_header_after_title' ); ?>
			</header>
		<?php
	}

	/**
	 * Render the admin page title.
	 * In the header.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the admin page title.
	 */
	public function admin_page_title(): void {
		?>
			<h1 class="tec-admin__header-title"><?php esc_html_e( 'The Events Calendar', 'tribe-common' ); ?></h1>
		<?php
	}

	/**
	 * Render the admin page main content.
	 * This will be wrapped in a #tec-admin__content HTML section element.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the admin page main content.
	 */
	public function admin_page_main_content_wrapper(): void {
		?>
		<main id="tec-admin-page-content" <?php tribe_classes( $this->content_classes() ); ?>>
			<?php $this->admin_page_main_content(); ?>
		</main>
		<?php
	}

	/**
	 * Render the admin page main content.
	 * This will be wrapped in a #tec-admin-content HTML section element.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the admin page main content.
	 */
	abstract public function admin_page_main_content(): void;

	/**
	 * Render the admin page sidebar content.
	 * This will be wrapped in a #tec-admin__sidebar HTML aside element.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the admin page sidebar content.
	 */
	public function admin_page_sidebar_wrapper(): void {
		if ( ! static::$has_sidebar ) {
			return;
		}

		?>
		<aside id="tec-admin-page-sidebar" <?php tribe_classes( $this->sidebar_classes() ); ?>>
			<?php $this->admin_page_sidebar_content(); ?>
		</aside>
		<?php
	}

	/**
	 * Render the admin page sidebar content.
	 * This will be wrapped in a #tec-admin-sidebar HTML aside element.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the admin page sidebar content.
	 */
	abstract public function admin_page_sidebar_content(): void;

	/**
	 * Render the admin page footer content.
	 * This will be wrapped in a #tec-admin__footer HTML footer element.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the admin page footer content.
	 */
	public function admin_page_footer_wrapper(): void {
		if ( ! static::$has_footer ) {
			return;
		}

		?>
		<footer id="tec-admin-page-footer" <?php tribe_classes( $this->footer_classes() ); ?>>
			<?php do_action( 'tec_admin_page_footer_content' ); ?>
		</footer>
		<?php
	}

	/**
	 * Render the admin page footer content.
	 * This will be wrapped in a #tec-admin-footer HTML footer element.
	 *
	 * @since 6.4.1
	 *
	 * @return void Renders the admin page footer content.
	 */
	abstract public function admin_page_footer_content(): void;
}
