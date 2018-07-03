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
	 * Sets up `posts_where` filtering to get posts with a title like the value.
	 *
	 * @since TBD
	 *
	 * @param string $value
	 */
	public function to_get_posts_with_title_like( $value ) {
		$this->query_vars['like']['post_title'][] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_like' ) ) ) {
			add_filter( 'posts_where', array( $this, 'filter_by_like' ), 10, 2 );
		}
	}

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
	 * @return string|void
	 */
	protected function and_field_like( $field, $entry ) {
		/** @var wpdb $wpdb */
		global $wpdb;
		$like = '%' . $wpdb->esc_like( $entry ) . '%';

		return $wpdb->prepare( " AND {$wpdb->posts}.{$field} LIKE %s ", $like );
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
	 * Sets up `posts_where` filtering to get posts with a content like the value.
	 *
	 * @since TBD
	 *
	 * @param string $value
	 */
	public function to_get_posts_with_content_like( $value ) {
		$this->query_vars['like']['post_content'][] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_like' ) ) ) {
			add_filter( 'posts_where', array( $this, 'filter_by_like' ), 10, 2 );
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
	 * Sets up `posts_where` filtering to get posts with a status not in an interval.
	 *
	 * @since TBD
	 *
	 * @param string $value
	 */
	public function to_get_posts_with_status_not_in( $value ) {
		$this->query_vars['status'][] = $value;

		if ( ! has_filter( 'posts_where', array( $this, 'filter_by_status_not_in' ) ) ) {
			add_filter( 'posts_where', array( $this, 'filter_by_status_not_in' ), 10, 2 );
		}

		$args = array( 'suppress_filters' => false );
	}

	/**
	 * Filters the WHERE clause of the query to match posts with a status
	 * not in an interval.
	 *
	 * @since TBD
	 *
	 * @param string   $where
	 * @param WP_Query $query
	 *
	 * @return string
	 */
	public function filter_by_status_not_in( $where, WP_Query $query ) {
		return $this->where_field_not_in( $where, $query, 'post_status' );
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
}
