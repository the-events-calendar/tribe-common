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

	public function search_terms( $search, $page, $arguments, $source ) {
		$data = array();

		$data['args'] = $arguments;

		return $data;
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