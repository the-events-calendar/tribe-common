<?php
/**
 * Settings Tab
 *
 * @since 4.0.1
 */

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
	 * Class constructor.
	 *
	 * @param string $id   The tab's id (no spaces or special characters).
	 * @param string $name The tab's visible name.
	 * @param array  $args Additional arguments for the tab.
	 */
	public function __construct( $id, $name, $args = [] ) {

		// Setup the defaults.
		$this->defaults = [
			'fields'           => [],
			'priority'         => 50,
			'show_save'        => true,
			'display_callback' => false,
			'network_admin'    => false,
		];

		// Parse args with defaults.
		$this->args = wp_parse_args( $args, $this->defaults );

		// Set each instance variable and filter.
		$this->id   = apply_filters( 'tribe_settings_tab_id', $id );
		$this->name = apply_filters( 'tribe_settings_tab_name', $name );
		foreach ( $this->defaults as $key => $value ) {
			$this->{$key} = apply_filters( 'tribe_settings_tab_' . $key, $this->args[ $key ], $id );
		}

		// Run actions & filters.
		if ( ! $this->network_admin ) {
			add_filter( 'tribe_settings_all_tabs', [ $this, 'add_all_tabs' ] );
		}
		add_filter( 'tribe_settings_tabs', [ $this, 'add_tab' ], $this->priority );
	}

	/**
	 * Filters the tabs array from Tribe__Settings
	 * and adds the current tab to it
	 * does not add a tab if it's empty.
	 *
	 * @param array $tabs the $tabs from Tribe__Settings.
	 *
	 * @return array $tabs the filtered tabs.
	 */
	public function add_tab( $tabs ): array {
		$hide_settings_tabs = Tribe__Settings_Manager::get_network_option( 'hideSettingsTabs', [] );
		if ( ( isset( $this->fields ) || has_action( 'tribe_settings_content_tab_' . $this->id ) ) && ( empty( $hide_settings_tabs ) || ! in_array( $this->id, $hide_settings_tabs ) ) ) {
			if ( ( is_network_admin() && $this->args['network_admin'] ) || ( ! is_network_admin() && ! $this->args['network_admin'] ) ) {
				$tabs[ $this->id ] = $this->name;
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
	 * @param array $field the $fields from Tribe__Settings.
	 *
	 * @return array $fields the filtered fields.
	 */
	public function add_fields( $fields ): array {
		if ( ! empty ( $this->fields ) ) {
			$fields[ $this->id ] = $this->fields;
		} elseif ( has_action( 'tribe_settings_content_tab_' . $this->id ) ) {
			$fields[ $this->id ] = $this->fields = [ 0 => null ]; // Just to trick it.
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
		if ( $this->display_callback && is_callable( $this->display_callback ) ) {
			call_user_func( $this->display_callback );

			return;
		}

		$sent_data = get_option( 'tribe_settings_sent_data', [] );

		if ( is_array( $this->fields ) && ! empty( $this->fields ) ) {
			foreach ( $this->fields as $key => $field ) {
				if ( isset( $sent_data[ $key ] ) ) {
					// If we just saved [or attempted to], get the value that was input.
					$value = $sent_data[ $key ];
				} else {
					// Some options should always be stored at network level.
					$network_option = isset( $field['network_option'] ) ? (bool) $field['network_option'] : false;

					if ( is_network_admin() ) {
						$parent_option = ( isset( $field['parent_option'] ) ) ? $field['parent_option'] : Tribe__Main::OPTIONNAMENETWORK;
					} else {
						$parent_option = ( isset( $field['parent_option'] ) ) ? $field['parent_option'] : Tribe__Main::OPTIONNAME;
					}
					// Get the field's parent_option in order to later get the field's value.
					$parent_option = apply_filters( 'tribe_settings_do_content_parent_option', $parent_option, $key );
					$default       = ( isset( $field['default'] ) ) ? $field['default'] : null;
					$default       = apply_filters( 'tribe_settings_field_default', $default, $field );

					if ( ! $parent_option && ( $network_option || is_network_admin() ) ) {
						// No parent option, network admin.
						$value = get_site_option( $key, $default );
					} elseif ( ! $parent_option ) {
						// No parent option.
						$value = get_option( $key, $default );
					} elseif ( $parent_option == Tribe__Main::OPTIONNAME ) {
						// Get the options from Tribe__Settings_Manager if we're getting the main array.
						$value = Tribe__Settings_Manager::get_option( $key, $default );
					} elseif ( $parent_option == Tribe__Main::OPTIONNAMENETWORK ) {
						// Get the network options from Tribe__Settings_Manager.
						$value = Tribe__Settings_Manager::get_network_option( $key, $default );
					} elseif ( is_network_admin() ) {
						// Get the parent option for network admin.
						$options = (array) get_site_option( $parent_option );
						$value   = ( isset( $options[ $key ] ) ) ? $options[ $key ] : $default;
					} else {
						// Else, get the parent option normally.
						$options = (array) get_option( $parent_option );
						$value = ( isset( $options[ $key ] ) ) ? $options[ $key ] : $default;
					}
				}

				// Escape the value for display.
				if ( ! empty( $field['esc_display'] ) && function_exists( $field['esc_display'] ) ) {
					$value = $field['esc_display']( $value );
				} elseif ( is_string( $value ) ) {
					$value = esc_attr( stripslashes( $value ) );
				}

				// Filter the value.
				$value = apply_filters( 'tribe_settings_get_option_value_pre_display', $value, $key, $field );

				// Create the field.
				new Tribe__Field( $key, $field, $value );
			}
		} else {
			// No fields setup for this tab yet.
			echo '<p>' . esc_html__( 'There are no fields set up for this tab yet.', 'tribe-common' ) . '</p>';
		}
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
