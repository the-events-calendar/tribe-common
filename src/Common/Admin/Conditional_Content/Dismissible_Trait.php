<?php

declare( strict_types=1 );

namespace TEC\Common\Admin\Conditional_Content;

/**
 * Trait Dismissible_Trait
 *
 * @since   TBD
 *
 * @package TEC\Common\Admin\Conditional_Content
 */
trait Dismissible_Trait {
	/**
	 * User Meta Key that stores which notices have been dismissed.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $meta_key = 'tec-dismissible-content';

	/**
	 * Request param used to pass the nonce for dismissal.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $nonce_action_prefix = 'tec-dismissible-content-nonce-';

	/**
	 * User Meta Key prefix that stores when notices have been dismissed.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $meta_key_time_prefix = 'tec-dismissible-content-time-';

	/**
	 * Get the nonce action for this dismissible content.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_nonce_action(): string {
		if ( empty( $this->slug ) ) {
			return '';
		}

		return $this->nonce_action_prefix . $this->slug;
	}

	/**
	 * Get the nonce for this dismissible content.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_nonce(): string {
		return wp_create_nonce( $this->get_nonce_action() );
	}

	/**
	 * This will allow the user to Dismiss the Notice using JS.
	 *
	 * We will dismiss the notice without checking to see if the slug was already
	 * registered (via a call to exists()) for the reason that, during dismissal
	 * ajax request, some valid notices may not have been registered yet.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function handle_dismiss(): void {
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
		$nonce_action = $this->get_nonce_action();

		if ( ! wp_verify_nonce( $nonce, $nonce_action ) ) {
			wp_send_json( false );
		}

		// Send a JSON answer with the status of dismissal.
		wp_send_json( $this->dismiss() );
	}

	/**
	 * A Method to actually add the Meta value recording that this content has been dismissed.
	 *
	 * @since TBD
	 *
	 * @param int|null|string $user_id The user ID.
	 *
	 * @return boolean
	 */
	protected function dismiss( $user_id = null ): bool {
		if ( empty( $this->slug ) ) {
			return false;
		}

		if ( is_null( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		// If this user has dismissed we don't care either.
		if ( $this->has_user_dismissed( $user_id ) ) {
			return true;
		}

		update_user_meta( $user_id, $this->meta_key_time_prefix . $this->slug, time() );

		return add_user_meta( $user_id, $this->meta_key, $this->slug );
	}

	/**
	 * Removes the user meta that holds if this content has been dismissed.
	 *
	 * @since TBD
	 *
	 * @param int|null|string $user_id The user ID.
	 *
	 * @return boolean
	 */
	public function undismiss( $user_id = null ): bool {
		if ( empty( $this->slug ) ) {
			return false;
		}

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		// If this user has dismissed we don't care either.
		if ( ! $this->has_user_dismissed( $user_id ) ) {
			return false;
		}

		return delete_user_meta( $user_id, $this->meta_key, $this->slug );
	}

	/**
	 * Checks if a given user has dismissed a given notice.
	 *
	 * @since TBD
	 *
	 * @param int|null|string $user_id The user ID.
	 *
	 * @return boolean
	 */
	public function has_user_dismissed( $user_id = null ): bool {
		if ( empty( $this->slug ) ) {
			return false;
		}

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		$dismissed_notices = get_user_meta( $user_id, $this->meta_key );

		if ( ! is_array( $dismissed_notices ) ) {
			return false;
		}

		if ( ! in_array( $this->slug, $dismissed_notices, true ) ) {
			return false;
		}

		return true;
	}
}
