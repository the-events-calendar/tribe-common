<?php

namespace Tribe;

use Tribe__Rewrite;

class RewriteTest extends \Codeception\TestCase\WPTestCase {
	private $wp_rewrite_backup;

	/**
	 * @before
	 */
	public function backup_wp_rewrite(): void {
		global $wp_rewrite;

		$this->wp_rewrite_backup = $wp_rewrite;
	}

	/**
	 * @after
	 */
	public function restore_wp_rewrite(): void {
		global $wp_rewrite;

		$wp_rewrite = $this->wp_rewrite_backup;
	}

	/**
	 * It should correctly handle rewrite rules whose query string is an array
	 *
	 * @test
	 */
	public function should_correctly_handle_rewrite_rules_whose_query_string_is_an_array(): void {
		$rules = [
			'events/([^/]+)/?$'                  => 'index.php?post_type=tribe_events&eventDisplay=custom&tribe_event_display=custom&tribe_event_category=$matches[1]',
			'events/([^/]+)/page/([0-9]{1,})/?$' => 'index.php?post_type=tribe_events&eventDisplay=custom&tribe_event_display=custom&tribe_event_category=$matches[1]&paged=$matches[2]',
			// Some plugins will store callbacks in the form of callable arrays in the rewrite rules.
			'test/some-path/([^/]+)/?$'          => [ __CLASS__, 'callback' ]
		];
		update_option( 'rewrite_rules', $rules );
		global $wp_rewrite;
		$wp_rewrite->rules = $rules;

		$rewrite = new \Tribe__Events__Rewrite( $wp_rewrite );

		$canonical = $rewrite->get_canonical_url( home_url( '/test/some-path/123/' ) );
		$this->assertEquals( home_url( '/test/some-path/123/' ), $canonical );
	}

	/**
	 * @test
	 */
	public function should_filter_rewrite_rules_array(): void {
		$rules = [
			'events/' . Tribe__Rewrite::PERCENT_PLACEHOLDER . '/?$' => 'index.php?post_type=tribe_events&eventDisplay=custom&tribe_event_display=custom',
			'events/?$'                                             => 'index.php?post_type=tribe_events&eventDisplay=custom&tribe_event_display=custom',
			'faux/' . Tribe__Rewrite::PERCENT_PLACEHOLDER           => 'index.php?post_type=tribe_faux&eventDisplay=custom&tribe_event_display=custom',
		];

		global $wp_rewrite;

		$rewrite        = new Tribe__Rewrite( $wp_rewrite );
		// Replaces our percent placeholder.
		$filtered_rules = $rewrite->filter_rewrite_rules_array( $rules );
		foreach ( $filtered_rules as $rule_match => $url ) {
			$this->assertNotContains( Tribe__Rewrite::PERCENT_PLACEHOLDER, $rule_match );
		}

		// Safely returns bad values.
		$faux_rules = [ 4, false, null, '', [] ];
		foreach ( $faux_rules as $faux ) {
			$this->assertEquals( $faux, $rewrite->filter_rewrite_rules_array( $faux ) );
		}
	}
}
