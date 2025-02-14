<?php
/**
 * Handles the Read/Unread state of notifications.
 *
 * @since 6.4.0
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

/**
 * Trait Readable_Trait
 *
 * @since   6.4.0
 */
trait Readable_Trait {
	/**
	 * User Meta Key that stores which notifications have been read.
	 *
	 * @since 6.4.0
	 *
	 * @var string
	 */
	protected string $read_meta_key = 'tec-readable-content';

	/**
	 * Request param used to pass the nonce for reading.
	 *
	 * @since 6.4.0
	 *
	 * @var string
	 */
	protected string $read_nonce_action_prefix = 'tec-readable-content-nonce-';

	/**
	 * User Meta Key prefix that stores when notifications have been read.
	 *
	 * @since 6.4.0
	 *
	 * @var string
	 */
	protected string $read_meta_key_time_prefix = 'tec-readable-content-time-';

	/**
	 * Get the nonce action for this readable content.
	 *
	 * @since 6.4.0
	 *
	 * @return string
	 */
	public function get_read_nonce_action(): string {
		if ( empty( $this->slug ) ) {
			return '';
		}

		return $this->read_nonce_action_prefix . $this->slug;
	}

	/**
	 * Get the nonce for this readable content.
	 *
	 * @since 6.4.0
	 *
	 * @return string
	 */
	public function get_read_nonce(): string {
		return wp_create_nonce( $this->get_read_nonce_action() );
	}

	/**
	 * This will allow the user to read the notification using JS.
	 *
	 * @since 6.4.0
	 *
	 * @return void
	 */
	public function handle_read(): void {
		if ( empty( $this->slug ) ) {
			wp_send_json( false );
		}

		$slug = tribe_get_request_var( 'slug', false );
		if ( empty( $slug ) ) {
			wp_send_json( false );
		}

		$slug = sanitize_key( $slug );

		if ( $this->slug !== $slug ) {
			wp_send_json( false );
		}

		$nonce        = tribe_get_request_var( 'nonce', false );
		$nonce_action = $this->get_read_nonce_action();

		if ( ! wp_verify_nonce( $nonce, $nonce_action ) ) {
			wp_send_json( false );
		}

		// Send a JSON answer with the status of reading.
		wp_send_json( $this->read() );
	}

	/**
	 * A Method to add the Meta value that this notification has been read.
	 *
	 * @since 6.4.0
	 *
	 * @param int|null|string $user_id The user ID.
	 *
	 * @return boolean
	 */
	protected function read( $user_id = null ): bool {
		if ( empty( $this->slug ) ) {
			return false;
		}

		if ( is_null( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		// If this user has read we don't care either.
		if ( $this->has_user_read( $user_id ) ) {
			return true;
		}

		update_user_meta( $user_id, $this->read_meta_key_time_prefix . $this->slug, time() );

		return (bool) add_user_meta( $user_id, $this->read_meta_key, $this->slug );
	}

	/**
	 * Removes the user meta that holds if this content has been read.
	 *
	 * @since 6.4.0
	 *
	 * @param int|null|string $user_id The user ID.
	 *
	 * @return boolean
	 */
	public function unread( $user_id = null ): bool {
		if ( empty( $this->slug ) ) {
			return false;
		}

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		// If this user has read we don't care either.
		if ( ! $this->has_user_read( $user_id ) ) {
			return false;
		}

		return delete_user_meta( $user_id, $this->read_meta_key, $this->slug );
	}

	/**
	 * Checks if a given user has read a given notification.
	 *
	 * @since 6.4.0
	 *
	 * @param int|null|string $user_id The user ID.
	 *
	 * @return boolean
	 */
	public function has_user_read( $user_id = null ): bool {
		if ( empty( $this->slug ) ) {
			return false;
		}

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		$read_notifications = get_user_meta( $user_id, $this->read_meta_key );

		if ( ! is_array( $read_notifications ) ) {
			return false;
		}

		if ( ! in_array( $this->slug, $read_notifications, true ) ) {
			return false;
		}

		return true;
	}
}
