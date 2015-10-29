<?php

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class with a few helpers for the Administration Pages
 */
class Tribe__Admin__Help_Page {
	/**
	 * Static Singleton Holder
	 * @var Tribe__Admin__Help_Page|null
	 */
	protected static $instance;

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Tribe__Admin__Help_Page
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Get the list of plugins
	 *
	 * @param  string  $plugin_name    Should get only one plugin?
	 * @param  boolean $is_active Only get active plugins?
	 * @return array
	 */
	public function get_plugins( $plugin_name = null, $is_active = true ) {
		$plugins = array();

		$plugins['the-events-calendar'] = array(
			'name' => 'the-events-calendar',
			'title' => __( 'The Events Calendar', 'tribe-common' ),
			'repo' => 'http://wordpress.org/extend/plugins/the-events-calendar/',
			'stars_url' => 'http://wordpress.org/support/view/plugin-reviews/the-events-calendar?filter=5',
			'description' => __( 'The Events Calendar is a carefully crafted, extensible plugin that lets you easily share your events.', 'tribe-common' ),
			'is_active' => false,
			'version' => null,
		);

		if ( class_exists( 'Tribe__Events__Main' ) ){
			$plugins['the-events-calendar']['version'] = Tribe__Events__Main::VERSION;
			$plugins['the-events-calendar']['is_active'] = true;
		}

		$plugins['event-tickets'] = array(
			'name' => 'event-tickets',
			'title' => __( 'Event Tickets', 'tribe-common' ),
			'repo' => 'http://wordpress.org/extend/plugins/event-tickets/',
			'stars_url' => 'http://wordpress.org/support/view/plugin-reviews/event-tickets?filter=5',
			'description' => __( 'Events Tickets is a carefully crafted, extensible plugin that lets you easily sell tickets for your events.', 'tribe-common' ),
			'is_active' => false,
			'version' => null,
		);

		if ( class_exists( 'Tribe__Tickets__Main' ) ){
			$plugins['event-tickets']['version'] = Tribe__Tickets__Main::VERSION;
			$plugins['event-tickets']['is_active'] = true;
		}

		$plugins['advanced-post-manager'] = array(
			'name' => 'advanced-post-manager',
			'title' => __( 'Advanced Post Manager', 'tribe-common' ),
			'repo' => 'http://wordpress.org/extend/plugins/advanced-post-manager/',
			'stars_url' => 'http://wordpress.org/support/view/plugin-reviews/advanced-post-manager?filter=5',
			'description' => __( 'Turbo charge your posts admin for any custom post type with sortable filters and columns, and auto-registration of metaboxes.', 'tribe-common' ),
			'is_active' => false,
			'version' => null,
		);

		if ( class_exists( 'Tribe_APM' ) ){
			$plugins['advanced-post-manager']['version'] = 1;
			$plugins['advanced-post-manager']['is_active'] = true;
		}

		$plugins = (array) apply_filters( 'tribe_help_plugins', $plugins );

		// Only active ones?
		if ( true === $is_active ){
			foreach ( $plugins as $key => $plugin ) {
				if ( true !== $plugin['is_active'] ){
					unset( $plugins[ $key ] );
				}
			}
		}

		// Do the search
		if ( is_string( $plugin_name ) ) {
			if ( isset( $plugins[ $plugin_name ] ) ) {
				return $plugins[ $plugin_name ];
			} else {
				return false;
			}
		} else {
			return $plugins;
		}
	}

	/**
	 * Get the formatted text of the possible plugins
	 *
	 * @param  boolean $is_active Filter only active plugins
	 * @return string
	 */
	public function get_plugins_text( $is_active = true ) {
		$plugins = $this->get_plugins( null, $is_active );
		$count = count( $plugins );
		$plugins_text = '';
		$i = 0;

		foreach ( $plugins as $plugin ) {
			$i++;
			if ( $plugin['is_active'] !== $is_active ){
				continue;
			}

			$plugins_text .= $plugin['title'];

			if ( $i === $count - 1 ) {
				$plugins_text .= esc_html__( ' and ', 'tribe-common' );
			} elseif ( $i !== $count ) {
				$plugins_text .= ', ';
			}
		}

		return $plugins_text;
	}

