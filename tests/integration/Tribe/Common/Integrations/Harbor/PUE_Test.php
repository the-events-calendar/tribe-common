<?php

namespace TEC\Common\Integrations\Harbor;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Libraries\Harbor;
use TEC\Common\StellarWP\Uplink\Resources\Plugin as Uplink_Plugin;
use Tribe\Tests\Traits\With_Harbor_State;
use Tribe\Tests\Traits\With_Uopz;

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
	use With_Uopz;

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
	 * Simulate Uplink's license-field AJAX so Harbor rewrites the validation result.
	 *
	 * @param callable $callback Callback to run with the AJAX action set.
	 *
	 * @return mixed
	 */
	private function with_uplink_license_ajax_request( callable $callback ) {
		$previous           = $_REQUEST['action'] ?? null;
		$_REQUEST['action'] = 'pue-validate-key-uplink-tec';

		try {
			return $callback();
		} finally {
			if ( null === $previous ) {
				unset( $_REQUEST['action'] );
			} else {
				$_REQUEST['action'] = $previous;
			}
		}
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

		$this->assertSame( 'https://herald.nexcess.com', $result );
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
	 * Uplink products (Seating, Event Tickets Plus) never enter Tribe__PUE__Checker.
	 * Harbor.php owns this filter and mirrors PUE: reject unmanaged unified keys,
	 * treat Harbor-managed fields as already licensed via Unified License Manager.
	 *
	 * @test
	 * @dataProvider uplink_unified_key_handling_provider
	 */
	public function it_should_handle_unified_key_on_uplink_client_validate_license(
		string $plugin_slug,
		array $licensed_features,
		string $expectation
	): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( $licensed_features );

		$resource = new Uplink_Plugin(
			$plugin_slug,
			$plugin_slug,
			'1.0.0',
			$plugin_slug . '/' . $plugin_slug . '.php',
			\stdClass::class
		);

		$results = new \TEC\Common\StellarWP\Uplink\API\Validation_Response(
			'LWSW-PASTED-INTO-UPLINK-FIELD',
			'local',
			(object) [ 'api_message' => 'would-have-been-remote' ],
			$resource
		);

		$filtered = $this->with_uplink_license_ajax_request(
			static function () use ( $results, $plugin_slug ) {
				return apply_filters(
					'stellarwp/uplink/tec/client_validate_license',
					$results,
					[
						'plugin' => $plugin_slug,
						'key'    => 'LWSW-PASTED-INTO-UPLINK-FIELD',
					]
				);
			}
		);

		if ( 'passthrough' === $expectation ) {
			$this->assertSame( $results, $filtered );

			return;
		}

		$this->assertNotSame( $results, $filtered );
		$message = strtolower( wp_strip_all_tags( $filtered->get_message()->get() ) );

		if ( 'valid' === $expectation ) {
			$this->assertTrue( $filtered->is_valid() );
			$this->assertStringContainsString( 'unified license manager', $message );

			return;
		}

		$this->assertFalse( $filtered->is_valid() );
		$this->assertSame( 'invalid', $filtered->get_result() );
		$this->assertStringContainsString( 'unified license key', $message );
	}

	public function uplink_unified_key_handling_provider(): array {
		return [
			'seating when not harbor-managed is rejected'          => [
				'tec-seating',
				[ 'events-calendar-pro' ],
				'reject',
			],
			'event tickets plus when not harbor-managed is rejected' => [
				'event-tickets-plus',
				[ 'events-calendar-pro' ],
				'reject',
			],
			'seating when harbor-managed is valid via ULM'         => [
				'tec-seating',
				[ 'seating' ],
				'valid',
			],
			'event tickets plus when harbor-managed is valid via ULM' => [
				'event-tickets-plus',
				[ 'event-tickets-plus' ],
				'valid',
			],
		];
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
		$this->assertSame( 'https://herald.nexcess.com', $update_url );

		delete_option( 'pue_install_key_events_calendar_pro' );
		delete_option( 'pue_install_key_event_tickets_plus' );
		delete_option( 'pue_install_key_tribe_filterbar' );
	}

	/**
	 * @test
	 * @dataProvider auth_url_decorator_provider
	 */
	public function it_should_decorate_auth_url_only_for_matching_slug_and_path( string $slug, string $url, bool $should_change ): void {
		$result = apply_filters( 'tec_common_uplink_auth_url', $url, $slug );

		if ( $should_change ) {
			$this->assertNotSame( $url, $result );
		} else {
			$this->assertSame( $url, $result );
		}
	}

	public function auth_url_decorator_provider(): array {
		return [
			'matching slug and path rewrites to portal URL' => [ 'tec-seating', 'https://example.com/seating-connect/', true ],
			'matching slug but wrong path leaves URL untouched' => [ 'tec-seating', 'https://example.com/seating-connect/wrong-path', false ],
			'non-matching slug leaves URL untouched'        => [ 'events-calendar-pro', 'https://example.com/seating-connect/', false ],
		];
	}

	/**
	 * @test
	 */
	public function it_should_skip_remote_validation_for_harbor_managed_product(): void {
		$remote_call_count = 0;
		// validate_key() → request_info() uses wp_remote_post for remote PUE checks.
		$this->set_fn_return(
			'wp_remote_post',
			static function () use ( &$remote_call_count ) {
				++$remote_call_count;

				return new \WP_Error( 'unexpected_remote_validation', 'Remote validation should not be performed.' );
			},
			true
		);

		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$checker  = new \Tribe__PUE__Checker( 'deprecated', 'events-calendar-pro', [], 'events-calendar-pro/events-calendar-pro.php' );
		$response = $checker->validate_key( 'any-key-value' );

		$this->assertSame( 0, $remote_call_count, 'Remote validation should not be performed for Harbor-managed products.' );
		$this->assertSame( 1, $response['status'] );
		$this->assertStringContainsString( 'Unified License Manager', $response['message'] );
	}

	/**
	 * @test
	 */
	public function it_should_reject_unified_key_for_non_harbor_managed_product(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$checker  = new \Tribe__PUE__Checker( 'deprecated', 'tribe-filterbar', [], 'the-events-calendar-filterbar/the-events-calendar-filterbar.php' );
		$response = $checker->validate_key( 'LWSW-PASTED-INTO-WRONG-FIELD' );

		$this->assertSame( 0, $response['status'] );
		$this->assertStringContainsString( 'unified license key', strtolower( wp_strip_all_tags( $response['message'] ) ) );
		$this->assertStringContainsString( '<a href="', $response['message'] );
	}

	/**
	 * @test
	 * @dataProvider pre_validate_key_provider
	 */
	public function it_should_handle_pre_validate_key_filter(
		string $slug,
		string $plugin_file,
		string $key,
		?int $expected_status,
		?string $expected_message_fragment
	): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$checker = new \Tribe__PUE__Checker( 'deprecated', $slug, [], $plugin_file );
		$result  = apply_filters( 'tec_common_pue_pre_validate_key', null, $key, $checker );

		if ( null === $expected_status ) {
			$this->assertNull( $result );

			return;
		}

		$this->assertIsArray( $result );
		$this->assertSame( $expected_status, $result['status'] );
		$this->assertStringContainsString(
			$expected_message_fragment,
			strtolower( wp_strip_all_tags( $result['message'] ) )
		);
	}

	public function pre_validate_key_provider(): array {
		return [
			'legacy key on non-managed product passes through' => [
				'tribe-filterbar',
				'the-events-calendar-filterbar/the-events-calendar-filterbar.php',
				'legacy-product-key',
				null,
				null,
			],
			'harbor-managed product short-circuits as valid'   => [
				'events-calendar-pro',
				'events-calendar-pro/events-calendar-pro.php',
				'any-key-value',
				1,
				'unified license manager',
			],
			'unified key on non-managed product is rejected'  => [
				'tribe-filterbar',
				'the-events-calendar-filterbar/the-events-calendar-filterbar.php',
				'LWSW-PASTED-INTO-WRONG-FIELD',
				0,
				'unified license key',
			],
		];
	}

	/**
	 * @test
	 * @dataProvider save_license_field_value_provider
	 */
	public function it_should_handle_license_field_value_on_save(
		string $field_id,
		string $submitted_value,
		?string $stored_value,
		string $expected_saved
	): void {
		if ( null !== $stored_value ) {
			update_option( $field_id, $stored_value );
		}

		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$saved = apply_filters( 'tribe_settings_save_field_value', $submitted_value, $field_id );

		$this->assertSame( $expected_saved, $saved );

		if ( null !== $stored_value ) {
			delete_option( $field_id );
		}
	}

	public function save_license_field_value_provider(): array {
		return [
			'harbor-managed field ignores submitted value'       => [
				'pue_install_key_events_calendar_pro',
				'LWSW-SHOULD-NOT-BE-STORED',
				'legacy-ecp-key',
				'legacy-ecp-key',
			],
			'unified key on non-managed field keeps stored value' => [
				'pue_install_key_tribe_filterbar',
				'LWSW-PASTED-INTO-WRONG-FIELD',
				'legacy-filterbar-key',
				'legacy-filterbar-key',
			],
			'normal key on non-managed field is stored'            => [
				'pue_install_key_tribe_filterbar',
				'new-legacy-filterbar-key',
				null,
				'new-legacy-filterbar-key',
			],
		];
	}

	/**
	 * @test
	 */
	public function it_should_disable_harbor_managed_legacy_license_fields(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$fields = apply_filters(
			'tribe_license_fields',
			[
				'pue_install_key_events_calendar_pro' => [
					'type'       => 'license_key',
					'attributes' => [],
				],
				'pue_install_key_tribe_filterbar'     => [
					'type'       => 'license_key',
					'attributes' => [],
				],
			]
		);

		$this->assertSame( 'disabled', $fields['pue_install_key_events_calendar_pro']['attributes']['disabled'] );
		$this->assertSame( 'readonly', $fields['pue_install_key_events_calendar_pro']['attributes']['readonly'] );

		$this->assertArrayNotHasKey( 'disabled', $fields['pue_install_key_tribe_filterbar']['attributes'] );
	}

	/**
	 * Uplink fields (Seating, Event Tickets Plus) are HTML, not PUE license_key
	 * fields. Harbor-managed ones must still be locked against editing.
	 *
	 * @test
	 * @dataProvider uplink_license_field_html_provider
	 */
	public function it_should_disable_harbor_managed_uplink_license_fields(
		string $slug,
		array $licensed_features,
		bool $expect_disabled
	): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( $licensed_features );

		$html = '<input type="text" name="pue_install_key_example" value="LWSW-KEY" class="regular-text stellarwp-uplink__settings-field" />';

		$filtered = apply_filters( 'stellarwp/uplink/tec/license_field_html', $html, $slug );

		if ( $expect_disabled ) {
			$this->assertStringContainsString( 'readonly="readonly"', $filtered );
			$this->assertStringContainsString( 'disabled="disabled"', $filtered );
			$this->assertStringContainsString( 'stellarwp-uplink__settings-field', $filtered );

			return;
		}

		$this->assertSame( $html, $filtered );
	}

	public function uplink_license_field_html_provider(): array {
		return [
			'seating when harbor-managed is disabled'              => [
				'tec-seating',
				[ 'seating' ],
				true,
			],
			'event tickets plus when harbor-managed is disabled'   => [
				'event-tickets-plus',
				[ 'event-tickets-plus' ],
				true,
			],
			'seating when not harbor-managed stays editable'       => [
				'tec-seating',
				[ 'events-calendar-pro' ],
				false,
			],
			'event tickets plus when not harbor-managed stays editable' => [
				'event-tickets-plus',
				[ 'events-calendar-pro' ],
				false,
			],
		];
	}

	/**
	 * @test
	 */
	public function it_should_disable_only_uplink_license_text_inputs(): void {
		// Test empty HTML.
		$this->assertSame( '', $this->call_disable_uplink_license_input( '' ) );

		// Test tooltip HTML. Should not be modified.
		$tooltip = '<p class="tooltip description">A valid license key is required for support and updates</p>';
		$this->assertSame( $tooltip, $this->call_disable_uplink_license_input( $tooltip ) );

		// Test license input HTML. The input in the HTML should be disabled and readonly.
		$license_input = '<input type="text" name="pue_install_key_tec_seating" value="LWSW-KEY" class="regular-text stellarwp-uplink__settings-field" />';
		$filtered      = $this->call_disable_uplink_license_input( $license_input );

		$this->assertStringContainsString( 'readonly="readonly"', $filtered );
		$this->assertStringContainsString( 'disabled="disabled"', $filtered );
		$this->assertStringContainsString( 'stellarwp-uplink__settings-field', $filtered );
	}

	/**
	 * Invoke the private HTML helper without going through Harbor field management.
	 */
	private function call_disable_uplink_license_input( string $html ): string {
		$method = new \ReflectionMethod( PUE::class, 'disable_uplink_license_input' );
		$method->setAccessible( true );

		return $method->invoke( new PUE( tribe(), tribe( Harbor::class ) ), $html );
	}
}
