<?php
namespace Tribe\Utils;

class Example_Object {
	/**
	 * @var string
	 */
	protected $string_value;

	public function __construct( $value ) {
		$this->string_value = $value;
	}

	public function __toString() {
		return $this->string_value;
	}

	public static function get_classes() {
		return 'static-callable';
	}

	public function get_classes_dynamic() {
		return 'dynamic-callable';
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

			'static-callable' => [
				// Value
				[ Example_Object::class, 'get_classes' ],
				// Expected
				[
					'static-callable',
				],
			],

			'dynamic-callable' => [
				// Value
				[ new Example_Object( '' ), 'get_classes_dynamic' ],
				// Expected
				[
					'dynamic-callable',
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
}