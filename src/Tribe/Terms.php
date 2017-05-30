<?php


class Tribe__Terms {

	public static function translate_terms_to_ids( $terms, $taxonomy ) {
		$terms = (array) $terms;

		$term_ids = array();
		foreach ( $terms as $term ) {
			if ( ! strlen( trim( $term ) ) ) {
				continue;
			}

			if ( is_numeric( $term ) ) {
				$term = absint( $term );
				$term_info = get_term( $term, $taxonomy, ARRAY_A );
			} else {
				$term_info = term_exists( $term, $taxonomy );
			}

			if ( ! $term_info ) {
				// Skip if a non-existent term ID is passed.
				if ( is_numeric( $term ) ) {
					continue;
				}
				$term_info = wp_insert_term( $term, $taxonomy );
			}

			if ( is_wp_error( $term_info ) ) {
				continue;
			}

			$term_ids[] = $term_info['term_id'];
		}

		return array_unique( $term_ids );
	}
}