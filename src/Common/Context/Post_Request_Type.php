<?php
/**
 * Provides methods to check and get the post state from the current request context.
 *
 * @since 5.0.13
 *
 * @package TEC\Common\Context;
 */

namespace TEC\Common\Context;

use WP_Post;
use Tribe__Utils__Array as Arr;

/**
 * Class Post_Request_Type.
 *
 * @since 5.0.13
 *
 * @package TEC\Common\Context;
 */
class Post_Request_Type {

	/**
	 * Whether the current request is one to quick edit a single post of the specified post type or not.
	 *
	 * @since 5.0.13
	 *
	 * @param string|array<string> $post_type The post type or post types to check.
	 *
	 * @return bool Whether the current request is one to quick edit a single post of the specified post type or not.
	 */
	public function is_inline_editing_post( $post_type ): bool {
		if ( ! ( ! empty( $post_type ) && wp_doing_ajax() && tribe_get_request_var( 'action' ) === 'inline-save' ) ) {
			return false;
		}

		$post_id = tribe_get_request_var( 'post_ID', null );

		if ( empty( $post_id ) || ! is_numeric( $post_id ) ) {
			return false;
		}

		return in_array( get_post_type( $post_id ), (array) $post_type, true );
	}

	/**
	 * Whether the current request is one to edit a list of the specified post types or not.
	 *
	 * The admin edit screen for a post type is the one that lists all the posts of that typ,
	 * it has the URL `/wp-admin/edit.php?post_type=<post_type>`.
	 *
	 * @since 5.0.13
	 *
	 * @param string|array<string> $post_type The post type or post types to check.
	 *
	 * @return bool Whether the current request is one to edit a list of the specified post types or not.
	 */
	public function is_editing_post_list( $post_type ): bool {
		// Quick check: are we on the `/wp-admin/edit.php` page?
		global $pagenow;

		if ( $pagenow !== 'edit.php' ) {
			return false;
		}

		// Run some more thorough checks for the post type(s).
		$post_types = array_filter( (array) $post_type );

		return $this->is_editing_post( $post_types );
	}

	/**
	 * Whether we are currently creating a new post, a post of post type(s) or not.
	 *
	 * @since 4.7.7
	 *
	 * @param null $post_type The optional post type to check.
	 *
	 * @return bool Whether we are currently creating a new post, a post of post type(s) or not.
	 */
	public function is_new_post( $post_type = null ): bool {
		global $pagenow;
		$is_new = 'post-new.php' === $pagenow;

		return $is_new && $this->is_editing_post( $post_type );
	}


	/**
	 * Whether we are currently editing a post(s), post type(s) or not.
	 *
	 * @since 4.7.7
	 *
	 * @param null|array|string|int $post_or_type A post ID, post type, an array of post types or post IDs, `null`
	 *                                            to just make sure we are currently editing a post.
	 *
	 * @return bool
	 */
	public function is_editing_post( $post_or_type = null ): bool {
		global $pagenow;
		$is_new     = 'post-new.php' === $pagenow;
		$is_post    = 'post.php' === $pagenow;
		$is_editing = 'edit.php' === $pagenow;

		if ( ! ( $is_new || $is_post || $is_editing ) ) {
			return false;
		}

		if ( ! empty( $post_or_type ) ) {
			$lookup = [];
			// Prevent a slew of warnings every time we call this.
			if ( isset( $_REQUEST ) ) {
				$lookup[] = (array) $_REQUEST;
			}

			if ( isset( $_GET ) ) {
				$lookup[] = (array) $_GET;
			}

			if ( isset( $_POST ) ) {
				$lookup[] = (array) $_POST;
			}

			if ( empty( $lookup ) ) {
				return false;
			}

			$current_post = Arr::get_in_any( $lookup, 'post', get_post() );

			if ( is_numeric( $post_or_type ) ) {
				$post = $is_post ? get_post( $post_or_type ) : null;

				return ! empty( $post ) && $post == $current_post;
			}

			$post_types = is_array( $post_or_type ) ? $post_or_type : [ $post_or_type ];

			$post = $is_post ? get_post( $current_post ) : null;

			if ( count( array_filter( $post_types, 'is_numeric' ) ) === count( $post_types ) ) {
				return ! empty( $post ) && in_array( $post->ID, $post_types );
			}

			if ( $is_post && $post instanceof WP_Post ) {
				$post_type = $post->post_type;
			} else {
				$post_type = Arr::get_in_any( $lookup, 'post_type', 'post' );
			}

			return (bool) count( array_intersect( $post_types, [ $post_type ] ) );
		}

		return $is_new || $is_post;
	}
}
