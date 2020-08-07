<?php

namespace Tribe\functions;

class queryTest extends \Codeception\TestCase\WPTestCase {
	public function test_tribe_normalize_orderby_data_provider() {
		return [
			'empty_string'                 => [ '', [] ],
			'empty_array'                  => [ [], [] ],
			'array_w_empty_string'         => [ [ '' ], [] ],
			'array_of_keys'                => [
				[ 'title', 'event_date', 'foo' ],
				[ 'title' => 'ASC', 'event_date' => 'ASC', 'foo' => 'ASC' ],
			],
			'array_of_keys_and_directions' => [
				[ 'title' => 'ASC', 'event_date' => 'DESC', 'foo' => 'DESC' ],
				[ 'title' => 'ASC', 'event_date' => 'DESC', 'foo' => 'DESC' ],
			],
			'mixed_array'                  => [
				[ 'title', 'event_date' => 'DESC', 'event_duration' => 'ASC', 'venue' ],
				[ 'title' => 'ASC', 'event_date' => 'DESC', 'event_duration' => 'ASC', 'venue' => 'ASC' ],
			],
		];
	}

	/**
	 * @dataProvider test_tribe_normalize_orderby_data_provider
	 */
	public function test_tribe_normalize_orderby( $input, $expected ) {
		$this->assertEquals( $expected, tribe_normalize_orderby( $input, 'ASC' ) );
	}
}
