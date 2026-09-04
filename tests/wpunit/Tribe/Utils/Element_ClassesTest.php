<?php
namespace Tribe\Utils;

class Example_Object {
	/**
	 * @var string
	 */
	protected $string_value;

	/**
	 * @var bool
	 */
	public static $invoked = false;

	public function __construct( $value ) {
		$this->string_value = $value;
	}

	public function __toString() {
		return $this->string_value;
	}

	public static function flag() {
		self::$invoked = true;

		return 'flagged';
	}
}

class Element_ClassesTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_be_instantiable() {
		$el_classes = new Element_Classes;
		$this->assertInstanceOf( Element_Classes::class, $el_classes );
	}

	public function classes_to_array_provider() {
		return [
			'string' => [
				// Value
				'test-class',
				// Expected
				[
					'test-class'
				],
			],

			'integer' => [
				// Value
				134,
				// Expected
				[],
			],

			'float' => [
				// Value
				1.5,
				// Expected
				[],
			],


			'numeric' => [
				// Value
				'15',
				// Expected
				[],
			],

			'remove-invalid-chars' => [
				// Value
				'test-/@\\\'":;!>#=$<+25_values',
				// Expected
				[
					'test-25_values'
				],
			],

			'allow-case-sensititity' => [
				// Value
				'Test-Upper-CaSe',
				// Expected
				[
					'Test-Upper-CaSe'
				],
			],

			'empty-string' => [
				// Value
				'',
				// Expected
				[],
			],

			'empty-array' => [
				// Value
				[],
				// Expected
				[],
			],

			'array-of-values' => [
				// Value
				[
					'test-string',
					134,
					'my-other-15-string',
				],
				// Expected
				[
					'test-string',
					'my-other-15-string',
				],
			],

			'dynamic-callable-__toString' => [
				// Value
				new Example_Object( 'dynamic-callable-__toString' ),
				// Expected
				[
					'dynamic-callable-__toString',
				],
			],

			'array-with-keys-and-bool-conditionals' => [
				// Value
				[
					'test-string' => true,
					134 => true,
					'test-false' => false,
					222 => false,
				],
				// Expected
				[
					'test-string',
				],
			],

		];
	}

	/**
	 * @todo  Support the following case:
	 *
	 *	'array-with-keys-and-callable-conditional' => [
	 *		// Value
	 *		[
	 *			'test-closure-true' => function() {
	 *				return true;
	 *			},
	 *			'test-closure-false' => function() {
	 *				return false;
	 *			},
	 *			'test-callable-string-true' => '__return_true',
	 *			'test-callable-string-false' => '__return_false',
	 *		],
	 *		// Expected
	 *		[
	 *			'test-closure-true',
	 *			'test-callable-string-true',
	 *		],
	 *	],
	 *
	 */

	/**
	 * @test
	 *
	 * @dataProvider classes_to_array_provider
	 *
	 * @group utils
	 */
	public function it_should_return_expected_classes_in_array_values( $value, $expected ) {
		$el_classes = new Element_Classes( $value );
		$actual = $el_classes->get_classes();
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_allow_overwriting_values() {
		$base_classes = [
			'test-class',
			'test-condition-class'       => true,
			'test-false-condition-class' => false,
			'override'                   => true,
		];

		$el_classes = new Element_Classes( $base_classes, [ 'override' => false ] );
		$actual = $el_classes->get_classes();
		$expected = [ 'test-class', 'test-condition-class' ];

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_return_space_sparated_string() {
		$base_classes = [
			'test-class-one',
			'test-class-two',
			'test-class-three',
		];

		$el_classes = new Element_Classes( $base_classes, 'test-class-four' );
		$actual = $el_classes->get_classes_as_string();
		$expected = 'test-class-one test-class-two test-class-three test-class-four';

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_return_class_attribute_with_spaces() {
		$base_classes = [
			'test-class-one',
			'test-class-two',
			'test-class-three',
		];

		$el_classes = new Element_Classes( $base_classes, 'test-class-four' );
		$actual = $el_classes->get_attribute();
		$expected = ' class="test-class-one test-class-two test-class-three test-class-four" ';

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_return_class_attribute_with_spaces_when_invoked() {
		$base_classes = [
			'test-class-one',
			'test-class-two',
			'test-class-three',
		];

		$el_classes = new Element_Classes;
		$actual = $el_classes( $base_classes, 'test-class-four' );
		$expected = ' class="test-class-one test-class-two test-class-three test-class-four" ';

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_return_class_attribute_with_spaces_when_cast_to_string() {
		$base_classes = [
			'test-class-one',
			'test-class-two',
			'test-class-three',
		];

		$el_classes = new Element_Classes( $base_classes, 'test-class-four' );
		$actual = (string) $el_classes;
		$expected = ' class="test-class-one test-class-two test-class-three test-class-four" ';

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_not_invoke_a_string_callable_map_value() {
		$this->setExpectedIncorrectUsage( 'Tribe\Utils\Element_Classes::parse_array' );

		Example_Object::$invoked = false;

		$el_classes = new Element_Classes( [ 'test-class' => Example_Object::class . '::flag' ] );
		$el_classes->get_classes();

		$this->assertFalse( Example_Object::$invoked );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_not_invoke_an_array_callable() {
		$this->setExpectedIncorrectUsage( 'Tribe\Utils\Element_Classes::parse' );

		Example_Object::$invoked = false;

		$el_classes = new Element_Classes( [ [ Example_Object::class, 'flag' ] ] );
		$el_classes->get_classes();

		$this->assertFalse( Example_Object::$invoked );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_evaluate_closure_conditionals() {
		$el_classes = new Element_Classes(
			[
				'visible-class' => static function () {
					return true;
				},
				'hidden-class'  => static function () {
					return false;
				},
			]
		);

		$this->assertEquals( [ 'visible-class' ], $el_classes->get_classes() );
	}

	/**
	 * Runs a callback while capturing whether `_doing_it_wrong()` fires during it.
	 *
	 * @param callable $callback
	 *
	 * @return bool
	 */
	protected function fires_doing_it_wrong( callable $callback ) {
		$fired = false;
		$catch = static function () use ( &$fired ) {
			$fired = true;
		};

		add_action( 'doing_it_wrong_run', $catch );
		add_filter( 'doing_it_wrong_trigger_error', '__return_false' );

		$callback();

		remove_action( 'doing_it_wrong_run', $catch );
		remove_filter( 'doing_it_wrong_trigger_error', '__return_false' );

		return $fired;
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_warn_when_a_string_callable_map_value_is_used() {
		$this->setExpectedIncorrectUsage( 'Tribe\Utils\Element_Classes::parse_array' );

		$fired = $this->fires_doing_it_wrong( function () {
			$el_classes = new Element_Classes( [ 'test-class' => Example_Object::class . '::flag' ] );
			$el_classes->get_classes();
		} );

		$this->assertTrue( $fired );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_warn_when_a_bare_function_name_map_value_is_used() {
		$this->setExpectedIncorrectUsage( 'Tribe\Utils\Element_Classes::parse_array' );

		$fired = $this->fires_doing_it_wrong( function () {
			$el_classes = new Element_Classes( [ 'test-class' => '__return_true' ] );
			$el_classes->get_classes();
		} );

		$this->assertTrue( $fired );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_warn_when_an_array_callable_is_used() {
		$this->setExpectedIncorrectUsage( 'Tribe\Utils\Element_Classes::parse' );

		$fired = $this->fires_doing_it_wrong( function () {
			$el_classes = new Element_Classes( [ [ Example_Object::class, 'flag' ] ] );
			$el_classes->get_classes();
		} );

		$this->assertTrue( $fired );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_warn_once_per_callable_value_when_multiple_are_present() {
		$this->setExpectedIncorrectUsage( 'Tribe\Utils\Element_Classes::parse_array' );

		$fired_count = 0;
		$count       = static function () use ( &$fired_count ) {
			$fired_count++;
		};

		add_action( 'doing_it_wrong_run', $count );
		add_filter( 'doing_it_wrong_trigger_error', '__return_false' );

		$el_classes = new Element_Classes(
			[
				'first'  => '__return_true',
				'second' => '__return_false',
			]
		);
		$el_classes->get_classes();

		remove_action( 'doing_it_wrong_run', $count );
		remove_filter( 'doing_it_wrong_trigger_error', '__return_false' );

		$this->assertEquals( 2, $fired_count );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_not_warn_for_plain_string_and_bool_values() {
		$fired = $this->fires_doing_it_wrong( function () {
			$el_classes = new Element_Classes( [ 'test-class' => true, 'other-class' ] );
			$el_classes->get_classes();
		} );

		$this->assertFalse( $fired );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_not_warn_for_closure_map_values() {
		$fired = $this->fires_doing_it_wrong( function () {
			$el_classes = new Element_Classes(
				[
					'test-class' => static function () {
						return true;
					},
				]
			);
			$el_classes->get_classes();
		} );

		$this->assertFalse( $fired );
	}
}
