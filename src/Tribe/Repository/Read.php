<?php

/**
 * Class Tribe__Repository__Read
 *
 * @since TBD
 *
 * The basic Read repository; this is the kind of object you get back from a
 * method like `tribe_events()->fetch()`.
 */
class Tribe__Repository__Read
	extends Tribe__Repository__Specialized_Base
	implements Tribe__Repository__Read_Interface {

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
	 * @var array An array of query modifying callbacks populated while applying
	 *            the filters.
	 */
	protected $query_modifiers = array();
	/**
	 * @var bool Whether the current query is void or not.
	 */
	protected $void_query = false;
	/**
	 * @var array
	 */
	protected $default_args = array();

	/**
	 * @var array An array of query arguments that will be populated while applying
	 *            filters.
	 */
	protected $query_args = array();

	/**
	 * @var WP_Query The current query object built and modified by the instance.
	 */
	protected $current_query;

	/**
	 * @var Tribe__Repository__Query_Filters
	 */
	protected $filter_query;

	/**
	 * Tribe__Repository__Read constructor.
	 *
	 * @since TBD
	 *
	 * @param array                            $schema
	 * @param Tribe__Repository__Query_Filters $query_filters
	 * @param array                            $default_args
	 */
	public function __construct(
		array $schema,
		Tribe__Repository__Query_Filters $query_filters,
		array $default_args = array()
	) {
		parent::__construct( $schema );
		$this->default_args = array_merge( self::$common_args, $default_args );
		$this->filter_query = $query_filters;
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
	 * {@inheritdoc}
	 */
	public function by( $key, $value ) {
		if ( $this->void_query ) {
			// No point in doing more computations if the query is void.
			return $this;
		}

		$call_args = func_get_args();

		try {
			$query_modifier = $this->modify_query( $key, $call_args );

			/**
			 * Primitives are just merged in.
			 * Since we are using `array_merge_recursive` we expect them to be arrays.
			 */
			if ( ! ( is_object( $query_modifier ) || is_callable( $query_modifier ) ) ) {

				if ( ! is_array( $query_modifier ) ) {
					throw new InvalidArgumentException( 'Query modifier should be an array!' );
				}

				/**
				 * We do an `array_merge` recursive here to allow "stacking" of same kind of queries;
				 * e.g. two or more `tax_query`.
				 */
				$this->query_args = array_merge_recursive( $this->query_args, $query_modifier );
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
	 * Whether a filter defined and handled by the repository exists or not.
	 *
	 * @since TBD
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
	 * @since TBD
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
	 * {@inheritdoc}
	 */
	public function where( $key, $value ) {
		return call_user_func_array( array( $this, 'by' ), func_get_args() );
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
		 * @since TBD
		 *
		 * @param WP_Query $query
		 */
		do_action_ref_array( "{$this->filter_name}_pre_count_posts", array( &$query ) );

		$ids = $query->get_posts();

		return is_array( $ids ) ? count( $ids ) : 0;
	}

	/**
	 * Build, without initializing it, the query.
	 *
	 * @since TBD
	 *
	 * @return WP_Query
	 */
	protected function build_query() {
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

			$real_offset                  = $per_page === - 1 ? $offset : ( $per_page * $page - 1 ) + $offset;
			$query_args['offset']         = $real_offset;
			$query_args['posts_per_page'] = $per_page === - 1 ? 99999999999 : $per_page;
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
		 * @since TBD
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

		// skip counting the found rows to speed up the query
		$query->set( 'no_found_rows', true );
		// we'll let the class build the items later
		$query->set( 'fields', 'ids' );

		/**
		 * Filters the query object by reference before getting the posts.
		 *
		 * @since TBD
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
		$this->query_args['order_by'] = $order_by;

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
	 * @since TBD
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
	 * Returns modified query arguments after applying a default filter.
	 *
	 * @since TBD
	 *
	 * @param      string $key
	 * @param      mixed  $value
	 *
	 * @return array
	 */
	protected function apply_default_modifier( $key, $value ) {
		$args = array();

		$call_args = func_get_args();
		$arg_1     = isset( $call_args[2] ) ? $call_args[2] : null;

		switch ( $key ) {
			default:
				// leverage built-in WP_Query filters
				$args = array( $key => $value );
				break;
			case 'ID':
			case 'id':
				$args = array( 'p' => $value );
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
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '=' );
				break;
			case 'meta_not_equals':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '!=' );
				break;
			case 'meta_gt':
			case 'meta_greater_than':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '>' );
				break;
			case 'meta_gte':
			case 'meta_greater_than_or_equal':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '>=' );
				break;
			case 'meta_like':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'LIKE' );
				break;
			case 'meta_not_like':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'NOT LIKE' );
				break;
			case 'meta_lt':
			case 'meta_less_than':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '<' );
				break;
			case 'meta_lte':
			case 'meta_less_than_or_equal':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, '<=' );
				break;
			case 'meta_in':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'IN' );
				break;
			case 'meta_not_in':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'NOT IN' );
				break;
			case 'meta_between':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'BETWEEN' );
				break;
			case 'meta_not_between':
				$args = $this->build_meta_query( $meta_key = $value, $meta_value = $arg_1, 'NOT BETWEEN' );
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
	 * @since TBD
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
				$array_key => array(
					'inclusive' => true,
					'column'    => $column,
					'relation'  => 'AND',
					'after'     => $date->format( 'Y-m-d H:i:s' ),
				),
			),
		);
	}

	/**
	 * Builds a date query entry to get posts before a date.
	 *
	 * @since TBD
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
		$date     = new DateTime( $value, new DateTimeZone( $timezone ) );

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
	 * @since TBD
	 *
	 * @param string $meta_key
	 * @param string|array $meta_value
	 * @param string $compare
	 *
	 * @return array
	 */
	protected function build_meta_query( $meta_key, $meta_value = 'value', $compare = '=' ) {
		$array_key = sanitize_title( sprintf( '%s-%s', $meta_key, $compare ) );

		$meta_query = array(
			'meta_query' => array(
				'relation' => 'AND',
				$array_key => array(
					'key'     => $meta_key,
					'value'   => $meta_value,
					'compare' => strtoupper( $compare ),
				),
			),
		);

		if ( in_array( $compare, array( 'EXISTS', 'NOT EXISTS' ) ) ) {
			unset( $meta_query['meta_query'][ $array_key ]['value'] );
		}

		return $meta_query;
	}

	/**
	 * Builds a taxonomy query entry.
	 *
	 * @since TBD
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
			$array_key = sanitize_title( sprintf( '%s-%s', $taxonomy, $operator ) );
		} else {
			$array_key = sanitize_title( sprintf( '%s-%s-%s', $taxonomy, $field, $operator ) );
		}

		return array(
			'tax_query' => array(
				'relation' => 'AND',
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
	 * Returns the query modifier for a key.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 * @param array  $call_args
	 *
	 * @return mixed
	 *
	 * @throws Tribe__Repository__Usage_Error If the required filter is not defined by the class.
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
	 * Formats a post handled by the repository to the expected
	 * format.
	 *
	 * Extending classes should use this method to format return values to the expected format.
	 *
	 * @since TBD
	 *
	 * @param int|WP_Post $id
	 *
	 * @return WP_Post
	 */
	protected function format_item( $id ) {
		return get_post( $id );
	}

	/**
	 * {@inheritdoc}
	 */
	public function take( $n  ) {
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
}
