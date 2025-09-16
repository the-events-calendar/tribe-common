<?php
use TEC\Common\StellarWP\Uplink\API\Validation_Response;
/**
 * Facilitates storage and display of license key warning notices.
 *
 * @internal
 */
class Tribe__PUE__Notices {
	const INVALID_KEY = 'invalid_key';
	const UPGRADE_KEY = 'upgrade_key';
	const EXPIRED_KEY = 'expired_key';
	const STORE_KEY   = 'tribe_pue_key_notices';

	/**
	 * List of registered plugin names for use in notifications.
	 *
	 * Holds an array of plugin names that are registered during
	 * a request. Only registered plugins will appear in notifications.
	 *
	 * @var string[]
	 */
	protected $registered = [];

	/**
	 * Notices previously saved in the database.
	 *
	 * Holds the saved notices retrieved from the database,
	 * typically via the `get_option` function. It serves as a reference for
	 * comparing or merging with current notices.
	 *
	 * @var array<string, array<string, bool>>
	 */
	protected $saved_notices = [];

	/**
	 * Notices to be displayed or processed during the current request.
	 *
	 * Contains the current set of notices, which may include
	 * notices added during the current request. It is merged with
	 * `$saved_notices` to ensure consistency.
	 *
	 * @var array<string, array<string, bool>>
	 */
	protected $notices = [];

	protected $plugin_names = [
		'pue_install_key_event_tickets_plus'       => 'Event Tickets Plus',
		'pue_install_key_events_community'         => 'The Events Calendar: Community Events',
		'pue_install_key_events_community_tickets' => 'The Events Calendar: Community Events Tickets',
		'pue_install_key_image_widget_plus'        => 'Image Widget Plus',
		'pue_install_key_tribe_eventbrite'         => 'The Events Calendar: Eventbrite Tickets',
		'pue_install_key_tribe_filterbar'          => 'The Events Calendar: Filter Bar',
		'pue_install_key_event_aggregator'         => 'Event Aggregator',
		'pue_install_key_events_calendar_pro'      => 'The Events Calendar PRO',
	];

	/**
	 * Sets up license key related admin notices.
	 */
	public function __construct() {
		$this->populate();
		add_action( 'current_screen', [ $this, 'setup_notices' ] );
		add_action( 'tribe_pue_notices_save_notices', [ $this, 'maybe_undismiss_notices' ] );
		add_filter( 'stellarwp/uplink/tec/client_validate_license', [ $this, 'clear_notices_after_uplink_connect' ] );
	}

	/**
	 * Clears any license key notices for the specified plugin.
	 *
	 * @since 6.4.1
	 *
	 * @param Validation_Response $results The validation results from uplink.
	 *
	 * @return Validation_Response
	 */
	public function clear_notices_after_uplink_connect( Validation_Response $results ): Validation_Response {
		if ( ! $results->is_valid() ) {
			return $results;
		}

		$this->populate();
		if ( empty( $this->notices ) || ! is_array( $this->notices ) ) {
			return $results;
		}

		foreach ( $this->notices as $key => $data ) {
			if ( isset( $results->slug ) ) {
				unset( $this->notices[ $key ][ $results->slug ] );
			}

			if ( isset( $results->name ) ) {
				unset( $this->notices[ $key ][ $results->name ] );
			}
		}

		$this->save_notices();

		return $results;
	}

	/**
	 * Registers a plugin name that should be used in license key notifications.
	 *
	 * If, on a given request, the name is not registered then the plugin name will not
	 * feature in any notifications. The benefit is that if a plugin is suddenly removed,
	 * it's name can be automatically dropped from any pre-registered persistent
	 * notifications.
	 *
	 * @param string $plugin_name
	 */
	public function register_name( $plugin_name ) {
		$this->registered[] = $plugin_name;
	}

	/**
	 * Restores and sanitizes plugin notices to ensure data integrity and prevent memory issues.
	 *
	 * Retrieves saved notices from the database, validates and sanitizes them,
	 * and sets them as the current request's notices.
	 *
	 * @since 6.4.2 Switched from `array_merge_recursive` to `wp_parse_args` to fix data duplication issues. Added additional sanitation and memory safeguards to handle large data sets effectively.
	 *
	 * @return void
	 */
	protected function populate() {
		$this->saved_notices = $this->sanitize_notices( (array) get_option( self::STORE_KEY, [] ) );

		// Init the notices as the saved notices.
		$this->notices = $this->saved_notices;
	}

