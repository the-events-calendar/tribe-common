<?php
namespace TEC\Common\Fields;

class Factory_Test extends \Codeception\TestCase\WPTestCase {
	/**
	 * Provides a slug -> Classname list for consumption.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function fields_classname_provider() {
		return [
			[ 'checkbox', 'Checkbox' ],
			[ 'checkbox_bool', 'Checkbox_Bool' ],
			[ 'checkbox_list', 'Checkbox_List' ],
			[ 'color', 'Color' ],
			[ 'dropdown_chosen', 'Dropdown_Chosen' ],
			[ 'dropdown_select2', 'Dropdown_Select2' ],
			[ 'dropdown', 'Dropdown' ],
			[ 'email', 'Email' ],
			[ 'heading', 'Heading' ],
			[ 'html', 'Html' ],
			[ 'image', 'Image' ],
			[ 'license_key', 'License_Key' ],
			[ 'number', 'Number' ],
			[ 'radio', 'Radio' ],
			[ 'text', 'Text' ],
			[ 'textarea', 'Textarea' ],
			[ 'wysiwyg', 'Wysiwyg' ],
		];
	}

	/**
	 * Provides a slug -> Class list for consumption.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function fields_class_provider() {
		return [
			[ 'checkbox_bool', 'Checkbox' ],
			[ 'checkbox_list', 'Checkbox' ],
			[ 'checkbox', 'Checkbox' ],
			[ 'color', 'Color' ],
			[ 'dropdown_chosen', 'Dropdown' ],
			[ 'dropdown_select2', 'Dropdown' ],
			[ 'dropdown', 'Dropdown' ],
			[ 'email', 'Email' ],
			[ 'heading', 'Heading' ],
			[ 'html', 'Html' ],
			[ 'image', 'Image' ],
			[ 'license_key', 'License_Key' ],
			[ 'number', 'Number' ],
			[ 'radio', 'Radio' ],
			[ 'text', 'Text' ],
			[ 'textarea', 'Textarea' ],
			[ 'wrapped_html', 'Html' ],
			[ 'wysiwyg', 'Wysiwyg' ],
		];
	}

	/**
	 * Provides a legacy slug -> Class list for compatibility checks.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function compatibility_types_provider() {
		return [
			[ 'checkbox_bool'    => 'toggle' ],
			[ 'checkbox_list'    => 'checkbox' ],
			[ 'dropdown_chosen'  => 'dropdown' ],
			[ 'dropdown_select2' => 'dropdown' ],
			[ 'wrapped_html'     => 'html' ],
		];
	}

	/**
	 * @test
	 * It should get classname from type arg.
	 * @dataProvider fields_classname_provider
	 */
	public function it_should_get_classname_from_args( $key, $classname ) {
		$converted = Factory::clean_type_to_classname( $key );

		$this->assertEquals( $classname, $converted );
	}

	/**
	 * @test
	 * It should pass valid types.
	 * @dataProvider fields_class_provider
	 */
	public function it_should_pass_valid_types( $key, $classname ) {
		$valid = Factory::validate_type( $key );

		$this->assertTrue( $valid );
	}

	/**
	 * @test
	 * It should fail invalid types.
	 */
	public function it_should_fail_invalid_types() {
		$valid = Factory::validate_type( 'fnord' );

		$this->assertFalse( $valid );
	}

	/**
	 * @test
	 * It should return the proper type.
	 * @dataProvider fields_class_provider
	 */
	public function it_should_return_the_proper_type( $key, $class ) {
		$converted = Factory::normalize_type( $key );
		$class = strtolower( $class );

		$this->assertEquals( $class, $converted );
	}

	/**
	 * @test
	 * It should fail when passed an invalid type
	 * @dataProvider compatibility_types_provider
	 */
	public function it_should_fail_when_passed_an_invalid_type() {
		$converted = Factory::normalize_type( 'fnord' );

		$this->assertNull( $converted );
	}
}
