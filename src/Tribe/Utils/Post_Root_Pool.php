<?php


class Tribe__Utils__Post_Root_Pool {

	/**
	 * @var string
	 */
	protected $pool_transient_name = 'tribe_ticket_prefix_pool';

	/**
	 * @var array|bool
	 */
	protected static $prefix_pool = false;

	/**
	 * @var string
	 */
	protected $root_separator = '-';

	/**
	 * @var array
	 */
	protected $postfix = 1;

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
	 * @param string $post_name
	 *
	 * @param string $postfix
	 *
	 * @return string
	 */
	protected function build_root_from( $post_name, $postfix = '' ) {
		$candidate = $this->build_root_candidate( $post_name, $postfix );

		$initial_candidate = $candidate;

		while ( $this->is_in_pool( $candidate ) ) {
			$postfix   = $this->postfix;
			$candidate = $initial_candidate . '-' . $postfix;
			$this->postfix ++;
		}

		$this->postfix = 1;

		$this->insert_root_in_pool( $candidate );

		return $candidate;
	}

	/**
	 * @return string
	 */
	public function get_pool_transient_name() {
		return $this->pool_transient_name;
	}

	/**
	 * @param $string
	 *
	 * @return string
	 */
	protected function uc_first_letter( $string ) {
		return is_numeric( $string ) ? $string : strtoupper( $string[0] );
	}

	/**
	 * @param string $candidate
	 */
	protected function is_in_pool( $candidate ) {
		return in_array( $candidate, $this->fetch_pool() );
	}

	/**
	 * @return array
	 */
	protected function fetch_pool() {
		if ( false === self::$prefix_pool ) {
			$this->maybe_init_pool();
		}

		return self::$prefix_pool;
	}

	protected function maybe_init_pool() {
		self::$prefix_pool = get_transient( $this->pool_transient_name );
		if ( self::$prefix_pool === false ) {
			self::$prefix_pool = array();
			set_transient( $this->pool_transient_name, array() );
		}
	}

	/**
	 * @param string $unique_root
	 */
	protected function insert_root_in_pool( $unique_root ) {
		$prefix_pool       = $this->fetch_pool();
		$prefix_pool[]     = $unique_root;
		self::$prefix_pool = $prefix_pool;
		set_transient( $this->pool_transient_name, $prefix_pool );
	}

	public static function reset_pool() {
		self::$prefix_pool = false;
	}

	/**
	 * @param $post_name
	 * @param $postfix
	 *
	 * @return string
	 */
	protected function build_root_candidate( $post_name, $postfix ) {
		$frags = explode( '-', $post_name );

		$candidate = implode( '', array_map( 'strtoupper', $frags ) );

		if ( strlen( $candidate ) > 9 ) {
			$frags     = array_filter( $frags );
			$candidate = implode( '', array_map( array( $this, 'uc_first_letter' ), $frags ) );
		}

		$candidate = $candidate . $postfix;

		return $candidate;
	}

	/**
	 * Primes the post pool.
	 *
	 * @param array $pool
	 * @param bool  $override_transient If `true` the transient too will be overwritten.
	 */
	public function set_pool( array $pool, $override_transient = false ) {
		self::$prefix_pool = $pool;
		if ( $override_transient ) {
			set_transient( $this->pool_transient_name, $pool );
		}
	}
}