	/**
	 * Recursively sanitizes notices to prevent nesting and ensure data integrity.
	 *
	 * @param array $notices The array of notices to sanitize.
	 *
	 * @since 6.4.2
	 *
	 * @return array Sanitized notices.
	 */
	protected function sanitize_notices( array $notices ): array {
		foreach ( $notices as $key => &$plugin_list ) {
			// Ensure the value is an array; otherwise, reset it.
			if ( ! is_array( $plugin_list ) ) {
				$plugin_list = [];
				continue;
			}

			foreach ( $plugin_list as $plugin => $data ) {
				// Flatten deeply nested arrays and set the value to `true`.
				$plugin_list[ $plugin ] = true;
			}
		}

		// Remove numeric keys and empty keys to ensure the notices array only contains valid string keys.
		$notices = array_filter(
			$notices,
			fn( $key ) => is_string( $key ) && strlen( trim( $key ) ) > 0,
			ARRAY_FILTER_USE_KEY
		);

		return $this->setup_notice_structure( $notices );
	}

	/**
	 * Ensures the required notice keys exist in the notices array and initializes them as arrays.
	 *
	 * This method guarantees that the notice structure includes specific predefined keys
	 * (e.g., `invalid_key`, `upgrade_key`, `expired_key`). If a required key is missing,
	 * it will be added with an empty array as its value. If a key exists but is not an array,
	 * it will be converted to an array.
	 *
	 * @since 6.4.2
	 *
	 * @param array $notices The array of notices to check and modify.
	 *                       Keys are expected to be predefined constants.
	 *
	 * @return array The updated notices array with required keys initialized.
	 */
	protected function setup_notice_structure( array $notices ): array {
		$required_keys = [
			self::INVALID_KEY,
			self::UPGRADE_KEY,
			self::EXPIRED_KEY,
		];

		foreach ( $required_keys as $key ) {
			$notices[ $key ] = isset( $notices[ $key ] ) ? (array) $notices[ $key ] : [];
		}

		return $notices;
	}

	/**
	 * Saves any license key notices already added.
	 *
	 * @since 6.4.2 Sanitize notices prior to storing them.
	 */
	public function save_notices() {
		$this->notices = $this->sanitize_notices( (array) $this->notices );

		update_option( self::STORE_KEY, $this->notices );

		/**
		 * Fires after PUE license key notices have been saved.
		 *
		 * @param array $current_notices
		 * @param array $previously_saved_notices
		 */
		do_action( 'tribe_pue_notices_save_notices', $this->notices, $this->saved_notices );
	}

	/**
	 * Undismisses license key notifications where appropriate.
	 *
	 * The idea is that if an invalid key is detected for one or more plugins, we show a notification
	 * until a user dismisses it. That user will not then see the notification again unless or until
	 * an additional plugin name is added to the invalid key list.
	 *
	 * Example:
	 *
	 *     - Notification listing "Eventbrite" and "Pro" keys as invalid shows
	 *     - User X dismisses the notification
	 *     - The "Pro" license is fixed/corrected - notification remains in a "dismissed" status for User X
	 *     - "Filter Bar" is added to the list of invalid keys
	 *     - The invalid key notification is undismissed, to make all users (including User X) aware of
	 *       the problem re Filter Bar
	 */
	public function maybe_undismiss_notices() {
		foreach ( $this->notices as $notice_type => $plugin_list ) {
			if ( is_array( $this->saved_notices ) && ! empty( $this->saved_notices[ $notice_type ] ) ) {
				$new_plugins = array_diff_key( $this->notices[ $notice_type ], $this->saved_notices[ $notice_type ] );
			} else {
				$new_plugins = $this->notices[ $notice_type ];
			}

			if ( ! empty( $new_plugins ) ) {
				Tribe__Admin__Notices::instance()->undismiss_for_all( 'pue_key-' . $notice_type );
			}
		}
	}

	/**
	 * Used to include a plugin in a notification.
	 *
	 * For example, this could be used to add "My Plugin" to the expired license key
	 * notification by passing Tribe__PUE__Notices::EXPIRED_KEY as the second param.
	 *
	 * Plugins can only be added to one notification group at a time, so if a plugin
	 * was already added to the MISSING_KEY group and is subsequently added to the
	 * INVALID_KEY group, the previous entry (under MISSING_KEY) will be cleared.
	 *
	 * @since 6.9.3 Added early bail if $plugin_name is an empty string.
	 *
	 * @param string $notice_type Notice Type.
	 * @param string $plugin_name Plugin Name.
	 */
	public function add_notice( $notice_type, $plugin_name ) {
		if ( '' === trim( (string) $plugin_name ) ) {
			return;
		}
		$this->clear_notices( $plugin_name, true );
		$this->notices[ $notice_type ][ $plugin_name ] = true;
		$this->save_notices();
	}

