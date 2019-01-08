<?php

abstract class Tribe__Repository
	implements Tribe__Repository__Interface {

	/**
	 * @var  array An array of keys that cannot be updated on this repository.
	 */
	protected static $blocked_keys = array(
		'ID',
		'post_type',
		'post_modified',
		'post_modified_gmt',
		'guid',
		'comment_count',
	);

	/**
	 * @var array A list of the default filters supported and implemented by the repository.
	 */
	protected static $default_modifiers = array(
		'p',
		'author',
		'author_name',
		'author__in',
		'author__not_in',
		'has_password',
		'post_password',
		'cat',
		'category__and',
		'category__in',
		'category__not_in',
		'category_name',
		'comment_count',
		'comment_status',
		'menu_order',
		'title',
		'title_like',
		'name',
		'post_name__in',
		'ping_status',
		'post__in',
		'post__not_in',
		'post_parent',
		'post_parent__in',
		'post_parent__not_in',
		'post_mime_type',
		's',
		'search',
		'tag',
		'tag__and',
		'tag__in',
		'tag__not_in',
		'tag_id',
		'tag_slug__and',
		'tag_slug__in',
		'ID',
		'id',
		'date',
		'after_date',
		'before_date',
		'date_gmt',
		'after_date_gmt',
		'before_date_gmt',
		'post_title',
		'post_content',
		'post_excerpt',
		'post_status',
		'to_ping',
		'post_modified',
		'post_modified_gmt',
		'post_content_filtered',
		'guid',
		'perm',
		'meta',
		'meta_equals',
		'meta_not_equals',
		'meta_gt',
		'meta_greater_than',
		'meta_gte',
		'meta_greater_than_or_equal',
		'meta_like',
		'meta_not_like',
		'meta_lt',
		'meta_less_than',
		'meta_lte',
		'meta_less_than_or_equal',
		'meta_in',
		'meta_not_in',
		'meta_between',
		'meta_not_between',
		'meta_exists',
		'meta_not_exists',
		'meta_regexp',
		'meta_equals_regexp',
		'meta_not_regexp',
		'meta_not_equals_regexp',
		'taxonomy_exists',
		'taxonomy_not_exists',
		'term_id_in',
		'term_id_not_in',
		'term_id_and',
		'term_name_in',
		'term_name_not_in',
		'term_name_and',
		'term_slug_in',
		'term_slug_not_in',
		'term_slug_and',
	);

	/**
	 * @var array An array of default arguments that will be applied to all queries.
	 */
	protected static $common_args = array(
		'post_type'        => 'post',
		'suppress_filters' => false,
		'posts_per_page'   => - 1,
	);

	/**
	 * @var array A list of query modifiers that will trigger a overriding merge, thus
	 *            replacing previous values, when set multiple times.
	 */
	protected static $replacing_modifiers = array(
		'p',
		'author',
		'author_name',
		'author__in',
		'author__not_in',
		'has_password',
		'post_password',
		'cat',
		'category__and',
		'category__in',
		'category__not_in',
		'category_name',
		'comment_count',
		'comment_status',
		'menu_order',
		'title',
		'title_like',
		'name',
		'post_name__in',
		'ping_status',
		'post__in',
		'post__not_in',
		'post_parent',
		'post_parent__in',
		'post_parent__not_in',
		'post_mime_type',
		's',
		'search',
		'tag',
		'tag__and',
		'tag__in',
		'tag__not_in',
		'tag_id',
		'tag_slug__and',
		'tag_slug__in',
		'ID',
		'id',
		'date',
		'after_date',
		'before_date',
		'date_gmt',
		'after_date_gmt',
		'before_date_gmt',
		'post_title',
		'post_content',
		'post_excerpt',
		'post_status',
		'to_ping',
		'post_modified',
		'post_modified_gmt',
		'post_content_filtered',
		'guid',
		'perm',
	);

	/**
	 * @var int
	 */
	protected static $meta_alias = 0;
	/**
	 * @var array A list of keys that denote the value to check should be cast to array.
	 */
	protected static $multi_value_keys = array( 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' );
	/**
	 * @var array A map of SQL comparison operators to their human-readable counterpart.
	 */
	protected static $comparison_operators = array(
		'='           => 'equals',
		'!='          => 'not-equals',
		'>'           => 'gt',
		'>='          => 'gte',
		'<'           => 'lt',
		'<='          => 'lte',
		'LIKE'        => 'like',
		'NOT LIKE'    => 'not-like',
		'IN'          => 'in',
		'NOT IN'      => 'not-in',
		'BETWEEN'     => 'between',
		'NOT BETWEEN' => 'not-between',
		'EXISTS'      => 'exists',
		'NOT EXISTS'  => 'not-exists',
		'REGEXP'      => 'regexp',
		'NOT REGEXP'  => 'not-regexp',
	);
	/**
	 * @var string
	 */
	protected $filter_name = 'default';
	/**
	 * @var array The post IDs that will be updated.
	 */
	protected $ids = array();
	/**
	 * @var bool Whether the post IDs to update have already been fetched or not.
	 */
	protected $has_ids = false;
	/**
	 * @var array The updates that will be saved to the database.
	 */
	protected $updates = array();
	/**
	 * @var array A list of taxonomies this repository will recognize.
	 */
	protected $taxonomies = array();
	/**
	 * @var array A map detailing which fields should be converted from a
	 *            GMT time and date to a local one.
	 */
	protected $to_local_time_map = array(
		'post_date_gmt' => 'post_date',
	);
	/**
	 * @var array A map detailing which fields should be converted from a
	 *            localized time and date to a GMT one.
	 */
	protected $to_gmt_map = array(
		'post_date' => 'post_date_gmt',
	);
	/**
	 * @var array
	 */
	protected $default_args = array( 'post_type' => 'post' );
	/**
	 * @var array An array of query modifying callbacks populated while applying
	 *            the filters.
	 */
	protected $query_modifiers = array();
	/**
	 * @var bool Whether the current query is void or not.
	 */
	protected $void_query = false;
	/**
	 * @var array An array of query arguments that will be populated while applying
	 *            filters.
	 */
	protected $query_args = array(
		'meta_query' => array( 'relation' => 'AND' ),
		'tax_query'  => array( 'relation' => 'AND' ),
	);
	/**
	 * @var WP_Query The current query object built and modified by the instance.
	 */
	protected $current_query;
	/**
	 * @var array An associative array of the filters that will be applied and the used values.
	 */
	protected $current_filters = array();
	/**
	 * @var Tribe__Repository__Query_Filters
	 */
	public $filter_query;
	/**
	 * @var string The filter that should be used to get a post by its primary key.
	 */
	protected $primary_key = 'p';
	/**
	 * @var array A map of callbacks in the shape [ <slug> => <callback|primitive> ]
	 */
	protected $schema = array();
	/**
	 * @var Tribe__Repository__Interface
	 */
	protected $main_repository;
	/**
	 * @var Tribe__Repository__Formatter_Interface
	 */
	protected $formatter;
	/**
	 * @var bool
	 */
	protected $skip_found_rows = true;

	/**
	 * @var Tribe__Repository__Interface
	 */
	protected $query_builder;

	/**
	 * Tribe__Repository constructor.
	 *
	 * @since 4.7.19
	 */
	public function __construct() {
		$this->filter_query = new Tribe__Repository__Query_Filters();
		$this->default_args = array_merge( array( 'posts_per_page' => - 1 ), $this->default_args );
		$post_types         = (array) Tribe__Utils__Array::get( $this->default_args, 'post_type', array() );
		$this->taxonomies   = get_taxonomies( array( 'object_type' => $post_types ), 'names' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_args() {
		return $this->default_args;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_default_args( array $default_args ) {
		$this->default_args = $default_args;
	}

	/**
	 * Returns the value of a protected property.
	 *
	 * @since 4.7.19
	 *
	 * @param string $name
	 *
	 * @return mixed|null
	 * @throws Tribe__Repository__Usage_Error If trying to access a non defined property.
	 */
	public function __get( $name ) {
		if ( ! property_exists( $this, $name ) ) {
			throw Tribe__Repository__Usage_Error::because_property_is_not_defined( $name, $this );
		}

		return $this->{$name};
	}

	/**
	 * Magic method to set protected properties.
	 *
	 * @since 4.7.19
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @throws Tribe__Repository__Usage_Error As properties have to be set extending
	 * the class, using setter methods or via constructor injection
	 */
	public function __set( $name, $value ) {
		throw Tribe__Repository__Usage_Error::because_properties_should_be_set_correctly( $name, $this );
	}

	/**
	 * Whether the class has a property with the specific name or not.
	 *
	 * @since 4.7.19
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset( $name ) {
		return property_exists( $this, $name ) && isset( $this->{$name} );
	}

	/**
	 * {@inheritdoc}
	 */
	public function where( $key, $value ) {
		$call_args = func_get_args();
		return call_user_func_array( array( $this, 'by' ), $call_args );
	}

	/**
	 * {@inheritdoc}
	 */
	public function page( $page ) {
		$this->query_args['paged'] = absint( $page );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function per_page( $per_page ) {
		// we allow for `-1` here
		$this->query_args['posts_per_page'] = $per_page;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		if ( $this->void_query ) {
			return 0;
		}

		$query = $this->build_query();
		$query->set( 'fields', 'ids' );

		/**
		 * Filters the query object by reference before counting found posts in the current page.
		 *
		 * @since 4.7.19
		 *
		 * @param WP_Query $query
		 */
		do_action_ref_array( "{$this->filter_name}_pre_count_posts", array( &$query ) );

		$ids = $query->get_posts();

		return is_array( $ids ) ? count( $ids ) : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function build_query() {
		/**
		 * Allow classes extending or decorating the repository to act before
		 * the query is built or replace its building completely.
		 */
		if ( null !== $this->query_builder ) {
			$built = $this->query_builder->build_query();

			if ( null !== $built ) {
				return $built;
			}
		}

		$query = new WP_Query();

		$this->filter_query->set_query( $query );

		/**
		 * Here we merge, not recursively, to allow user-set query arguments
		 * to override the default ones.
		 */
		$query_args = array_merge( $this->default_args, $this->query_args );

		$default_post_status       = current_user_can( 'read_private_posts' ) ? 'any' : '';
		$query_args['post_status'] = Tribe__Utils__Array::get( $query_args, 'post_status', $default_post_status );

		/**
		 * Filters the query arguments that will be used to fetch the posts.
		 *
		 * @param array    $query_args An array of the query arguments the query will be
		 *                             initialized with.
		 * @param WP_Query $query      The query object, the query arguments have not been parsed yet.
		 * @param          $this       $this This repository instance
		 */
		$query_args = apply_filters( "{$this->filter_name}_query_args", $query_args, $query, $this );

		if ( isset( $query_args['offset'] ) ) {
			$offset   = absint( $query_args['offset'] );
			$per_page = (int) Tribe__Utils__Array::get( $query_args, 'posts_per_page', get_option( 'posts_per_page' ) );
			$page     = (int) Tribe__Utils__Array::get( $query_args, 'paged', 1 );

			$real_offset                  = $per_page === - 1 ? $offset : ( $per_page * ( $page - 1 ) ) + $offset;
			$query_args['offset']         = $real_offset;
			$query_args['posts_per_page'] = $per_page === - 1 ? 99999999999 : $per_page;

			/**
			 * Unset the `offset` query argument to avoid applying it multiple times when this method
			 * is used, on the same repository, more than once.
			 */
			unset( $this->query_args['offset'] );
		}

		foreach ( $query_args as $key => $value ) {
			$query->set( $key, $value );
		}

		/**
		 * Here process the previously set query modifiers passing them the
		 * query object before it executes.
		 * The query modifiers should modify the query by reference.
		 */
		foreach ( $this->query_modifiers as $arg ) {
			if ( is_object( $arg ) ) {
				// __invoke, assume changes are made by reference
				$arg( $query );
			} elseif ( is_callable( $arg ) ) {
				// assume changes are made by reference
				$arg( $query );
			}
		}

		return $query;
	}

	/**
	 * {@inheritdoc}
	 */
	public function found() {
		if ( $this->void_query ) {
			return 0;
		}

		$query = $this->build_query();
		$query->set( 'fields', 'ids' );

		/**
		 * Filters the query object by reference before counting found posts.
		 *
		 * @since 4.7.19
		 *
		 * @param WP_Query $query
		 */
		do_action_ref_array( "{$this->filter_name}_pre_found_posts", array( &$query ) );

		$query->get_posts();

		return (int) $query->found_posts;
	}

	/**
	 * {@inheritdoc}
	 */
	public function all() {
		if ( $this->void_query ) {
			return array();
		}

		$query = $this->build_query();

		$return_ids = 'ids' === $query->get( 'fields', '' );

		/**
		 * Do not skip counting the rows if we have some filtering to do on
		 * `found_posts`.
		 */
		$query->set( 'no_found_rows', $this->skip_found_rows );
		// we'll let the class build the items later
		$query->set( 'fields', 'ids' );

		/**
		 * Filters the query object by reference before getting the posts.
		 *
		 * @since 4.7.19
		 *
		 * @param WP_Query $query
		 */
		do_action_ref_array( "{$this->filter_name}_pre_get_posts", array( &$query ) );

		$results = $query->get_posts();

		/**
		 * Allow extending classes to customize the return value.
		 * Since we are filtering the array returning empty values while formatting
		 * the item will exclude it from the return values.
		 */
		return $return_ids
			? $results
			: array_filter( array_map( array( $this, 'format_item' ), $results ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function offset( $offset, $increment = false ) {
		/**
		 * The `offset` argument will only be used when `posts_per_page` is not -1
		 * and will ignore pagination.
		 * So we filter to apply a real SQL OFFSET; we also leave in place the `offset`
		 * query var to have a fallback should the LIMIT cause proving difficult to filter.
		 */
		$this->query_args['offset'] = $increment
			? absint( $offset ) + (int) Tribe__Utils__Array::get( $this->query_args, 'offset', 0 )
			: absint( $offset );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function order( $order = 'ASC' ) {
		$order = strtoupper( $order );

		if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {
			return $this;
		}

		$this->query_args['order'] = $order;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function order_by( $order_by ) {
		$this->query_args['orderby'] = $order_by;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function fields( $fields ) {
		$this->query_args['fields'] = $fields;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function permission( $permission ) {
		if ( ! in_array( $permission, array( self::PERMISSION_READABLE, self::PERMISSION_EDITABLE ), true ) ) {
			return $this;
		}

		$this->query_args['perm'] = $permission;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function in( $post_ids ) {
		$this->add_args( 'post__in', $post_ids );

		return $this;
	}

	/**
	 * Merges arguments into a query arg.
	 *
	 * @since 4.7.19
	 *
	 * @param string    $key
	 * @param array|int $value
	 */
	protected function add_args( $key, $value ) {
		$this->query_args[ $key ] = (array) $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function not_in( $post_ids ) {
		$this->add_args( 'post__not_in', $post_ids );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parent( $post_id ) {
		$this->add_args( 'post_parent__in', $post_id );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parent_in( $post_ids ) {
		$this->add_args( 'post_parent__in', $post_ids );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parent_not_in( $post_ids ) {
		$this->add_args( 'post_parent__not_in', $post_ids );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function search( $search ) {
		$this->query_args['s'] = $search;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function first() {
		$query     = $this->build_query();
		$return_id = 'ids' === $query->get( 'fields', '' );
		$query->set( 'fields', 'ids' );
		$ids = $query->get_posts();

		if ( empty( $ids ) ) {
			return null;
		}

		return $return_id ? reset( $ids ) : $this->format_item( reset( $ids ) );
	}

	/**
	 * Formats a post handled by the repository to the expected
	 * format.
	 *
	 * Extending classes should use this method to format return values to the expected format.
	 *
	 * @since 4.7.19
	 *
	 * @param int|WP_Post $id
	 *
	 * @return WP_Post
	 */
	protected function format_item( $id ) {
		return null === $this->formatter
			? get_post( $id )
			: $this->formatter->format_item( $id );
	}

	/**
	 * {@inheritdoc}
	 */
	public function last() {
		$query     = $this->build_query();
		$return_id = 'ids' === $query->get( 'fields', '' );
		$query->set( 'fields', 'ids' );
		$ids = $query->get_posts();

		if ( empty( $ids ) ) {
			return null;
		}

		return $return_id ? end( $ids ) : $this->format_item( end( $ids ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function nth( $n ) {
		$per_page = (int) Tribe__Utils__Array::get_in_any( array(
			$this->query_args,
			$this->default_args,
		), 'posts_per_page', get_option( 'posts_per_page' ) );

		if ( - 1 != $per_page && $n > $per_page ) {
			return null;
		}

		$query = $this->build_query();

		$return_id = 'ids' === $query->get( 'fields', '' );

		$i = absint( $n ) - 1;
		$query->set( 'fields', 'ids' );
		$ids = $query->get_posts();

		if ( empty( $ids[ $i ] ) ) {
			return null;
		}

		return $return_id ? $ids[ $i ] : $this->format_item( $ids[ $i ] );
	}

	/**
	 * Applies and returns a schema entry.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param mixed  ...$args Additional arguments for the application.
	 *
	 * @return mixed A scalar value or a callable.
	 */
	public function apply_modifier( $key, $value ) {
		$call_args = func_get_args();

		$application = Tribe__Utils__Array::get( $this->schema, $key, null );

		/**
		 * Return primitives, including `null`, as they are.
		 */
		if ( ! is_callable( $application ) ) {
			return $application;
		}

		/**
		 * Allow for callbacks to fire immediately and return more complex values.
		 * This also means that callbacks meant to run on the next step, the one
		 * where args are applied, will need to be "wrapped" in callbacks themselves.
		 * The `$key` is removed from the args to get the value first and avoid
		 * unused args.
		 */
		$args_without_key = array_splice( $call_args, 1 );

		return call_user_func_array( $application, $args_without_key );
	}

	/**
	 * {@inheritdoc}
	 */
	public function take( $n ) {
		$query     = $this->build_query();
		$return_id = 'ids' === $query->get( 'fields', '' );
		$query->set( 'fields', 'ids' );
		$matching_ids = $query->get_posts();

		if ( empty( $matching_ids ) ) {
			return array();
		}

		$spliced = array_splice( $matching_ids, 0, $n );

		return $return_id ? $spliced : array_map( array( $this, 'format_item' ), $spliced );
	}

	/**
	 * Fetches a single instance of the post type handled by the repository.
	 *
	 * Similarly to the `get_post` function permissions are not taken into account when returning
	 * an instance by its primary key; extending classes can refine this behaviour to suit.
	 *
	 * @param mixed $primary_key
	 *
	 * @return WP_Post|null|mixed
	 */
	public function by_primary_key( $primary_key ) {
		return $this->by( $this->primary_key, $primary_key )->first();
	}

	/**
	 * {@inheritdoc}
	 */
	public function by( $key, $value ) {
		if ( $this->void_query ) {
			// No point in doing more computations if the query is void.
			return $this;
		}

		$call_args = func_get_args();

		$this->current_filters[ $key ] = $value;

		try {
			$query_modifier = $this->modify_query( $key, $call_args );

			/**
			 * Here we allow the repository to call one of its own methods and return `null`.
			 * A repository might have a `where` or `by` that is just building
			 * a more complex query using a base `where` or `by`.
			 */
			if ( null === $query_modifier ) {
				return $this;
			}

			/**
			 * Primitives are just merged in.
			 * Since we are using `array_merge_recursive` we expect them to be arrays.
			 */
			if ( ! ( is_object( $query_modifier ) || is_callable( $query_modifier ) ) ) {

				if ( ! is_array( $query_modifier ) ) {
					throw new InvalidArgumentException( 'Query modifier should be an array!' );
				}

				$replace_modifiers = in_array( $key, $this->replacing_modifiers(), true );
				if ( $replace_modifiers ) {
					/**
					 * We do a merge to make sure new values will override and replace the old
					 * ones.
					 */
					$this->query_args = array_merge( $this->query_args, $query_modifier );
				} else {
					/**
					 * We do a recursive merge to allow "stacking" of same kind of queries;
					 * e.g. two or more `tax_query`.
					 */
					$this->query_args = array_merge_recursive( $this->query_args, $query_modifier );
				}
			} else {
				/**
				 * If we get back something that is not an array then we add it to
				 * the stack of query modifying callbacks we'll call on the query
				 * after building it.
				 */
				$this->query_modifiers[] = $query_modifier;
			}
		} catch ( Tribe__Repository__Void_Query_Exception $e ) {
			/**
			 * We allow for the `apply` method to orderly fail to micro-optimize.
			 * If applying one parameter would yield no results then let's immediately bail.
			 * Schema should throw t
			 * his Exception if a light-weight on the filters would already
			 * deem a query as yielding nothing.
			 */
			$this->void_query = true;

			return $this;
		}

		/**
		 * Catching other type of exceptions is something the client code should handle!
		 */

		return $this;
	}

	/**
	 * Returns the query modifier for a key.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 * @param array  $call_args
	 *
	 * @return mixed
	 *
	 * @throws Tribe__Repository__Usage_Error If the required filter is not defined by the class.
	 * @throws Tribe__Repository__Void_Query_Exception To signal the query would yield no results.
	 */
	protected function modify_query( $key, $call_args ) {
		if ( ! $this->schema_has_modifier_for( $key ) ) {
			if ( $this->has_default_modifier( $key ) ) {
				// let's use the default filters normalizing the key first
				$call_args[0]   = $this->normalize_key( $key );
				$query_modifier = call_user_func_array( array( $this, 'apply_default_modifier' ), $call_args );
			} else {
				throw Tribe__Repository__Usage_Error::because_the_read_filter_is_not_defined( $key, $this );
			}
		} else {
			$query_modifier = call_user_func_array( array( $this, 'apply_modifier' ), $call_args );
		}

		return $query_modifier;
	}

	/**
	 * Whether the current schema defines an application for the key or not.
	 *
	 * @since 4.7.19
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	protected function schema_has_modifier_for( $key ) {
		return isset( $this->schema[ $key ] );
	}

	/**
	 * Whether a filter defined and handled by the repository exists or not.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function has_default_modifier( $key ) {
		$normalized_key = $this->normalize_key( $key );

		return in_array( $normalized_key, self::$default_modifiers, true );
	}

	/**
	 * Normalizes the filter key to allow broad matching of the `by` filters.
	 *
	 * @since 4.7.19
	 *
	 * E.g. `by( 'id', 23 )` is the same as `by( 'ID', 23 ).
	 * E.g. `by( 'parent', 23 )` is the same as `by( `post_parent`, 23 )`
	 *
	 * @param string $key
	 *
	 * @return string The normalized filter key
	 */
	protected function normalize_key( $key ) {
		// `ID` to `id`
		$normalized = strtolower( $key );

		$post_prefixed = array(
			'password',
			'name__in',
			'_in',
			'_not_in',
			'parent',
			'parent__in',
			'parent__not_in',
			'mime_type',
			'content',
			'excerpt',
			'status',
			'modified',
			'modified_gmt',
			'content_filtered',
		);

		if ( in_array( $key, $post_prefixed, true ) ) {
			$normalized = 'post_' . $key;
		}

		return $normalized;
	}

	/**
	 * Returns a list of modifiers that, when applied multiple times,
	 * will replace the previous value.
	 *
	 * This behaviour is in opposition to "stackable" modifiers that will,
	 * instead, be composed and stacked.
	 *
	 * @since 4.7.19
	 *
	 * @return array
	 */
	protected function replacing_modifiers() {
		return self::$replacing_modifiers;
	}

	/**
	 * Batch filter application method.
	 *
	 * This is the same as calling `where` multiple times with different arguments.
	 *
	 * @since 4.7.19
	 *
	 * @param array $args An associative array of arguments to filter
	 *                    the posts by in the shape [ <key>, <value> ].
	 *
	 * @return Tribe__Repository__Read_Interface|Tribe__Repository__Update_Interface
	 */
	public function where_args( array $args ) {
		return $this->by_args( $args );
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_args( array $args ) {
		foreach ( $args as $key => $value ) {
			$this->by( $key, $value );
		}

		return $this;
	}

	/**
	 * Commits the updates to the selected post IDs to the database.
	 *
	 * @since 4.7.19
	 *
	 * @param bool $sync Whether to apply the updates in a synchronous process
	 *                   or in an asynchronous one.
	 *
	 * @return array A list of the post IDs that have been (synchronous) or will
	 *               be (asynchronous) updated. When running in sync mode the return
	 *               value will be a map in the shape [ <id> => <update_result> ] where
	 *               `true` indicates a correct update.
	 *
	 * @throws Tribe__Repository__Usage_Error If trying to update a field that cannot be
	 *                                        updated.
	 */
	public function save( $sync = true ) {
		$ids = $this->get_ids();

		if ( empty( $ids ) ) {
			return array();
		}

		$exit     = array();
		$postarrs = array();

		foreach ( $ids as $id ) {
			$postarr = array(
				'ID'         => $id,
				'tax_input'  => array(),
				'meta_input' => array(),
			);

			foreach ( $this->updates as $key => $value ) {
				if ( is_callable( $value ) ) {
					$value = $value( $id, $key, $this );
				}

				if ( ! $this->can_be_udpated( $key ) ) {
					throw Tribe__Repository__Usage_Error::because_this_field_cannot_be_updated( $key, $this );
				}

				if ( $this->is_a_post_field( $key ) ) {
					if ( $this->requires_converted_date( $key ) ) {
						$this->update_postarr_dates( $key, $value, $postarr );
					} else {
						$postarr[ $key ] = $value;
					}
				} elseif ( $this->is_a_taxonomy( $key ) ) {
					$postarr['tax_input'][ $key ] = $value;
				} else {
					// it's a custom field
					$postarr['meta_input'][ $key ] = $value;
				}
			}

			$postarrs[ $id ] = $postarr;
		}

		// @todo actually implement async

		foreach ( $postarrs as $id => $postarr ) {
			$this_exit   = wp_update_post( $postarr );
			$exit[ $id ] = $id === $this_exit ? true : $this_exit;
		}

		return $exit;
	}

	/**
	 * Gets the post IDs that should be updated.
	 *
	 * @since 4.7.19
	 *
	 * @return array An array containing the post IDs to update.
	 */
	protected function get_ids() {
		/** @var WP_Query $query */
		$query = $this->get_query();
		$query->set( 'fields', 'ids' );

		return $query->get_posts();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query() {
		return $this->build_query();
	}

	/**
	 * Whether the current key can be updated by this repository or not.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function can_be_udpated( $key ) {
		return ! in_array( $key, self::$blocked_keys, true );
	}

	/**
	 * Whether the key is a field of the posts table or not.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function is_a_post_field( $key ) {
		return in_array( $key, array(
			'ID',
			'post_author',
			'post_date',
			'post_date_gmt',
			'post_content',
			'post_title',
			'post_excerpt',
			'post_status',
			'comment_status',
			'ping_status',
			'post_password',
			'post_name',
			'to_ping',
			'pinged',
			'post_modified',
			'post_modified_gmt',
			'post_content_filtered',
			'post_parent',
			'guid',
			'menu_order',
			'post_type',
			'post_mime_type',
			'comment_count',
		), true );
	}

	/**
	 * Whether the current key is a date one requiring a converted key pair too or not.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function requires_converted_date( $key ) {
		return array_key_exists( $key, $this->to_local_time_map ) || array_key_exists( $key, $this->to_gmt_map );
	}

	/**
	 * Updates the update post payload to add dates that should be provided in GMT
	 * and localized version.
	 *
	 * @since 4.7.19
	 *
	 * @param       string     $key
	 * @param       string|int $value
	 * @param array            $postarr
	 */
	protected function update_postarr_dates( $key, $value, array &$postarr ) {
		if ( array_key_exists( $key, $this->to_gmt_map ) ) {
			$postarr[ $this->to_gmt_map[ $key ] ] = Tribe__Timezones::to_tz( $value, 'UTC' );
		} elseif ( array_key_exists( $key, $this->to_local_time_map ) ) {
			$postarr[ $this->to_local_time_map[ $key ] ] = Tribe__Timezones::to_tz( $value, Tribe__Timezones::wp_timezone_string() );
		}
		$postarr[ $key ] = $value;
	}

	/**
	 * Whether the current key identifies one of the supported taxonomies or not.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function is_a_taxonomy( $key ) {
		return in_array( $key, $this->taxonomies, true );
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_args( array $update_map ) {
		foreach ( $update_map as $key => $value ) {
			$this->set( $key, $value );
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set( $key, $value ) {
		if ( ! is_string( $key ) ) {
			throw Tribe__Repository__Usage_Error::because_udpate_key_should_be_a_string( $this );
		}

		$this->updates[ $key ] = $value;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function filter_name( $filter_name ) {
		$this->filter_name = trim( $filter_name );

		return $this;

	}

	/**
	 * {@inheritdoc}
	 */
	public function set_formatter( Tribe__Repository__Formatter_Interface $formatter ) {
		$this->formatter = $formatter;
	}

	/**
	 * Filters the query to only return posts that are related, via a meta key, to posts
	 * that satisfy a condition.
	 *
	 * @param string|array $meta_keys One ore more `meta_keys` relating the queried post type(s)
	 *                                to another post type.
	 * @param string       $compare   The SQL compoarison operator.
	 * @param string       $field     One (a column in the `posts` table) that should match
	 *                                the comparison criteria; required if the comparison operator is not `EXISTS` or
	 *                                `NOT EXISTS`.
	 * @param string|array $values    One or more values the post field(s) should be compared to;
	 *                                required if the comparison operator is not `EXISTS` or `NOT EXISTS`.
	 *
	 * @return $this
	 * @throws Tribe__Repository__Usage_Error If the comparison operator requires
	 */
	public function where_meta_related_by( $meta_keys, $compare, $field = null, $values = null ) {
		$meta_keys = Tribe__Utils__Array::list_to_array( $meta_keys );

		if ( ! in_array( $compare, array( 'EXISTS', 'NOT EXISTS' ) ) ) {
			if ( empty( $field ) || empty( $values ) ) {
				throw Tribe__Repository__Usage_Error::because_this_comparison_operator_requires_fields_and_values( $meta_keys, $compare, $this );
			}
			$field = esc_sql( $field );
		}

		/** @var wpdb $wpdb */
		global $wpdb;
		$p  = $this->sql_slug( 'meta_related_post', $compare, $meta_keys );
		$pm = $this->sql_slug( 'meta_related_post_meta', $compare, $meta_keys );

		$this->filter_query->join( "LEFT JOIN {$wpdb->postmeta} {$pm} ON {$wpdb->posts}.ID = {$pm}.post_id" );
		$this->filter_query->join( "LEFT JOIN {$wpdb->posts} {$p} ON {$pm}.meta_value = {$p}.ID" );

		$keys_in = $this->prepare_interval( $meta_keys );

		if ( 'EXISTS' === $compare ) {
			$this->filter_query->where( "{$pm}.meta_key IN {$keys_in} AND {$pm}.meta_id IS NOT NULL" );
		} elseif ( 'NOT EXISTS' === $compare ) {
			$this->filter_query->where( "{$pm}.meta_id IS NULL" );
		} else {
			if ( in_array( $compare, self::$multi_value_keys, true ) ) {
				$values = $this->prepare_interval( $values );
			} else {
				$values = $this->prepare_value( $values );
			}
			$this->filter_query->where( "{$pm}.meta_key IN {$keys_in} AND {$p}.{$field} {$compare} {$values}" );
		}

		return $this;
	}

	/**
	 * Builds a fenced group of WHERE clauses that will be used with OR logic.
	 *
	 * Mind that this is a lower level implementation of WHERE logic that requires
	 * each callback method to add, at least, one WHERE clause using the repository
	 * own `where_clause` method.
	 *
	 * @param array $callbacks       One or more WHERE callbacks that will be called
	 *                                this repository. The callbacks have the shape
	 *                                [ <method>, <...args>]
	 *
	 * @return $this
	 * @throws Tribe__Repository__Usage_Error If one of the callback methods does
	 *                                        not add any WHERE clause.
	 *
	 * @see Tribe__Repository::where_clause()
	 * @see Tribe__Repository__Query_Filters::where()
	 */
	public function where_or( $callbacks ) {
		$callbacks = func_get_args();
		$buffered       = $this->filter_query->get_buffered_where_clauses( true );
		$this->filter_query->buffer_where_clauses( true );
		$buffered_count = count( $buffered );

		foreach ( $callbacks as $c ) {
			call_user_func_array( array( $this, $c[0] ), array_slice( $c, 1 ) );

			if ( $buffered_count === count( $this->filter_query->get_buffered_where_clauses() ) ) {
				throw Tribe__Repository__Usage_Error::because_where_or_should_only_be_used_with_methods_that_add_where_clauses( $c, $this );
			}

			$buffered_count ++;
		}

		$buffered       = $this->filter_query->get_buffered_where_clauses( true );

		$fenced = sprintf( '( %s )', implode( ' OR ', $buffered ) );

		$this->where_clause( $fenced );

		return $this;
	}

	/**
	 * Returns modified query arguments after applying a default filter.
	 *
	 * @since 4.7.19
	 *
	 * @param      string $key
	 * @param      mixed  $value
	 *
	 * @return array
	 * @throws Tribe__Repository__Usage_Error If a filter is called with wrong arguments.
	 */
	protected function apply_default_modifier( $key, $value ) {
		$args = array();

		$call_args = func_get_args();
		$arg_1     = isset( $call_args[2] ) ? $call_args[2] : null;
		$arg_2     = isset( $call_args[3] ) ? $call_args[3] : null;

		/** @var wpdb $wpdb */
		global $wpdb;

		switch ( $key ) {
			default:
				// leverage built-in WP_Query filters
				$args = array( $key => $value );
				break;
			case 'ID':
			case 'id':
				$args = array( 'p' => $value );
				break;
			case 'search':
				$args = array( 's' => $value );
				break;
			case 'post_status':
				$this->query_args['post_status'] = (array) $value;
				break;
			case 'date':
			case 'after_date':
				$args = $this->get_posts_after( $value, 'post_date' );
				break;
			case 'before_date':
				$args = $this->get_posts_before( $value, 'post_date' );
				break;
			case 'date_gmt':
			case 'after_date_gmt':
				$args = $this->get_posts_after( $value, 'post_date_gmt' );
				break;
			case 'before_date_gmt':
				$args = $this->get_posts_before( $value, 'post_date_gmt' );
				break;
			case 'title_like':
				$this->filter_query->to_get_posts_with_title_like( $value );
				break;
			case 'post_content':
				$this->filter_query->to_get_posts_with_content_like( $value );
				break;
			case 'post_excerpt':
				$this->filter_query->to_get_posts_with_excerpt_like( $value );
				break;
			case 'to_ping':
				$this->filter_query->to_get_posts_to_ping( $value );
				$args = array( 'to_ping' => $value );
				break;
			case 'post_modified':
				$args = $this->get_posts_after( $value, 'post_modified' );
				break;
			case 'post_modified_gmt':
				$args = $this->get_posts_after( $value, 'post_modified_gmt' );
				break;
			case 'post_content_filtered':
				$this->filter_query->to_get_posts_with_filtered_content_like( $value );
				break;
			case 'guid':
				$this->filter_query->to_get_posts_with_guid_like( $value );
				break;
			case 'meta':
			case 'meta_equals':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '=', $format = $arg_2 );
				break;
			case 'meta_not_equals':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '!=', $format = $arg_2 );
				break;
			case 'meta_gt':
			case 'meta_greater_than':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '>', $format = $arg_2 );
				break;
			case 'meta_gte':
			case 'meta_greater_than_or_equal':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '>=', $format = $arg_2 );
				break;
			case 'meta_like':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'LIKE' );
				break;
			case 'meta_not_like':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'NOT LIKE' );
				break;
			case 'meta_lt':
			case 'meta_less_than':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '<', $format = $arg_2 );
				break;
			case 'meta_lte':
			case 'meta_less_than_or_equal':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '<=', $format = $arg_2 );
				break;
			case 'meta_in':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'IN', $format = $arg_2 );
				break;
			case 'meta_not_in':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'NOT IN', $format = $arg_2 );
				break;
			case 'meta_between':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'BETWEEN', $format = $arg_2 );
				break;
			case 'meta_not_between':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'NOT BETWEEN', $format = $arg_2 );
				break;
			case 'meta_exists':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'EXISTS' );
				break;
			case 'meta_not_exists':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'NOT EXISTS' );
				break;
			case 'meta_regexp':
			case 'meta_equals_regexp':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'REGEXP' );
				break;
			case 'meta_not_regexp':
			case 'meta_not_equals_regexp':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'NOT REGEXP' );
				break;
			case 'taxonomy_exists':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'term_id', 'EXISTS' );
				break;
			case 'taxonomy_not_exists':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'term_id', 'NOT EXISTS' );
				break;
			case 'term_id_in':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'term_id', 'IN' );
				break;
			case 'term_id_not_in':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'term_id', 'NOT IN' );
				break;
			case 'term_id_and':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'term_id', 'AND' );
				break;
			case 'term_name_in':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'name', 'IN' );
				break;
			case 'term_name_not_in':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'name', 'NOT IN' );
				break;
			case 'term_name_and':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'name', 'AND' );
				break;
			case 'term_slug_in':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'slug', 'IN' );
				break;
			case 'term_slug_not_in':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'slug', 'NOT IN' );
				break;
			case 'term_slug_and':
				$args = $this->build_tax_query( $taxonomy = $value, $terms = $arg_1, 'slug', 'AND' );
				break;
		}


		return $args;
	}

	/**
	 * Builds a date query entry to get posts after a date.
	 *
	 * @since 4.7.19
	 *
	 * @param string $value
	 * @param string $column
	 *
	 * @return array
	 */
	protected function get_posts_after( $value, $column = 'post_date' ) {
		$timezone = in_array( $column, array( 'post_date_gmt', 'post_modified_gmt' ) )
			? 'UTC'
			: Tribe__Timezones::generate_timezone_string_from_utc_offset( Tribe__Timezones::wp_timezone_string() );

		if ( is_numeric( $value ) ) {
			$value = "@{$value}";
		}

		$date = new DateTime( $value, new DateTimeZone( $timezone ) );

		$array_key = sprintf( '%s-after', $column );

		return array(
			'date_query' => array(
				'relation' => 'AND',
				$array_key => array(
					'inclusive' => true,
					'column'    => $column,
					'after'     => $date->format( 'Y-m-d H:i:s' ),
				),
			),
		);
	}

	/**
	 * Builds a date query entry to get posts before a date.
	 *
	 * @since 4.7.19
	 *
	 * @param string $value
	 * @param string $column
	 *
	 * @return array
	 */
	protected function get_posts_before( $value, $column = 'post_date' ) {
		$timezone = in_array( $column, array( 'post_date_gmt', 'post_modified_gmt' ) )
			? 'UTC'
			: Tribe__Timezones::generate_timezone_string_from_utc_offset( Tribe__Timezones::wp_timezone_string() );

		if ( is_numeric( $value ) ) {
			$value = "@{$value}";
		}

		$date = new DateTime( $value, new DateTimeZone( $timezone ) );

		$array_key = sprintf( '%s-before', $column );

		return array(
			'date_query' => array(
				'relation' => 'AND',
				$array_key => array(
					'inclusive' => true,
					'column'    => $column,
					'before'    => $date->format( 'Y-m-d H:i:s' ),
				),
			),
		);
	}

	/**
	 * Builds a meta query entry.
	 *
	 * @since 4.7.19
	 *
	 * @param string       $meta_key
	 * @param string|array $meta_value
	 * @param string       $compare
	 * @param string       $type_or_format The type of value to compare
	 *
	 * @return array|null
	 * @throws Tribe__Repository__Usage_Error If trying to compare multiple values with a single
	 *                                        comparison operator.
	 */
	protected function build_meta_query( $meta_key, $meta_value = 'value', $compare = '=', $type_or_format = null ) {
		$meta_keys = Tribe__Utils__Array::list_to_array( $meta_key );

		$postfix = Tribe__Utils__Array::get( self::$comparison_operators, $compare, '' );

		if ( count( $meta_keys ) === 1 ) {
			$array_key = $this->sql_slug( $meta_keys[0], $postfix );

			$args = array(
				'meta_query' => array(
					$array_key => array(
						'key'     => $meta_keys[0],
						'compare' => strtoupper( $compare ),
					),
				),
			);

			if ( ! in_array( $compare, array( 'EXISTS', 'NOT EXISTS' ) ) ) {
				$args['meta_query'][ $array_key ]['value'] = $meta_value;
			}

			if ( 0 === strpos( $type_or_format, '%' ) ) {
				throw Tribe__Repository__Usage_Error::because_the_type_is_a_wpdb_prepare_format( $meta_key, $type_or_format, $this );
			}

			if ( null !== $type_or_format ) {
				$args['meta_query'][ $array_key ]['type'] = $type_or_format;
			}

			return $args;
		}


		if ( null === $type_or_format ) {
			$type_or_format = '%s';
		} elseif ( 0 !== strpos( $type_or_format, '%' ) ) {
			throw Tribe__Repository__Usage_Error::because_the_format_is_not_a_wpdb_prepare_one( $meta_key, $type_or_format, $this );
		}

		/** @var wpdb $wpdb */
		global $wpdb;

		// Build custom WHERE and JOINS to reduce the JOIN clauses
		$pm_alias     = $this->sql_slug( 'meta', $postfix, ++ self::$meta_alias );
		$meta_keys_in = sprintf( "('%s')", implode( "','", array_map( 'esc_sql', $meta_keys ) ) );

		$this->validate_operator_and_values( $compare, $meta_keys, $meta_value );

		if ( in_array( $compare, self::$multi_value_keys, true ) ) {
			$meta_values = $this->prepare_interval( Tribe__Utils__Array::list_to_array( $meta_value ), $type_or_format );
		} else {
			$meta_values = $this->prepare_value( $meta_value, $type_or_format );
		}

		$this->filter_query->join( "JOIN {$wpdb->postmeta} {$pm_alias} ON {$wpdb->posts}.ID = {$pm_alias}.post_id" );

		if ( 'EXISTS' === $compare ) {
			$this->filter_query->where( "{$pm_alias}.meta_key IN {$meta_keys_in} AND {$pm_alias}.meta_id IS NOT NULL" );
		} else if ( 'NOT EXISTS' === $compare ) {
			$this->filter_query->where( "{$pm_alias}.meta_key NOT IN {$meta_keys_in} AND {$pm_alias}.meta_id IS NOT NULL" );
		} else {
			$this->filter_query->where( "{$pm_alias}.meta_key IN {$meta_keys_in} AND {$pm_alias}.meta_value {$compare} {$meta_values}" );
		}
	}

	/**
	 * Generates a SQL friendly slug from the provided, variadic, fragments.
	 *
	 * @since 4.7.19
	 *
	 * @param ...string $frag
	 *
	 * @return string
	 */
	protected function sql_slug( $frag ) {
		$frags = func_get_args();

		foreach ( $frags as &$frag ) {
			if ( is_string( $frag ) ) {
				Tribe__Utils__Array::get( self::$comparison_operators, $frag, $frag );
			} elseif ( is_array( $frag ) ) {
				$frag = implode( '_', $frag );
			}
		}


		$frags = array_filter( $frags );

		return strtolower( str_replace( '-', '_', sanitize_title( implode( '_', $frags ) ) ) );
	}

	/**
	 * Builds a taxonomy query entry.
	 *
	 * @since 4.7.19
	 *
	 * @param string           $taxonomy
	 * @param int|string|array $terms
	 * @param string           $field
	 * @param string           $operator
	 *
	 * @return array
	 */
	protected function build_tax_query( $taxonomy, $terms, $field, $operator ) {
		if ( in_array( $operator, array( 'EXISTS', 'NOT EXISTS' ) ) ) {
			$array_key = $this->sql_slug( $taxonomy, $operator );
		} else {
			$array_key = $this->sql_slug( $taxonomy, $field, $operator );
		}

		return array(
			'tax_query' => array(
				$array_key => array(
					'taxonomy' => $taxonomy,
					'field'    => $field,
					'terms'    => $terms,
					'operator' => strtoupper( $operator ),
				),
			),
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function join_clause( $join ) {
		$this->filter_query->join( $join );
	}

	/**
	 * {@inheritdoc}
	 */
	public function where_clause( $where ) {
		$this->filter_query->where( $where );
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_query_builder( $query_builder ) {
		$this->query_builder = $query_builder;
	}

	/**
	 * Builds and escapes an interval of strings.
	 *
	 * The return string includes opening and closing braces.
	 *
	 * @since 4.7.19
	 *
	 * @param string|array $values One or more values to use to build
	 *                             the interval.
	 * @param string       $format The format that should be used to escape
	 *                             the values; default to '%s'.
	 *
	 * @return string
	 */
	public function prepare_interval( $values, $format = '%s' ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		$values = Tribe__Utils__Array::list_to_array( $values );

		$prepared = array();
		foreach ( $values as $value ) {
			$prepared[] = $this->prepare_value( $value, $format );
		}

		return sprintf( '(' . $format . ')', implode( ',', $prepared ) );
	}

	/**
	 * Prepares a single value to be used in a SQL query.
	 *
	 * @since 4.7.19
	 *
	 * @param mixed  $value
	 * @param string $format
	 *
	 * @return string
	 */
	public function prepare_value( $value, $format = '%s' ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		return $wpdb->prepare( $format, $value );
	}

	/**
	 * Validates that a comparison operator is used with the correct type of values.
	 *
	 * This is just a wrap to signal this kind of code error not in bad SQL error but
	 * with a visible exception.
	 *
	 * @since 4.7.19
	 *
	 * @param string       $compare A SQL comparison operator
	 * @param string|array $meta_key
	 * @param mixed        $meta_value
	 *
	 * @throws Tribe__Repository__Usage_Error
	 */
	protected function validate_operator_and_values( $compare, $meta_key, $meta_value ) {
		if ( is_array( $meta_value ) && ! in_array( $compare, self::$multi_value_keys, true ) ) {
			throw Tribe__Repository__Usage_Error::because_single_value_comparisons_should_be_used_with_one_value(
				$meta_key,
				$meta_value,
				$compare,
				$this
			);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_related_to_min( $by_meta_keys, $min, $keys = null, $values = null ) {
		$min = $this->prepare_value( $min, '%d' );

		/** @var wpdb $wpdb */
		global $wpdb;

		$by_meta_keys = $this->prepare_interval( $by_meta_keys );

		$join      = '';
		$and_where = '';
		if ( ! empty( $keys ) || ! empty( $values ) ) {
			$join = "\nJOIN {$wpdb->postmeta} pm2 ON pm1.post_id = pm2.post_id\n";
		}
		if ( ! empty( $keys ) ) {
			$keys      = $this->prepare_interval( $keys );
			$and_where .= "\nAND pm2.meta_key IN {$keys}\n";
		}
		if ( ! empty( $values ) ) {
			$values    = $this->prepare_interval( $values );
			$and_where .= "\nAND pm2.meta_value IN {$values}\n";
		}

		$this->where_clause( "{$wpdb->posts}.ID IN (
			SELECT pm1.meta_value
			FROM {$wpdb->postmeta} pm1 {$join}
			WHERE pm1.meta_key IN {$by_meta_keys} {$and_where}
			GROUP BY( pm1.meta_value )
			HAVING COUNT(DISTINCT pm1.post_id) >= {$min}
		)" );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_related_to_max( $by_meta_keys, $max, $keys = null, $values = null ) {
		$max = $this->prepare_value( $max, '%d' );

		/** @var wpdb $wpdb */
		global $wpdb;

		$join      = '';
		$and_where = '';
		if ( ! empty( $keys ) || ! empty( $values ) ) {
			$join = "\nJOIN {$wpdb->postmeta} pm2 ON pm1.post_id = pm2.post_id\n";
		}
		if ( ! empty( $keys ) ) {
			$keys      = $this->prepare_interval( $keys );
			$and_where .= "\nAND pm2.meta_key IN {$keys}\n";
		}
		if ( ! empty( $values ) ) {
			$values    = $this->prepare_interval( $values );
			$and_where .= "\nAND pm2.meta_value IN {$values}\n";
		}

		$by_meta_keys = $this->prepare_interval( $by_meta_keys );

		$this->where_clause( "{$wpdb->posts}.ID IN (
			SELECT pm1.meta_value
			FROM {$wpdb->postmeta} pm1 {$join}
			WHERE pm1.meta_key IN {$by_meta_keys} {$and_where}
			GROUP BY( pm1.meta_value )
			HAVING COUNT(DISTINCT pm1.post_id) <= {$max}
		)" );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_related_to_between( $by_meta_keys, $min, $max, $keys = null, $values = null ) {
		$min = $this->prepare_value( $min, '%d' );
		$max = $this->prepare_value( $max, '%d' );

		/** @var wpdb $wpdb */
		global $wpdb;

		$by_meta_keys = $this->prepare_interval( $by_meta_keys );

		$join      = '';
		$and_where = '';
		if ( ! empty( $keys ) || ! empty( $values ) ) {
			$join = "\nJOIN {$wpdb->postmeta} pm2 ON pm1.post_id = pm2.post_id\n";
		}
		if ( ! empty( $keys ) ) {
			$keys      = $this->prepare_interval( $keys );
			$and_where .= "\nAND pm2.meta_key IN {$keys}\n";
		}
		if ( ! empty( $values ) ) {
			$values    = $this->prepare_interval( $values );
			$and_where .= "\nAND pm2.meta_value IN {$values}\n";
		}

		$this->where_clause( "{$wpdb->posts}.ID IN (
			SELECT pm1.meta_value
			FROM {$wpdb->postmeta} pm1 {$join}
			WHERE pm1.meta_key IN {$by_meta_keys} {$and_where}
			GROUP BY( pm1.meta_value )
			HAVING COUNT(DISTINCT pm1.post_id) BETWEEN {$min} AND {$max}
		)" );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_filter( $key, $value = null ) {
		return null === $value
			? array_key_exists( $key, $this->current_filters )
			: array_key_exists( $key, $this->current_filters ) && $this->current_filters[ $key ] == $value;
	}
}
