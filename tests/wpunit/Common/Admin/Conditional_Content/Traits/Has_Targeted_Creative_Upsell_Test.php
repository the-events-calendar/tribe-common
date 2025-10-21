<?php
/**
 * Tests for Has_Targeted_Creative_Upsell trait.
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */

declare( strict_types=1 );

namespace TEC\Common\Tests\Admin\Conditional_Content\Traits;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Admin\Conditional_Content\Traits\Has_Targeted_Creative_Upsell;

/**
 * Class Has_Targeted_Creative_Upsell_Test
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */
class Has_Targeted_Creative_Upsell_Test extends WPTestCase {

	/**
	 * Test implementation class.
	 */
	protected $test_class;

	/**
	 * Set up test environment.
	 *
	 * @before
	 */
	public function set_up(): void {
		// Create a test class that uses the trait and overrides get_selected_creative for testing.
		$this->test_class = new class() {
			use Has_Targeted_Creative_Upsell;

			public string $slug                 = 'test-sale';
			public ?array $mock_creative        = null;
			public bool $use_mock_creative      = false;
			public string $mock_page_context    = 'events';

			// Override to return mocked creative for testing.
			protected function get_selected_creative(): ?array {
				if ( $this->use_mock_creative ) {
					return $this->mock_creative;
				}

				// Call trait's implementation.
				$creative_map = $this->get_suite_creative_map();
				$context      = $this->get_admin_page_context();

				if ( empty( $creative_map ) || ! isset( $creative_map[ $context ] ) ) {
					return null;
				}

				$context_creatives = $creative_map[ $context ];

				// Return the first available creative (default in our test).
				foreach ( $context_creatives as $plugin_path => $creative ) {
					if ( 'default' === $plugin_path ) {
						return $creative;
					}
				}

				return null;
			}

			// Override to return mocked page context.
			protected function get_admin_page_context(): string {
				return $this->mock_page_context;
			}

			protected function get_suite_creative_map(): array {
				return [
					'events'  => [
						'default' => [
							'image_url'        => 'https://example.com/default-wide.png',
							'narrow_image_url' => 'https://example.com/default-narrow.png',
							'link_url'         => 'https://example.com/default',
							'alt_text'         => 'Default Events',
						],
					],
					'tickets' => [
						'default' => [
							'image_url'         => 'https://example.com/default-wide.png',
							'narrow_image_url'  => 'https://example.com/default-narrow.png',
							'sidebar_image_url' => 'https://example.com/default-sidebar.png',
							'link_url'          => 'https://example.com/default',
							'alt_text'          => 'Default Tickets',
						],
					],
				];
			}

			protected function get_wide_banner_image() {
				return 'test-sale/top-wide.png';
			}

			protected function get_narrow_banner_image() {
				return 'test-sale/top-narrow.png';
			}

			protected function get_sidebar_image() {
				return 'test-sale/sidebar.png';
			}

			protected function get_link_url(): string {
				return 'https://example.com/fallback';
			}

			protected function get_sale_name(): string {
				return 'Test Sale';
			}

			public function get_slug(): string {
				return $this->slug . '-' . date_i18n( 'Y' );
			}

			protected function get_creative_alt_text(): string {
				$creative = $this->get_selected_creative();

				if ( ! empty( $creative['alt_text'] ) ) {
					return $creative['alt_text'];
				}

				// Fallback to default behavior.
				return $this->get_sale_name() . ' ' . date_i18n( 'Y' );
			}

			// Expose protected methods for testing.
			public function public_has_upsell_opportunity(): bool {
				return $this->has_upsell_opportunity();
			}

			public function public_get_admin_page_context(): string {
				return $this->get_admin_page_context();
			}

			public function public_get_wide_banner_image_url(): string {
				return $this->get_wide_banner_image_url();
			}

			public function public_get_narrow_banner_image_url(): string {
				return $this->get_narrow_banner_image_url();
			}

			public function public_get_sidebar_image_url(): string {
				return $this->get_sidebar_image_url();
			}

			public function public_get_creative_link_url(): string {
				return $this->get_creative_link_url();
			}

			public function public_get_creative_alt_text(): string {
				return $this->get_creative_alt_text();
			}
		};
	}

	/**
	 * Tear down test environment.
	 *
	 * @after
	 */
	public function tear_down(): void {
		// No specific teardown needed.
	}

	/**
	 * Test that has_upsell_opportunity returns true when a creative is found.
	 *
	 * @test
	 */
	public function should_have_upsell_opportunity_when_creative_found() {
		// Mock a creative being found.
		$this->test_class->use_mock_creative = true;
		$this->test_class->mock_creative     = [
			'image_url' => 'https://example.com/ecp-wide.png',
		];

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertTrue( $result, 'Should have upsell opportunity when a creative is found' );
	}

	/**
	 * Test that has_upsell_opportunity returns false when no creative is found.
	 *
	 * @test
	 */
	public function should_not_have_upsell_opportunity_when_no_creative_found() {
		// Mock no creative being found.
		$this->test_class->use_mock_creative = true;
		$this->test_class->mock_creative     = null;

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertFalse( $result, 'Should not have upsell opportunity when no creative is found' );
	}

	/**
	 * Test that get_selected_creative returns default creative from map.
	 *
	 * @test
	 */
	public function should_return_default_creative_from_map() {
		// Ensure we're not using mock - use actual implementation.
		$this->test_class->use_mock_creative = false;

		$reflection = new \ReflectionClass( $this->test_class );
		$method     = $reflection->getMethod( 'get_selected_creative' );
		$method->setAccessible( true );

		$creative = $method->invoke( $this->test_class );

		$this->assertNotNull( $creative );
		$this->assertEquals( 'https://example.com/default-wide.png', $creative['image_url'] );
	}

