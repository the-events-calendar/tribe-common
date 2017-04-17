<?php

/**
 * Class Tribe__Ajax__Dropdown
 *
 * Handles common AJAX operations.
 */
class Tribe__Ajax__Dropdown {

	public function hook() {
		add_action( 'wp_ajax_tribe_dropdown', array( $this, 'route' ) );
		add_action( 'wp_ajax_nopriv_tribe_dropdown', array( $this, 'route' ) );
	}

	public function search_terms( $search, $page, $args, $source ) {
		$data = array();

		if ( empty( $args['taxonomy'] ) ) {
			$this->error( esc_attr__( 'Cannot look for Terms without a taxonomy', 'tribe-common' ) );
		}
		// We always want all the fields so we overwrite it
		$args['fields'] = 'all';
		$args['get'] = 'all';

		// On versions older than 4.5 taxonomy goes as an Param
		if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) ) {
			$terms = get_terms( $args['taxonomy'], $args );
		} else {
			$terms = get_terms( $args );
		}

		// $results = array();
		// foreach ( $terms as $key => $term ) {
		// 	$ancestor = get_ancestors( $term->term_id, $term->taxonomy );
		// 	$term->id = $term->term_id;
		// 	$term->text = $term->name;
		// 	$term->pad = max( 0, count( $ancestor ) );

		// 	$results[] = $term;
		// }

		$results = array();
		$this->sort_terms_hierarchicaly( $terms, $results );
		$results = $this->convert_children_to_array( $results );

		$data['results'] = $results;
		$data['taxonomies'] = get_taxonomies();

		return $data;
	}


	private function _get_terms( $search, $page, $args, $source ) {
		$data = array();

		if ( empty( $args['taxonomy'] ) ) {
			$this->error( esc_attr__( 'Cannot look for Terms without a taxonomy', 'tribe-common' ) );
		}
		// We always want all the fields so we overwrite it
		$args['fields'] = 'all';
		$args['get'] = 'all';
		$args['page'] = $page;
		$args['number'] = 20;

		// On versions older than 4.5 taxonomy goes as an Param
		if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) ) {
			$terms = get_terms( $args['taxonomy'], $args );
		} else {
			$terms = get_terms( $args );
		}

		// Set variable because $args['number'] can be subsequently overridden.
		$number = $args['number'];

		$args['offset'] = $offset = ( $page - 1 ) * $number;

		// Convert it to table rows.
		$count = 0;

		if ( is_taxonomy_hierarchical( $args['taxonomy'] ) && ! isset( $args['orderby'] ) ) {
			// We'll need the full set of terms then.
			$args['number'] = $args['offset'] = 0;
		}

		if ( is_taxonomy_hierarchical( $taxonomy ) && ! isset( $args['orderby'] ) ) {
			if ( ! empty( $args['search'] ) ) {// Ignore children on searches.
				$children = array();
			} else {
				$children = _get_term_hierarchy( $taxonomy );
			}
			// Some funky recursion to get the job done( Paging & parents mainly ) is contained within, Skip it for non-hierarchical taxonomies for performance sake
			$this->_rows( $taxonomy, $terms, $children, $offset, $number, $count );
		}
	}

	private function _rows( $taxonomy, $terms, &$children, $start, $per_page, &$count, $parent = 0, $level = 0 ) {

		$end = $start + $per_page;

		foreach ( $terms as $key => $term ) {

			if ( $count >= $end )
				break;

			if ( $term->parent != $parent && empty( $_REQUEST['s'] ) )
				continue;

			// If the page starts in a subtree, print the parents.
			if ( $count == $start && $term->parent > 0 && empty( $_REQUEST['s'] ) ) {
				$my_parents = $parent_ids = array();
				$p = $term->parent;
				while ( $p ) {
					$my_parent = get_term( $p, $taxonomy );
					$my_parents[] = $my_parent;
					$p = $my_parent->parent;
					if ( in_array( $p, $parent_ids ) ) // Prevent parent loops.
						break;
					$parent_ids[] = $p;
				}
				unset( $parent_ids );

				$num_parents = count( $my_parents );
				while ( $my_parent = array_pop( $my_parents ) ) {
					echo "\t";
					$this->single_row( $my_parent, $level - $num_parents );
					$num_parents--;
				}
			}

			if ( $count >= $start ) {
				echo "\t";
				$this->single_row( $term, $level );
			}

			++$count;

			unset( $terms[$key] );

			if ( isset( $children[$term->term_id] ) && empty( $_REQUEST['s'] ) )
				$this->_rows( $taxonomy, $terms, $children, $start, $per_page, $count, $term->term_id, $level + 1 );
		}
	}

	public function sort_terms_hierarchicaly( &$terms, &$into, $parent = 0 ) {
		foreach ( $terms as $i => $term ) {
			if ( $term->parent === $parent ) {
				// Prep for Select2
				$term->id = $term->term_id;
				$term->text = $term->name;

				$into[ $term->term_id ] = $term;
				unset( $terms[ $i ] );
			}
		}

		foreach ( $into as $term ) {
			$term->children = array();
			$this->sort_terms_hierarchicaly( $terms, $term->children, $term->term_id );
		}
	}

	public function convert_children_to_array( $results ) {
		if ( isset( $results->children ) ) {
			$results->children = $this->convert_children_to_array( $results->children );
		} else {
			foreach ( $results as $key => $item ) {
				$item = $this->convert_children_to_array( $item );
			}
		}

		return array_values( $results );
	}

	public function parse_params( $params ) {
		$defaults = array(
			'page'   => 0,
			'source' => null,
			'args'   => array(),
			'search' => null,
		);

		$arguments = wp_parse_args( $params, $defaults );

		// Return Object just for the sake of making it simpler to read
		return (object) $arguments;
	}

	public function route() {
		// Push all POST params into a Default set of data
		$args = $this->parse_params( $_POST );

		if ( empty( $args->source ) ) {
			$this->error( esc_attr__( 'Missing data source for this dropdown', 'tribe-common' ) );
		}

		// Define a Filter to allow external calls to our Select2 Dropboxes
		$filter = sanitize_key( 'tribe_dropdown_' . $args->source );
		if ( has_filter( $filter ) ) {
			$data = apply_filters( $filter, array(), $args->search, $args->page, $args->args, $args->source );
		} else {
			$data = call_user_func_array( array( $this, $args->source ), (array) $args );
		}

		// if we got a empty dataset we return an error
		if ( empty( $data ) ) {
			$this->error( esc_attr__( 'Empty data set for this dropdown', 'tribe-common' ) );
		} else {
			$this->success( $data );
		}
	}

	private function success( $data ) {
		// We need a Results item for Select2 Work
		if ( ! isset( $data['results'] ) ) {
			$data['results'] = array();
		}

		wp_send_json_success( $data );
	}

	private function error( $message ) {
		$data = array(
			'message' => $message,
			'results' => array(),
		);
		wp_send_json_error( $data );
	}

	public function __call( $name, $arguments ) {
		$message = __( 'The "%s" source is invalid and cannot be reached on "%s" instance.', 'tribe-common' );
		return $this->error( sprintf( $message, $name, __CLASS__ ) );
	}
}