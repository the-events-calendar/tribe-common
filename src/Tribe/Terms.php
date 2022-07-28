<?php


class Tribe__Terms {

	/**
	 * Translates an array or list of `term_id`s or `slug`s to an array of `term_id`s; if a term is missing and specified by `slug` it
	 * will be created.
	 *
	 * @param      array|string $terms An array or comma separated list of term `term_id` or `slug` or a single `term_id` or `slug`.
	 * @param      string $taxonomy
	 * @param bool $create_missing Whether terms that could not be found by `term_id` or `slug` should be creater or not.
	 *
	 * @return array An array containing the `term_id`s of the created terms.
	 */
	public static function translate_terms_to_ids( $terms, $taxonomy, $create_missing = true ) {
		$terms = is_string( $terms ) ? preg_split( '/\\s*,\\s*/', $terms ) : (array) $terms;

		$term_ids = [];
		foreach ( $terms as $term ) {
			if ( ! $term instanceof WP_Term && ! strlen( trim( $term ) ) ) {
				continue;
			}

			$is_string = is_string( $term );

			if ( $term instanceof WP_Term ) {
				// We already have the term object.
				$term_info = $term->to_array();
			} elseif ( $is_string && get_term_by( 'slug', $term, $taxonomy ) ) {
				// We have a matching term slug.
				$term_info = get_term_by( 'slug', $term, $taxonomy )->to_array();
			} elseif ( $is_string && get_term_by( 'name', $term, $taxonomy ) ) {
				// We have a matching term name.
				$term_info = get_term_by( 'name', $term, $taxonomy )->to_array();
			} elseif ( is_numeric( $term ) ) {
				// We have a matching term ID.
				$term = absint( $term );
				$term_info = get_term( $term, $taxonomy, ARRAY_A );
			} else {
				// Fallback
				$term_info = term_exists( $term, $taxonomy );
			}

			if ( ! $term_info ) {
				// Skip if a non-existent term ID is passed.
				if ( is_numeric( $term ) ) {
					continue;
				}

				// Passed a string - (optionally) create a new term.
				if ( true == $create_missing ) {
					$term_info = wp_insert_term( $term, $taxonomy );
				} else {
					continue;
				}
			}

			if ( is_wp_error( $term_info ) ) {
				continue;
			}

			$term_ids[] = $term_info['term_id'];
		}

		return array_unique( $term_ids );
	}
}
