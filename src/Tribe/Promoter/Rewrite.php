<?php

/**
 * Rewrite configuration class for Promoter.
 *
 * @since TBD
 */
class Tribe__Promoter__Rewrite extends Tribe__Rewrite {

	/**
	 * Tribe__Promoter__Rewrite constructor.
	 *
	 * @param WP_Rewrite|null $wp_rewrite Rewrite object.
	 */
	public function __construct( WP_Rewrite $wp_rewrite = null ) {
		$this->rewrite = $wp_rewrite;
	}

	/**
	 * Generate the rewrite rules.
	 *
	 * @since TBD
	 *
	 * @param WP_Rewrite $wp_rewrite Rewrite object.
	 */
	public function filter_generate( WP_Rewrite $wp_rewrite ) {
		$this->setup( $wp_rewrite );

		$this->add( array( '{{ promoter-auth }}' ), array( 'promoter-auth-check' => 1 ) );

		$wp_rewrite->rules = $this->rules + $wp_rewrite->rules;
	}

	/**
	 * Add rewrite tag.
	 *
	 * @since TBD
	 */
	public function add_rewrite_tags() {
		add_rewrite_tag( '%promoter-auth-check%', '([^&]+)' );
	}

}
