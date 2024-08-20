<?php
/**
 * Settings Tab
 *
 * @since 4.0.1
 */

use TEC\Common\Admin\Entities\Element;
use TEC\Common\Admin\Settings_Sidebar;

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'Tribe__Settings_Tab', false ) ) {
	return;
}

// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase,StellarWP.Classes.ValidClassName.NotSnakeCase,PEAR.NamingConventions.ValidClassName.Invalid

/**
 * Helper class that creates a settings tab
 * this is a public API, use it to create tabs
 * simply by instantiating this class.
 */
class Tribe__Settings_Tab {

	/**
	 * Tab ID, used in query string and elsewhere.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Tab's name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Tab's arguments.
	 *
	 * @var array
	 */
	public $args;

	/**
	 * Defaults for tabs.
	 *
	 * @var array
	 */
	public $defaults;

	/**
	 * Fields for the tab.
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * Whether to show the save button.
	 *
	 * @var boolean
	 */
	public $show_save;

	/**
	 * Display callback function.
	 *
	 * @var closure
	 */
	public $display_callback;

	/**
	 * Whether this tab is for network admin.
	 *
	 * @var boolean
	 */
	public $network_admin;

	/**
	 * Priority for the tab.
	 * Used to order tabs.
	 *
	 * @var int
	 */
	public $priority;

	/**
	 * Parent tab ID, if any.
	 *
	 * @since TBD
	 *
	 * @var ?Tribe__Settings_Tab $parent
	 */
	protected ?Tribe__Settings_Tab $parent = null;

	/**
	 * Array of child tabs, if any.
	 *
	 * @since TBD
	 *
	 * @var array<string, Tribe__Settings_Tab> $children
	 */
	public $children = [];

	/**
	 * Settings sidebar.
	 *
	 * This can be a callable function that returns a Settings_Sidebar object,
	 * an instance of Settings_Sidebar, or null.
	 *
	 * @since TBD
	 *
	 * @var callable|Settings_Sidebar|null
	 */
	protected $sidebar = null;

	/**
	 * Class constructor.
	 *
	 * @param string $id   The tab's id (no spaces or special characters).
	 * @param string $name The tab's visible name.
	 * @param array  $args {
	 *     Array of arguments for the tab.
	 *
	 *     @type array    $fields           Array of fields for the tab.
	 *     @type int      $priority         Priority for the tab.
	 *     @type bool     $show_save        Whether to show the save button.
	 *     @type callable $display_callback Display callback function.
	 *     @type bool     $network_admin    Whether this tab is for network admin.
	 *     @type array    $children         Array of child tabs, if any.
	 * }
	 */
	public function __construct( $id, $name, $args = [] ) {
		// Setup the defaults.
		$this->defaults = [
			'fields'           => [],
			'priority'         => 50,
			'show_save'        => true,
			'display_callback' => false,
			'network_admin'    => false,
			'children'         => [],
		];

		// Parse args with defaults.
		$this->args = wp_parse_args( $args, $this->defaults );

		/**
		 * Filter the settings tab ID.
		 *
		 * @param string $id The tab ID.
		 */
		$this->id   = apply_filters( 'tribe_settings_tab_id', $id );

		/**
		 * Filter the settings tab name.
		 *
		 * @param string $name The tab name.
		 */
		$this->name = apply_filters( 'tribe_settings_tab_name', $name );

		// Cycle through the defaults and set the class properties.
		foreach ( $this->defaults as $key => $value ) {
			/**
			 * Filter the value of the key.
			 *
			 * @param mixed  $value The value of the key.
			 * @param string $id    The tab ID.
			 */
			$this->{$key} = apply_filters( "tribe_settings_tab_{$key}", $this->args[ $key ], $id );
		}

		// Run actions & filters.
		if ( ! $this->network_admin ) {
			add_filter( 'tribe_settings_all_tabs', [ $this, 'add_all_tabs' ] );
		}

		$this->add_filters();
		$this->add_actions();
	}