	/**
	 * Get the Addons
	 *
	 * @param  string $plugin Plugin Name to filter
	 * @return array
	 */
	public function get_addons( $plugin = null ) {
		$addons = array();

		$addons[] = array(
			'title' => esc_html__( 'Events Calendar PRO', 'tribe-common' ),
			'link'  => 'http://m.tri.be/dr',
			'plugin' => array( 'the-events-calendar' ),
			'is_active' => class_exists( 'Tribe__Events__Pro__Main' ),
		);

		$addons[] = array(
			'title' => esc_html__( 'Eventbrite Tickets', 'tribe-common' ),
			'link'  => 'http://m.tri.be/ds',
			'plugin' => array( 'the-events-calendar' ),
			'is_active' => class_exists( 'Tribe__Events__Tickets__Eventbrite__Main' ),
		);

		$addons[] = array(
			'title' => esc_html__( 'Community Events', 'tribe-common' ),
			'link'  => 'http://m.tri.be/dt',
			'plugin' => array( 'the-events-calendar' ),
			'is_active' => class_exists( 'Tribe__Events__Community__Main' ),
		);

		$addons[] = array(
			'title' => esc_html__( 'Facebook Events', 'tribe-common' ),
			'link'  => 'http://m.tri.be/du',
			'plugin' => array( 'the-events-calendar' ),
			'is_active' => class_exists( 'Tribe__Events__Facebook__Importer' ),
		);

		$addons[] = array(
			'title' => esc_html__( 'Events Filter Bar', 'tribe-common' ),
			'link'  => 'http://m.tri.be/hu',
			'plugin' => array( 'the-events-calendar' ),
			'is_active' => class_exists( 'Tribe__Events__Filterbar__View' ),
		);

		$addons[] = array(
			'title' => esc_html__( 'Event Tickets Plus', 'tribe-common' ),
			'link'  => '@TODO',
			'plugin' => array( 'event-tickets' ),
			'is_active' => class_exists( 'Tribe__Tickets_Plus__Main' ),
		);

		/**
		 * Filter the array of premium addons upsold on the sidebar of the Settings > Help tab
		 *
		 * @param array $addons
		 */
		$addons = (array) apply_filters( 'tribe_help_addons', $addons );

		if ( is_null( $plugin ) ){
			return $addons;
		}

		// Allow for easily grab the addons for a plugin
		$filtered = array();
		foreach ( $addons as $addon ) {
			if ( ! in_array( $plugin, (array) $addon['plugin'] ) ){
				continue;
			}

			$filtered[] = $addon;
		}

		return $filtered;
	}

	/**
	 * From a Given link returns it with a GA arguments
	 *
	 * @param  string  $link     An absolute or a Relative link
	 * @param  boolean $relative Is the Link absolute or relative?
	 * @return string            Link with the GA arguments
	 */
	public function get_ga_link( $link = null, $relative = true ) {
		$query_args = array(
			'utm_source' => 'helptab',
			'utm_medium' => 'plugin-tec',
			'utm_campaign' => 'in-app',
		);

		if ( true === $relative ){
			$link = trailingslashit( Tribe__Main::$tec_url . $link );
		}

		return esc_url( add_query_arg( $query_args, $link ) );
	}

	/**
	 * Gets the Feed items from the The Events Calendar Blog
	 *
	 * @return array Feed Title and Link
	 */
	public function get_feed_items() {
		$news_rss = fetch_feed( Tribe__Main::FEED_URL );
		$news_feed = array();

		if ( ! is_wp_error( $news_rss ) ) {
			/**
			 * Filter the maximum number of items returned from the tribe news feed
			 *
			 * @param int $max_items default 5
			 */
			$maxitems  = $news_rss->get_item_quantity( apply_filters( 'tribe_help_rss_max_items', 5 ) );
			$rss_items = $news_rss->get_items( 0, $maxitems );
			if ( count( $maxitems ) > 0 ) {
				foreach ( $rss_items as $item ) {
					$item        = array(
						'title' => esc_html( $item->get_title() ),
						'link'  => esc_url( $item->get_permalink() ),
					);
					$news_feed[] = $item;
				}
			}
		}

		return $news_feed;
	}

