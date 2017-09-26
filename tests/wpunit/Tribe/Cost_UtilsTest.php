<?php

namespace Tribe;

use Tribe__Cost_Utils as Cost_Utils;

class Cost_UtilsTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Cost_Utils::class, $this->make_instance() );
	}

	/**
	 * @return Cost_Utils
	 */
	protected function make_instance() {
		return new Cost_Utils();
	}

	public function parse_currency_symbol_inputs() {
		return [
			[ 'foo', false ],
			[ 'foo bar', false ],
			[ '2$', '$' ],
			[ '$2', '$' ],
			[ '$ 2', '$' ],
			[ '2 $', '$' ],
			[ 'free, 20$, 30$', '$' ],
			[ [ 'free', '20$', '30$' ], '$' ],
			[ [ 'free', '20¥', '30$' ], false ],
			[ [ '20¥', '30$' ], false ],
			[ [ 'free', '20¥' ], '¥' ],
		];
	}

	/**
	 * Test parse_currency_symbol
	 *
	 * @test
	 * @dataProvider parse_currency_symbol_inputs
	 */
	public function test_parse_currency_symbol( $input, $expected ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected, $sut->parse_currency_symbol( $input ) );
	}

	public function parse_currency_position_inputs(  ) {
		return [
			[ 'foo', false ],
			[ 'foo bar', false ],
			[ '2$', 'postfix' ],
			[ '$2', 'prefix' ],
			[ '$ 2', 'prefix' ],
			[ '2 $', 'postfix' ],
			[ 'free, 20$, 30$', 'postfix' ],
			[ [ 'free', '20$', '30$' ], 'postfix' ],
			[ [ 'free', '20¥', '30$' ], false ],
			[ [ '20¥', '30$' ], false ],
			[ [ 'free', '20¥' ], 'postfix' ],
			[ [ '$20', '30$' ], false ],
			[ [ '$ 20', '$30' ], 'prefix' ],
			[ [ '20$', '30 $' ], 'postfix' ],
		];
}
	/**
	 * Test parse_currency_position
	 *
	 * @test
	 * @dataProvider parse_currency_position_inputs
	 */
	public function test_parse_currency_position($input,$expected) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected, $sut->parse_currency_position( $input ) );
	}

	public function maybe_format_with_currency_inputs() {
		return [
			['20','$','postfix', '20$'],
			['20','$','prefix', '$20'],
		];
	}
	/**
	 * Test maybe_format_with_currency
	 *
	 * @test
	 * @dataProvider maybe_format_with_currency_inputs
	 */
	public function test_maybe_format_with_currency( $cost, $symbol, $position, $expected ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected, $sut->maybe_format_with_currency( $cost, null, $symbol, $position ) );
	}

}