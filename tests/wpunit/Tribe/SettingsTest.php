<?php
namespace Tribe;

use Faker\Factory;
use Tribe__Settings as Settings;

class SettingsTest extends \Codeception\TestCase\WPTestCase {

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

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Settings::class, $sut );
	}

	/**
	 * @test
	 * it should not add network pages on non multisite installation
	 */
	public function it_should_not_add_network_pages_on_non_multisite_installation() {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->should_setup_network_pages() );
	}

	/**
	 * @test
	 */
	public function it_should_print_the_default_closing_form_element() {
		$sut = $this->make_instance();

		ob_start();
		$sut->settings_form_element_close();

		$this->assertSame( '</form>', ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function it_should_strip_disallowed_markup_from_the_filtered_closing_form_element() {
		$text    = Factory::create()->sentence();
		$script  = 'alert("' . $text . '")';
		$allowed = '<p>' . $text . '</p></form>';
		add_filter(
			'tribe_settings_closing_form_element',
			static function () use ( $allowed, $script ) {
				return '<script>' . $script . '</script>' . $allowed;
			}
		);
		$sut = $this->make_instance();

		ob_start();
		$sut->settings_form_element_close();

		/* wp_kses() drops the disallowed tags but keeps their text, which prints as inert text. */
		$this->assertSame( $script . $allowed, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function it_should_strip_the_input_and_select_controls_from_the_filtered_closing_form_element() {
		$faker = Factory::create();
		$label = $faker->word();
		add_filter(
			'tribe_settings_closing_form_element',
			static function () use ( $faker, $label ) {
				return '<input type="hidden" name="' . $faker->word() . '" value="' . $faker->word() . '">'
					. '<select name="' . $faker->word() . '"><option value="' . $faker->word() . '">' . $label . '</option></select></form>';
			}
		);
		$sut = $this->make_instance();

		ob_start();
		$sut->settings_form_element_close();

		$this->assertSame( $label . '</form>', ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function it_should_keep_the_form_attributes_the_post_allow_list_permits() {
		$markup = '</form><form action="' . Factory::create()->url() . '" method="post"></form>';
		add_filter(
			'wp_kses_allowed_html',
			static function ( $tags, $context ) {
				if ( 'post' === $context ) {
					$tags['form'] = [
						'action' => true,
						'method' => true,
					];
				}

				return $tags;
			},
			10,
			2
		);
		add_filter(
			'tribe_settings_closing_form_element',
			static function () use ( $markup ) {
				return $markup;
			}
		);
		$sut = $this->make_instance();

		ob_start();
		$sut->settings_form_element_close();

		$this->assertSame( $markup, ob_get_clean() );
	}

	/**
	 * @return Settings
	 */
	private function make_instance() {
		return new Settings();
	}
}
