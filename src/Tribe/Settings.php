<?php
/**
 * Settings
 *
 * @since 4.0.1
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use Tribe\Admin\Pages as Admin_Pages;

if ( did_action( 'tec_settings_init' ) ) {
	return;
}

// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase,StellarWP.Classes.ValidClassName.NotSnakeCase,PEAR.NamingConventions.ValidClassName.Invalid

/**
 * Helper class that allows registration of settings.
 */
class Tribe__Settings {
	/**
	 * Slug of the parent menu slug.
	 *
	 * @var string
	 */
	public static $parent_slug = 'tribe-common';

	/**
	 * Page of the parent menu.
	 *
	 * @var string
	 */
	public static $parent_page = 'edit.php';

	/**
	 * @var Tribe__Admin__Live_Date_Preview
	 */
	public $live_date_preview;

	/**
	 * The tabs that will appear in the settings page.
	 * Filtered on class construct.
	 *
	 * @var array<string, Tribe__Settings_Tab>
	 */
	public $tabs = [];

	/**
	 * All the tabs registered, not just the ones that will appear.
	 *
	 * @since TBD
	 *
	 * @var array<string, Tribe__Settings_Tab>
	 */
	public $all_tabs = [];

	/**
	 * Multidimensional array of the fields that will be generated
	 * for the entire settings panel, tabs are represented in the array keys.
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * The default tab for the settings panel.
	 * This should be a tab ID.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $default_tab = '';

	/**
	 * The current tab being displayed.
	 * This should be a tab ID.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $current_tab = '';

	/**
	 * Tabs that shouldn't show the save button.
	 *
	 * @var array<string> $no_save_tabs
	 */
	public $no_save_tabs = [];

	/**
	 * The slug used in the admin to generate the settings page.
	 *
	 * @var string
	 */
	public $admin_slug;

	/**
	 * The slug used in the admin to generate the help page.
	 *
	 * @var string
	 */
	protected $help_slug;

	/**
	 * The menu name used for the settings page.
	 *
	 * @var string
	 */
	public $menu_name;

	/**
	 * The required capability for the settings page.
	 *
	 * @var string
	 */
	public $required_cap;

	/**
	 * Errors that occur after a save operation.
	 *
	 * @var mixed
	 */
	public $errors;

	/**
	 * POST data before/after save.
	 *
	 * @var mixed
	 */
	public $sent_data;

	/**
	 * The $current_screen name corresponding to the admin page.
	 *
	 * @var string
	 */
	public $admin_page;

	/**
	 * True if a major error that prevents saving occurred.
	 *
	 * @var bool
	 */
	public $major_error;

	/**
	 * Holds validated fields.
	 *
	 * @var array
	 */
	public $validated;

	/**
	 * The settings page URL.
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * An array defining the suite root plugins.
	 *
	 * @var array
	 */
	protected $root_plugins = [
		'the-events-calendar/the-events-calendar.php',
		'event-tickets/event-tickets.php',
	];

	/**
	 * An associative array in the form [ <tab-slug> => array(...<fields>) ]
	 *
	 * @var array
	 */
	protected $fields_for_save = [];

	/**
	 * An array that contains the fields that are currently being validated.
	 *
	 * @var array
	 */
	protected $current_fields = [];

	/* Deprecated properties */

	/**
	 * Static Singleton Holder.
	 *
	 * @deprecated TBD use tribe( 'settings' ) instead.
	 *
	 * @var Tribe__Settings|null
	 */
	private static $instance;

	// phpcs:disable WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase

	/**
	 * All the tabs registered, not just the ones that will appear.
	 *
	 * @deprecated TBD use $all_tabs.
	 *
	 * @var array
	 */
	public $allTabs;

	/**
	 * The default tab for the settings panel.
	 * This should be a tab ID.
	 *
	 * @deprecated TBD Use $default_tab.
	 *
	 * @var string
	 */
	public $defaultTab;

	/**
	 * The current tab being displayed.
	 *
	 * @deprecated TBD Use $current_tab.
	 *
	 * @var string
	 */
	public $currentTab;

	/**
	 * Tabs that shouldn't show the save button.
	 *
	 * @deprecated TBD Use $no_save_tabs.
	 *
	 * @var array
	 */
	public $noSaveTabs;

	/**
	 * The slug used in the admin to generate the settings page.
	 *
	 * @deprecated TBD Use $admin_slug.
	 *
	 * @var string
	 */
	public $adminSlug;

	/**
	 * The menu name used for the settings page.
	 *
	 * @deprecated TBD Use $menu_name.
	 *
	 * @var string
	 */
	public $menuName;

	/**
	 * The required capability for the settings page.
	 *
	 * @deprecated TBD Use $required_cap.
	 *
	 * @var string
	 */
	public $requiredCap;

