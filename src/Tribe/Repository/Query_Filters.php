<?php

/**
 * Class Tribe__Repository__Query_Filters
 *
 * @since 4.7.19
 */
class Tribe__Repository__Query_Filters {

	/**
	 * @var array
	 */
	protected static $initial_query_vars = array(
		'like'   => array(
			'post_title'   => array(),
			'post_content' => array(),
			'post_excerpt' => array(),
		),
		'status' => array(),
		'join'   => array(),
		'where'  => array(),
	);

	/**
	 * @var array
	 */
	protected $query_vars;

	/**
	 * @var WP_Query
	 */
	protected $current_query;

	/**
	 * @var int A reasonably large number for the LIMIT clause.
	 */
	protected $really_large_number = 99999999;

	/**
	 * @var array A list of the filters this class has added.
	 */
	protected $active_filters = array();

	/**
	 * @var bool
	 */
	protected $buffer_where_clauses = false;

	/**
	 * @var array
	 */
	protected $buffered_where_clauses = array();

	/**
	 * Tribe__Repository__Query_Filters constructor.
	 *
	 * @since 4.7.19
	 */
	public function __construct() {
		$this->query_vars = self::$initial_query_vars;
	}

	/**
	 * Builds an "not exists or is not in" media query.
	 *
	 * @since 4.7.19
	 *
	 * @param array|string     $meta_keys On what meta_keys the check should be made.
	 * @param int|string|array $values    A single value, an array of values or a CSV list of values.
	 * @param string           $query_slug
	 *
	 * @return array
	 */
	public static function meta_not_in( $meta_keys, $values, $query_slug ) {
		$meta_keys = Tribe__Utils__Array::list_to_array( $meta_keys );
		$values    = Tribe__Utils__Array::list_to_array( $values );

		if ( empty( $meta_keys ) || count( $values ) === 0 ) {
			return array();
		}

		$args = array(
			'meta_query' => array(
				$query_slug => array(
					'relation' => 'AND',
				),
			),
		);

		foreach ( $meta_keys as $key ) {
			$args['meta_query'][ $query_slug ][ $key ] = array(
				'not-exists' => array(
					'key'     => $key,
					'compare' => 'NOT EXISTS',
				),
				'relation'   => 'OR',
			);

			if ( count( $values ) > 1 ) {
				$args['meta_query'][ $query_slug ][ $key ]['not-in'] = array(
					'key'     => $key,
					'compare' => 'NOT IN',
					'value'   => $values,
				);
			} else {
				$args['meta_query'][ $query_slug ][ $key ]['not-equals'] = array(
					'key'     => $key,
					'value'   => $values[0],
					'compare' => '!=',
				);
			}
		}

		return $args;
	}

	/**
	 * Builds an "exists and is in" media query.
	 *
	 * @since 4.7.19
	 *
	 * @param array|string     $meta_keys On what meta_keys the check should be made.
	 * @param int|string|array $values    A single value, an array of values or a CSV list of values.
	 * @param string           $query_slug
	 *
	 * @return array
	 */
	public static function meta_in( $meta_keys, $values, $query_slug ) {
		$meta_keys = Tribe__Utils__Array::list_to_array( $meta_keys );
		$values    = Tribe__Utils__Array::list_to_array( $values );

		if ( empty( $meta_keys ) || count( $values ) === 0 ) {
			return array();
		}

		$args = array(
			'meta_query' => array(
				$query_slug => array(
					'relation' => 'OR',
				),
			),
		);

		foreach ( $meta_keys as $meta_key ) {
			if ( count( $values ) > 1 ) {
				$args['meta_query'][ $query_slug ][ $meta_key ] = array(
					'key'     => $meta_key,
					'compare' => 'IN',
					'value'   => $values,
				);
			} else {
				$args['meta_query'][ $query_slug ][ $meta_key ] = array(
					'key'     => $meta_key,
					'compare' => '=',
					'value'   => $values[0],
				);
			}
		}

		return $args;
	}

	/**
	 * Builds a meta query to check that at least of the meta key exists.
	 *
	 * @since 4.7.19
	 *
	 * @param array|string $meta_keys
	 * @param string       $query_slug
	 *
	 * @return array
	 */
	public static function meta_exists( $meta_keys, $query_slug ) {
		$meta_keys = Tribe__Utils__Array::list_to_array( $meta_keys );

		if ( empty( $meta_keys ) ) {
			return array();
		}

		$args = array(
			'meta_query' => array(
				$query_slug => array(
					'relation' => 'OR',
				),
			),
		);

		foreach ( $meta_keys as $meta_key ) {
			$args['meta_query'][ $query_slug ][ $meta_key ] = array(
				'key'     => $meta_key,
				'compare' => 'EXISTS',
			);
		}

		return $args;
	}