	/**
	 * Adds actions for the tab.
	 *
	 * @since TBD
	 */
	public function add_actions(): void {}

	/**
	 * Adds filters for the tab.
	 *
	 * @since TBD
	 */
	public function add_filters() {
		add_filter( 'tribe_settings_tabs', [ $this, 'add_tab' ] );
	}

	/**
	 * Gets the tab's parent ID.
	 *
	 * @return string The tab's parent ID.
	 */
	public function get_parent_id(): string {
		return null !== $this->parent ? $this->parent->id : '';
	}

	/**
	 * Gets the tab's parent.
	 *
	 * @return ?Tribe__Settings_Tab The tab's parent.
	 */
	public function get_parent(): ?Tribe__Settings_Tab {
		return $this->parent;
	}

	/**
	 * Checks if the tab has a parent.
	 *
	 * @return bool
	 */
	public function has_parent(): bool {
		return null !== $this->parent;
	}

	/**
	 * Sets the parent tab.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Settings_Tab $tab The parent tab.
	 *
	 * @return void
	 */
	public function set_parent( Tribe__Settings_Tab $tab ) {
		$this->parent = $tab;
	}

	/**
	 * Filters the tabs array from Tribe__Settings
	 * and adds the current tab to it
	 * does not add a tab if it's empty.
	 *
	 * @param Tribe__Settings_Tab[] $tabs the $tabs from Tribe__Settings.
	 *
	 * @return Tribe__Settings_Tab[] $tabs the filtered tabs.
	 */
	public function add_tab( $tabs ): array {
		$hide_settings_tabs = Tribe__Settings_Manager::get_network_option( 'hideSettingsTabs', [] );

		if ( ( isset( $this->fields ) || has_action( 'tribe_settings_content_tab_' . $this->id ) ) && ( empty( $hide_settings_tabs ) || ! in_array( $this->id, $hide_settings_tabs ) ) ) {
			if ( ( is_network_admin() && $this->args['network_admin'] ) || ( ! is_network_admin() && ! $this->args['network_admin'] ) ) {
				if ( $this->has_parent() && isset( $tabs[ $this->get_parent_id() ] ) ) {
					$tabs[ $this->get_parent_id() ]->add_child( $this );
				} else {
					// If the parent tab is not set, add  to the top level.
					$tabs[ $this->id ] = $this;
				}

				add_filter( 'tribe_settings_fields', [ $this, 'add_fields' ] );
				add_filter( 'tribe_settings_no_save_tabs', [ $this, 'show_save_tab' ] );
				add_filter( 'tribe_settings_content_tab_' . $this->id, [ $this, 'do_content' ] );
			}
		}

		return $tabs;
	}

	/**
	 * Adds this tab to the list of total tabs, even if it is not displayed.
	 *
	 * @param array $all_tabs All the tabs from Tribe__Settings.
	 *
	 * @return array $all_tabs All the tabs.
	 */
	public function add_all_tabs( $all_tabs ): array {
		$all_tabs[ $this->id ] = $this->name;

		return $all_tabs;
	}

	/**
	 * Filters the fields array from Tribe__Settings
	 * and adds the current tab's fields to it
	 *
	 * @since TBD
	 *
	 * @param array $fields the $fields from Tribe__Settings.
	 *
	 * @return array $fields the filtered fields.
	 */
	public function add_fields( $fields ): array {
		if ( ! empty( $this->fields ) ) {
			$fields[ $this->id ] = $this->fields;
		} elseif ( has_action( 'tribe_settings_content_tab_' . $this->id ) ) {
			$this->fields        = [ 0 => null ]; // Just to trick it.
			$fields[ $this->id ] = $this->fields;
		}

		return $fields;
	}