	// phpcs:enable WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase

	/**
	 * Class constructor.
	 */
	public function __construct() {
		// Set instance variables.
		$this->menu_name    = apply_filters( 'tribe_settings_menu_name', esc_html__( 'Events', 'tribe-common' ) );
		$this->required_cap = apply_filters( 'tribe_settings_req_cap', 'manage_options' );
		$this->admin_slug   = apply_filters( 'tribe_settings_admin_slug', 'tribe-common' );
		$this->help_slug    = apply_filters( 'tribe_settings_help_slug', 'tribe-common-help' );
		$this->errors       = get_option( 'tribe_settings_errors', [] );
		$this->major_error  = get_option( 'tribe_settings_major_error', false );
		$this->sent_data    = get_option( 'tribe_settings_sent_data', [] );
		$this->validated    = [];
		$this->default_tab  = null;
		$this->current_tab  = null;

		$this->hook();
	}

	/**
	 * Hooks the actions and filters required for the class to work.
	 */
	public function hook() {
		// Run actions & filters.
		add_action( 'admin_init', [ $this, 'init_tabs' ] );
		add_action( 'tribe_settings_below_tabs', [ $this, 'display_errors' ] );
		add_action( 'tribe_settings_below_tabs', [ $this, 'display_success' ] );
		add_action( 'tribe_settings_tab_after_link', [ $this, 'add_child_tabs_to_nav' ] );

		do_action( 'tec_settings_init' );
	}

	/**
	 * Determines whether or not the full admin pages should be initialized.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function should_setup_pages(): bool {
		// @todo: Deprecate this and update where needed.
		return true;
	}

	/**
	 * Init all the tabs.
	 *
	 * @since TBD
	 */
	public function init_tabs() {
		$admin_pages = tribe( 'admin.pages' );
		$admin_page  = $admin_pages->get_current_page();

		if ( empty( $admin_pages->has_tabs( $admin_page ) ) ) {
			return;
		}

		// Load settings tab-specific helpers and enhancements.
		Tribe__Admin__Live_Date_Preview::instance();

		do_action( 'tribe_settings_do_tabs', $admin_page ); // This is the hook used to add new tabs.

		/**
		 * Filter the tabs that will appear in the settings page.
		 *
		 * @since 4.15.0
		 *
		 * @param array  $tabs<string,Tribe__Settigns_Tab> The tabs that will appear in the settings page.
		 * @param string $admin_page                       The admin page ID.
		 */
		$this->tabs = (array) apply_filters( 'tribe_settings_tabs', [], $admin_page );

		/**
		 * Filter the list of all tabs.
		 *
		 * @since 4.15.0
		 *
		 * @param array<string,Tribe__Settigns_Tab> $all_tabs   The list of all tabs.
		 * @param string                            $admin_page The admin page ID.
		 */
		$this->all_tabs = (array) apply_filters( 'tribe_settings_all_tabs', [], $admin_page );

		/**
		 * Filter the tabs that shouldn't show the save button.
		 *
		 * @since 4.15.0
		 *
		 * @param array<string>  $no_save_tabs The tabs that shouldn't show the save button. In the format [ 'tab->id' ].
		 * @param string         $admin_page   The admin page ID.
		 */
		$this->no_save_tabs = (array) apply_filters( 'tribe_settings_no_save_tabs', [], $admin_page );


		if ( is_network_admin() ) {
			/**
			 * Filter the default tab for the network settings page.
			 *
			 * @since 4.15.0
			 *
			 * @param string $default_tab The default tab for the network settings page.
			 * @param string $admin_page  The admin page ID.
			 */
			$this->default_tab = apply_filters( 'tribe_settings_default_tab_network', 'network', $admin_page );
		} else {
			/**
			 * Filter the default tab for the settings page.
			 *
			 * @since 4.15.0
			 *
			 * @param string $default_tab The default tab for the settings page.
			 * @param string $admin_page  The admin page ID.
			 */
			$default_tab       = apply_filters( 'tribe_settings_default_tab', 'viewing', $admin_page );
			$this->default_tab = in_array( $default_tab, $this->tabs ) ? $default_tab : array_key_first( $this->tabs );
		}

		/**
		 * Filter the current tab.
		 *
		 * @since 4.15.0
		 *
		 * @param string $current_tab The current tab ID.
		 * @param string $admin_page  The admin page ID.
		 */
		$this->current_tab = $this->get_current_tab();
		$this->url         = $this->get_tab_url( $this->current_tab );

		/**
		 * Filter the fields for save.
		 *
		 * @since 4.15.0
		 *
		 * @param array  $fields_for_save The fields for save.
		 * @param string $admin_page      The admin page ID.
		 */
		$this->fields_for_save = (array) apply_filters( 'tribe_settings_fields', [], $admin_page );

		do_action( 'tribe_settings_after_do_tabs', $admin_page );

		/**
		 * Filter the fields for the settings page.
		 *
		 * @since 4.15.0
		 *
		 * @param array  $fields     The fields for the settings page.
		 * @param string $admin_page The admin page ID.
		 */
		$this->fields = (array) apply_filters( 'tribe_settings_fields', [], $admin_page );

		$this->validate();
	}

