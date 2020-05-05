<?php

namespace Tribe;
use Tribe\Common\Tests\Dummy_Plugin_Origin;
use Tribe__Template as Template;

include_once codecept_data_dir( 'classes/Dummy_Plugin_Origin.php' );

class TemplateTest extends \Codeception\TestCase\WPTestCase {

	function setUp() {
		parent::setUp();
	}

	/**
	 * It should allow setting a number of values at the same time
	 *
	 * @test
	 */
	public function should_allow_setting_a_number_of_values_at_the_same_time() {
		$template = new Template();

		$template->set_values( [
			'twenty-three' => '23',
			'eighty-nine'  => 89,
			'an_array'     => [ 'key' => 2389 ],
			'an_object'    => (object) [ 'key' => 89 ],
			'a_null_value' => null,
		] );

		$this->assertEquals( '23', $template->get( 'twenty-three' ) );
		$this->assertEquals( 89, $template->get( 'eighty-nine' ) );
		$this->assertEquals( [ 'key' => 2389 ], $template->get( 'an_array' ) );
		$this->assertEquals( (object) [ 'key' => 89 ], $template->get( 'an_object' ) );
		$this->assertEquals( null, $template->get( 'a_null_value' ) );
	}

	/**
	 * It should allow setting contextual values without overriding the primary values
	 *
	 * @test
	 */
	public function should_allow_setting_contextual_values_without_overriding_the_primary_values() {
		$template      = new Template();
		$global_set    = [
			'twenty-three' => '23',
			'eighty-nine'  => 89,
		];
		$global_values = $global_set;

		$template->set_values( $global_set, false );

		$this->assertEquals( $global_values, $template->get_global_values() );
		$this->assertEquals( [], $template->get_local_values() );
		$this->assertEquals( $global_values, $template->get_values() );

		$local_set = [
			'eighty-nine' => 2389,
			'another_var' => 'another_value',
		];
		$template->set_values( $local_set );

		$this->assertEquals( $global_values, $template->get_global_values() );
		$this->assertEquals( $local_set, $template->get_local_values() );
		$this->assertEquals( array_merge( $global_values, $local_set ), $template->get_values() );
	}

	/**
	 * @test
	 */
	public function should_include_entry_points_on_template_html() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-template:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-template:before_container_close', function () {
			echo '%%before_container_close%%';
		} );

		$html = $template->template( 'dummy-template', [], false );

		$this->assertContains( '<div class="test">%%after_container_open%%', $html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * @test
	 */
	public function should_include_custom_entry_points_on_template_html() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point::custom_entry_point', function () {
			echo '%%custom_entry_point%%';
		} );

		$customer_entry_point_html = $template->do_entry_point( 'custom_entry_point', false );
		$last_tag_html             = '</div>';
		$html                      = $template->template( 'dummy-template', [], false );
		$html                      = \Tribe\Utils\Strings::replace_last( $last_tag_html, $last_tag_html . $customer_entry_point_html, $html );

		$this->assertContains( '</div>%%custom_entry_point%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		// Invalid Test 1
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-01:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-01:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-01', [], false );

		$this->assertNotContains( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );

		// Invalid Test 2
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-02:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-02:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-02', [], false );

		$this->assertNotContains( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );

		// Invalid Test 3
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-03:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-03:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-03', [], false );

		$this->assertNotContains( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );

		// Invalid Test 4
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-04:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-04:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-04', [], false );

		$this->assertNotContains( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_include_with_valid_html() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		// Valid Test 1
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-01:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-01:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-01', [], false );

		$this->assertContains( '<a href="https://tri.be" class="test" target="_blank" title="Test Link" data-link="automated-tests">%%after_container_open%%', $html );
		$this->assertStringEndsWith( '%%before_container_close%%</a>', $html );

		// Valid Test 2
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-02:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-02:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-02', [], false );

		$replaced_html = str_replace( array( "\n", "\r" ), '', $html );
		$this->assertContains( 'data-view-breakpoint-pointer="99ccf293-c1b0-41b2-a1c8-033776ac6f10">%%after_container_open%%', $replaced_html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );

		// Valid Test 3
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-03:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-03:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-03', [], false );

		$replaced_html = str_replace( array( "\n", "\r" ), '', $html );
		$this->assertContains( '<div class="tribe-view tribe-view--base tribe-view--dummy">%%after_container_open%%', $replaced_html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_entry_points_disabled() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point_is_enabled', '__return_false' );

		add_action( 'tribe_template_entry_point:dummy/dummy-template:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-template:before_container_close', function () {
			echo '%%before_container_close%%';
		} );

		$html = $template->template( 'dummy-template', [], false );

		$this->assertNotContains( '<div class="test">%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%</div>', $html );
	}
}