	/**
	 * Get the information from the Plugin API data
	 *
	 * @param  object $plugin Plugin Object to be used
	 * @return object         An object with the API data
	 */
	private function get_plugin_api_data( $plugin = null ) {
		if ( is_scalar( $plugin ) ){
			return false;
		}

		$plugin = (object) $plugin;

		/**
		 * Filter the amount of time (seconds) we will keep api data to avoid too many external calls
		 * @var int
		 */
		$timeout = apply_filters( 'tribe_help_api_data_timeout', 3 * HOUR_IN_SECONDS );
		$transient = 'tribe_help_api_data-' . $plugin->name;
		$data = get_transient( $transient );

		if ( false === $data ) {
			if ( ! function_exists( 'plugins_api' ) ) {
				include_once ABSPATH . '/wp-admin/includes/plugin-install.php';
			}

			// Fetch the data
			$data = plugins_api( 'plugin_information', array(
				'slug' => $plugin->name,
				'is_ssl' => is_ssl(),
				'fields' => array(
					'banners' => true,
					'reviews' => true,
					'downloaded' => true,
					'active_installs' => true,
				)
			) );

			if ( ! is_wp_error( $data ) ) {
				// Format Downloaded Infomation
				$data->downloaded = $data->downloaded ? number_format( $data->downloaded ) : _x( 'n/a', 'not available', 'tribe-common' );
			} else {
				// If there was a bug on the Current Request just leave
				return false;
			}

			set_transient( $transient, $data, $timeout );
		}
		$data->up_to_date = ( version_compare( $plugin->version, $data->version, '<' ) ) ? esc_html__( 'You need to upgrade!', 'tribe-common' ) : esc_html__( 'You are up to date!', 'tribe-common' );

		/**
		 * Filters the API data that was stored in the Transient option
		 *
		 * @var array
		 * @var object The plugin object, check `$this->get_plugins()` for more info
		 */
		return (object) apply_filters( 'tribe_help_api_data', $data, $plugin );
	}

	public function get_html_from_text( $mixed = '' ) {
		// If it's an StdObj or String it will be converted
		$mixed = (array) $mixed;

		// Loop to start the HTML
		foreach ( $mixed as &$line ) {
			if ( is_string( $line ) ){
				continue;
			} elseif ( is_array( $line ) ) {
				// Allow the developer to pass some configuration
				if ( empty( $line['type'] ) || ! in_array( $line['type'], array( 'ul', 'ol' ) ) ){
					$line['type'] = 'ul';
				}

				$text = '<' . $line['type'] . '>' . "\n";
				foreach ( $line as $key => $item ) {
					// Don't add non-numeric items (a.k.a.: configuration)
					if ( ! is_numeric( $key ) ) {
						continue;
					}

					// Only add List Item if is a UL or OL
					if ( in_array( $line['type'], array( 'ul', 'ol' ) ) ){
						$text .= '<li>' . "\n";
					}

					$text .= $this->get_html_from_text( $item );

					if ( in_array( $line['type'], array( 'ul', 'ol' ) ) ){
						$text .= '</li>' . "\n";
					}
				}
				$text .= '</' . $line['type'] . '>' . "\n";

				// Create the list as html instead of array
				$line = $text;
			}
		}

		return wpautop( implode( "\n\n", $mixed ) );
	}

