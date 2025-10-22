<?php
/**
 * This trait is used to dismiss conditional content in the admin.
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Conditional_Content\Traits;

use Exception;

/**
 * Trait Is_Dismissible
 *
 * Provides functionality to dismiss and track dismissal of conditional content.
 *
 * Classes using this trait MUST define:
 * - protected string $slug             - Unique identifier for the content
 * - public function get_slug(): string - Method to get the full slug (usually with year)
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */
trait Is_Dismissible {
	/**
	 * User Meta Key that stores which notices have been dismissed.
	 *
	 * @since 6.9.8
	 *
	 * @var string
	 */
	protected string $meta_key = 'tec-dismissible-content';

	/**
	 * Request param used to pass the nonce for dismissal.
	 *
	 * @since 6.9.8
	 *
	 * @var string
	 */
	protected string $nonce_action_prefix = 'tec-dismissible-content-nonce-';

	/**
	 * User Meta Key prefix that stores when notices have been dismissed.
	 *
	 * @since 6.9.8
	 *
	 * @var string
	 */
	protected string $meta_key_time_prefix = 'tec-dismissible-content-time-';

	/**
	 * Whether the promotional content is dismissible.
	 *
	 * @since 6.9.8
	 *
	 * @return bool
	 */
	public function is_dismissible(): bool {
		return true;
	}

	/**
	 * Get the nonce action for this dismissible content.
	 *
	 * @since 6.9.8
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
	 * @since 6.9.8
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
	 * @since 6.9.8
	 *
	 * @return void
	 *
	 * @throws Exception If slug is required.
	 */
	public function handle_dismiss(): void {
		if ( empty( $this->slug ) ) {
			throw new Exception( 'Slug is required' );
		}

		$slug = sanitize_key( tribe_get_request_var( 'slug', false ) );

		if ( ! $slug ) {
			return;
		}

		if ( $this->get_slug() !== $slug ) {
			return;
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
	 * @since 6.9.8
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

		update_user_meta( $user_id, $this->meta_key_time_prefix . $this->get_slug(), time() );

		return (bool) add_user_meta( $user_id, $this->meta_key, $this->get_slug() );
	}

	/**
	 * Removes the user meta that holds if this content has been dismissed.
	 *
	 * @since 6.9.8
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

		return delete_user_meta( $user_id, $this->meta_key, $this->get_slug() );
	}

	/**
	 * Checks if a given user has dismissed a given notice.
	 *
	 * @since 6.9.8
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

		$is_dismissed = true;

		if ( ! is_array( $dismissed_notices ) ) {
			$is_dismissed = false;
		} elseif ( ! in_array( $this->get_slug(), $dismissed_notices, true ) ) {
			$is_dismissed = false;
		}

		/**
		 * Filters the result of the user dismissal check.
		 *
		 * @since 6.9.8
		 *
		 * @param bool   $result     The result of the user dismissal check.
		 * @param object $instance   The conditional content object.
		 */
		return (bool) apply_filters( "tec_admin_conditional_content_{$this->slug}_has_user_dismissed", $is_dismissed, $this );
	}
}
