<?php

/**
 * Class Tribe__Repository__Query_Filters
 *
 * @since TBD
 */
class Tribe__Repository__Query_Filters {

	/**
	 * @var array
	 */
	protected $query_vars = array(
		'like'   => array(
			'post_title'   => array(),
			'post_content' => array(),
			'post_excerpt' => array(),
		),
		'status' => array(),
	);

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
	 * Filters the WHERE clause of the query to match posts with a field like.
	 *
	 * @since TBD
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
	 * @since TBD
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
	 * Sets the current query object.
	 *
	 * @since TBD
	 *
	 * @param WP_Query $query
	 */
	public function set_query( WP_Query $query ) {
		$this->current_query = $query;
	}

	/**
	 * Sets up `posts_where` filtering to get posts with a title like the value.
	 *
	 * @since TBD
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
	 * Sets up `posts_where` filtering to get posts with a content like the value.
	 *
	 * @since TBD
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
	 * @since TBD
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
	 * Builds the escaped WHERE entry to match a field not in the entry.
	 *
	 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
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
	 * Sets up `posts_where` filtering to get posts with a filtered content like the value.
	 *
	 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
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
	 * Builds the escaped WHERE entry to match a field in the entry.
	 *
	 * @since TBD
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
	 * @since TBD
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
	 * Proxy method to add a  filter calling the WordPress `add_filter` function
	 * and keep track of it.
	 *
	 * @since TBD
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
	 * Removes all the filters this class applied.
	 *
	 * @since TBD
	 */
	public function remove_filters() {
		foreach ( $this->active_filters as list( $tag, $function_to_add, $priority ) ) {
			remove_filter( $tag, $function_to_add, $priority );
		}
	}

	/**
	 * Clean up before the object destruction.
	 *
	 * @since TBD
	 */
	public function __destruct() {
		// let's make sure we clean up when the object is dereferenced
		$this->remove_filters();
	}

	/**
	 * Builds an "not exists or is not in" media query.
	 *
	 * @since TBD
	 *
	 * @param array|string            $meta_keys On what meta_keys the check should be made.
	 * @param int|string|array $values    A single value, an array of values or a CSV list of values.
	 * @param string           $query_slug
	 *
	 * @return array
	 */
	public static function meta_not_in( $meta_keys, $values, $query_slug ) {
		$meta_keys = Tribe__Utils__Array::list_to_array( $meta_keys );
		$values    = Tribe__Utils__Array::list_to_array( $values );

		if ( empty( $meta_keys ) || empty( $values ) ) {
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
	 * @since TBD
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

		if ( empty( $meta_keys ) || empty( $values ) ) {
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
	 * @since TBD
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
	 * @since TBD
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

		if ( empty( $meta_keys ) || empty( $values ) ) {
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
	 * @since TBD
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

		if ( empty( $meta_keys ) || empty( $values ) ) {
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
}
