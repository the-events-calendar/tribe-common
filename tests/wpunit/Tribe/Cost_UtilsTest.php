<?php

namespace Tribe;

use Exception;
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
		$post_id = wp_insert_post( [
			'post_title' => 'Test event',
		] );

		return [
			'post_id, prefix, symbol=$'         => [ $post_id, '20', 'prefix',  '$',   '$20' ],
			'post_id, prefix, symbol=false'     => [ $post_id, '20', 'prefix',  false, '$20' ],
			'post_id, prefix, symbol=null'      => [ $post_id, '20', 'prefix',  null,  '$20' ],
			'post_id, prefix, symbol=0'         => [ $post_id, '20', 'prefix',  0,     '$20' ],
			'post_id, postfix, symbol=$'        => [ $post_id, '20', 'postfix', '$',   '20$' ],
			'post_id, postfix, symbol=false'    => [ $post_id, '20', 'postfix', false, '20$' ],
			'post_id, postfix, symbol=null'     => [ $post_id, '20', 'postfix', null,  '20$' ],
			'post_id, postfix, symbol=0'        => [ $post_id, '20', 'postfix', 0,     '20$' ],
			'no post_id, prefix, symbol=$'      => [ null,     '20', 'prefix',  '$',   '$20' ],
			'no post_id, prefix, symbol=false'  => [ null,     '20', 'prefix',  false, '$20' ],
			'no post_id, prefix, symbol=null'   => [ null,     '20', 'prefix',  null,  '$20' ],
			'no post_id, prefix, symbol=0'      => [ null,     '20', 'prefix',  0,     '$20' ],
			'no post_id, postfix, symbol=$'     => [ null,     '20', 'postfix', '$',   '20$' ],
			'no post_id, postfix, symbol=false' => [ null,     '20', 'postfix', false, '20$' ],
			'no post_id, postfix, symbol=null'  => [ null,     '20', 'postfix', null,  '20$' ],
			'no post_id, postfix, symbol=0'     => [ null,     '20', 'postfix', 0,     '20$' ],
		];
	}
	/**
	 * Test maybe_format_with_currency
	 *
	 * @test
	 * @dataProvider maybe_format_with_currency_inputs
	 */
	public function test_maybe_format_with_currency( $post_id, $cost, $position, $symbol, $expected ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected, html_entity_decode( $sut->maybe_format_with_currency( $cost, $post_id, $symbol, $position ) ) );
	}

	public function parse_separators_data_set() {
		return [
			'empty_string' => [ '', [ '.', ',' ] ],
			'zero' => [ 0, [ '.', ',' ] ],
			'string' => [ 'Free', [ '.', ',' ] ],
			'int_wo_separators' => [ '23', [ '.', ',' ] ],
			'w_decimal_separator' => [ '23.89', [ '.', ',' ] ],
			'w_thousands_separator' => [ '23,892', [ '.', ',' ] ],
			'w_both_separators' => [ '23,892.4', [ '.', ',' ] ],
			'w_both_separators_inverted' => [ '23.892,4', [ ',', '.' ] ],
			'w_comma_decimal_separator' => [ '23,89', [ ',', '.' ] ],
		];
}
	/**
	 * Test parse_separators
	 * @dataProvider parse_separators_data_set
	 */
	public function test_parse_separators( $value, $expected ) {
		$cost_utils = $this->make_instance();

		$parsed = $cost_utils->parse_separators( $value );

		$this->assertEquals( $expected, $parsed );
	}
}