	/**
	 * Sets whether the current tab should show the save
	 * button or not.
	 *
	 * @param array $no_save_tabs the $no_save_tabs from Tribe__Settings.
	 *
	 * @return array $no_save_tabs the filtered non saving tabs.
	 */
	public function show_save_tab( $no_save_tabs ): array {
		if ( ! $this->show_save || empty( $this->fields ) ) {
			$no_save_tabs[ $this->id ] = $this->id;
		}

		return $no_save_tabs;
	}

	/**
	 * Displays the content for the tab.
	 *
	 * @return void
	 */
	public function do_content(): void {
		// If there is a sidebar, make sure to hook it.
		if ( $this->has_sidebar() ) {
			add_action(
				'tribe_settings_after_form_element',
				function () {
					$this->get_sidebar()->render();
				}
			);
		}

		// If we have a display callback, use it.
		if ( $this->display_callback && is_callable( $this->display_callback ) ) {
			call_user_func( $this->display_callback );

			return;
		}

		// Ensure we have fields before continuing.
		if ( ! is_array( $this->fields ) || empty( $this->fields ) ) {
			printf(
				'<p>%s</p>',
				esc_html__( 'There are no fields set up for this tab yet.', 'tribe-common' )
			);

			return;
		}

		// Get the sent data that was stored in the options table.
		$sent_data = get_option( 'tribe_settings_sent_data', [] );

		// Loop through the fields, create and display them.
		foreach ( $this->fields as $key => $field ) {
			// If this field is an Element, then render it. Otherwise, create a new field object and run it.
			if ( $field instanceof Element ) {
				$field->render();
			} else {
				$field_object = new Tribe__Field( $key, $field, $sent_data[ $key ] ?? null );
				$field_object->do_field();
			}
		}
	}

	/**
	 * Adds a child tab to the current tab.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Settings_Tab $tab The child tab to add.
	 */
	public function add_child( Tribe__Settings_Tab $tab ): void {
		// Don't try to add the same child more than once.
		if ( $this->has_child( $tab->id ) ) {
			return;
		}

		$this->children[ $tab->id ] = $tab;
		$tab->set_parent( $this );
	}

	/**
	 * Checks if the current tab has children.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function has_children(): bool {
		return ! empty( $this->children );
	}

	/**
	 * Checks if the current tab has a child with the given slug.
	 *
	 * @since TBD
	 *
	 * @param string $slug The slug of the child tab to check for.
	 *
	 * @return bool
	 */
	public function has_child( $slug ): bool {
		return ! empty( $this->children[ $slug ] );
	}

	/**
	 * Gets the children of the current tab.
	 *
	 * @since TBD
	 *
	 * @return array<string, Tribe__Settings_Tab>
	 */
	public function get_children(): array {
		return $this->children;
	}

	/**
	 * Gets a child tab by its slug.
	 *
	 * @since TBD
	 *
	 * @param string $slug The slug of the child tab to get.
	 *
	 * @return ?Tribe__Settings_Tab The child tab if it exists, otherwise null.
	 */
	public function get_child( $slug ): ?Tribe__Settings_Tab {
		if ( ! $this->has_child( $slug ) ) {
			return null;
		}

		return $this->children[ $slug ];
	}

	/**
	 * Gets the priority of the current tab.
	 *
	 * @since TBD
	 *
	 * @return string The priority of the tab. This will be a float stored as a string i.e. '5' or '5.5'.
	 */
	public function get_priority(): string {
		return $this->priority;
	}

	/**
	 * Adds a sidebar to the current tab.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function has_sidebar(): bool {
		$parent_has_sidebar = $this->has_parent() && $this->get_parent()->has_sidebar();

		return null !== $this->sidebar|| $parent_has_sidebar;
	}

	/**
	 * Gets the sidebar for the current tab.
	 *
	 * @since TBD
	 *
	 * @return Settings_Sidebar|null
	 * @throws InvalidArgumentException If the sidebar is invalid.
	 */
	public function get_sidebar() {
		if ( $this->sidebar instanceof Settings_Sidebar ) {
			return $this->sidebar;
		}

		if ( is_callable( $this->sidebar ) ) {
			$sidebar = call_user_func( $this->sidebar );
			if ( ! $sidebar instanceof Settings_Sidebar ) {
				throw new InvalidArgumentException(
					esc_html__( 'The sidebar callback must return an instance of Settings_Sidebar', 'tribe-common' )
				);
			}

			return $sidebar;
		}

		// If we have a parent, try to get the parent's sidebar.
		if ( $this->has_parent() && $this->get_parent()->has_sidebar() ) {
			return $this->get_parent()->get_sidebar();
		}

		return null;
	}