	/**
	 * Test that get_selected_creative returns null when context not in map.
	 *
	 * @test
	 */
	public function should_return_null_when_context_not_in_map() {
		// Create a test class with limited creative map.
		$limited_test_class = new class() {
			use Has_Targeted_Creative_Upsell;

			protected function get_suite_creative_map(): array {
				return [
					'events' => [
						'default' => [
							'image_url' => 'https://example.com/default.png',
						],
					],
				];
			}

			protected function get_admin_page_context(): string {
				return 'unknown_context';
			}

			protected function get_wide_banner_image() {
				return 'test-sale/top-wide.png';
			}

			protected function get_narrow_banner_image() {
				return 'test-sale/top-narrow.png';
			}

			protected function get_sidebar_image() {
				return 'test-sale/sidebar.png';
			}

			protected function get_link_url(): string {
				return 'https://example.com/fallback';
			}

			protected function get_sale_name(): string {
				return 'Test Sale';
			}

			public function public_get_selected_creative(): ?array {
				return $this->get_selected_creative();
			}
		};

		$creative = $limited_test_class->public_get_selected_creative();

		$this->assertNull( $creative );
	}

	/**
	 * Test that get_admin_page_context returns 'tickets' for tickets pages.
	 *
	 * @test
	 */
	public function should_return_tickets_context_for_tickets_pages() {
		// Set mock page context.
		$this->test_class->mock_page_context = 'tickets';

		$context = $this->test_class->public_get_admin_page_context();

		$this->assertEquals( 'tickets', $context );
	}

	/**
	 * Test that get_admin_page_context returns 'events' for events pages.
	 *
	 * @test
	 */
	public function should_return_events_context_for_events_pages() {
		// Set mock page context.
		$this->test_class->mock_page_context = 'events';

		$context = $this->test_class->public_get_admin_page_context();

		$this->assertEquals( 'events', $context );
	}

	/**
	 * Test that get_admin_page_context returns 'default' when no page detected.
	 *
	 * @test
	 */
	public function should_return_default_context_when_no_page_detected() {
		// Set mock page context.
		$this->test_class->mock_page_context = 'default';

		$context = $this->test_class->public_get_admin_page_context();

		$this->assertEquals( 'default', $context );
	}

	/**
	 * Test that get_wide_banner_image_url returns creative URL when available.
	 *
	 * @test
	 */
	public function should_return_creative_wide_banner_url_when_available() {
		// Mock a creative with custom URL.
		$this->test_class->use_mock_creative = true;
		$this->test_class->mock_creative     = [
			'image_url' => 'https://example.com/custom-wide.png',
		];

		$url = $this->test_class->public_get_wide_banner_image_url();

		$this->assertEquals( 'https://example.com/custom-wide.png', $url );
	}

	/**
	 * Test that get_narrow_banner_image_url returns creative URL when available.
	 *
	 * @test
	 */
	public function should_return_creative_narrow_banner_url_when_available() {
		// Mock a creative with custom URL.
		$this->test_class->use_mock_creative = true;
		$this->test_class->mock_creative     = [
			'narrow_image_url' => 'https://example.com/custom-narrow.png',
		];

		$url = $this->test_class->public_get_narrow_banner_image_url();

		$this->assertEquals( 'https://example.com/custom-narrow.png', $url );
	}

	/**
	 * Test that get_creative_link_url returns creative URL when available.
	 *
	 * @test
	 */
	public function should_return_creative_link_url_when_available() {
		// Mock a creative with custom URL.
		$this->test_class->use_mock_creative = true;
		$this->test_class->mock_creative     = [
			'link_url' => 'https://example.com/custom-link',
		];

		$url = $this->test_class->public_get_creative_link_url();

		$this->assertEquals( 'https://example.com/custom-link', $url );
	}

	/**
	 * Test that get_creative_alt_text returns creative alt text when available.
	 *
	 * @test
	 */
	public function should_return_creative_alt_text_when_available() {
		// Mock a creative with custom alt text.
		$this->test_class->use_mock_creative = true;
		$this->test_class->mock_creative     = [
			'alt_text' => 'Custom Alt Text',
		];

		$alt_text = $this->test_class->public_get_creative_alt_text();

		$this->assertEquals( 'Custom Alt Text', $alt_text );
	}

	/**
	 * Test that get_creative_alt_text returns fallback when no creative alt text.
	 *
	 * @test
	 */
	public function should_return_fallback_alt_text_when_no_creative() {
		// Mock empty creative.
		$this->test_class->use_mock_creative = true;
		$this->test_class->mock_creative     = [];

		$alt_text = $this->test_class->public_get_creative_alt_text();

		$this->assertStringContainsString( 'Test Sale', $alt_text );
		$this->assertStringContainsString( date_i18n( 'Y' ), $alt_text );
	}

	/**
	 * Test that empty creative map returns null.
	 *
	 * @test
	 */
	public function should_return_null_when_creative_map_empty() {
		// Create a test class with empty creative map.
		$empty_test_class = new class() {
			use Has_Targeted_Creative_Upsell;

			protected function get_suite_creative_map(): array {
				return [];
			}

			protected function get_wide_banner_image() {
				return 'test-sale/top-wide.png';
			}

			protected function get_narrow_banner_image() {
				return 'test-sale/top-narrow.png';
			}

			protected function get_sidebar_image() {
				return 'test-sale/sidebar.png';
			}

			protected function get_link_url(): string {
				return 'https://example.com/fallback';
			}

			protected function get_sale_name(): string {
				return 'Test Sale';
			}

			public function public_get_selected_creative(): ?array {
				return $this->get_selected_creative();
			}
		};

		$creative = $empty_test_class->public_get_selected_creative();

		$this->assertNull( $creative );
	}
}
