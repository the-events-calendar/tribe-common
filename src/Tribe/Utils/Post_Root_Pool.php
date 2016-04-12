<?php


class Tribe__Utils__Post_Root_Pool {

	/**
	 * @var string
	 */
	protected $root_separator = '-';

	/**
	 * @var array
	 */
	protected $prefixes = array();

	public function __construct(  ) {
}
	/**
	 * Generates a unique root for a post using its post_name.
	 *
	 * @param WP_Post $post
	 *
	 * @return string
	 */
	public function generate_unique_root( WP_Post $post ) {
		$post_name = $post->post_name;

		$root = $this->build_root_from( $post_name );

		return $root . $this->root_separator;
	}

	/**
	 * @param $post_name
	 *
	 * @return string
	 */
	protected function build_root_from( $post_name, $unique_prefix = '' ) {
		$frags = explode( '-', $post_name );

		$candidate = implode( '', array_map( 'strtoupper', $frags ) );

		if ( strlen( $candidate ) < 10 ) {
			return $candidate;
		} else {
			$frags     = array_filter( $frags );
			$candidate = implode( '', array_map( array( $this, 'uc_first_letter' ), $frags ) );
		}

		if ( $this->is_in_pool( $candidate ) ) {
			$candidate = $this->build_root_from( $candidate, next( $this->prefixes) );
		}

		return $candidate . $unique_prefix;
	}

	/**
	 * @param $string
	 *
	 * @return string
	 */
	protected function uc_first_letter( $string ) {
		return is_numeric( $string ) ? $string : strtoupper( $string[0] );
	}
}