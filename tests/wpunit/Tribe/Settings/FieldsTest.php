<?php
namespace TEC\Common\Settings;

class FieldsTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	public function fields_classname_provider() {
		return [
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
			[ 'wrapped_html', 'Wrapped_Html' ],
			[ 'wysiwyg', 'Wysiwyg' ],
		];
	}

	/**
	 * @test
	 * It should get classname from type arg.
	 * @dataProvider fields_classname_provider
	 */
	public function it_should_get_classname_from_args( $key, $classname ) {
		$converted = Field::clean_type_to_classname( $key );
		$this->assertEquals( $classname, $converted );
	}
}
