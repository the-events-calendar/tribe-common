<?php

namespace TEC\Common\Integrations\Harbor;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Libraries\Harbor;
use TEC\Common\StellarWP\Uplink\Resources\Plugin as Uplink_Plugin;
use Tribe\Tests\Traits\With_Harbor_State;

/**
 * Exercises the four PUE filter hooks that the Harbor consolidation introduces
 * by seeding real Harbor state (catalog + licensed products + unified key) and
 * then running the WordPress filter chain end-to-end.
 *
 * Each scenario models a real customer state:
 *   - A unified license authorizes a specific subset of products (X1, X2, X3).
 *   - The site has a specific subset of products installed (X1, X3, X4).
 *   - The PUE hooks must transparently hand the unified key to the authorized
 *     overlap and leave the rest alone so legacy per-product keys still work.
 */
class PUE_Test extends WPTestCase {
	use With_Harbor_State;

	/**
	 * The priority airplane-mode (a wp-browser test harness plugin) registers its
	 * `pre_http_request` filter at. Its filter returns a WP_Error for every
	 * outbound request, which short-circuits Harbor's PUE filter before it can
	 * examine the URL. Captured in setUp and detached around HTTP-filter tests.
	 *
	 * @var array{0: object, 1: string}|null
	 */
	private $airplane_mode_callback = null;

	private function detach_airplane_mode(): void {
		if ( ! class_exists( \Airplane_Mode_Core::class ) ) {
			return;
		}
		$instance = \Airplane_Mode_Core::getInstance();
		if ( remove_filter( 'pre_http_request', [ $instance, 'disable_http_reqs' ], 10 ) ) {
			$this->airplane_mode_callback = [ $instance, 'disable_http_reqs' ];
		}
	}

	/**
	 * @after
	 */
	public function reattach_airplane_mode(): void {
		if ( $this->airplane_mode_callback === null ) {
			return;
		}
		add_filter( 'pre_http_request', $this->airplane_mode_callback, 10, 3 );
		$this->airplane_mode_callback = null;
	}

	/**
	 * @test
	 */
	public function it_should_return_unified_key_for_licensed_plugin_option(): void {
		$unified_key = $this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro', 'event-tickets' ] );

		$this->assertSame( $unified_key, get_option( 'pue_install_key_events_calendar_pro' ) );
		$this->assertSame( $unified_key, get_option( 'pue_install_key_event_tickets' ) );
	}

	/**
	 * @test
	 */
	public function it_should_pass_through_option_for_unlicensed_plugin(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$legacy_key = 'legacy-filterbar-key';
		update_option( 'pue_install_key_tribe_filterbar', $legacy_key );

		$this->assertSame( $legacy_key, get_option( 'pue_install_key_tribe_filterbar' ) );

		delete_option( 'pue_install_key_tribe_filterbar' );
	}

	/**
	 * @test
	 */
	public function it_should_ignore_options_not_matching_pue_install_key_prefix(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		update_option( 'some_unrelated_option', 'keep-me' );

		$this->assertSame( 'keep-me', get_option( 'some_unrelated_option' ) );

		delete_option( 'some_unrelated_option' );
	}

	/**
	 * @test
	 */
	public function it_should_translate_tec_slug_to_harbor_slug_for_option_read(): void {
		$unified_key = $this->seed_unified_license_key();
		// Harbor catalog uses 'events-promoter'; option name uses the TEC slug 'promoter'.
		$this->seed_harbor_catalog_for_tec( [ 'events-promoter' ] );

		$this->assertSame( $unified_key, get_option( 'pue_install_key_promoter' ) );
	}

	/**
	 * @test
	 */
	public function it_should_not_override_option_when_no_unified_key_is_stored(): void {
		// No seed_unified_license_key() call — lw_harbor_has_unified_license_key() === false.
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$legacy_key = 'legacy-ecp-key';
		update_option( 'pue_install_key_events_calendar_pro', $legacy_key );

		$this->assertSame( $legacy_key, get_option( 'pue_install_key_events_calendar_pro' ) );

		delete_option( 'pue_install_key_events_calendar_pro' );
	}

	/**
	 * @test
	 */
	public function it_should_return_unified_key_via_uplink_filter_for_licensed_product(): void {
		$unified_key = $this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$resource = new Uplink_Plugin( 'events-calendar-pro', 'ECP', '1.0.0', '/tmp/ecp.php', static::class );

		$result = apply_filters( 'stellarwp/uplink/tec/license_get_key', 'legacy-ecp-key', $resource );

		$this->assertSame( $unified_key, $result );
	}

	/**
	 * @test
	 */
	public function it_should_leave_uplink_license_unchanged_for_unlicensed_product(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$resource = new Uplink_Plugin( 'tribe-filterbar', 'FilterBar', '1.0.0', '/tmp/fb.php', static::class );

		$result = apply_filters( 'stellarwp/uplink/tec/license_get_key', 'legacy-filterbar-key', $resource );

		$this->assertSame( 'legacy-filterbar-key', $result );
	}

	/**
	 * @test
	 */
	public function it_should_translate_tec_slug_to_harbor_slug_for_uplink_filter(): void {
		$unified_key = $this->seed_unified_license_key();
		// Seated plugin has slug 'seating' on the Harbor side, 'tec-seating' on the TEC side.
		$this->seed_harbor_catalog_for_tec( [ 'seating' ] );

		$resource = new Uplink_Plugin( 'tec-seating', 'Seating', '1.0.0', '/tmp/seating.php', static::class );

		$result = apply_filters( 'stellarwp/uplink/tec/license_get_key', 'legacy-seating-key', $resource );

		$this->assertSame( $unified_key, $result );
	}