	/**
	 * Builds a meta query to check that a meta is either equal to a value or
	 * not exists.
	 *
	 * @since 4.7.19
	 *
	 * @param array|string $meta_keys
	 * @param array|string $values
	 * @param string       $query_slug
	 *
	 * @return array
	 */
	public static function meta_in_or_not_exists( $meta_keys, $values, $query_slug ) {
		$meta_keys = Tribe__Utils__Array::list_to_array( $meta_keys );
		$values    = Tribe__Utils__Array::list_to_array( $values );

		if ( empty( $meta_keys ) || count( $values ) === 0 ) {
			return array();
		}

		$args = array(
			'meta_query' => array(
				$query_slug => array(
					'relation' => 'AND',
				),
			),
		);

		foreach ( $meta_keys as $meta_key ) {
			$args['meta_query'][ $query_slug ][ $meta_key ]['does-not-exist'] = array(
				'key'     => $meta_key,
				'compare' => 'NOT EXISTS',
			);
			$args['meta_query'][ $query_slug ][ $meta_key ]['relation']       = 'OR';
			if ( count( $values ) > 1 ) {
				$args['meta_query'][ $query_slug ][ $meta_key ]['in'] = array(
					'key'     => $meta_key,
					'compare' => 'IN',
					'value'   => $values,
				);
			} else {
				$args['meta_query'][ $query_slug ][ $meta_key ]['equals'] = array(
					'key'     => $meta_key,
					'compare' => '=',
					'value'   => $values[0],
				);
			}
		}

		return $args;
	}

	/**
	 * Builds a meta query to check that a meta is either not equal to a value or
	 * not exists.
	 *
	 * @since 4.7.19
	 *
	 * @param array|string $meta_keys
	 * @param array|string $values
	 * @param string       $query_slug
	 *
	 * @return array
	 */
	public static function meta_not_in_or_not_exists( $meta_keys, $values, $query_slug ) {
		$meta_keys = Tribe__Utils__Array::list_to_array( $meta_keys );
		$values    = Tribe__Utils__Array::list_to_array( $values );

		if ( empty( $meta_keys ) || count( $values ) === 0 ) {
			return array();
		}

		$args = array(
			'meta_query' => array(
				$query_slug => array(
					'relation' => 'AND',
				),
			),
		);

		foreach ( $meta_keys as $meta_key ) {
			$args['meta_query'][ $query_slug ][ $meta_key ]['does-not-exist'] = array(
				'key'     => $meta_key,
				'compare' => 'NOT EXISTS',
			);
			$args['meta_query'][ $query_slug ][ $meta_key ]['relation']       = 'OR';

			if ( count( $values ) > 1 ) {
				$args['meta_query'][ $query_slug ][ $meta_key ]['not-in'] = array(
					'key'     => $meta_key,
					'compare' => 'NOT IN',
					'value'   => $values,
				);
			} else {
				$args['meta_query'][ $query_slug ][ $meta_key ]['not-equals'] = array(
					'key'     => $meta_key,
					'compare' => '!=',
					'value'   => $values[0],
				);
			}
		}

		return $args;
	}

	/**
	 * Filters the WHERE clause of the query to match posts with a field like.
	 *
	 * @since 4.7.19
	 *
	 * @param string   $where
	 * @param WP_Query $query
	 *
	 * @return string
	 */
	public function filter_by_like( $where, WP_Query $query ) {
		if ( $query !== $this->current_query ) {
			return $where;
		}

		if ( empty( $this->query_vars['like'] ) ) {
			return $where;
		}

		foreach ( $this->query_vars['like'] as $field => $entries ) {
			foreach ( $entries as $entry ) {
				$where .= $this->and_field_like( $field, $entry );
			}
		}

		return $where;
	}

	/**
	 * Builds the escaped WHERE entry to match a field like the entry.
	 *
	 * @since 4.7.19
	 *
	 * @param string $field
	 * @param string $entry
	 *
	 * @return string
	 */
	protected function and_field_like( $field, $entry ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		$like       = $wpdb->esc_like( $entry );
		$variations = array(
			$wpdb->prepare( "{$wpdb->posts}.{$field} LIKE %s ", "{$like}%" ),
			$wpdb->prepare( "{$wpdb->posts}.{$field} LIKE %s ", "%{$like}%" ),
			$wpdb->prepare( "{$wpdb->posts}.{$field} LIKE %s ", "%{$like}" ),
		);

		return ' AND (' . implode( ' OR ', $variations ) . ')';
	}

