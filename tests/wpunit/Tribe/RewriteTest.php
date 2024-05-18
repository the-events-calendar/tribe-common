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
	 * It should correctly handle rewrite rules whose query string is an array
	 *
	 * @test
	 */
	public function should_allow_filtering_dynamic_matchers(): void {
		$rules = [
			'events/([^/]+)/?$'                  => 'index.php?post_type=tribe_events&eventDisplay=custom&tribe_event_display=custom&tribe_event_category=$matches[1]',
			'events/([^/]+)/page/([0-9]{1,})/?$' => 'index.php?post_type=tribe_events&eventDisplay=custom&tribe_event_display=custom&tribe_event_category=$matches[1]&paged=$matches[2]',
		];
		update_option( 'rewrite_rules', $rules );
		global $wp_rewrite;
		$wp_rewrite->rules = $rules;

		$callback_filter = static function( $dynamic_matchers, $query_vars ) {
			$dynamic_matchers['testing-feed/(feed|rdf|rss|rss2|atom)'] = "testing-feed/{$query_vars['feed']}";
			return $dynamic_matchers;
		};

		add_filter( 'tec_common_rewrite_dynamic_matchers', $callback_filter, 15, 2 );

		$query_vars = [
			'feed' => 'rss2',
		];

		// Easy way to test without reflection.
		$test_rewrite = new class( $wp_rewrite ) extends Tribe__Rewrite {
			public function test_get_dynamic_matchers( $query_vars ) {
				return $this->get_dynamic_matchers( $query_vars );
			}
		};

		$matchers = $test_rewrite->test_get_dynamic_matchers( $query_vars );

		$this->assertContains( 'testing-feed/(feed|rdf|rss|rss2|atom)', array_keys( $matchers ), 'Could not find the injected dynamic matcher key.' );
		$this->assertContains( 'testing-feed/rss2', array_values( $matchers ), 'Could not find the injected dynamic matcher value' );
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

		$rewrite = new Tribe__Rewrite( $wp_rewrite );
		// Replaces our percent placeholder.
		$filtered_rules = $rewrite->filter_rewrite_rules_array( $rules );
		foreach ( $filtered_rules as $rule_match => $url ) {
			$this->assertStringNotContainsString( Tribe__Rewrite::PERCENT_PLACEHOLDER, $rule_match );
		}

		// Safely returns bad values.
		$faux_rules = [ 4, false, null, '', [] ];
		foreach ( $faux_rules as $faux ) {
			$this->assertEquals( $faux, $rewrite->filter_rewrite_rules_array( $faux ) );
		}
	}

	public function filtered_matchers_provider(): \Generator {
		// Test localized matchers.
		yield 'events archive page' => [
			home_url( '/index.php?post_type=tribe_events' ),
			home_url( '/events/' ),
			home_url( '/classes/' ),
		];

		yield 'list page' => [
			home_url( '/index.php?post_type=tribe_events&eventDisplay=list' ),
			home_url( '/events/list/' ),
			home_url( '/classes/table/' ),
		];

		// Test dynamic matchers.
		yield 'list page 2' => [
			home_url( '/index.php?post_type=tribe_events&eventDisplay=list&page=2' ),
			home_url( '/events/list/page/2/' ),
			home_url( '/classes/table/semester/2/' ),
		];
	}

	/**
	 * @dataProvider filtered_matchers_provider
	 * @test
	 */
	public function should_allow_filtering_matchers( string $url, string $expected_wo_filter, string $expected_w_filter ): void {
		// TEC post types are hard-coded in the base class code, might as well use them.
		$wp_rewrite         = new \WP_Rewrite();
		$test_rewrite_rules = [
			'/(?:events)/?$'                         => 'index.php?post_type=tribe_events',
			'/(?:events)/(?:list)/?$'                => 'index.php?post_type=tribe_events&eventDisplay=list',
			'/(?:events)/(?:list)/(?:page)/(\d+)/?$' => 'index.php?post_type=tribe_events&eventDisplay=list&page=$matches[1]',
		];
		update_option( 'rewrite_rules', $test_rewrite_rules );
		$wp_rewrite->rules = $test_rewrite_rules;

		// Create a Rewrite class extending the base one.
		$rewrite = new class( $wp_rewrite ) extends Tribe__Rewrite {
			protected function get_post_types() {
				return [ 'tribe_events', 'tribe_venue', 'tribe_organizer' ];
			}

			protected function get_matcher_to_query_var_map() {
				return [
					'list'    => 'eventDisplay',
					'archive' => 'post_type',
					'page'    => 'page',
				];
			}

			public function get_bases( $method = 'regex' ) {
				return [
					'archive' => '(?:events)',
					'list'    => '(?:list)',
					'page'    => '(?:page)',
				];
			}
		};

		// Test without filtering the matchers.
		$clean_url = $rewrite->get_clean_url( $url, true );
		$this->assertEquals( $expected_wo_filter, $clean_url );

		// Test filtering the matchers.
		add_filter( 'tec_common_rewrite_localize_matcher', static function ( $localized_matcher, $base ) {
			$map = [
				'archive' => 'classes',
				'list'    => 'table',
				'page'    => 'semester',
			];

			return $map[ $base ] ?? $localized_matcher;
		}, 10, 2 );

		// Flush the cache to make sure we're not getting a cached value.
		wp_cache_flush();
		$clean_url = $rewrite->get_clean_url( $url, true );
		$this->assertEquals( $expected_w_filter, $clean_url );
	}
}