	/**
	 * Prints the Plugin box for the given plugin
	 *
	 * @param  string $plugin Plugin Name key
	 * @return void
	 */
	public function print_plugin_box( $plugin ) {
		$plugin = (object) $this->get_plugins( $plugin, false );
		$api_data = $this->get_plugin_api_data( $plugin );
		$addons = $this->get_addons( $plugin->name );
		$plugins = get_plugins();

		if ( $api_data ) {
			if ( ! function_exists( 'install_plugin_install_status' ) ) {
				include_once ABSPATH . '/wp-admin/includes/plugin-install.php';
			}
			$status = install_plugin_install_status( $api_data );
			$plugin_active = is_plugin_active( $status['file'] );
			$plugin_exists = isset( $plugins[ $status['file'] ] );

			if ( 'install' !== $status['status'] && ! $plugin_active ){
				$args = array(
					'action' => 'activate',
					'plugin' => $status['file'],
					'plugin_status' => 'all',
					'paged' => 1,
					's' => '',
				);
				$activate_url = wp_nonce_url( add_query_arg( $args, 'plugins.php' ), 'activate-plugin_' . $status['file'] );
				$link = '<a class="button" href="' . $activate_url . '" aria-label="' . esc_attr( sprintf( __( 'Activate %s', 'tribe-common' ), $plugin->name ) ) . '">' . esc_html__( 'Activate Plugin', 'tribe-common' ) . '</a>';
			} elseif ( 'update_available' === $status['status'] ) {
				$args = array(
					'action' => 'upgrade-plugin',
					'plugin' => $status['file'],
				);
				$update_url = wp_nonce_url( add_query_arg( $args, 'update.php' ), 'upgrade-plugin_' . $status['file'] );

				$link = '<a class="button" href="' . $update_url . '">' . esc_html__( 'Upgrade Plugin', 'tribe-common' ) . '</a>';
			} elseif ( $plugin_exists ) {
				$link = '<a class="button disabled">' . esc_html__( 'You are up to date!', 'tribe-common' ) . '</a>';
			}
		}

		if ( ! isset( $link ) ){
			if ( $api_data ) {
				$args = array(
					'tab' => 'plugin-information',
					'plugin' => $plugin->name,
					'TB_iframe' => true,
					'width' => 772,
					'height' => 600,
				);
				$iframe_url = add_query_arg( $args, admin_url( '/plugin-install.php' ) );
				$link = '<a class="button thickbox" href="' . $iframe_url . '" aria-label="' . esc_attr( sprintf( __( 'Install %s', 'tribe-common' ), $plugin->name ) ) . '">' . esc_html__( 'Install Plugin', 'tribe-common' ) . '</a>';
			} else {
				$link = null;
			}
		}
		?>
		<div class="tribe-help-plugin-info">
			<h3><a href="<?php echo esc_url( $plugin->repo ); ?>"><?php echo esc_html( $plugin->title ); ?></a></h3>

			<?php
			if ( ! empty( $plugin->description ) && ! $plugin->is_active ) {
				echo wpautop( $plugin->description );
			}
			?>

			<?php if ( $api_data ) { ?>
			<div>
				<b><?php esc_html_e( 'Latest Version:', 'tribe-common' ); ?></b> <?php echo esc_html( $api_data->version ); ?>
				<br />

				<b><?php esc_html_e( 'Requires:', 'tribe-common' ); ?></b> <?php echo esc_html__( 'WordPress ', 'tribe-common' ) . esc_html( $api_data->requires ); ?>+
				<br />

				<b><?php esc_html_e( 'Active Users:', 'tribe-common' ); ?></b> <?php echo esc_html( number_format( $api_data->active_installs ) ); ?>+
				<br />

				<b><?php esc_html_e( 'Rating:', 'tribe-common' ); ?></b>
				<a href="<?php echo esc_url( $plugin->stars_url ); ?>" target="_blank">
				<?php wp_star_rating( array(
					'rating' => $api_data->rating,
					'type'   => 'percent',
					'number' => $api_data->num_ratings,
				) );?>
				</a>
			</div>
			<?php } ?>

			<?php
			// Only show the link to the users can use it
			if ( current_user_can( 'update_plugins' ) && current_user_can( 'install_plugins' ) ){
				echo ( $link ? '<p style="text-align: center;">' . $link . '</p>' : '' );
			}
			?>

			<?php if ( ! empty( $addons ) ) { ?>
				<h3><?php esc_html_e( 'Premium Add-Ons', 'tribe-common' ); ?></h3>
				<ul class='tribe-list-addons'>
					<?php foreach ( $addons as $addon ) {
						echo '<li class="' . ( isset( $addon['is_active'] ) && $addon['is_active'] ? 'tribe-active-addon' : '' ) . '">';
						if ( isset( $addon['link'] ) ) {
							echo '<a href="' . esc_url( $addon['link'] ) . '" target="_blank">';
						}
						echo esc_html( $addon['title'] );
						if ( isset( $addon['coming_soon'] ) ) {
							echo is_string( $addon['coming_soon'] ) ? ' ' . $addon['coming_soon'] : ' ' . esc_html__( '(Coming Soon!)', 'tribe-common' );
						}
						if ( isset( $addon['link'] ) ) {
							echo '</a>';
						}
						echo '</li>';
					} ?>
				</ul>
			<?php } ?>
		</div>
	<?php
	}
}
