<?php
/**
 * An extension of the base collection implementation to handle posts.
 *
 * @since 4.9.5
 */

use Tribe\Traits\With_Post_Attribute_Detection;

/**
 * Class Tribe__Utils__Post_Collection
 *
 * @since 4.9.5
 */
class Tribe__Utils__Post_Collection extends Tribe__Utils__Collection {
	use With_Post_Attribute_Detection;

	/**
	 * A list of the taxonomies supported by the post types in the collection.
	 *
	 * @since TBD
	 *
	 * @var array<string>
	 */
	protected $taxonomies;

	/**
	 * Tribe__Utils__Post_Collection constructor.
	 *
	 * Overrides the base constructor to ensure all elements in the collection are, in fact, posts.
	 * Elements that do not resolve to a post are discarded.
	 *
	 * @param array $items
	 */
	public function __construct( array $items ) {
		parent::__construct( array_filter( array_map( 'get_post', $items ) ) );
	}

	/**
	 * Plucks a post field, a taxonomy or a custom field from the collection.
	 *
	 * @since TBD
	 *
	 * @param string $key      The name of the field to pluck; the method will try to detect the type of field
	 *                         from its name. If any issues might arise due to fields of different types with the
	 *                         same name, then use the `pluck_<type>` methods directly.
	 * @param bool   $single   Whether to pluck a single taxonomy term or custom fields or an array of all the taxonomy
	 *                         terms or custom fields for each post.
	 * @param array  $args     A list of n optional arguments that will be passed down to the `pluck_<type>` methods.
	 *                         Currently only the the `pluck_taxonomy` will support one more argument to define the
	 *                         query arguments for the term query.
	 *
	 * @return array<string>|array<array> Either an array of plucked fields when plucking post fields or single
	 *                                    custom fields or taxonomy terms, or an array of arrays, each one a list
	 *                                    of all the taxonomy terms or custom fields entries for each post.
	 */
	public function pluck( $key, $single = true, ...$args ) {
		$type = $this->detect_field_type( $key );

		switch ( $type ) {
			case 'post_field':
				return $this->pluck_field( $key );
				break;
			case 'taxonomy':
				return $this->pluck_taxonomy( $key, $single, ...$args );
				break;
			default:
				return $this->pluck_meta( $key, $single );
				break;
		}
	}

	/**
	 * Detects the type of a post field from its name.
	 *
	 * @since TBD
	 *
	 * @param string $key The name of the field to check.
	 *
	 * @return string The type of field detected for the key, either `post_field`, `taxonomy` or `custom_field`.
	 */
	protected function detect_field_type( $key ) {
		if ( $this->is_a_post_field( $key ) ) {
			return 'post_field';
		}

		// Init taxonomies as late as possible and only once.
		$this->init_taxonomies();

		if ( $this->is_a_taxonomy( $key ) ) {
			return 'taxonomy';
		}

		return 'custom_field';
	}

	/**
	 * Initialize the post collection taxonomies by filling up the `$taxonomies` property.
	 *
	 * Note the collection will use the first post in the collection to fill the taxonomies array,
	 * this assumes the collection is homogeneous in its post types.
	 *
	 * @since TBD
	 */
	protected function init_taxonomies() {
		if ( ! empty( $this->taxonomies ) ) {
			// Already set up, return.
			return;
		}

		if ( empty( $this->items ) ) {
			// We cannot detect taxonomies from an empty list of items.
			$this->taxonomies = [];

			return;
		}

		// Use the first post to detect the taxonomies.
		$this->taxonomies = get_object_taxonomies( reset($this->items), 'names' );
	}

	/**
	 * Plucks a post field from all posts in the collection.
	 *
	 * Note: there is no check on the name of the plucked post field: if a non-existing post field is requested, then
	 * the method will return an empty array.
	 *
	 * @since TBD
	 *
	 * @param string $field The name of the post field to pluck.
	 *
	 * @return array<string> A list of the plucked post fields from each item in the collection.
	 */
	public function pluck_field( $field ) {
		return wp_list_pluck( $this->items, $field );
	}

	/**
	 * Plucks a meta key for all elements in the collection.
	 *
	 * Elements that are not posts or do not have the meta set will have an
	 * empty string value.
	 *
	 * @since 4.9.5
	 *
	 * @param string $meta_key The meta key to pluck.
	 * @param bool   $single   Whether to fetch the meta key as single or not.
	 *
	 * @return array An array of meta values for each item in the collection; items that
	 *               do not have the meta set or that are not posts, will have an empty
	 *               string value.
	 */
	public function pluck_meta( $meta_key, $single = true ) {
		$plucked = [];

		foreach ( $this as $item ) {
			$plucked[] = get_post_meta( $item->ID, $meta_key, $single );
		}

		return $plucked;
	}

	/**
	 * Plucks taxonomy terms assigned to the posts in the collection.
	 *
	 * Note: there is no check on the taxonomy being an existing one or not; that responsibility
	 * is on the user code.
	 *
	 * @since TBD
	 *
	 * @param string $taxonomy The name of the post taxonomy to pluck terms for.
	 * @param bool   $single   Whether to return only the first results or all of them.
	 * @param array  $args     A set of arguments as supported by the `WP_Term_Query::__construct` method.
	 *
	 * @return array<mixed>|array<array> Either an array of the requested results if `$single` is `true`
	 *                                   or an array of arrays if `$single` is `false`.
	 */
	public function pluck_taxonomy( $taxonomy, $single = true, array $args = [ 'fields' => 'names' ] ) {
		$plucked = [];

		if ( $single ) {
			// Let's avoid wasting queries.
			$args['limit'] = 1;
		}

		foreach ( $this as $item ) {
			$terms     = wp_get_object_terms( $item->ID, $taxonomy, $args );
			$plucked[] = $single ? reset( $terms ) : $terms;
		}

		return $plucked;
	}
}
