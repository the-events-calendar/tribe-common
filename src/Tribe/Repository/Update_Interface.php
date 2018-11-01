<?php

/**
 * Interface Tribe__Repository__Update_Interface
 *
 * @since 4.7.19
 */
interface Tribe__Repository__Update_Interface {
	/**
	 * Sets a key on the posts to update using a value or a callback.
	 *
	 * The callback method will be passed the post ID, the `$key` and
	 * the Update repository instance.
	 * The update will check, in order, if the key is a post table field,
	 * a taxonomy and will, finally, set on a custom field.
	 * Updates to the same key will not stack.
	 *
	 * @since 4.7.19
	 *
	 * @since 4.7.19
	 *
	 * @param string         $key
	 * @param mixed|callable $value
	 *
	 * @return Tribe__Repository__Update_Interface
	 * @throws Tribe__Repository__Usage_Error If $key is not a string
	 */
	public function set( $key, $value );

	/**
	 * Sets updates in bulk using a map.
	 *
	 * Updates to the same key will not stack.
	 *
	 * @since 4.7.19
	 *
	 * @param array $update_map A map relating update keys to values.
	 *
	 * @return Tribe__Repository__Update_Interface
	 * @throws Tribe__Repository__Usage_Error If not all keys are strings.
	 *
	 * @see   the `set` method
	 */
	public function set_args( array $update_map );

	/**
	 * Commits the updates to the selected post IDs to the database.
	 *
	 * @since 4.7.19
	 *
	 * @param bool $return_promise Whether to return a promise object or just the ids
	 *                             of the updated posts; if `true` then a promise will
	 *                             be returned whether the update is happening in background
	 *                             or not.
	 *
	 * @return array|Tribe__Promise A list of the post IDs that have been (synchronous) or will
	 *               be (asynchronous) updated if `$return_promise` is set to `false`;
	 *               the Promise object if `$return_promise` is set to `true`.
	 */
	public function save( $return_promise = false );

	/**
	 * Adds an alias for an update/save field.
	 *
	 * @since TBD
	 *
	 * @param string $alias The alias to add.
	 * @param string $field_name The field name this alias should resolve to, this
	 *                           can be posts table field, a taxonomy name or a custom
	 *                           field.
	 */
	public function add_update_field_alias( $alias, $field_name );

	/**
	 * Returns the update fields aliases for the repository.
	 *
	 * @since TBD
	 *
	 * @return array This repository update fields aliases map.
	 */
	public function get_update_fields_aliases();

	/**
	 * Replaces the update fields aliases for this repository.
	 *
	 * @since TBD
	 *
	 * @param array $update_fields_aliases The new update fields aliases
	 *                                     map for this repository.
	 */
	public function set_update_fields_aliases( array $update_fields_aliases );

	/**
	 * Filters the post array before updates.
	 * * Extending classes that need to perform some logic checks during updates
	 * should extend this method.
	 *
	 * @since TBD
	 *
	 * @param array    $postarr The post array that will be sent to the update callback.
	 * @param int|null $post_id The ID  of the post that will be updated.
	 *
	 * @return array The filtered post array.
	 */
	public function filter_postarr_for_update( array $postarr, $post_id );
}