	/**
	 * Get a specific tab by slug.
	 * If the slug is not found in the parent tabs, it will then search child tabs for it.
	 *
	 * @since TBD
	 *
	 * @param string $id The tab ID.
	 *
	 * @return Tribe__Settings_Tab|null
	 */
	public function get_tab( $id ): ?Tribe__Settings_Tab {
		// Find tab if a parent.
		$tab_object = $this->tabs[ $id ] ?? null;

		// Find tab if a child tab.
		if ( empty( $tab_object ) ) {
			foreach ( $this->tabs as $tab ) {
				if ( $tab->has_child( $id ) ) {
					$tab_object = $tab->get_child( $id );
					break;
				}
			}
		}

		return $tab_object;
	}

	/**
	 * Gets the current tab ID.
	 *
	 * @since TBD
	 *
	 * @return ?string
	 */
	public function get_current_tab(): ?string {
		$admin_page  = tribe( 'admin.pages' )->get_current_page();
		$current_tab = apply_filters( 'tribe_settings_current_tab', tribe_get_request_var( 'tab', $this->default_tab ), $admin_page );

		// Find tab if a parent.
		$tab_object = $this->get_tab( $current_tab );

		if ( empty( $tab_object ) ) {
			$this->current_tab = $current_tab;
			return $this->current_tab;
		}

		// Parent tabs have no content! If one is selected, default to the first child.
		if ( $tab_object->has_children() ) {
			$current_tab = array_key_first( $tab_object->get_children() );
		}

		$this->current_tab = $current_tab;

		return $this->current_tab;
	}

	/**
	 * Get the current settings page URL
	 *
	 * @since 4.15.0
	 *
	 * @param array $args An array of arguments to add to the URL.
	 *
	 * @return string The current settings page URL.
	 */
	public function get_settings_page_url( array $args = [] ) {
		$admin_pages = tribe( 'admin.pages' );
		$page        = $admin_pages->get_current_page();
		$tab         = tribe_get_request_var( 'tab', $this->default_tab );
		$defaults    = [
			'page' => $page,
			'tab'  => $tab,
		];

		// Allow the link to be "changed" on the fly.
		$args = wp_parse_args( $args, $defaults );

		$url = add_query_arg(
			$args,
			is_network_admin() ? network_admin_url( 'settings.php' ) : admin_url( 'admin.php' )
		);

		return apply_filters( 'tribe_settings_page_url', $url, $page, $tab );
	}

	/**
	 * Get the settings page title.
	 *
	 * @since 4.15.0
	 *
	 * @param string $admin_page The admin page ID.
	 * @return string The settings page title.
	 */
	public function get_page_title( $admin_page ) {
		$page_title = sprintf(
			// Translators: %s is the name of the menu item.
			__( '%s Settings', 'tribe-common' ),
			$this->menu_name
		);

		/**
		 * Filter the tribe settings page title.
		 *
		 * @since 4.15.0
		 *
		 * @param string $page_title The settings page title.
		 * @param string $admin_page The admin page ID.
		 */
		return apply_filters( 'tribe_settings_page_title', $page_title, $admin_page );
	}

	/**
	 * Get the settings page logo.
	 *
	 * @since TBD
	 *
	 * @param string $admin_page The admin page ID.
	 * @return string The settings page logo.
	 */
	public function get_page_logo( $admin_page ) {
		$logo_source = tribe_resource_url( 'images/logo/tec-brand.svg', false, null, Tribe__Main::instance() );

		/**
		 * Filter the tribe settings page logo source URL.
		 *
		 * @since TBD
		 *
		 * @param string $logo_source The settings page logo resource URL.
		 * @param string $admin_page The admin page ID.
		 */
		$logo_source = apply_filters( 'tec_settings_page_logo_source', $logo_source, $admin_page );

		ob_start();
		?><img
			src="<?php echo esc_url( $logo_source ); ?>"
			alt=""
			role="presentation"
			id="tec-settings-logo"
		/>
		<?php
		return ob_get_clean();
	}

	/**
	 * Handles the attributes for the form.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $attributes The attributes to add to the form.
	 *
	 * @return string The attributes string.
	 */
	public function do_form_attributes( $attributes ): string {
		$string = '';
		foreach ( $attributes as $key => $value ) {
			if ( empty( $key ) || empty( $value ) ) {
				continue;
			}

			$string .= esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
		}

		return $string;
	}

