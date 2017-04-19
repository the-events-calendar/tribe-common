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

		if ( ! empty( $search ) ) {
			$args['search'] = $search;
		}

		// On versions older than 4.5 taxonomy goes as an Param
		if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) ) {
			$terms = get_terms( $args['taxonomy'], $args );
		} else {
			$terms = get_terms( $args );
		}

		$results = array();

		if ( empty( $args['search'] ) ) {
			$this->sort_terms_hierarchicaly( $terms, $results );
			$results = $this->convert_children_to_array( $results );
		} else {
			foreach ( $terms as $term ) {
				// Prep for Select2
				$term->id = $term->term_id;
				$term->text = $term->name;
				$term->breadcrumbs = array();

				if ( 0 !== (int) $term->parent ) {
					$ancestors = get_ancestors( $term->id, $term->taxonomy );
					$ancestors = array_reverse( $ancestors );
					foreach ( $ancestors as $ancestor ) {
						$ancestor = get_term( $ancestor );
						$term->breadcrumbs[] = $ancestor->name;
					}
				}

				$results[] = $term;
			}
		}

		$data['results'] = $results;
		$data['taxonomies'] = get_taxonomies();

		return $data;
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
			'search' => null,
			'page'   => 0,
			'args'   => array(),
			'source' => null,
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