	/**
	 * Sets the sidebar for the current tab.
	 *
	 * @param callable|Settings_Sidebar $sidebar The sidebar to set.
	 *
	 * @return void
	 */
	public function add_sidebar( $sidebar ) {
		$this->validate_sidebar( $sidebar );
		$this->sidebar = $sidebar;
	}

	/**
	 * Validates the sidebar.
	 *
	 * @since TBD
	 *
	 * @param callable|Settings_Sidebar $sidebar The sidebar to validate.
	 *
	 * @return void
	 * @throws InvalidArgumentException If the sidebar is invalid.
	 */
	protected function validate_sidebar( $sidebar ) {
		// If it's a callable or an instance of Settings_Sidebar, we're good.
		if ( is_callable( $sidebar ) || $sidebar instanceof Settings_Sidebar ) {
			return;
		}

		// Everything else is invalid.
		throw new InvalidArgumentException(
			esc_html__( 'The sidebar must be a callable function or an instance of Settings_Sidebar', 'tribe-common' )
		);
	}

	/* Deprecated Methods */

	// phpcs:disable

	/**
	 * Adds this tab to the list of total tabs, even if it is not displayed.
	 *
	 * @deprecated TBD use add_all_tabs instead.
	 *
	 * @param array $all_tabs All the tabs from Tribe__Settings.
	 *
	 * @return array $all_tabs All the tabs.
	 */
	public function addAllTabs( $all_tabs ) {
		_deprecated_function( __METHOD__, 'TBD', 'add_all_tabs' );

		return $this->add_all_tabs( $all_tabs );
	}

	/* Deprecated Methods */

	/**
	 * filters the fields array from Tribe__Settings
	 * and adds the current tab's fields to it
	 *
	 * @deprecated TBD use add_fields instead.
	 *
	 * @param array $field the $fields from Tribe__Settings.
	 *
	 * @return array $fields the filtered fields
	 */
	public function addFields( $fields ) {
		_deprecated_function( __METHOD__, 'TBD', 'add_fields' );

		return $this->add_fields( $fields );
	}

	/**
	 * sets whether the current tab should show the save
	 * button or not
	 *
	 * @deprecated TBD use show_save_tab instead.
	 *
	 * @param array $no_save_tabs the $no_save_tabs from Tribe__Settings
	 *
	 * @return array $no_save_tabs the filtered non saving tabs
	 */
	public function showSaveTab( $no_save_tabs ) {
		_deprecated_function( __METHOD__, 'TBD', 'show_save_tab' );

		return $this->show_save_tab( $no_save_tabs );
	}


	/**
	 * Displays the content for the tab.
	 *
	 * @deprecated TBD use do_content instead.
	 *
	 * @return void
	 */
	public function doContent() {
		_deprecated_function( __METHOD__, 'TBD', 'do_content' );

		$this->do_content();
	}

	/**
	 * filters the tabs array from Tribe__Settings
	 * and adds the current tab to it
	 * does not add a tab if it's empty
	 *
	 * @deprecated TBD use add_tab instead.
	 *
	 * @param array $tabs the $tabs from Tribe__Settings
	 *
	 * @return array $tabs the filtered tabs
	 */
	public function addTab( $tabs ) {
		_deprecated_function( __METHOD__, 'TBD', 'add_tab' );

		return $this->add_tab( $tabs );
	}
	// phpcs:enable
}
