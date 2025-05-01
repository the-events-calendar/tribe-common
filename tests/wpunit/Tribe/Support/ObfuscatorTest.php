<?php
namespace Tribe;

use Tribe__Support__Obfuscator as Obfuscator;

class ObfuscatorTest extends \Codeception\TestCase\WPTestCase {

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
		$this->assertInstanceOf( 'Tribe__Support__Obfuscator', new Obfuscator() );
	}

	public function should_obfuscate_keys_provider() {
		return [
			[ 'foo', false ],
			[ '_some_prefix_lookalike', false ],
			[ 'prefix_lookalike', false ],
			[ 'some_prefix_', true ],
			[ '_prefix_', true ],
			[ 'some_prefix_foo', true ],
			[ 'almost_some_prefix_here', false ],
			[ '_prefix_foo', true ],
			[ '_prefix_some_prefix_foo', true ],
			[ 'some_prefix__prefix_foo', true ],
		];
	}

	/**
	 * @test
	 * it should identify keys that should be obfuscated
	 * @dataProvider should_obfuscate_keys_provider
	 */
	public function it_should_identify_keys_that_should_be_obfuscated( $key, $should_obfuscate ) {
		$sut = new Obfuscator( [ 'some_prefix_', '_prefix_' ] );
		$this->assertEquals( $should_obfuscate, $sut->should_obfuscate( $key ) );
	}

	public function obfuscateable_provider() {
		return [
			[ '1', '#' ],
			[ '12', '##' ],
			[ '123', '###' ],
			[ '1234', '1###' ],
			[ '12345', '1####' ],
			[ '123456', '1####6' ],
			[ '1234567', '1#####7' ],
			[ '12345678', '1######8' ],
			[ '123456789', '1#######9' ],
			[ '1234567890', '12######90' ],
			[ '12345678901234567890', '123##############890' ],
			[ '12345678901234567890123456789012', '1234########################9012' ],
			[ '15bf11111111111111111111111111111111a5d5', '15bf################################a5d5' ],
		];
	}

	/**
	 * @test
	 * it should properly obfuscate
	 * @dataProvider obfuscateable_provider
	 */
	public function it_should_properly_obfuscate( $value, $obfuscated ) {
		$sut = new Obfuscator( [ 'some_prefix_' ] );
		$this->assertEquals( $obfuscated, $sut->obfuscate( 'some_prefix_key', $value ) );
	}
}