	/**
	 * Filters the found posts value to apply filtering and selections on the PHP
	 * side of things.
	 *
	 * Here we perform, after the query did run, further filtering operations that would
	 * result in more JOIN and/or sub-SELECT clauses being added to the query.
	 *
	 * @since 4.7.19
	 *
	 * @param int      $found_posts The number of found posts.
	 * @param WP_Query $query       The current query object.
	 *
	 * @return string
	 */
	public function filter_found_posts( $found_posts, WP_Query $query ) {
		if ( $query !== $this->current_query ) {
			return $found_posts;
		}

		if ( empty( $this->query_vars['found_posts_filters'] ) ) {
			return $found_posts;
		}

		$filtered_found_posts = $found_posts;
		$ids_only             = $query->get( 'fields' ) === 'ids';

		/** @var wpdb $wpdb */
		global $wpdb;

		/**
		 * Handles meta-based relations between posts.
		 */
		foreach ( $this->query_vars['found_posts_filters']['meta_related'] as list( $meta_keys, $field, $field_values, $compare ) ) {
			$post_ids          = $ids_only ? $query->posts : wp_list_pluck( $query->posts, 'ID' );
			$post_ids_interval = '(' . implode( ',', $post_ids ) . ')';
			$meta_keys         = "('" . implode( "','", array_map( 'esc_sql', $meta_keys ) ) . "')";
			$field             = esc_sql( $field );
			$field_values      = is_array( $field_values )
				? "('" . implode( "','", array_map( 'esc_sql', $field_values ) ) . "')"
				: $wpdb->prepare( '%s', $field_values );

			$relation_query = "
				SELECT DISTINCT( pm.post_id )
				FROM {$wpdb->posts} p
				JOIN {$wpdb->postmeta} pm
				ON pm.meta_value = p.ID
				WHERE pm.post_id IN {$post_ids_interval}
				AND pm.meta_key IN {$meta_keys}
				AND p.{$field} {$compare} {$field_values}
				";

			$matching_ids = $wpdb->get_col( $relation_query );

			if ( empty( $matching_ids ) ) {
				$query->posts         = array();
				$filtered_found_posts = 0;
				break;
			}

			if ( $ids_only ) {
				$query->posts = array_intersect( $query->posts, $matching_ids );
			} else {
				$updated_query_posts = array();
				foreach ( $query->posts as $this_post ) {
					if ( in_array( $this_post->ID, $matching_ids ) ) {
						$updated_query_posts[] = $this_post;
					}
				}
				$query->posts = $updated_query_posts;
			}
			$filtered_found_posts = count( $query->posts );
		}

		$query->post_count = $filtered_found_posts;

		return $filtered_found_posts;
	}

	/**
	 * Sets the current query object.
	 *
	 * @since 4.7.19
	 *
	 * @param WP_Query $query
	 */
	public function set_query( WP_Query $query ) {
		$this->current_query = $query;
	}