	/**
	 * Generate the main option page.
	 * Includes the view file.
	 *
	 * @since TBD
	 */
	public function generate_page(): void {
		$admin_pages  = tribe( 'admin.pages' );
		$admin_page   = $admin_pages->get_current_page();
		$current_tab  = $this->get_current_tab();
		$wrap_classes = apply_filters( 'tribe_settings_wrap_classes', [ 'tribe_settings', 'wrap' ], $admin_page );

		ob_start();
		do_action( 'tribe_settings_top', $admin_page );
		?>
		<div <?php tribe_classes( $wrap_classes ); ?>>
			<h1>
				<?php echo wp_kses_post( $this->get_page_logo( $admin_page ) ); ?>
				<?php echo esc_html( $this->get_page_title( $admin_page ) ); ?>
			</h1>
			<?php
			do_action( 'tribe_settings_above_tabs' );
			$this->generate_tabs();
			do_action( 'tribe_settings_below_tabs' );
			do_action( 'tribe_settings_below_tabs_tab_' . $current_tab, $admin_page );
			?>
			<div class="tribe-settings-form form">
				<?php
				do_action( 'tribe_settings_above_form_element' );
				do_action( 'tribe_settings_above_form_element_tab_' . $current_tab, $admin_page );
				?>
				<form id="tec-settings-form" method="post">
				<?php
				do_action( 'tribe_settings_before_content' );
				do_action( 'tribe_settings_before_content_tab_' . $current_tab );
				do_action( 'tribe_settings_content_tab_' . $current_tab );

				if ( ! has_action( 'tribe_settings_content_tab_' . $current_tab ) ) {
					?>
					<p><?php echo esc_html__( "You've requested a non-existent tab.", 'tribe-common' ); ?></p>
					<?php
				}
				do_action( 'tribe_settings_after_content_tab_' . $current_tab );
				do_action( 'tribe_settings_after_content', $current_tab );

				if ( has_action( 'tribe_settings_content_tab_' . $current_tab ) && ! in_array( $current_tab, $this->no_save_tabs ) ) {
					wp_nonce_field( 'saving', 'tribe-save-settings' );
					?>
					<div class="tec_settings__footer">
						<hr class="tec_settings__separator--footer">
						<input type="hidden" name="current-settings-tab" id="current-settings-tab" value="<?php echo esc_attr( $this->current_tab ); ?>" />
						<input id="tribeSaveSettings" class="button-primary" type="submit" name="tribeSaveSettings" value="<?php echo esc_attr__( 'Save Changes', 'tribe-common' ); ?>" />
					</div>
					<?php
				}

				echo apply_filters( 'tribe_settings_closing_form_element', '</form>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
				do_action( 'tribe_settings_after_form_element' );
				do_action( 'tribe_settings_after_form_element_tab_' . $current_tab, $admin_page );
				?>
			</div>
			<?php do_action( 'tribe_settings_after_form_div' ); ?>
		</div>
		<?php
		do_action( 'tribe_settings_bottom' );

		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Generate the tab navigation in the settings screen.
	 *
	 * Each level of the tab nav is a unordered list inside a nav element.
	 * This function generates the structure and the generate_tab
	 * function creates the individual list items.
	 *
	 * @since TBD
	 */
	public function generate_tabs(): void {
		if ( ! is_array( $this->tabs ) || empty( $this->tabs ) ) {
			return;
		}

		ob_start();
		?>
			<nav id="tribe-settings-tabs" class="tec-nav__wrapper">
				<ul class="tec-nav">
					<li class="tec-nav__tab tec-nav__tab--skip-link">
						<a href="#tec-settings-form" class="screen-reader-shortcut"><?php esc_html_e( 'Skip to tab content', 'tribe-common' ); ?></a>
					</li>
				<?php

				foreach ( $this->tabs as $tab ) {
					if ( ! empty( $tab->parent ) ) {
						// This tab belongs in the subnav!
						continue;
					}

					$this->generate_tab( $tab );
				}
				?>
				</ul>
				<?php do_action( 'tribe_settings_after_tabs' ); ?>
			</nav>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Generate the content for a single specified tab.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Settings_Tab $tab The tab object.
	 */
	public function generate_tab( Tribe__Settings_Tab $tab ) {
		$url         = $this->get_tab_url( $tab->id );
		$class       = [ 'tec-nav__tab' ];
		$current_tab = $this->get_current_tab();

		if ( $tab->has_children() && $tab->has_child( $current_tab ) ) {
			// Current tab is a child tab of passed tab.
			$class[] = 'tec-nav__tab--subnav-active';
		} elseif ( $tab->has_children() && $tab->id === $current_tab ) {
			// Current tab is a parent tab. Set to first child.
			$this->current_tab = array_key_first( $tab->get_children() );
			$class[]           = 'tec-nav__tab--subnav-active';
		} elseif ( $tab->id === $current_tab ) {
			$class[] = 'tec-nav__tab--active';
		}

		ob_start();
		?>
		<li <?php tribe_classes( $class ); ?>>
			<a
				id="<?php echo esc_attr( $tab->id ); ?>"
				class="tec-nav__link"
				href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $tab->name ); ?></a>
			<?php do_action( 'tribe_settings_tab_after_link', $tab ); ?>
		</li>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add the current tab's children to the nav as a subnav.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Settings_Tab $tab The parent tab object.
	 * @return void
	 */
	public function add_child_tabs_to_nav( Tribe__Settings_Tab $tab ) {
		if ( ! $tab->has_children() ) {
			return;
		}

		?>
		<ul class="tec-nav__subnav">
			<?php
			$child_tabs = $tab->get_children();
			uasort( $child_tabs, [ $this, 'sort_by_priority' ] );

			foreach ( $child_tabs as $child ) {
				$this->generate_tab( $child );
			}

			$this->get_duck_tab();
			?>
		</ul>
		<?php
	}

	/**
	 * A little something for Jack.
	 * Shows a duck on the far right end of a subnav on hover.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function get_duck_tab(): void {
		?>
			<li class="tec-nav__tab tec-nav__tab--duck">
				<a class="screen-reader-shortcut" role="presentation">
					<img
						class="tec-nav__duck"
						role="presentation"
						src="<?php echo esc_url( tribe_resource_url( 'images/icons/duck.svg', false, null, Tribe__Main::instance() ) ); ?>"
						alt="<?php esc_attr_e( 'For you, Jack!', 'tribe-common' ); ?>"
					/>
				</a>
			</li>
		<?php
	}

	/**
	 * A method to sort tabs by priority in ascending order.
	 *
	 * @since TBD
	 *
	 * @param  object $a First tab to compare.
	 * @param  object $b Second tab to compare.
	 *
	 * @return int
	 */
	protected function sort_by_priority( $a, $b ): int {
		$a_priority = (float) $a->get_priority();
		$b_priority = (float) $b->get_priority();

		if ( $a_priority === $b_priority ) {
			return 0;
		}

		return ( $a_priority < $b_priority ) ? -1 : 1;
	}

	/**
	 * Generate the URL for a tab.
	 *
	 * @since 4.15.0
	 *
	 * @param string $tab The tab slug.
	 *
	 * @return string $url The URL.
	 */
	public function get_tab_url( $tab ) {
		$admin_pages = tribe( 'admin.pages' );
		$admin_page  = $admin_pages->get_current_page();
		$wp_page     = is_network_admin() ? network_admin_url( 'settings.php' ) : admin_url( 'admin.php' );
		$url         = add_query_arg(
			[
				'page' => $admin_page,
				'tab'  => $tab,
			],
			$wp_page
		);

		$url = apply_filters( 'tec_events_settings_tab_url', $url, $admin_page, $tab );

		return $url;
	}

	/**
	 * Validate the settings.
	 */
	public function validate() {
		$admin_pages = tribe( 'admin.pages' );
		$admin_page  = $admin_pages->get_current_page();

		do_action( 'tribe_settings_validate_before_checks', $admin_page );

		// Check that the right POST && variables are set.
		$tribe_save_settings  = tribe_get_request_var( 'tribe-save-settings', false );
		$current_settings_tab = tribe_get_request_var( 'current-settings-tab', $this->get_current_tab() );
		if ( $tribe_save_settings && $current_settings_tab ) {
			// Check permissions.
			if ( ! current_user_can( Admin_Pages::get_capability() ) ) {
				$this->errors[]    = esc_html__( "You don't have permission to do that.", 'tribe-common' );
				$this->major_error = true;
			}

			// Check the nonce.
			if ( ! wp_verify_nonce( $tribe_save_settings, 'saving' ) ) {
				$this->errors[]    = esc_html__( 'The request was sent insecurely.', 'tribe-common' );
				$this->major_error = true;
			}

			// Check that the request originated from the current tab.
			if ( $current_settings_tab !== $this->current_tab ) {
				$this->errors[]    = esc_html__( "The request wasn't sent from this tab.", 'tribe-common' );
				$this->major_error = true;
			}

			// Bail if we have errors.
			if ( count( $this->errors ) ) {
				remove_action( 'shutdown', [ $this, 'delete_options' ] );
				add_option( 'tribe_settings_errors', $this->errors );
				add_option( 'tribe_settings_major_error', $this->major_error );
				wp_safe_redirect( $this->get_settings_page_url() );
				exit;
			}

			do_action( 'tribe_settings_validate', $admin_page );
			do_action( 'tribe_settings_validate_tab_' . $this->current_tab, $admin_page );

			// Set the current fields.
			$this->current_fields = $this->fields_for_save[ $this->current_tab ];
			$fields               = $this->current_fields;

			if ( is_array( $fields ) ) {
				// Loop through the fields and validate them.
				foreach ( $fields as $field_id => $field ) {
					// Get the value.
					$value = tribe_get_request_var( $field_id, null );
					$value = apply_filters( 'tribe_settings_validate_field_value', $value, $field_id, $field );

					// Make sure it has validation set up for it, else do nothing.
					if (
						( ! isset( $field['conditional'] ) || $field['conditional'] )
						&& ( ! empty( $field['validation_type'] ) || ! empty( $field['validation_callback'] ) )
					) {
						do_action( 'tribe_settings_validate_field', $field_id, $value, $field );
						do_action( 'tribe_settings_validate_field_' . $field_id, $value, $field );

						// Validate this field.
						$validate = new Tribe__Validate( $field_id, $field, $value );

						if ( isset( $validate->result->error ) ) {
							// Validation failed.
							$this->errors[ $field_id ] = $validate->result->error;
						} elseif ( $validate->result->valid ) {
							// Validation passed.
							$this->validated[ $field_id ]        = new stdClass();
							$this->validated[ $field_id ]->field = $validate->field;
							$this->validated[ $field_id ]->value = $validate->value;
						}
					}
				}

				// Do not generate errors for dependent fields that should not show.
				if ( ! empty( $this->errors ) ) {
					$keep         = array_filter( array_keys( $this->errors ), [ $this, 'dependency_checks' ] );
					$compare      = empty( $keep ) ? [] : array_combine( $keep, $keep );
					$this->errors = array_intersect_key( $this->errors, $compare );
				}

				// Run the save method.
				$this->save();
			}
		}
	}

	/**
	 * Save the settings.
	 *
	 * @since 4.15.0 Add the current page as parameter for the actions.
	 */
	public function save() {
		$admin_pages = tribe( 'admin.pages' );
		$admin_page  = $admin_pages->get_current_page();

		// Some hooks.
		do_action( 'tribe_settings_save', $admin_page );
		do_action( 'tribe_settings_save_tab_' . $this->current_tab, $admin_page );

		// We'll need this later.
		$parent_options = [];

		/**
		 * Loop through each validated option and either
		 * save it as is or figure out its parent option ID
		 * (in that case, it's a serialized option array and
		 * will be saved in the next loop).
		 */
		if ( ! empty( $this->validated ) ) {
			foreach ( $this->validated as $field_id => $validated_field ) {
				// Get the value and filter it.
				$value = $validated_field->value;
				/**
				 * Filter the value of the field before saving.
				 *
				 * @param mixed  $value           The value of the field.
				 * @param string $field_id        The ID of the field.
				 * @param object $validated_field The validated field object.
				 */
				$value = apply_filters( 'tribe_settings_save_field_value', $value, $field_id, $validated_field );

				// Figure out the parent option [could be set to false] and filter it.
				if ( is_network_admin() ) {
					$parent_option = ( isset( $validated_field->field['parent_option'] ) ) ? $validated_field->field['parent_option'] : Tribe__Main::OPTIONNAMENETWORK;
				}
				if ( ! is_network_admin() ) {
					$parent_option = ( isset( $validated_field->field['parent_option'] ) ) ? $validated_field->field['parent_option'] : Tribe__Main::OPTIONNAME;
				}

				$parent_option  = apply_filters( 'tribe_settings_save_field_parent_option', $parent_option, $field_id );
				$network_option = isset( $validated_field->field['network_option'] ) ? (bool) $validated_field->field['network_option'] : false;

				do_action( 'tribe_settings_save_field', $field_id, $value, $validated_field );
				do_action( 'tribe_settings_save_field_' . $field_id, $value, $validated_field );

				if ( ! $parent_option ) {
					if ( $network_option || is_network_admin() ) {
						update_site_option( $field_id, $value );
					} else {
						update_option( $field_id, $value );
					}
				} else {
					// Set the parent option.
					$parent_options[ $parent_option ][ $field_id ] = $value;
				}
			}
		}

		/**
		 * Loop through parent option arrays
		 * and save them
		 * NOTE: in the case of the main option Tribe Options,
		 * this will save using the Tribe__Settings_Manager::set_options method.
		 */
		foreach ( $parent_options as $option_id => $new_options ) {
			// Get the old options.
			if ( is_network_admin() ) {
				$old_options = (array) get_site_option( $option_id );
			} else {
				$old_options = (array) get_option( $option_id );
			}

			// Set the options by parsing old + new and filter that.
			$options = apply_filters( 'tribe_settings_save_option_array', wp_parse_args( $new_options, $old_options ), $option_id );

			if ( $option_id === Tribe__Main::OPTIONNAME ) {
				// Save using the Tribe__Settings_Manager method.
				Tribe__Settings_Manager::set_options( $options );
			} elseif ( $option_id === Tribe__Main::OPTIONNAMENETWORK ) {
				Tribe__Settings_Manager::set_network_options( $options );
			} elseif ( is_network_admin() ) {
				// Save using regular WP method.
				update_site_option( $option_id, $options );
			} else {
				update_option( $option_id, $options );
			}
		}

		do_action( 'tribe_settings_after_save', $admin_page );
		do_action( 'tribe_settings_after_save_' . $this->current_tab, $admin_page );

		remove_action( 'shutdown', [ $this, 'delete_options' ] );

		add_option( 'tribe_settings_sent_data', $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		add_option( 'tribe_settings_errors', $this->errors );
		add_option( 'tribe_settings_major_error', $this->major_error );

		wp_safe_redirect( esc_url_raw( add_query_arg( [ 'saved' => true ], $this->get_settings_page_url() ) ) );
		exit;
	}

	/**
	 * Display errors, if any, after saving.
	 *
	 * @since TBD
	 */
	public function display_errors(): void {
		// Fetch the errors and filter them.
		$errors = (array) apply_filters( 'tribe_settings_display_errors', $this->errors );
		$count  = apply_filters( 'tribe_settings_count_errors', count( $errors ) );

		// Bail if we don't have errors.
		if ( ! apply_filters( 'tribe_settings_display_errors_or_not', ( $count > 0 ) ) ) {
			return;
		}

		$output  = '<div id="message" class="error"><p><strong>';
		$output .= esc_html__( 'Your form had the following errors:', 'tribe-common' );
		$output .= '</strong></p><ul class="tribe-errors-list">';

		// Loop through each error.
		foreach ( $errors as $error ) {
			$output .= '<li>' . (string) $error . '</li>';
		}

		if ( count( $errors ) ) {
			$message = ( isset( $this->major_error ) && $this->major_error )
				? esc_html__( 'None of your settings were saved. Please try again.' )
				: esc_html( _n( 'The above setting was not saved. Other settings were successfully saved.', 'The above settings were not saved. Other settings were successfully saved.', $count, 'tribe-common' ) );
		}

		$output .= '</ul><p>' . $message . '</p></div>';

		// Final output, filtered of course.
		echo wp_kses_post( apply_filters( 'tribe_settings_error_message', $output ) );
	}

	/**
	 * Display success message after saving.
	 *
	 * @since TBD
	 */
	public function display_success(): void {
		$errors = (array) apply_filters( 'tribe_settings_display_errors', $this->errors );
		$count  = apply_filters( 'tribe_settings_count_errors', count( $errors ) );

		// Are we coming from the saving place?
		if ( tribe_get_request_var( 'saved', false ) && ! apply_filters( 'tribe_settings_display_errors_or_not', ( $count > 0 ) ) ) {
			// Output the filtered message.
			$message = esc_html__( 'Settings saved.', 'tribe-common' );
			$output  = '<div id="message" class="updated"><p><strong>' . $message . '</strong></p></div>';
			echo wp_kses_post( apply_filters( 'tribe_settings_success_message', $output, $this->current_tab ) );
		}

		// Delete Temporary Options After Display Errors and Success.
		$this->delete_options();
	}

	/**
	 * Delete temporary options.
	 *
	 * @since TBD
	 */
	public function delete_options(): void {
		delete_option( 'tribe_settings_errors' );
		delete_option( 'tribe_settings_major_error' );
		delete_option( 'tribe_settings_sent_data' );
	}

	/**
	 * Returns the main admin settings URL.
	 *
	 * @since 4.15.0
	 *
	 * @param array $args An array of arguments to add to the URL.
	 *
	 * @return string
	 */
	public function get_url( array $args = [] ): string {
		$defaults = [
			'page'   => $this->admin_slug,
			'parent' => self::$parent_page,
		];

		// Allow the link to be "changed" on the fly.
		$args = wp_parse_args( $args, $defaults );

		$url = admin_url( $args['parent'] );

		// Keep the resulting URL args clean.
		unset( $args['parent'] );

		return apply_filters( 'tribe_settings_url', add_query_arg( $args, $url ), $args, $url );
	}

	/**
	 * The "slug" used for adding submenu pages.
	 *
	 * @return string
	 */
	public function get_parent_slug(): string {
		$slug = self::$parent_page;

		// If we don't have an event post type, then we can just use the tribe-common slug.
		if ( 'edit.php' === $slug || 'admin.php?page=tribe-common' === $slug ) {
			$slug = self::$parent_slug;
		}

		return $slug;
	}

	/**
	 * Gets the slug for the help page.
	 *
	 * @return string
	 */
	public function get_help_slug(): string {
		return $this->help_slug;
	}

	/**
	 * Determines whether or not the network admin pages should be initialized.
	 *
	 * When running in parallel with TEC 3.12.4, TEC should be relied on to handle the admin screens
	 * that version of TEC (and lower) is tribe-common ignorant. Therefore, tribe-common has to be
	 * the smarter, more lenient codebase.
	 * Beyond this at least one of the two "root" plugins (The Events Calendar and Event Tickets)
	 * should be network activated to add the page.
	 *
	 * @return bool
	 */
	public function should_setup_network_pages(): bool {
		$root_plugin_is_mu_activated = array_sum( array_map( 'is_plugin_active_for_network', $this->root_plugins ) ) >= 1;

		if ( ! $root_plugin_is_mu_activated ) {
			return false;
		}

		if ( ! class_exists( 'Tribe__Events__Main' ) ) {
			return true;
		}

		if ( version_compare( Tribe__Events__Main::VERSION, '4.0beta', '>=' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Sets what `common` should consider root plugins.
	 *
	 * @param array $root_plugins An array of plugins in the `<folder>/<file.php>` format.
	 */
	public function set_root_plugins( array $root_plugins ): void {
		$this->root_plugins = $root_plugins;
	}

	/**
	 * Whether the specified field dependency condition is valid or not depending on
	 * its parent field value.
	 *
	 * @since 4.7.7
	 *
	 * @param string $field_id The id of the field that might be removed.
	 *
	 * @return bool `true` if the field dependency condition is valid, `false` if the field
	 *              dependency condition is not valid.
	 */
	protected function dependency_checks( $field_id ): bool {
		$does_not_exist = ! array_key_exists( $field_id, $this->current_fields );

		if ( $does_not_exist ) {
			return false;
		}

		$has_no_dependency = ! isset( $this->current_fields[ $field_id ]['validate_if'] );

		if ( $has_no_dependency ) {
			return true;
		}

		$condition = $this->current_fields[ $field_id ]['validate_if'];

		if ( $condition instanceof Tribe__Field_Conditional ) {
			$parent_field = Tribe__Utils__Array::get( $this->validated, $condition->depends_on(), null );

			return $condition->check( $parent_field->value, $this->current_fields );
		}

		return is_callable( $condition )
			? call_user_func( $condition, $this->current_fields )
			: true == $condition;
	}

	/* Deprecated Methods */

	// phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	/**
	 * Static Singleton Factory Method.
	 *
	 * @deprecated TBD Use tribe( 'settings' )
	 *
	 * @return Tribe__Settings
	 */
	public static function instance() {
		_deprecated_function( __METHOD__, 'TBD', "tribe( 'settings' )" );
		return tribe( 'settings' );
	}

	/**
	 * Init all the tabs.
	 *
	 * @deprecated TBD Use init_tabs
	 */
	public function initTabs() {
		_deprecated_function( __METHOD__, 'TBD', 'init_tabs' );
		$this->init_tabs();
	}

	/**
	 * Create the main option page.
	 *
	 * @deprecated 4.15.0
	 */
	public function addPage() {
		_deprecated_function( __METHOD__, '4.15.0' );
	}

	/**
	 * Create the network options page.
	 *
	 * @deprecated 4.15.0
	 */
	public function addNetworkPage() {
		_deprecated_function( __METHOD__, '4.15.0' );
	}

	/**
	 * Generate the tabs in the settings screen.
	 *
	 * @deprecated TBD
	 */
	public function generateTabs() {
		_deprecated_function( __METHOD__, 'TBD', 'generate_tabs' );
		$this->generate_tabs();
	}

	/**
	 * Display errors, if any, after saving.
	 *
	 * @deprecated TBD
	 */
	public function displayErrors() {
		_deprecated_function( __METHOD__, 'TBD', 'display_errors' );
		$this->display_errors();
	}

	/**
	 * Display success message after saving.
	 *
	 * @deprecated TBD
	 */
	public function displaySuccess() {
		_deprecated_function( __METHOD__, 'TBD', 'display_success' );
		$this->display_success();
	}


	/**
	 * Delete temporary options.
	 *
	 * @deprecated TBD
	 */
	public function deleteOptions() {
		_deprecated_function( __METHOD__, 'TBD', 'delete_options' );
		$this->delete_options();
	}

	/**
	 * Generate the main option page.
	 * includes the view file.
	 *
	 * @deprecated TBD
	 *
	 * @since 4.15.0 Add the current page as parameter for the actions.
	 */
	public function generatePage() {
		_deprecated_function( __METHOD__, 'TBD', 'generate_page' );
		$this->generate_page();
	}
	// phpcs:enable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
}
