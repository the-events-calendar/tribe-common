<?php

namespace Tribe\Formatter;

use Tribe__Formatter__Base as Formatter;

class BaseTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \Tribe__REST__Validator_Interface
	 */
	protected $validator;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->validator = $this->prophesize( \Tribe__REST__Validator_Interface::class );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

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
		return new Formatter( new \Tribe__REST__Validator() );
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
}