<?php
/**
 * Events Gutenberg Assets
 *
 * @since TBD
 */
class Tribe__Editor__Assets {
	/**
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'wp_loaded', array( $this, 'register' ) );
	}

	/**
	 * Registers and Enqueues the assets
	 *
	 * @since TBD
	 *
	 * @param string $key Which key we are checking against
	 *
	 * @return boolean
	 */
	public function register() {

		$plugin = Tribe__Main::instance();
		$editor_js_config = array(
			'common' => array(
				'admin_url' => admin_url(),
				'timeZone' => array(
					'show_time_zone' => false,
					'label' => $this->get_timezone_label(),
				),
				'rest' => array(
					'url' => get_rest_url(),
					'nonce' => array(
						'wp_rest' => wp_create_nonce( 'wp_rest' ),
						'add_ticket_nonce' => wp_create_nonce( 'add_ticket_nonce' ),
						'edit_ticket_nonce' => wp_create_nonce( 'edit_ticket_nonce' ),
						'remove_ticket_nonce' => wp_create_nonce( 'remove_ticket_nonce' ),
					),
					'namespaces' => array(
						'core' => 'wp/v2',
					),
				),
				'date_settings' => array( $this, 'get_date_settings' ),
				'constants' => array(
					'hide_upsell' => ( defined( 'TRIBE_HIDE_UPSELL' ) && TRIBE_HIDE_UPSELL ) ? 'true' : 'false',
				),
				'countries' => tribe( 'languages.locations' )->get_countries(),
				'us_states' => Tribe__View_Helpers::loadStates(),
			),
		);

		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-data',
			'app/data.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize' => array(
					array(
						'name' => 'tribe_editor_js_config',
						/**
						 * Array used to setup the FE with custom variables from the BE
						 *
						 * @since TBD
						 *
						 * @param array An array with the variables to be localized
						 */
						'data' => apply_filters( 'tribe_editor_js_config', $editor_js_config ),
					),
				),
				'priority'  => 11,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-utils',
			'app/utils.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 12,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-store',
			'app/store.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 13,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-icons',
			'app/icons.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 14,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-hoc',
			'app/hoc.js',
			/**
			 * @todo revise this dependencies
			 */
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 15,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-components',
			'app/components.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 16,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-elements',
			'app/elements.js',
			/**
			 * @todo revise this dependencies
			 */
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 17,
			)
		);
		/**
		 * @todo: figure out why element styles are loading for tickets but not events.
		 */
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-components',
			'app/components.js',
			/**
			 * @todo revise this dependencies
			 */
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 17,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-elements-styles',
			'app/elements.css',
			array(),
			'enqueue_block_editor_assets',
			array(
				'in_footer'    => false,
			)
		);
	}

	/**
	 * Returns the site timezone as a string
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_timezone_label() {
		return class_exists( 'Tribe__Timezones' )
			? Tribe__Timezones::wp_timezone_string()
			: get_option( 'timezone_string', 'UTC' );
	}

	/**
	 * Get Localization data for Date settings
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_date_settings() {
		global $wp_locale;
		return array(
			'l10n'     => array(
				'locale'        => get_user_locale(),
				'months'        => array_values( $wp_locale->month ),
				'monthsShort'   => array_values( $wp_locale->month_abbrev ),
				'weekdays'      => array_values( $wp_locale->weekday ),
				'weekdaysShort' => array_values( $wp_locale->weekday_abbrev ),
				'meridiem'      => (object) $wp_locale->meridiem,
				'relative'      => array(
					/* translators: %s: duration */
					'future' => __( '%s from now', 'default' ),
					/* translators: %s: duration */
					'past'   => __( '%s ago', 'default' ),
				),
			),
			'formats'  => array(
				'time'       => get_option( 'time_format', __( 'g:i a', 'default' ) ),
				'date'       => get_option( 'date_format', __( 'F j, Y', 'default' ) ),
				'dateNoYear' => __( 'F j', 'default' ),
				'datetime'   => __( 'F j, Y g:i a', 'default' ),
			),
			'timezone' => array(
				'offset' => get_option( 'gmt_offset', 0 ),
				'string' => $this->get_timezone_label(),
			),
		);
	}
}
