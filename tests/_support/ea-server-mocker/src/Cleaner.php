<?php


class Tribe__Events__Aggregator_Mocker__Cleaner
	implements Tribe__Events__Aggregator_Mocker__Binding_Provider_Interface, Tribe__Events__Aggregator_Mocker__Option_Provider_Interface {

	protected $deleted = array();

	protected $clean_on = array(
		'ea_mocker-clean-all',
		'ea_mocker-clean-events',
		'ea_mocker-clean-venues',
		'ea_mocker-clean-organizers',
		'ea_mocker-clean-ea-records',
	);

	public function hook(  ) {
		$this->clean();
		add_action( 'admin_notices', array( $this, 'notices' ) );
	}

	protected function clean() {
		if ( ! $this->should_clean() ) {
			return;
		}

		foreach ( $this->clean_on as $trigger ) {
			if ( empty( $_POST[ $trigger ] ) ) {
				continue;
			}

			if ( $trigger !== 'ea_mocker-clean-all' ) {
				$post_types = (array) tec_sanitize_string( $_POST[ $trigger ] );
			} else {
				$post_types = array( 'tribe_events', 'tribe-ea-record', 'tribe_venue', 'tribe_organizer' );
			}

			if ( empty( $post_types ) ) {
				continue;
			}

			/** @var \wpdb $wpdb */
			global $wpdb;

			foreach ( $post_types as $post_type ) {
				$ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type ='{$post_type}'" );

				if ( empty( $ids ) ) {
					continue;
				}

				$delete_meta = "DELETE pm FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE p.post_type = '{$post_type}'";
				$delete_posts = "DELETE FROM {$wpdb->posts} WHERE post_type ='{$post_type}'";

				$deleted_meta = $wpdb->query( $delete_meta );
				$deleted_posts = $wpdb->query( $delete_posts );

				$this->deleted[ $post_type ] = array( 'posts' => $deleted_posts, 'meta' => $deleted_meta );
			}
		}

		update_option( 'ea_mocker-cleaner-show_notice', $this->deleted );
	}

	public function notices() {
		$option = get_option( 'ea_mocker-cleaner-show_notice', false );

		if ( false === $option || empty( $_GET['settings-updated'] ) || empty( $_GET['page'] ) || 'ea-mocker' !== $_GET['page'] ) {
			return;
		}

		delete_option( 'ea_mocker-cleaner-show_notice' );

		if ( ! empty( $option) ) {
			?>
			<div class="notice notice-success">
				<p>Clean results:</p>
				<ul>
					<?php foreach ( $option as $post_type => $count ) : ?>
						<li><?php printf( "%d <code>%s</code> posts and %d meta entries", $count['posts'], $post_type,
								$count['meta'] ) ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
		} else {
			?>
			<div class="notice notice-success">
				<p>Seems like there was nothing to delete.</p>
			</div>
			<?php
		}
	}

	/**
	 * Returns an array of options that should trigger the mocker as enabled.
	 *
	 * The options will be evaluated in a logic OR condition. Returning `true` in this method will always activate
	 * the provider.
	 *
	 * @return array|bool
	 */
	public static function enable_on() {
		return true;
	}

	/**
	 * Binds mock implementations overriding the existing ones.
	 */
	public static function bind() {
		return;
	}

	protected function should_clean() {
		try {
			foreach ( $this->clean_on as $trigger ) {
				if ( empty( $_POST[ $trigger ] ) ) {
					continue;
				}
				throw new RuntimeException( 'Ok, go on.' );
			}
		} catch ( RuntimeException $e ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns an array of options the class uses.
	 */
	public static function provides_options() {
		return array( 'ea_mocker-cleaner-show_notice' );
	}
}
