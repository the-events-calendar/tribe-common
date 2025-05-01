<?php
namespace Tribe\functions\templateTags;

class generalTest extends \Codeception\TestCase\WPTestCase {

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown(): void {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * Test tribe_format_currency
	 */
	public function test_tribe_format_currency() {
		$post_id = $this->factory->post->create();
		add_filter(
			'tribe_currency_symbol',
			function () {
				return 'Q';
			}
		);
		add_filter(
			'tribe_reverse_currency_position',
			function () {
				return false;
			}
		);

		$this->assertEquals( 'Q12', tribe_format_currency( 12, $post_id ) );
		$this->assertEquals( 'F12', tribe_format_currency( 12, $post_id, 'F' ) );
		$this->assertEquals( '12F', tribe_format_currency( 12, $post_id, 'F', true ) );
		$this->assertEquals( '12Q', tribe_format_currency( 12, $post_id, 'Q', true ) );
	}

	/**
	 * Test tribe_asset_print_group
	 */
	public function test_tribe_asset_print_group() {
		// Ensure the version will stay fixed.
		add_filter(
			'tribe_asset_version',
			static function () {
				return '1.0.0';
			}
		);
		// Register a group of assets that would never be printed.
		tribe_assets(
			\Tribe__Main::instance(),
			[
				[ 'tribe-test-css', 'test-style-1.css' ],
				[ 'tribe-test-js', 'test-script-1.js' ],
			],
			// This action cannot possibly have happened.
			'test_test_test',
			[
				// This would never be queued in normal conditions.
				'conditionals' => '__return_false',
				'groups'       => [ 'test-group' ],
			]
		);

		$output = tribe_asset_print_group( 'test-group', false );

		$expected_tmpl = <<< TAG
<script type="text/javascript" src="{{ common_url }}/js/test-script-1.js?ver=1.0.0" id="tribe-test-js-js"></script>
<link rel='stylesheet' id='tribe-test-css-css' href='{{ common_url }}/css/test-style-1.css?ver=1.0.0' type='text/css' media='all' />

TAG;
		$expected      = str_replace( '{{ common_url }}', home_url( '/wp-content/plugins/the-events-calendar/common/src/resources' ), $expected_tmpl );
		$this->assertEquals( $expected, $output );
	}
}
