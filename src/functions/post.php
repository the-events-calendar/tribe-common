<?php
if ( ! function_exists( 'tribe_post_count' ) ) {
	/**
	 * Counts the amount of posts we have for a given Series, it's important to note this will be cached
	 * and not using WP_Query for performance purposes
	 *
	 * @see    wp_count_posts  This method is highly based on the work from WP Core on that Function
	 *
	 * @since  TBD
	 *
	 * @param  array|string  $post_type  Which Post Type we should be counting for
	 * @param  array|int     $parent     Which Parent Post we should be counting for
	 * @param  array|int     $author     Which Post Author we should be counting for
	 *
	 * @return object  Return how many posts in total for each Post Status
	 */
	function tribe_post_count( $post_type = array(), $parent = array(), $author = array() ) {
		global $wpdb;

		$args = (object) array(
			'post_type' => (array) $post_type;
			'parent'    => (array) $parent;
			'author'    => (array) $author;
		);

		/**
		 * Filters the counting SQL
		 *
		 * @since  TBD
		 *
		 * @param  object  $args  {
		 *       @type  array|string  $post_type  Which Post Type we should be counting for
		 *       @type  array|int     $parent     Which Parent Post we should be counting for
		 *       @type  array|int     $author     Which Post Author we should be counting for
		 * }
		 */
		$args = apply_filters( 'tribe_post_count_args', $args );

		// Use an array to build the query, which allows better filtering
		$query = array(
			'select' => 'SELECT post_status, COUNT( * ) AS num_posts',
			'from'   => "FROM {$wpdb->posts}",
			'join'   => '',
			'where'  => array( 'WHERE 1=1' ),
		);

		if ( 1 === count( $args->post_type ) ) {
			$query['where']['post_type'] = $wpdb->prepare( 'post_type = %s', $args->post_type );
		} elseif ( ! empty( $args->post_type ) ) {
			$query['where']['post_type'] = "post_type IN ( '" . implode( "', '", $args->post_type ) . "' )";
		}

		if ( 1 === count( $args->parent ) ) {
			$query['where']['post_parent'] = $wpdb->prepare( 'post_parent = %d', $args->parent );
		} elseif ( ! empty( $args->parent ) ) {
			$query['where']['post_parent'] = "post_parent IN ( '" . implode( "', '", $args->parent ) . "' )";
		}

		if ( 1 === count( $args->author ) ) {
			$query['where']['post_author'] = $wpdb->prepare( 'post_author = %d', $args->author );
		} elseif ( ! empty( $args->author ) ) {
			$query['where']['post_author'] = "post_author IN ( '" . implode( "', '", $args->author ) . "' )";
		}

		if ( is_user_logged_in() ) {
			$attach_privacy = false;
			// Loop on all post types we are dealing to make sure each permission is checked
			foreach ( $args->post_type as $type ) {
				$post_type_object = get_post_type_object( $type );
				if ( $post_type_object && ! current_user_can( $post_type_object->cap->read_private_posts ) ) {
					$attach_privacy = true;
					break;
				}
			}

			if ( $attach_privacy ) {
				$query['where']['privacy'] = $wpdb->prepare(
					"( post_status != 'private' OR ( post_author = %d AND post_status = 'private' ) )",
					get_current_user_id()
				);
			}
		}
		$query['groupby'] = 'GROUP BY post_status';

		/**
		 * Filters the counting SQL
		 *
		 * @since  TBD
		 *
		 * @param  array         $query      An Array with the SQL separated by field (select, from, join, where, groupby)
		 * @param  array|string  $post_type  Which Post Type we should be counting for
		 * @param  array|int     $parent     Which Parent Post we should be counting for
		 * @param  array|int     $author     Which Post Author we should be counting for
		 */
		$query = apply_filters( 'tribe_post_count_sql', $query, $args->post_type, $args->parent, $args->author );

		$cache_key = 'count-' . substr( md5( maybe_serialize( $query ) ), 0, 16 );
		$counts    = tribe( 'cache' )->get( $cache_key );
		$has_cache = false !== $counts;

		if ( ! $has_cache ) {
			// Make the query a string
			$query['where'] = implode( " AND ", $query['where'] );
			$query = implode( " \n", array_filter( $query ) );

			$results = (array) $wpdb->get_results( $query );
			$counts = array_fill_keys( get_post_stati(), 0 );

			foreach ( $results as $row ) {
				$counts[ $row->post_status ] = $row->num_posts;
			}

			// Makes sure we are dealing with integers
			$counts = (object) array_map( 'absint', $counts );

			// Save the found information on the Cache
			tribe( 'cache' )->set( $cache_key, $counts );
		}

		/**
		 * Filters count of posts to allow better control over from the outside
		 *
		 * @since  TBD
		 *
		 * @param  object        $counts     Counter for each Post Status we are talking about
		 * @param  array|string  $post_type  Which Post Type we should be counting for
		 * @param  array|int     $parent     Which Parent Post we should be counting for
		 * @param  array|int     $author     Which Post Author we should be counting for
		 * @param  bool          $cache      Whether we are dealing with cache answer or not
		 */
		return apply_filters( 'tribe_post_count', $counts, $args->post_type, $args->parent, $args->author, $has_cache );
	}
}