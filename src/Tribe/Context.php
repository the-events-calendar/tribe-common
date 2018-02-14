<?php

/**
 * Class Tribe__Context
 *
 * @since 4.7.7
 */
class Tribe__Context {

	/**
	 * Whether we are currently creating a new post, a post of post type(s) or not.
	 *
	 * @since 4.7.7
	 *
	 * @param null|array|string|int $post_or_type A post type, an array of post types, `null`
	 *                                            to just make sure we are currently creating a post.
	 *
	 * @return bool
	 */
	public function is_new_post( $post_type = null ) {
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
	public function is_editing_post( $post_or_type = null ) {
		global $pagenow;
		$is_new  = 'post-new.php' === $pagenow;
		$is_post = 'post.php' === $pagenow;

		if ( ! $is_new && ! $is_post ) {
			return false;
		}

		if ( null !== $post_or_type ) {
			$lookup = array( $_GET, $_POST, $_REQUEST );

			$current_post = Tribe__Utils__Array::get_in_any( $lookup, 'post', get_post() );

			if ( is_numeric( $post_or_type ) ) {

				$post = $is_post ? get_post( $post_or_type ) : null;

				return ! empty( $post ) && $post == $current_post;
			}

			$post_types = is_array( $post_or_type ) ? $post_or_type : array( $post_or_type );

			$post = $is_post ? $current_post : null;

			if ( count( array_filter( $post_types, 'is_numeric' ) ) === count( $post_types ) ) {
				return ! empty( $post ) && in_array( $post->ID, $post_types );
			}

			if ( $is_post && $post instanceof WP_Post ) {
				$post_type = $post->post_type;
			} else {
				$post_type = Tribe__Utils__Array::get_in_any( $lookup, 'post_type', 'post' );
			}

			return (bool) count( array_intersect( $post_types, array( $post_type ) ) );
		}

		return $is_new || $is_post;
	}
}