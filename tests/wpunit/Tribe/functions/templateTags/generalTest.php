<?php
namespace Tribe\functions\templateTags;

class generalTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
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

		/*
		 * Compare attributes, not markup: the `type` attributes and the attribute order of the tags
		 * WordPress prints differ between the WordPress versions the suite runs on.
		 */
		$common_url = home_url( '/wp-content/plugins/the-events-calendar/common/src/resources' );
		$dom        = new \DOMDocument();
		$dom->loadHTML( $output );
		$script = $dom->getElementsByTagName( 'script' )->item( 0 );
		$link   = $dom->getElementsByTagName( 'link' )->item( 0 );

		$this->assertNotNull( $script );
		$this->assertNotNull( $link );
		$this->assertSame( $common_url . '/js/test-script-1.js?ver=1.0.0', $script->getAttribute( 'src' ) );
		$this->assertSame( 'tribe-test-js-js', $script->getAttribute( 'id' ) );
		$this->assertSame( $common_url . '/css/test-style-1.css?ver=1.0.0', $link->getAttribute( 'href' ) );
		$this->assertSame( 'tribe-test-css-css', $link->getAttribute( 'id' ) );
		$this->assertSame( 'stylesheet', $link->getAttribute( 'rel' ) );
		$this->assertLessThan( strpos( $output, '<link' ), strpos( $output, '<script' ) );
	}
}
