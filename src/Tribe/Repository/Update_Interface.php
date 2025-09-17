<?php

/**
 * Interface Tribe__Repository__Update_Interface
 *
 * @since 4.7.19
 */
interface Tribe__Repository__Update_Interface extends Tribe__Repository__Setter_Interface {
	/**
	 * Filters the post array before updates.
	 *
	 * Extending classes that need to perform some logic checks during updates
	 * should extend this method.
	 *
	 * @since 4.9.5
	 *
	 * @param array    $postarr The post array that will be sent to the update callback.
	 * @param int|null $post_id The ID  of the post that will be updated.
	 *
	 * @return array|false The filtered post array or `false` to indicate the
	 *                     update should not happen.
	 */
	public function filter_postarr_for_update( array $postarr, $post_id );

	/**
	 * Builds the post array that should be used to update or create a post of
	 * the type managed by the repository.
	 *
	 * @since 4.9.5
	 *
	 * @param int|null $id The post ID that's being updated or `null` to get the
	 *                     post array for a new post.
	 *
	 * @return array The post array ready to be passed to the `wp_update_post` or
	 *               `wp_insert_post` functions.
	 *
	 * @throws Tribe__Repository__Usage_Error If running an update and trying to update
	 *                                        a blocked field.
	 */
	public function build_postarr( $id = null );

	/**
	 * Filters the post array before creation.
	 *
	 * Extending classes that need to perform some logic checks during creations
	 * should extend this method.
	 *
	 * @since 4.9.5
	 *
	 * @param array $postarr The post array that will be sent to the creation callback.
	 *
	 * @return array|false The filtered post array or false to indicate creation should not
	 *                     proceed.
	 */
	public function filter_postarr_for_create( array $postarr );
}
