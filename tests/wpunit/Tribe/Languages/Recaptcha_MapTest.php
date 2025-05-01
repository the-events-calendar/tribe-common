<?php
namespace Tribe\Languages;

use Tribe__Languages__Recaptcha_Map as Map;

class Recaptcha_MapTest extends \Codeception\TestCase\WPTestCase {

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
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Map::class, $sut );
	}

	public function language_codes_support_provider() {
		return [
			[ '', false ],
			[ 'en-US', true ],
			[ 'en_US', true ],
			[ 'en', true ],
			[ 'fr', true ],
			[ 'zh-HK', true ],
			[ 'zh_HK', true ],
			[ 'fil', true ],
			[ 'zh_hk', false ],
			[ 'EN', false ],
			[ 'EN-US', false ],
			[ 'foo', false ],
		];
	}

	/**
	 * @test
	 * it should mark language codes as supported or not supported
	 * @dataProvider language_codes_support_provider
	 */
	public function it_should_mark_language_codes_as_supported_or_not_supported( $code, $expected ) {
		$sut = $this->make_instance();

		if ( $expected === true ) {
			$this->assertTrue( $sut->is_supported( $code ) );
		} else {
			$this->assertFalse( $sut->is_supported( $code ) );
		}
	}

	public function language_codes_conversion_provider() {
		return [
			[ '', false ],
			[ 'en-US', 'en' ],
			[ 'en_US', 'en' ],
			[ 'en', 'en' ],
			[ 'fr', 'fr' ],
			[ 'zh-HK', 'zh-HK' ],
			[ 'zh_HK', 'zh-HK' ],
			[ 'fil', 'fil' ],
			[ 'zh_hk', false ],
			[ 'EN', false ],
			[ 'EN-US', false ],
			[ 'foo', false ],
		];
	}

	/**
	 * @test
	 * it should convert language codes from WP format to Recaptcha format
	 * @dataProvider language_codes_conversion_provider
	 */
	public function it_should_convert_language_codes_from_wp_format_to_recaptcha_format( $code, $expected ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected, $sut->convert_language_code( $code ) );
	}

	/**
	 * @return Map
	 */
	private function make_instance() {
		return new Map();
	}
}