	/**
	 * Sets up `posts_where` filtering to get posts with a title like the value.
	 *
	 * @since 4.7.19
	 *
	 * @param string $value
	 */
	public function to_get_posts_with_title_like( $value ) {
		$this->query_vars['like']['post_title'][] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_like' ) ) ) {
			$this->add_filter( 'posts_where', array( $this, 'filter_by_like' ), 10, 2 );
		}
	}

	/**
	 * Proxy method to add a  filter calling the WordPress `add_filter` function
	 * and keep track of it.
	 *
	 * @since 4.7.19
	 *
	 * @param string   $tag
	 * @param callable $function_to_add
	 * @param int      $priority
	 * @param int      $accepted_args
	 */
	protected function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		$this->active_filters[] = array( $tag, $function_to_add, $priority );
		add_filter( $tag, $function_to_add, $priority, $accepted_args );
	}

	/**
	 * Sets up `posts_where` filtering to get posts with a content like the value.
	 *
	 * @since 4.7.19
	 *
	 * @param string $value
	 */
	public function to_get_posts_with_content_like( $value ) {
		$this->query_vars['like']['post_content'][] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_like' ) ) ) {
			$this->add_filter( 'posts_where', array( $this, 'filter_by_like' ), 10, 2 );
		}
	}

	/**
	 * Sets up `posts_where` filtering to get posts with an excerpt like the value.
	 *
	 * @since 4.7.19
	 *
	 * @param string $value
	 */
	public function to_get_posts_with_excerpt_like( $value ) {
		$this->query_vars['like']['post_excerpt'] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_like' ) ) ) {
			add_filter( 'posts_where', array( $this, 'filter_by_like' ), 10, 2 );
		}
	}

	/**
	 * Sets up `posts_where` filtering to get posts with a filtered content like the value.
	 *
	 * @since 4.7.19
	 *
	 * @param string $value
	 */
	public function to_get_posts_with_filtered_content_like( $value ) {
		$this->query_vars['like']['post_content_filtered'][] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_like' ) ) ) {
			add_filter( 'posts_where', array( $this, 'filter_by_like' ), 10, 2 );
		}
	}

	/**
	 * Sets up `posts_where` filtering to get posts with a guid that equals the value.
	 *
	 * @since 4.7.19
	 *
	 * @param string $value
	 */
	public function to_get_posts_with_guid_like( $value ) {
		$this->query_vars['like']['guid'][] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_like' ) ) ) {
			add_filter( 'posts_where', array( $this, 'filter_by_like' ), 10, 2 );
		}
	}

	/**
	 * Sets up `posts_where` filtering to get posts with a `to_ping` field equal to the value.
	 *
	 * @since 4.7.19
	 *
	 * @param string $value
	 */
	public function to_get_posts_to_ping( $value ) {
		$this->query_vars['to_ping'] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_to_ping' ) ) ) {
			add_filter( 'posts_where', array( $this, 'filter_by_to_ping' ), 10, 2 );
		}
	}

	/**
	 * Filters the WHERE clause of the query to match posts with a specific `to_ping`
	 * entry.
	 *
	 * @since 4.7.19
	 *
	 * @param string   $where
	 * @param WP_Query $query
	 *
	 * @return string
	 */
	public function filter_by_to_ping( $where, WP_Query $query ) {
		return $this->where_field_is( $where, $query, 'ping_status' );
	}

	/**
	 * Builds the escaped WHERE entry to match a field that equals the entry.
	 *
	 * @since 4.7.19
	 *
	 * @param string   $where
	 * @param WP_Query $query
	 * @param string   $field
	 *
	 * @return string
	 */
	protected function where_field_is( $where, WP_Query $query, $field ) {
		if ( $query !== $this->current_query ) {
			return $where;
		}


		if ( empty( $this->query_vars[ $field ] ) ) {
			return $where;
		}

		/** @var wpdb $wpdb */
		global $wpdb;

		$where .= $wpdb->prepare( " AND {$wpdb->posts}.{$field} = %s ", $this->query_vars[ $field ] );

		return $where;
	}

	/**
	 * Clean up before the object destruction.
	 *
	 * @since 4.7.19
	 */
	public function __destruct() {
		// let's make sure we clean up when the object is dereferenced
		$this->remove_filters();
	}

	/**
	 * Removes all the filters this class applied.
	 *
	 * @since 4.7.19
	 */
	public function remove_filters() {
		foreach ( $this->active_filters as list( $tag, $function_to_add, $priority ) ) {
			remove_filter( $tag, $function_to_add, $priority );
		}
	}

	/**
	 * Add a custom WHERE clause to the query.
	 *
	 * @since 4.7.19
	 *
	 * @param string $where_clause
	 */
	public function where( $where_clause ) {
		if ( $this->buffer_where_clauses ) {
			$this->buffered_where_clauses[] = '(' . $where_clause . ')';
		} else {
			$this->query_vars['where'][] = '(' . $where_clause . ')';

			if ( ! has_filter( 'posts_where', array( $this, 'filter_posts_where' ) ) ) {
				add_filter( 'posts_where', array( $this, 'filter_posts_where' ), 10, 2 );
			}
		}
	}

	/**
	 * Add a custom JOIN clause to the query.
	 *
	 * @since 4.7.19
	 *
	 * @param string $join_clause
	 */
	public function join( $join_clause ) {
		$this->query_vars['join'][] = $join_clause;

		if ( ! has_filter( 'posts_join', array( $this, 'filter_posts_join' ) ) ) {
			add_filter( 'posts_join', array( $this, 'filter_posts_join' ), 10, 2 );
		}
	}

	/**
	 * Whether WHERE clauses should be buffered or not.
	 *
	 * @since 4.7.19
	 *
	 * @param bool $buffer_clauses
	 */
	public function buffer_where_clauses( $buffer_clauses ) {
		$this->buffer_where_clauses = (bool) $buffer_clauses;
	}

	/**
	 * Returns the buffered WHERE clause and, optionally, cleans
	 * and deactivates buffering.
	 *
	 * @since 4.7.19
	 *
	 * @param bool $get_clean Whether  to clean the buffered WHERE
	 *                        clauses and deactivate buffering before
	 *                        returning them or not.
	 *
	 * @return array
	 */
	public function get_buffered_where_clauses( $get_clean = false ) {
		$clauses = $this->buffered_where_clauses;

		if ( $get_clean ) {
			$this->buffer_where_clauses   = false;
			$this->buffered_where_clauses = array();
		}

		return $clauses;
	}

	/**
	 * Builds the escaped WHERE entry to match a field not in the entry.
	 *
	 * @since 4.7.19
	 *
	 * @param string   $where
	 * @param WP_Query $query
	 * @param string   $field
	 *
	 * @return string
	 */
	protected function where_field_not_in( $where, WP_Query $query, $field ) {
		if ( $query !== $this->current_query ) {
			return $where;
		}

		if ( empty( $this->query_vars[ $field ] ) ) {
			return $where;
		}

		$input = $this->query_vars[ $field ];

		$stati_interval = $this->create_interval_of_strings( $input );

		$where .= $this->and_field_not_in_interval( $field, $stati_interval );

		return $where;
	}

	/**
	 * Creates a SQL interval of strings.
	 *
	 * @since 4.7.19
	 *
	 * @param string|array $input
	 *
	 * @return string
	 */
	protected function create_interval_of_strings( $input ) {
		$buffer = array();

		/** @var wpdb $wpdb */
		global $wpdb;

		foreach ( $input as $string ) {
			$buffer[] = is_array( $string ) ? $string : array( $string );
		}

		$buffer = array_unique( call_user_func_array( 'array_merge', $buffer ) );

		$safe_strings = array();
		foreach ( $buffer as $raw_status ) {
			$safe_strings[] = $wpdb->prepare( '%s', $string );
		}

		return implode( "''", $safe_strings );
	}

	/**
	 * Builds a WHERE clause where field is not in interval.
	 *
	 * @since 4.7.19
	 *
	 * @param string $field
	 * @param string $interval
	 *
	 * @return string
	 */
	protected function and_field_not_in_interval( $field, $interval ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		return " AND {$wpdb->posts}.{$field} NOT IN ('{$interval}') ";
	}

	/**
	 * Builds the escaped WHERE entry to match a field in the entry.
	 *
	 * @since 4.7.19
	 *
	 * @param string   $where
	 * @param WP_Query $query
	 * @param string   $field
	 *
	 * @return string
	 */
	protected function where_field_in( $where, WP_Query $query, $field ) {
		if ( $query !== $this->current_query ) {
			return $where;
		}

		if ( empty( $this->query_vars[ $field ] ) ) {
			return $where;
		}

		$interval = $this->create_interval_of_strings( $this->query_vars[ $field ] );

		$where .= $this->and_field_in_interval( $field, $interval );

		return $where;
	}

	/**
	 * Builds a AND WHERE clause.
	 *
	 * @since 4.7.19
	 *
	 * @param string $field
	 * @param string $interval
	 *
	 * @return string
	 */
	protected function and_field_in_interval( $field, $interval ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		return " AND {$wpdb->posts}.{$field} IN ('{$interval}') ";
	}

	/**
	 * Filter the `posts_where` filter to add custom WHERE clauses.
	 *
	 * @since 4.7.19
	 *
	 * @param string   $where
	 * @param WP_Query $query
	 *
	 * @return string
	 */
	public function filter_posts_where( $where, WP_Query $query ) {
		if ( $query !== $this->current_query ) {
			return $where;
		}

		if ( empty( $this->query_vars['where'] ) ) {
			return $where;
		}

		$where .= ' AND ' . implode( "\nAND ", $this->query_vars['where'] ) . ' ';

		return $where;
	}

	/**
	 * Filter the `posts_join` filter to add custom JOIN clauses.
	 *
	 * @since 4.7.19
	 *
	 * @param string   $join
	 * @param WP_Query $query
	 *
	 * @return string
	 */
	public function filter_posts_join( $join, WP_Query $query ) {
		if ( $query !== $this->current_query ) {
			return $join;
		}

		if ( empty( $this->query_vars['join'] ) ) {
			return $join;
		}

		$join .= "\n" . implode( "\n ", $this->query_vars['join'] ) . ' ';

		return $join;
	}
}