	/**
	 * Returns whether or not a given plugin name has a specific notice
	 *
	 * @since 6.9.3 Bail early if the $plugin_name is an empty string.
	 *
	 * @param string      $plugin_name Plugin Name.
	 * @param string|null $notice_type Notice Type.
	 *
	 * @return boolean
	 */
	public function has_notice( $plugin_name, $notice_type = null ) {
		// Bail if plugin name is empty.
		if ( '' === trim( (string) $plugin_name ) ) {
			return false;
		}

		// If we match a pue key we use that value.
		if ( isset( $this->plugin_names[ $plugin_name ] ) ) {
			$plugin_name = $this->plugin_names[ $plugin_name ];
		}

		if ( $notice_type ) {
			return ! empty( $this->notices[ $notice_type ][ $plugin_name ] );
		}

		foreach ( $this->notices as $notice_type => $plugins ) {
			if ( ! empty( $plugins[ $plugin_name ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Removes any notifications for the specified plugin.
	 *
	 * Useful when a valid license key is detected for a plugin, where previously
	 * it might have been included under a warning notification.
	 *
	 * If the optional second param is set to true then this change will not
	 * immediately be committed to storage (useful if we know this will happen in
	 * any case later on in the same request).
	 *
	 * @param string $plugin_name
	 * @param bool $defer_saving_change = false
	 */
	public function clear_notices( $plugin_name, $defer_saving_change = false ) {
		foreach ( $this->notices as $notice_type => &$list_of_plugins ) {
			unset( $list_of_plugins[ $plugin_name ] );
		}

		if ( ! $defer_saving_change ) {
			$this->save_notices();
		}
	}

	/**
	 * Tests to see if there are any extant notifications and renders them if so.
	 *
	 * This must run prior to Tribe__Admin__Notices::hook() (which currently runs during
	 * "current_screen" priority 20).
	 */
	public function setup_notices() {
		// Don't allow this to run multiple times
		remove_action( 'current_screen', [ $this, 'setup_notices' ] );

		// No need to display license key notices to users without appropriate capabilities
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		foreach ( $this->notices as $notice_type => $plugin_names ) {
			if ( empty( $plugin_names ) ) {
				continue;
			}

			$callback = [ $this, 'render_' . $notice_type ];

			if ( is_callable( $callback ) ) {
				tribe_notice( 'pue_key-' . $notice_type, $callback, 'dismiss=1&type=warning' );
			}
		}
	}

	/**
	 * Select all products with empty license keys
	 * and format their names
	 *
	 * This information will be used to remove products
	 * with no license keys from $this->notices['invalid_key']
	 *
	 * @since 4.8
	 *
	 * @return array
	 */
	public function select_empty_keys() {
		/** @var $wpdb */
		global $wpdb;

		$sql = "
			SELECT option_name
				FROM {$wpdb->options}
				WHERE option_name LIKE 'pue_install_key_%'
					AND option_value=''
			";

		$empty_keys = $wpdb->get_results( $sql, ARRAY_N );

		$formatted_empty_keys = [];
		foreach ( $empty_keys as $empty_key ) {
			$empty_key              = Tribe__Utils__Array::get( $empty_key, [ 0 ] );
			$formatted_empty_keys[] = Tribe__Utils__Array::get( $this->plugin_names, $empty_key );
		}

		return $formatted_empty_keys;
	}

	/**
	 * Generate a notice listing any plugins for which license keys have been entered but
	 * are invalid (in the sense of not matching PUE server records or having been revoked
	 * rather than having expired which is handled separately).
	 *
	 * In the context of the plugin admin screen, will not render if the key-has-expired
	 * notice is also scheduled to display.
	 */
	public function render_invalid_key() {
		global $pagenow;

		$empty_keys = $this->select_empty_keys();

		if ( empty( $empty_keys ) ) {
			return;
		}

		// Remove the invalid_key notice for products with an empty license key
		foreach ( $empty_keys as $empty_key ) {
			if ( array_key_exists( $empty_key, $this->notices['invalid_key'] ) ) {
				unset( $this->notices['invalid_key'][ $empty_key ] );
			}
		}

		if ( 'plugins.php' === $pagenow && ! empty( $this->notices[ self::EXPIRED_KEY ] ) ) {
			return;
		}

		$plugin_names = $this->get_formatted_plugin_names( self::INVALID_KEY );

		/**
		 * Filters the list of plugins that should trigger an invalid key notice in PUE.
		 *
		 * @since 5.0.0
		 *
		 * @param array $plugin_names Array of plugin names that should trigger the invalid key notice.
		 */
		$plugin_names = apply_filters( 'tec_pue_invalid_key_notice_plugins', $plugin_names );

		if ( empty( $plugin_names ) ) {
			return;
		}

		$prompt = sprintf(
			_n(
				"It looks like you're using %1\$s, but the license key is invalid. Please download the latest version %2\$sfrom your account%3\$s.",
				"It looks like you're using %1\$s, but the license keys are invalid. Please download the latest versions %2\$sfrom your account%3\$s.",
				count( $this->notices[ self::INVALID_KEY ] ),
				'tribe-common'
			),
			$plugin_names,
			'<a href="http://evnt.is/19n4" target="_blank">',
			'</a>'
		);

		/**
		 * Filters the actions that can be taken if an invalid key is present
		 *
		 * @param string $actions Actions
		 * @param array $plugin_names Plugin names the message applies to
		 */
		$action_steps = apply_filters( 'tribe_notice_invalid_key_actions', $this->find_your_key_text(), $plugin_names );

		if ( $action_steps ) {
			$action_steps = "<p>{$action_steps}</p>";
		}

		$this->render_notice( 'pue_key-' . self::INVALID_KEY, "<p>{$prompt}</p> {$action_steps}" );
	}

	/**
	 * Generate a notice listing any plugins for which license keys have expired.
	 *
	 * This notice should only appear at the top of the plugin admin screen and "trumps"
	 * the missing/invalid key notice on that screen only.
	 */
	public function render_expired_key() {
		global $pagenow;

		if ( 'plugins.php' !== $pagenow ) {
			return;
		}

		$plugin_names = $this->get_formatted_plugin_names( self::EXPIRED_KEY );

		/**
		 * Filters the list of plugins that should trigger an expired key notice in PUE.
		 *
		 * @since 5.0.0
		 *
		 * @param array $plugin_names Array of plugin names that should trigger the expired key notice.
		 */
		$plugin_names = apply_filters( 'tec_pue_expired_key_notice_plugins', $plugin_names );

		if ( empty( $plugin_names ) ) {
			return;
		}

		$prompt = sprintf( _n(
				'There is an update available for %1$s but your license has expired. %2$sVisit the Events Calendar website to renew your license.%3$s',
				'Updates are available for %1$s but your license keys have expired. %2$sVisit the Events Calendar website to renew your licenses.%3$s',
				count( $this->notices[ self::EXPIRED_KEY ] ),
				'tribe-common'
			),
			$plugin_names,
			'<a href="http://evnt.is/195d" target="_blank">',
			'</a>'
		);

		$renew_action =
			'<a href="http://evnt.is/195y" target="_blank" class="button button-primary">' .
			__( 'Renew Your License Now', 'tribe-common' ) .
			'<span class="screen-reader-text">' .
			__( ' (opens in a new window)', 'tribe-common' ) .
			'</span></a>';

		$this->render_notice( 'pue_key-' . self::EXPIRED_KEY, "<p>$prompt</p> <p>$renew_action</p>" );
	}

	/**
	 * Generate a notice listing any plugins which have valid license keys, but those keys
	 * have met or exceeded the permitted number of installations they can be applied to.
	 */
	public function render_upgrade_key() {
		$plugin_names = $this->get_formatted_plugin_names( self::UPGRADE_KEY );

		/**
		 * Filters the list of plugins that should trigger an upgrade key notice in PUE.
		 *
		 * @since 5.0.0
		 *
		 * @param array $plugin_names Array of plugin names that should trigger the upgrade key notice.
		 */
		$plugin_names = apply_filters( 'tec_pue_upgrade_key_notice_plugins', $plugin_names );

		if ( empty( $plugin_names ) ) {
			return;
		}

		$prompt = sprintf( _n(
				'You have a license key for %1$s but the key is out of installs. %2$sVisit the Events Calendar website%3$s to manage your installs, upgrade your license, or purchase a new one.',
				'You have license keys for %1$s but your keys are out of installs. %2$sVisit the Events Calendar website%3$s to manage your installs, upgrade your licenses, or purchase new ones.', count( $this->notices[ self::UPGRADE_KEY ] ),
				'tribe-common'
			),
			$plugin_names,
			'<a href="http://evnt.is/195d" target="_blank">',
			'</a>'
		);

		$this->render_notice( 'pue_key-' . self::UPGRADE_KEY, "<p>$prompt</p>" );
	}

	/**
	 * Renders the notice itself (the provided HTML will be wrapped in a suitable container div).
	 *
	 * @param string $slug
	 * @param string $inner_html
	 */
	protected function render_notice( $slug, $inner_html ) {

		// Enqueue the notice CSS.
		tribe( 'assets' )->enqueue( [ 'tribe-common-admin' ] );

		$mascot = esc_url( Tribe__Main::instance()->plugin_url . 'src/resources/images/mascot.png' );

		$html =
			'<div class="api-check">
				<div class="tribe-mascot">
					<img src="' . $mascot . '" style="max-height: 150px; max-width: 150px; height: 100%; width: auto;"/>
				</div>
				<div class="notice-content">' . $inner_html . '</div>
			</div>';

		Tribe__Admin__Notices::instance()->render( $slug, $html, false );
	}

	/**
	 * @return string
	 */
	protected function find_your_key_text() {
		return sprintf(
			__( 'You can always check the status of your licenses by logging in to %1$syour account on theeventscalendar.com%2$s.', 'tribe-common' ),
			'<a href="http://evnt.is/195d" target="_blank">',
			'</a>'
		);
	}

	/**
	 * Transforms a list of plugins into human readable string.
	 *
	 * Examples of output:
	 *
	 *     # One name
	 *     "Ticket Pro"
	 *
	 *     # Two names
	 *     "Ticket Pro and Calendar Legend"
	 *
	 *     # Three names
	 *     "Ticket Pro, Calendar Legend and Date Stars"
	 *
	 * @since 4.9.12
	 *
	 * @param array|string $plugins Array of plugin classes.
	 *
	 * @return string|false The formatted plugin names.
	 */
	public function get_formatted_plugin_names_from_classes( $plugins ) {
		$plugin_list = [];

		foreach ( (array) $plugins as $class_name ) {
			$pue = tribe( Tribe__Dependency::class )->get_pue_from_class( $class_name );

			if ( ! $pue ) {
				continue;
			}

			if ( ! isset( $this->plugin_names[ $pue->pue_install_key ] ) ) {
				continue;
			}

			$plugin_list[] = $this->plugin_names[ $pue->pue_install_key ];
		}

		$num_plugins = count( $plugin_list );

		if ( 0 === $num_plugins ) {
			return false;
		}

		if ( 1 === $num_plugins ) {
			$html = current( $plugin_list );
		} elseif ( 1 < $num_plugins ) {
			$all_but_last = join( ', ', array_slice( $plugin_list, 0, count( $plugin_list ) - 1 ) );
			$last = current( array_slice( $plugin_list, count( $plugin_list ) - 1, 1 ) );
			$html = sprintf( _x( '%1$s and %2$s', 'formatted plugin list', 'tribe-common' ), $all_but_last, $last );
		}

		return '<span class="plugin-list">' . $html . '</span>';
	}

	/**
	 * Transforms the array referenced by group into a human readable,
	 * comma delimited list.
	 *
	 * Examples of output:
	 *
	 *     # One name
	 *     "Ticket Pro"
	 *
	 *     # Two names
	 *     "Ticket Pro and Calendar Legend"
	 *
	 *     # Three names
	 *     "Ticket Pro, Calendar Legend and Date Stars"
	 *
	 *     # Fallback
	 *     "Unknown Plugin(s)"
	 *
	 * @param string $group The group.
	 *
	 * @return string The formatted plugin names.
	 */
	protected function get_formatted_plugin_names( $group ) {
		if ( ! count( $this->notices[ $group ] ) ) {
			return '';
		}

		$plugin_list = array_intersect( $this->registered, array_keys( $this->notices[ $group ] ) );
		$num_plugins = count( $plugin_list );

		if ( 0 === $num_plugins ) {
			return '';
		} elseif ( 1 === $num_plugins ) {
			$html = current( $plugin_list );
		} elseif ( 1 < $num_plugins ) {
			$all_but_last = join( ', ', array_slice( $plugin_list, 0, count( $plugin_list ) - 1 ) );
			$last = current( array_slice( $plugin_list, count( $plugin_list ) - 1, 1 ) );
			$html = sprintf( _x( '%1$s and %2$s', 'formatted plugin list', 'tribe-common' ), $all_but_last, $last );
		}

		return '<span class="plugin-list">' . $html . '</span>';
	}
}
