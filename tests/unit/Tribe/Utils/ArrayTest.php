<?php

use Tribe__Utils__Array as Arr;

class Tribe__Utils__Array_Test extends \Codeception\Test\Unit {
	public function shape_filter_data_provider() {
		$test_shape = [
			'a'       =>
				[
					'deeply' =>
						[
							'nested' =>
								[
									'key',
									'key_2'
								],
						],
				],
			'another' =>
				[
					'deeply' =>
						[
							'nested' =>
								[
									'key',
									'key_2'
								],
						],
				],
			'key_2',
			'key_3'
		];
		$test_optional_shape = [
			'?a'      =>
				[
					'deeply' =>
						[
							'nested' =>
								[
									'key',
									'key_2'
								],
						],
				],
			'another' =>
				[
					'deeply' =>
						[
							'?nested' =>
								[
									'key',
									'key_2'
								],
						],
				],
			'?key_2',
			'key_3'
		];

		return [
			'empty array, non empty shape'           => [
				'array'    => [],
				'shape'    => $test_shape,
				'expected' => [
					'a'       =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => null,
											'key_2' => null
										],
								],
						],
					'another' =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => null,
											'key_2' => null
										],
								],
						],
					'key_2'   => null,
					'key_3'   => null,
				],
			],
			'empty array, optional shape'            => [
				'array'    => [],
				'shape'    => $test_optional_shape,
				'expected' => [
					'another' =>
						[
							'deeply' => [],
						],
					'key_3'   => null
				],
			],
			'empty array, empty shape'               => [
				'array'    => [],
				'shape'    => [],
				'expected' => [],
			],
			'non-empty array, empty shape'           => [
				'array'    => [
					'a'       =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => 23,
											'key_2' => 89
										],
								],
						],
					'another' =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => 'foo',
											'key_2' => 'bar',
										],
								],
						],
					'key_2'   => 'foo',
					'key_3'   => 'bar',
				],
				'shape'    => [],
				'expected' => [],
			],
			'test shape on non-empty array'          => [
				'array'    => [
					'a'     =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => 23,
											'key_2' => 89
										],
								],
						],
					'key_2' => 'foo',
				],
				'shape'    => $test_shape,
				'expected' => [
					'a'       =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => 23,
											'key_2' => 89
										],
								],
						],
					'another' =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => null,
											'key_2' => null,
										],
								],
						],
					'key_2'   => 'foo',
					'key_3'   => null,
				],
			],
			'test optional shape on non-empty array' => [
				'array'    => [
					'a'     =>
						[
							'b' => [ 'c' => [ 'd' ] ],
						],
					'key_3' => 'foo-bar'
				],
				'shape'    => $test_optional_shape,
				'expected' => [
					'a'       =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => null,
											'key_2' => null,
										],
								],
						],
					'another' =>
						[
							'deeply' => [],
						],
					'key_3'   => 'foo-bar',
				],
			],
			'test shape on diff. sorted array'       => [
				'array'    => [
					'key_2'   => 'bar-baz',
					'another' =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key_2' => 'bar',
											'key'   => 'foo',
										],
								],
						],
					'key_3'   => 'foo-bar',
					'a'       =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key_2' => 89,
											'key'   => 23,
										],
								],
						],
				],
				'shape'    => $test_shape,
				'expected' => [
					'a'       =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => 23,
											'key_2' => 89,
										],
								],
						],
					'another' =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key'   => 'foo',
											'key_2' => 'bar',
										],
								],
						],
					'key_2'   => 'bar-baz',
					'key_3'   => 'foo-bar',
				],
			],

			'test optional shape on diff. sorted array' => [
				'array'    => [
					'key_3'   => 'foo-bar',
					'another' =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key_2' => 'bar',
											'key'   => 'foo',
										],
								],
						],
					'key_2'   => 'bar-baz',
				],
				'shape'    => $test_optional_shape,
				'expected' => [
					'another' =>
						[
							'deeply' =>
								[
									'nested' =>
										[
											'key_2' => 'bar',
											'key'   => 'foo',
										],
								],
						],
					'key_2'   => 'bar-baz',
					'key_3'   => 'foo-bar',
				],
			]
		];
	}

	/**
	 * @dataProvider shape_filter_data_provider
	 */
	public function test_shape_filter( array $input, array $shape, array $expected ) {
		$shaped = Arr::shape_filter( $input, $shape );
		$this->assertEquals( $expected, $shaped );
	}

	public function usearch_data_provider() {
		$value_gt_needle = static function ( $needle, $value ): bool {
			return $value > $needle;
		};
		$matches_needle = static function ( $needle, $value ): bool {
			return $value === $needle;
		};
		$callback_using_value_and_key = static function ( $needle, $value, $key ): bool {
			return $value === $needle && $key === 'three';
		};

		return [
			'empty haysatck'                                    => [ 'foo', [], false, $value_gt_needle ],
			'haystack not contains needle'                      => [
				23,
				[ 'foo', 'bar', 'baz' ],
				false,
				$value_gt_needle
			],
			'haystack contains 1 needle'                        => [ 23, [ 89, 23, 113, 17 ], 1, $matches_needle ],
			'haystack contains multiple needles'                => [
				23,
				[ 89, 23, 113, 17, 23, 11, 23 ],
				1,
				$matches_needle
			],
			'haystack contains multiple needles w/ string keys' => [
				23,
				[ 'one' => 89, 'two' => 23, 'three' => 23 ],
				'two',
				$matches_needle
			],
			'callback using value and key'                      => [
				23,
				[ 'one' => 89, 'two' => 23, 'three' => 23 ],
				'three',
				$callback_using_value_and_key
			],
		];
	}

	/**
	 * @dataProvider usearch_data_provider
	 */
	public function test_usearch( $needle, array $haystack, $expected, callable $callback ) {
		$this->assertEquals( $expected, Arr::usearch( $needle, $haystack, $callback ) );
	}
}
