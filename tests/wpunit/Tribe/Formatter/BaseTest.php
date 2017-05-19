<?php

namespace Tribe\Formatter;

use Tribe__Formatter__Base as Formatter;

class BaseTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Formatter::class, $this->make_instance() );
	}

	/**
	 * @return Formatter
	 */
	protected function make_instance() {
		return new Formatter( new \Tribe__Validator__Base() );
	}

	/**
	 * It should return empty array if raw array is empty
	 *
	 * @test
	 */
	public function it_should_return_empty_array_if_raw_array_is_empty() {
		$sut = $this->make_instance();

		$this->assertEquals( [], $sut->process( [] ) );
	}

	/**
	 * It should format monodimensional arrays.
	 *
	 * @test
	 */
	public function it_should_format_monodimensional_arrays() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'foo' => [ 'required' => true, 'validate_callback' => 'is_string' ],
			'bar' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
			'baz' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'foo' => 'some string',
			'baz' => 89,
		];

		$formatted = $sut->process( $raw );

		$this->assertCount( 2, $formatted );
		$this->assertArrayHasKey( 'foo', $formatted );
		$this->assertArrayHasKey( 'baz', $formatted );
	}

	/**
	 * It should throw if required argument is missing
	 *
	 * @test
	 */
	public function it_should_throw_if_required_argument_is_missing() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'foo' => [ 'required' => true, 'validate_callback' => 'is_string' ],
			'baz' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
			'bar' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'foo' => 'some string',
			'bar' => 89,
		];

		$this->expectException( \InvalidArgumentException::class );

		$sut->process( $raw );
	}

	/**
	 * It should throw if required value does not validate
	 *
	 * @test
	 */
	public function it_should_throw_if_required_value_does_not_validate() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'foo' => [ 'required' => true, 'validate_callback' => 'is_string' ],
			'baz' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
			'bar' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'foo' => 'some string',
			'bar' => 'lorem',
		];

		$this->expectException( \InvalidArgumentException::class );

		$sut->process( $raw );
	}

	/**
	 * It should throw if optional value does not validate
	 *
	 * @test
	 */
	public function it_should_throw_if_optional_value_does_not_validate() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'foo' => [ 'required' => true, 'validate_callback' => 'is_string' ],
			'baz' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
			'bar' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'foo' => 'some string',
			'bar' => 89,
			'baz' => 'lorem',
		];

		$this->expectException( \InvalidArgumentException::class );

		$sut->process( $raw );
	}

	/**
	 * It should remove non supported keys from input array
	 *
	 * @test
	 */
	public function it_should_remove_non_supported_keys_from_input_array() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'foo' => [ 'required' => true, 'validate_callback' => 'is_string' ],
			'baz' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
			'bar' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'foo'   => 'some string',
			'bar'   => 89,
			'baz'   => 23,
			'dolor' => 'sit'
		];


		$formatted = $sut->process( $raw );

		$this->assertArrayHasKey( 'foo', $formatted );
		$this->assertArrayHasKey( 'bar', $formatted );
		$this->assertArrayHasKey( 'baz', $formatted );
		$this->assertArrayNotHasKey( 'dolor', $formatted );
	}


	/**
	 * It should correctly render the context in monodimensional format validation for missin required argument
	 *
	 * @test
	 */
	public function it_should_correctly_render_the_context_in_monodimensional_format_validation_for_missing_required_argument() {
		$sut = $this->make_instance();

		$sut->set_context( [ 'Some context' ] );
		$sut->set_format_map( [
			'foo' => [ 'required' => true, 'validate_callback' => 'is_string' ],
			'baz' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
			'bar' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'foo' => 'some string',
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > baz"/' );

		$sut->process( $raw );
	}

	/**
	 * It should correctly render the context in monodimensional format validation for invalid parameter
	 *
	 * @test
	 */
	public function it_should_correctly_render_the_context_in_monodimensional_format_validation_for_invalid_parameter() {
		$sut = $this->make_instance();

		$sut->set_context( [ 'Some context' ] );
		$sut->set_format_map( [
			'foo' => [ 'required' => true, 'validate_callback' => 'is_string' ],
			'baz' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
			'bar' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'foo' => 'some string',
			'baz' => 'some string',
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > baz"/' );

		$sut->process( $raw );
	}

	/**
	 * It should support nested arrays in format map
	 *
	 * @test
	 */
	public function it_should_support_nested_arrays_in_format_map() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'foo' => [
				'one'   => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'two'   => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'three' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
			],
			'baz' => [
				'four' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'five' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'six'  => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
			],
			'bar' => [
				'seven' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'eight' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'nine'  => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
			],
		] );

		$raw = [
			'foo' => [
				'one' => 13,
				'two' => 18,
			],
			'baz' => [
				'four' => 7,
				'five' => 11,
			],
			'bar' => [
				'seven' => 17,
				'eight' => 123,
			],
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( $raw, $formatted );
	}

	/**
	 * It should throw if required arg is missing in multi level array
	 *
	 * @test
	 */
	public function it_should_throw_if_required_arg_is_missing_in_multi_level_array() {
		$sut = $this->make_instance();

		$sut->set_context( [ 'Some context' ] );
		$sut->set_format_map( [
			'foo' => [
				'one' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'sub' => [
					'two'     => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
					'sub-sub' => [
						'three' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
					]
				]
			],
		] );

		$raw = [
			'foo' => [
				'one' => 13,
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > foo > sub"/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if nested required key is missing
	 *
	 * @test
	 */
	public function it_should_throw_if_nested_required_key_is_missing() {
		$sut = $this->make_instance();

		$sut->set_context( [ 'Some context' ] );
		$sut->set_format_map( [
			'foo' => [
				'one' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'sub' => [
					'two'     => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
					'sub-sub' => [
						'three' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
					]
				]
			],
		] );

		$raw = [
			'foo' => [
				'one' => 13,
				'sub' => [
					'two' => 23,
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > foo > sub > sub-sub"/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if nested required key is invalid
	 *
	 * @test
	 */
	public function it_should_throw_if_nested_required_key_is_invalid() {
		$sut = $this->make_instance();

		$sut->set_context( [ 'Some context' ] );
		$sut->set_format_map( [
			'foo' => [
				'one' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'sub' => [
					'two'     => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
					'sub-sub' => [
						'three' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
					]
				]
			],
		] );

		$raw = [
			'foo' => [
				'one' => 13,
				'sub' => [
					'two'     => 23,
					'sub-sub' => [
						'three' => 'nan',
					],
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > foo > sub > sub-sub > three"/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if nested optional key is invalid
	 *
	 * @test
	 */
	public function it_should_throw_if_nested_optional_key_is_invalid() {
		$sut = $this->make_instance();

		$sut->set_context( [ 'Some context' ] );
		$sut->set_format_map( [
			'foo' => [
				'one' => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
				'sub' => [
					'two'     => [ 'required' => true, 'validate_callback' => 'is_numeric' ],
					'sub-sub' => [
						'three' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
					]
				]
			],
		] );

		$raw = [
			'foo' => [
				'one' => 13,
				'sub' => [
					'two'     => 23,
					'sub-sub' => [
						'three' => 'nan',
					],
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > foo > sub > sub-sub > three"/' );

		$sut->process( $raw );
	}

	/**
	 * It should allow aliasing keys in monodimensional array
	 *
	 * @test
	 */
	public function it_should_allow_aliasing_keys_in_monodimensional_array() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => 'foo' ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'alias' => 'baz' ],
		] );

		$raw = [
			'foo' => 23,
			'two' => 89,
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one' => 23,
			'two' => '89',
		], $formatted );
	}

	/**
	 * It should use the key over the alias in monodimensional arrays
	 *
	 * @test
	 */
	public function it_should_use_the_key_over_the_alias_in_monodimensional_arrays() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => 'foo' ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'alias' => 'baz' ],
		] );

		$raw = [
			'one' => 17,
			'foo' => 23,
			'baz' => 111,
			'two' => 89,
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one' => 17,
			'two' => 89,
		], $formatted );
	}

	/**
	 * It should throw when aliased required key is missing
	 *
	 * @test
	 */
	public function it_should_throw_when_aliased_required_key_is_missing() {
		$sut = $this->make_instance();

		$sut->set_context( [ 'Some context' ] );
		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => 'foo' ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'alias' => 'baz' ],
		] );

		$raw = [
			'baz' => 89,
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/Some context > one \\(foo\\)/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw when aliased key is invalid
	 *
	 * @test
	 */
	public function it_should_throw_when_aliased_key_is_invalid() {
		$sut = $this->make_instance();

		$sut->set_context( [ 'Some context' ] );
		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => 'foo' ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'alias' => 'baz' ],
		] );

		$raw = [
			'foo' => 'nan',
			'two' => 89,
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/Some context > one \\(foo\\)/' );

		$sut->process( $raw );
	}

	/**
	 * It should allow aliasing keys in nested array
	 *
	 * @test
	 */
	public function it_should_allow_aliasing_keys_in_nested_array() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'one' => [
				'sub' => [
					'sub-sub' => [
						'two' => [ 'required' => false, 'validate_callback' => 'is_string', 'alias' => 'bar' ],
					],
				],
			],
		] );

		$raw = [
			'one' => [
				'some-key' => 23,
				'sub'      => [
					'sub-sub' => [
						'bar' => 'some string'
					],
				],
			],
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one' => [
				'sub' => [
					'sub-sub' => [
						'two' => 'some string'
					]
				]
			]
		], $formatted );
	}

	/**
	 * It should use key over alias in nested array
	 *
	 * @test
	 */
	public function it_should_use_key_over_alias_in_nested_array() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'one' => [
				'sub' => [
					'sub-sub' => [
						'two' => [ 'required' => false, 'validate_callback' => 'is_string', 'alias' => 'bar' ],
					],
				],
			],
		] );

		$raw = [
			'one' => [
				'some-key' => 23,
				'sub'      => [
					'sub-sub' => [
						'bar' => 'some string',
						'two' => 'two string'
					],
				],
			],
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one' => [
				'sub' => [
					'sub-sub' => [
						'two' => 'two string'
					]
				]
			]
		], $formatted );
	}

	/**
	 * It should throw if aliased reuired key is missing
	 *
	 * @test
	 */
	public function it_should_throw_if_aliased_reuired_key_is_missing() {
		$sut = $this->make_instance();

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'one' => [
				'sub' => [
					'sub-sub' => [
						'two' => [ 'required' => true, 'validate_callback' => 'is_string', 'alias' => 'bar' ],
					],
				],
			],
		] );

		$raw = [
			'one' => [
				'sub' => [
					'sub-sub' => [
						'not-bar' => 'some string',
					],
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > one > sub > sub-sub > two \\(bar\\)"/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if nested aliased key is invalid
	 *
	 * @test
	 */
	public function it_should_throw_if_nested_aliased_key_is_invalid() {
		$sut = $this->make_instance();

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'one' => [
				'sub' => [
					'sub-sub' => [
						'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'alias' => 'bar' ],
					],
				],
			],
		] );

		$raw = [
			'one' => [
				'sub' => [
					'sub-sub' => [
						'bar' => 'nan',
					],
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > one > sub > sub-sub > two \\(bar\\)"/' );

		$sut->process( $raw );
	}

	/**
	 * It should allow converting valid values in monodimensional array
	 *
	 * @test
	 */
	public function it_should_allow_converting_valid_values_in_monodimensional_array() {
		$sut = $this->make_instance();

		$add_two = function ( $val ) {
			return $val + 2;
		};

		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'conversion_callback' => $add_two ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'conversion_callback' => $add_two ],
		] );

		$raw = [
			'one' => 23,
			'two' => 89,
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one' => 25,
			'two' => 91,
		], $formatted );
	}

	/**
	 * It should throw if conversion fails for key
	 *
	 * @test
	 */
	public function it_should_throw_if_conversion_fails_for_key() {
		$sut = $this->make_instance();

		$throwing = function () {
			throw new \RuntimeException( 'Something happened' );
		};

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'conversion_callback' => $throwing ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'conversion_callback' => $throwing ],
		] );

		$raw = [
			'one' => 23,
			'two' => 89,
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > one"/' );
		$this->expectExceptionMessageRegExp( '/Something happened/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if conversion fails for aliased key
	 *
	 * @test
	 */
	public function it_should_throw_if_conversion_fails_for_aliased_key() {
		$sut = $this->make_instance();

		$throwing = function () {
			throw new \RuntimeException( 'Something happened' );
		};

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => 'foo', 'conversion_callback' => $throwing ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'conversion_callback' => $throwing ],
		] );

		$raw = [
			'foo' => 23,
			'two' => 89,
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > one \\(foo\\)"/' );
		$this->expectExceptionMessageRegExp( '/Something happened/' );

		$sut->process( $raw );
	}

	/**
	 * It should convert nested keys
	 *
	 * @test
	 */
	public function it_should_convert_nested_keys() {
		$sut = $this->make_instance();

		$add_two = function ( $val ) {
			return $val + 2;
		};

		$sut->set_format_map( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'conversion_callback' => $add_two ],
					],
				],
			],
		] );

		$raw = [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => 23
					],
				],
			],
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => 25
					],
				],
			],
		], $formatted );
	}

	/**
	 * It should throw if conversion fails for nested key
	 *
	 * @test
	 */
	public function it_should_throw_if_conversion_fails_for_nested_key() {
		$sut = $this->make_instance();

		$throwing = function () {
			throw new \RuntimeException( 'Something happened' );
		};

		$sut->set_format_map( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'conversion_callback' => $throwing ],
					],
				],
			],
		] );

		$raw = [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => 23
					],
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > main > sub > sub-sub > one"/' );
		$this->expectExceptionMessageRegExp( '/Something happened/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if conversion fails for aliased nested key
	 *
	 * @test
	 */
	public function it_should_throw_if_conversion_fails_for_aliased_nested_key() {
		$sut = $this->make_instance();

		$throwing = function () {
			throw new \RuntimeException( 'Something happened' );
		};

		$sut->set_format_map( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => 'foo', 'conversion_callback' => $throwing ],
					],
				],
			],
		] );

		$raw = [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => 23
					],
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > main > sub > sub-sub > one \\(foo\\)"/' );
		$this->expectExceptionMessageRegExp( '/Something happened/' );

		$sut->process( $raw );
	}

	/**
	 * It should support multiple aliases for a key
	 *
	 * @test
	 */
	public function it_should_support_multiple_aliases_for_a_key() {
		$sut = $this->make_instance();

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => [ 'foo', 'bar', 'baz' ] ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'bar' => 23,
			'two' => 89,
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one' => 23,
			'two' => 89,
		], $formatted );
	}

	/**
	 * It should check for aliases in order
	 *
	 * @test
	 */
	public function it_should_check_for_aliases_in_order() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => [ 'foo', 'bar', 'baz' ] ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'bar' => 23,
			'two' => 89,
			'foo' => 21,
			'baz' => 22,
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one' => 21,
			'two' => 89,
		], $formatted );
	}

	/**
	 * It should support multiple aliases for nested key
	 *
	 * @test
	 */
	public function it_should_support_multiple_aliases_for_nested_key() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => [ 'foo', 'baz', 'bar' ] ],
					],
				],
			],
		] );

		$raw = [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'bar' => 23
					],
				],
			],
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => 23
					],
				],
			],
		], $formatted );
	}

	/**
	 * It should check for multiple aliases in order in nested key
	 *
	 * @test
	 */
	public function it_should_check_for_multiple_aliases_in_order_in_nested_key() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => [ 'foo', 'baz', 'bar' ] ],
					],
				],
			],
		] );

		$raw = [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'bar' => 23,
						'foo' => 21,
						'baz' => 22,
					],
				],
			],
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => 21
					],
				],
			],
		], $formatted );
	}

	/**
	 * It should throw if multiple aliased key is missing
	 *
	 * @test
	 */
	public function it_should_throw_if_multiple_aliased_key_is_missing() {
		$sut = $this->make_instance();

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => [ 'foo', 'bar', 'baz' ] ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'two' => 89,
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > one \\(foo|bar|baz\\)"/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if multiple aliased key is invalid
	 *
	 * @test
	 */
	public function it_should_throw_if_multiple_aliased_key_is_invalid() {
		$sut = $this->make_instance();

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => [ 'foo', 'bar', 'baz' ] ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric' ],
		] );

		$raw = [
			'bar' => 'nan',
			'two' => 89,
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > one \\(foo|bar|baz\\)"/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if multiple aliased required nested key is missing
	 *
	 * @test
	 */
	public function it_should_throw_if_multiple_aliased_required_nested_key_is_missing() {
		$sut = $this->make_instance();

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => [ 'foo', 'baz', 'bar' ] ],
					],
				],
			],
		] );

		$raw = [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'two' => 23,
					],
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > main > sub > sub-sub > one \\(foo|baz|bar\\)"/' );

		$sut->process( $raw );
	}

	/**
	 * It should throw if multiple aliased nested key is invalid
	 *
	 * @test
	 */
	public function it_should_throw_if_multiple_aliased_nested_key_is_invalid() {
		$sut = $this->make_instance();

		$sut->set_context( 'Some context' );
		$sut->set_format_map( [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'alias' => [ 'foo', 'baz', 'bar' ] ],
					],
				],
			],
		] );

		$raw = [
			'main' => [
				'sub' => [
					'sub-sub' => [
						'bar' => 'nan',
					],
				],
			],
		];

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessageRegExp( '/"Some context > main > sub > sub-sub > one \\(foo|baz|bar\\)"/' );

		$sut->process( $raw );
	}

	/**
	 * It should allow conversion callbacks to generate data and have it merged in the processed array
	 *
	 * @test
	 */
	public function it_should_allow_conversion_callbacks_to_generate_data_and_have_it_merged_in_the_processed_array() {
		$sut = $this->make_instance();

		$add_element_one = function ( $value, &$generated_data ) {
			$generated_data['three'] = 'zzap';

			return $value;
		};

		$add_element_two = function ( $value, &$generated_data ) {
			$generated_data['four'] = 'zort';

			return $value;
		};

		$sut->set_format_map( [
			'one' => [ 'required' => true, 'validate_callback' => 'is_numeric', 'conversion_callback' => $add_element_one ],
			'two' => [ 'required' => false, 'validate_callback' => 'is_numeric', 'conversion_callback' => $add_element_two ],
		] );

		$raw = [
			'one' => 23,
			'two' => 89,
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one'   => 23,
			'two'   => 89,
			'three' => 'zzap',
			'four'  => 'zort',
		], $formatted );
	}

	/**
	 * It should allow specifying a default value for the keys
	 *
	 * @test
	 */
	public function it_should_allow_specifying_a_default_value_for_the_fields() {
		$sut = $this->make_instance();

		$sut->set_format_map( [
			'one' => [ 'required' => false, 'default' => 23 ],
		] );

		$raw = [
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( [
			'one' => 23,
		], $formatted );
	}

	/**
	 * It should apply validation and conversion on default value
	 *
	 * @test
	 */
	public function it_should_apply_validation_and_conversion_on_default_value() {
		$sut = $this->make_instance();

		$validated = false;
		$converted = false;

		$validate = function ( $value ) use ( &$validated ) {
			$validated = $value;

			return true;
		};

		$convert = function ( $value ) use ( &$converted ) {
			$converted = $value;

			return $value + 2;
		};

		$sut->set_format_map( [
			'one' => [ 'required' => false, 'default' => 23, 'validate_callback' => $validate, 'conversion_callback' => $convert ],
		] );

		$raw = [
		];

		$formatted = $sut->process( $raw );

		$this->assertEquals( 23, $validated );
		$this->assertEquals( 23, $converted );
		$this->assertEquals( [
			'one' => 25,
		], $formatted );
	}
}