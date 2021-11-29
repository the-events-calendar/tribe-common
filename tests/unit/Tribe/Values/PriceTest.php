<?php

namespace Tribe\Values;

class PriceTest extends \Codeception\Test\Unit {

	public function test_get_initial_value_returns_unchanged() {
		$initial_value = 10;
		$price         = new Price( $initial_value );
		$this->assertEquals( $initial_value, $price->get_initial_representation() );
		$price->set_value( 25 );
		$this->assertEquals( $initial_value, $price->get_initial_representation() );
	}

	/**
	 * @dataProvider numerical_values
	 */
	public function test_normalize_clears_number_formatting( $value, $expected ) {
		$price      = new Price();
		$normalized = $price->normalize( $value );
		$this->assertEquals( $expected, $normalized );
	}

	/**
	 * @dataProvider numerical_values
	 */
	public function test_set_value_updates_all_available_formats( $value, $float, $integer, $formatted ) {
		$price = new Price( 0 );
		$price->set_value( $value );
		$this->assertEquals( $float, $price->get_float() );
		$this->assertEquals( $integer, $price->get_integer() );
		$this->assertEquals( $formatted, $price->get_formatted() );
	}

	public function numerical_values() {
		return [
			[ 10, 10.0, 1000, '$ 1,000.00' ],
/*			[ 10.0, 10.0 ],
			[ '10', 10.0 ],
			[ 'R$ 10', 10.0 ],
			[ 'R$10', 10.0 ],
			[ '$ 1.234,56', 1234.56 ],
			[ '$1,234.56', 1234.56 ],
			[ '1,234.56 .د.م.', 1234.56 ],
			[ '1,234.56 ฿', 1234.56 ],
			[ '1,234.56 ₺', 1234.56 ],
			[ '1,234.56 ﷼', 1234.56 ],
			[ '1.234,56 $', 1234.56 ],
			[ '1.234,56 Ft', 1234.56 ],
			[ '1.234,56 kr', 1234.56 ],
			[ '1.234,56 Kč', 1234.56 ],
			[ '1.234,56 p.', 1234.56 ],
			[ '1.234,56 zł', 1234.56 ],
			[ '1.234,56 ₫', 1234.56 ],
			[ 'HK$ 1,234.56', 1234.56 ],
			[ 'kr. 1.234,56', 1234.56 ],
			[ 'R.1,234.56', 1234.56 ],
			[ 'R$ 1.234,56', 1234.56 ],
			[ 'RM 1,234.56', 1234.56 ],
			[ '£1,234.56', 1234.56 ],
			[ '¥ 1,234.56', 1234.56 ],
			[ '¥ 1,234.56', 1234.56 ],
			[ '₩ 1,234.56', 1234.56 ],
			[ '₪ 1.234,56', 1234.56 ],
			[ '€1.234,56', 1234.56 ],
			[ '₱ 1,234.56', 1234.56 ],
			[ '₹ 1,234.56', 1234.56 ],
			[ '元 1,234.56', 1234.56 ],
			[ '1e+3', 1000.00 ],
			[ 'abc.df', 0.0 ],
			[ 'abcdf', 0.0 ],
*/
		];
	}
}
