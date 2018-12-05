<?php

/**
 * Class Tribe__Context
 *
 * @since 4.7.7
 */
class Tribe__Context {
	/**
	 * Whether the context of the current HTTP request is an AJAX one or not.
	 *
	 * @var bool
	 */
	protected $doing_ajax;

	/**
	 * Whether the context of the current HTTP request is a Cron one or not.
	 *
	 * @var bool
	 */
	protected $doing_cron;

	/**
	 * Whether we are currently creating a new post, a post of post type(s) or not.
	 *
	 * @since 4.7.7
	 *
	 * @param null $post_type The optional post type to check.
	 *
	 * @return bool Whether we are currently creating a new post, a post of post type(s) or not.
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

	/**
	 * Helper function to indicate whether the current execution context is AJAX.
	 *
	 * This method exists to allow us test code that behaves differently depending on the execution
	 * context; passing a value to this argument will set it to that value in future checks, a test-related usage.
	 *
	 * @since 4.7.12
	 *
	 * @param bool $doing_ajax An injectable status to override the `DOING_AJAX` check.
	 *
	 * @return boolean
	 */
	public function doing_ajax( $doing_ajax = null ) {
		if ( null !== $doing_ajax ) {
			$this->doing_ajax = (bool) $doing_ajax;
		} else {
			$this->doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
		}

		return $this->doing_ajax;
	}

	/**
	 * Checks whether the context of the current HTTP request is a Cron one or not.
	 *
	 * @since 4.7.23
	 *
	 * @param bool|null $doing_cron If set then this method will act as a setter; the current
	 *                         method call, and the following ones, will return this value.
	 *
	 * @return bool whether the context of the current HTTP request is a Cron one or not.
	 */
	public function doing_cron( $doing_cron = null ) {
		if ( null !== $doing_cron ) {
			$this->doing_cron = (bool) $doing_cron;
		} else {
			$this->doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
		}

		return $this->doing_cron;
	}
}
