<?php
/**
 * Facilitates storage and display of license key warning notices.
 *
 * @internal
 */
class Tribe__PUE__Notices {
	const MISSING_KEY = 'missing_key';
	const INVALID_KEY = 'invalid_key';
	const UPGRADE_KEY = 'upgrade_key';
	const EXPIRED_KEY = 'expired_key';
	const TRANSIENT   = 'tribe_pue_key_notices';

	protected $notices = array();

	/**
	 * Sets up license key related admin notices.
	 */
	public function __construct() {
		$this->populate();
		add_action( 'current_screen', array( $this, 'setup_notices' ) );
	}

	/**
	 * Restores plugins added on previous requests to the relevant notification
	 * groups.
	 */
	protected function populate() {
		$saved_notices = (array) get_transient( self::TRANSIENT );

		if ( empty( $saved_notices ) ) {
			return;
		}

		$this->notices = array_merge_recursive( $this->notices, $saved_notices );

		// Cleanup
		foreach ( $this->notices as $key => &$plugin_lists ) {
			// Purge any elements that are not arrays
			if ( ! is_array( $plugin_lists ) ) {
				unset( $this->notices[ $key ] );
				continue;
			}

			// Remove any duplicates
			$plugin_lists = array_unique( $plugin_lists );
		}
	}

	/**
	 * Saves any license key notices already added.
	 */
	public function save_notices() {
		set_transient( self::TRANSIENT, $this->notices );
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
	 * @param string $plugin_name
	 * @param string $notice_type
	 */
	public function add_notice( $plugin_name, $notice_type ) {
		$this->clear_notices( $plugin_name );
		$this->notices[ $notice_type ][] = $plugin_name;
		$this->save_notices();
	}

	/**
	 * Removes any notifications for the specified plugin.
	 *
	 * Useful when a valid license key is detected for a plugin, where previously
	 * it might have been included under a warning notification.
	 *
	 * @param $plugin_name
	 */
	public function clear_notices( $plugin_name ) {
		foreach ( $this->notices as $notice_type => &$list_of_plugins ) {
			$list_of_plugins = array_flip( $list_of_plugins );
			unset( $list_of_plugins[ $plugin_name ] );
			$list_of_plugins = array_flip( $list_of_plugins );
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
		remove_action( 'current_screen', array( $this, 'setup_notices' ) );

		// No need to display license key notices to users without appropriate capabilities
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		foreach ( $this->notices as $notice_type => $plugin_names ) {
			if ( empty( $plugin_names ) ) {
				continue;
			}

			$callback = array( $this, 'render_' . $notice_type );

			if ( is_callable( $callback ) ) {
				tribe_notice( 'pue_key-' . $notice_type, $callback, 'dismiss=1&type=warning' );
			}
		}
	}

	/**
	 * Generate a notice listing any plugins for which license keys have not yet been entered.
	 */
	public function render_missing_key() {
		$prompt = sprintf( _n(
				"It looks like you're using %s, but you haven't entered a license key. Add your license key so that you can always have access to our latest versions!",
				"It looks like you're using %s, but you haven't entered any license keys. Add your license keys so that you can always have access to our latest versions!",
				count( $this->notices[ self::MISSING_KEY ] ),
				'tribe-common'
			),
			$this->get_formatted_plugin_names( self::MISSING_KEY )
		);

		$action_steps = $this->find_your_key_text();

		$this->render_notice( 'pue_key-' . self::MISSING_KEY, "<p>$prompt</p> <p>$action_steps</p>" );
	}

	/**
	 * Generate a notice listing any plugins for which license keys have been entered but
	 * are invalid (in the sense of not matching PUE server records or having been revoked
	 * rather than having expired which is handled separately).
	 */
	public function render_invalid_key() {
		$prompt = sprintf( _n(
			"It looks like you're using %s, but the license key you supplied does not appear to be valid. Please review and fix so that you can always have access to our latest versions!",
			"It looks like you're using %s, but the license keys you supplied do not appear to be valid. Please review and fix so that you can always have access to our latest versions!",
			count( $this->notices[ self::INVALID_KEY ] ),
			'tribe-common'
		),
			$this->get_formatted_plugin_names( self::INVALID_KEY )
		);

		$action_steps = $this->find_your_key_text();

		$this->render_notice( 'pue_key-' . self::EXPIRED_KEY, "<p>$prompt</p> <p>$action_steps</p>" );
	}

	/**
	 * Generate a notice listing any plugins for which license keys have expired.
	 */
	public function render_expired_key() {
		$prompt = sprintf( _n(
				'There is an update available for %1$s but your license has expired. %2$sVisit the Events Calendar website to renew your license.%3$s',
				'Updates are available for %1$s but your license keys have expired. %2$sVisit the Events Calendar website to renew your licenses.%3$s',
				count( $this->notices[ self::EXPIRED_KEY ] ),
				'tribe-common'
			),
			$this->get_formatted_plugin_names( self::EXPIRED_KEY ),
			'<a href="http://m.tri.be/195d" target="_blank">',
			'</a>'
		);

		$renew_action =
			'<a href="http://m.tri.be/195y" target="_blank" class="button button-primary">' .
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
		$prompt = sprintf( _n(
			'There is an update available for %1$s but your license key is out of installs. %2$sVisit the Events Calendar website%3$s to to manage your installs, upgrade your license, or purchase a new one.',
			'Updates are available for %1$s but your license keys are out of installs. %2$sVisit the Events Calendar website%3$s to to manage your installs, upgrade your licenses, or purchase new ones.',
			count( $this->notices[ self::UPGRADE_KEY ] ),
			'tribe-common'
		),
			$this->get_formatted_plugin_names( self::UPGRADE_KEY ),
			'<a href="http://m.tri.be/195d" target="_blank">',
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
		$spirit_animal = esc_url( Tribe__Main::instance()->plugin_url . 'src/resources/images/spirit-animal.png' );

		$html = "
			<div class='api-check'>
				<img class='tribe-spirit-animal' src='$spirit_animal' />
				$inner_html
			</div>
		";

		Tribe__Admin__Notices::instance()->render( $slug, $html );
	}

	/**
	 * @return string
	 */
	protected function find_your_key_text() {
		return sprintf(
			__( 'You can find your license keys by logging in to %1$syour account on theeventscalendar.com%2$s and you can enter them over on the %3$ssettings page%2$s.', 'tribe-common' ),
			'<a href="http://m.tri.be/195d" target="_blank">',
			'</a>',
			'<a href="' . admin_url( 'edit.php?page=tribe-common&tab=licenses&post_type=tribe_events' ) . '">'
		);
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
	 * @param string $group
	 *
	 * @return string
	 */
	protected function get_formatted_plugin_names( $group ) {
		$num_plugins = count( $this->notices[ $group ] );

		if ( ! $num_plugins ) {
			$html = __( 'Unknown Plugin(s)', 'tribe-common' );
		}

		if ( 1 === $num_plugins ) {
			$html = current( $this->notices[ $group ] );
		}

		if ( 1 < $num_plugins ) {
			$all_but_last = join( ', ', array_slice( $this->notices[ $group ], 0, count( $this->notices[ $group ] ) - 1 ) );
			$last = current( array_slice( $this->notices[ $group ], count( $this->notices[ $group ] ) - 1, 1 ) );
			$html = sprintf( _x( '%1$s and %2$s', 'formatted plugin list', 'tribe-common' ), $all_but_last, $last );
		}

		return '<span class="plugin-list">' . $html . '</span>';
	}
}