	/**
	 * @test
	 */
	public function it_should_swap_update_url_to_herald_for_licensed_product(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$result = apply_filters( 'pue_get_update_url', 'https://pue.theeventscalendar.com/', 'events-calendar-pro' );

		$this->assertSame( 'https://herald.stellarwp.com', $result );
	}

	/**
	 * @test
	 */
	public function it_should_leave_update_url_unchanged_for_unlicensed_product(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$result = apply_filters( 'pue_get_update_url', 'https://pue.theeventscalendar.com/', 'tribe-filterbar' );

		$this->assertSame( 'https://pue.theeventscalendar.com/', $result );
	}

	/**
	 * @test
	 */
	public function it_should_intercept_validate_request_with_catalog_response_for_licensed_product(): void {
		$this->detach_airplane_mode();

		$unified_key = $this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$response = apply_filters(
			'pre_http_request',
			false,
			[ 'body' => wp_json_encode( [ 'plugin' => 'events-calendar-pro' ] ) ],
			'https://licensing.stellarwp.com/api/plugins/v2/license/validate'
		);

		$this->assertIsArray( $response );
		$this->assertSame( 200, $response['response']['code'] );

		$decoded = json_decode( $response['body'], true );
		$result  = $decoded['results'][0];

		$this->assertSame( 'events-calendar-pro', $result['plugin'] );
		$this->assertSame( 'events-calendar-pro', $result['slug'] );
		$this->assertSame( $unified_key, $result['license_key'] );
		$this->assertSame( 'Events Calendar Pro', $result['name'] );
		$this->assertSame( '1.0.0', $result['version'] );
		$this->assertSame( '2027-12-31 23:59:59', $result['expiration'] );
	}

	/**
	 * @test
	 */
	public function it_should_pass_http_request_through_for_unlicensed_product(): void {
		$this->detach_airplane_mode();

		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$response = apply_filters(
			'pre_http_request',
			false,
			[ 'body' => wp_json_encode( [ 'plugin' => 'tribe-filterbar' ] ) ],
			'https://licensing.stellarwp.com/api/plugins/v2/license/validate'
		);

		$this->assertFalse( $response );
	}

	/**
	 * @test
	 */
	public function it_should_pass_http_request_through_for_non_validate_path(): void {
		$this->detach_airplane_mode();

		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$response = apply_filters(
			'pre_http_request',
			false,
			[ 'body' => wp_json_encode( [ 'plugin' => 'events-calendar-pro' ] ) ],
			'https://licensing.stellarwp.com/api/plugins/v2/some-other-endpoint'
		);

		$this->assertFalse( $response );
	}

	/**
	 * Production-safety: even when the plugin is licensed and the path matches,
	 * an unexpected host must not be answered from the local catalog.
	 *
	 * @test
	 */
	public function it_should_pass_http_request_through_for_non_allowed_host_in_production(): void {
		$this->detach_airplane_mode();

		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$response = apply_filters(
			'pre_http_request',
			false,
			[ 'body' => wp_json_encode( [ 'plugin' => 'events-calendar-pro' ] ) ],
			'https://attacker.example.com/api/plugins/v2/license/validate'
		);

		$this->assertFalse( $response );
	}

	/**
	 * @test
	 */
	public function it_should_pass_http_request_through_when_response_already_set(): void {
		$this->detach_airplane_mode();

		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$existing_response = [ 'response' => [ 'code' => 418, 'message' => 'Teapot' ], 'body' => '{}' ];

		$response = apply_filters(
			'pre_http_request',
			$existing_response,
			[ 'body' => wp_json_encode( [ 'plugin' => 'events-calendar-pro' ] ) ],
			'https://licensing.stellarwp.com/api/plugins/v2/license/validate'
		);

		$this->assertSame( $existing_response, $response );
	}

	/**
	 * The central matrix: unified key authorizes [X1, X2, X3]. The site has
	 * [X1, X3, X4]. Verifies every hook behaves correctly for each intersection
	 * state in a single scenario: overlap (X1, X3), authorized-but-absent (X2),
	 * and installed-but-unauthorized (X4).
	 *
	 * @test
	 */
	public function it_should_correctly_split_behavior_across_overlapping_and_non_overlapping_products(): void {
		$unified_key = $this->seed_unified_license_key();
		// Authorized by the unified license: X1, X2, X3.
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro', 'event-tickets', 'event-tickets-plus' ] );

		// Site-installed overlap (X1, X3): unified-key behavior expected.
		update_option( 'pue_install_key_events_calendar_pro', 'legacy-ecp' );
		update_option( 'pue_install_key_event_tickets_plus', 'legacy-etp' );

		// Installed-but-unauthorized (X4): legacy key must survive untouched.
		update_option( 'pue_install_key_tribe_filterbar', 'legacy-fb' );

		$this->assertSame( $unified_key, get_option( 'pue_install_key_events_calendar_pro' ), 'X1 overlap returns unified key.' );
		$this->assertSame( $unified_key, get_option( 'pue_install_key_event_tickets_plus' ), 'X3 overlap returns unified key.' );
		$this->assertSame( 'legacy-fb', get_option( 'pue_install_key_tribe_filterbar' ), 'X4 unauthorized keeps its legacy key.' );

		// X2 is authorized but not installed — validate URL still flips to herald
		// if PUE code ever asks about it (e.g. background check).
		$update_url = apply_filters( 'pue_get_update_url', 'https://pue.theeventscalendar.com/', 'event-tickets' );
		$this->assertSame( 'https://herald.stellarwp.com', $update_url );

		delete_option( 'pue_install_key_events_calendar_pro' );
		delete_option( 'pue_install_key_event_tickets_plus' );
		delete_option( 'pue_install_key_tribe_filterbar' );
	}
}
