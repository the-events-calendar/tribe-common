<?php
/**
 * Interface Repository_Interface
 *
 * @since 6.10.0
 *
 * @package TEC\Common\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\Contracts;

use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;
use Tribe\Repository\Core_Read_Interface as Read_Interface;
use Tribe__Promise as Promise;
use Tribe__Repository__Setter_Interface as Setter_Interface;
use WP_Post;

/**
 * Interface Repository_Interface
 *
 * @since 6.10.0
 *
 * @package TEC\Common\Contracts
 */
interface Repository_Interface extends Setter_Interface, Read_Interface {
	/**
	 * Returns the current default query arguments of the repository.
	 *
	 * @since 6.10.0
	 *
	 * @return array
	 */
	public function get_default_args();

	/**
	 * Sets the default arguments of the repository.
	 *
	 * @since 6.10.0
	 *
	 * @param array $default_args The default arguments to set.
	 *
	 * @return void
	 */
	public function set_default_args( array $default_args );

	/**
	 * Adds an entry to the repository filter schema.
	 *
	 * @since 6.10.0
	 *
	 * @param string   $key      The filter key, the one that will be used in `by` and `where`
	 *                           calls.
	 * @param callable $callback The function that should be called to apply this filter.
	 *
	 * @return void
	 */
	public function add_schema_entry( $key, $callback );

	/**
	 * Sets the found rows calculation to be enabled for queries.
	 *
	 * @since 6.10.0
	 *
	 * @param bool $found_rows Whether found rows calculation should be enabled.
	 *
	 * @return self The repository instance, for chaining.
	 */
	public function set_found_rows( $found_rows );

	/**
	 * Fetches a single instance of the post type handled by the repository by
	 * the primary key.
	 *
	 * By default the primary key is the post ID.
	 *
	 * @since 6.10.0
	 *
	 * @param mixed $primary_key The primary key value.
	 *
	 * @return Model|null|WP_Post
	 */
	public function by_primary_key( $primary_key );

	/**
	 * Deletes a set of events fetched by using filters.
	 *
	 * @since 6.10.0
	 *
	 * @param bool $return_promise Whether to return the promise or just the deleted post IDs
	 *                             if the deletion happens in a background process; defaults
	 *                             to `false`.
	 *
	 * @return int[]|Promise An array of deleted post IDs, or that will be deleted in asynchronous
	 *                       mode or a promise object if `$return_promise` is set to `true`. The
	 *                       promise object will immediately execute its resolved or rejected callback
	 *                       if in synchronous mode.
	 */
	public function delete( bool $return_promise = false );

	/**
	 * Commits the updates to the selected post IDs to the database.
	 *
	 * @since 6.10.0
	 *
	 * @param bool $return_promise Whether to return a promise object or just the ids
	 *                             of the updated posts; if `true` then a promise will
	 *                             be returned whether the update is happening in background
	 *                             or not.
	 *
	 * @return array|Promise A list of the post IDs that have been (synchronous) or will
	 *                       be (asynchronous) updated if `$return_promise` is set to `false`;
	 *                       the Promise object if `$return_promise` is set to `true`.
	 */
	public function save( $return_promise = false );

	/**
	 * Adds an alias for an update/save field.
	 *
	 * @since 6.10.0
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
	 * @since 6.10.0
	 *
	 * @return array This repository update fields aliases map.
	 */
	public function get_update_fields_aliases();

	/**
	 * Replaces the update fields aliases for this repository.
	 *
	 * @since 6.10.0
	 *
	 * @param array $update_fields_aliases The new update fields aliases
	 *                                     map for this repository.
	 */
	public function set_update_fields_aliases( array $update_fields_aliases );

	/**
	 * Creates a post of the type managed by the repository with the fields
	 * provided using the `set` or `set_args` methods.
	 *
	 * @since 6.10.0
	 *
	 * @return WP_Post|Model|false The created post object or `false` if the creation
	 *                                  fails for logic or runtime issues.
	 *
	 * @throws RuntimeException If a relationship is not an array of integers or an integer.
	 */
	public function create();

	/**
	 * Sets the create args the repository will use to create posts.
	 *
	 * @since 6.10.0
	 *
	 * @param array $create_args The create args the repository will use to create posts.
	 *
	 * @return void
	 */
	public function set_create_args( array $create_args );

	/**
	 * Returns the create args the repository will use to create posts.
	 *
	 * @since 6.10.0
	 *
	 * @return array The create args the repository will use to create posts.
	 */
	public function get_create_args();
}
