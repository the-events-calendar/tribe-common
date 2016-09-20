<?php
namespace Tribe;

use Tribe__Deprecation as Deprecation;

class DeprecationTest extends \Codeception\TestCase\WPTestCase {

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
	 * it should trigger a deprecated message when adding to a deprecated filter
	 */
	public function it_should_trigger_a_deprecated_message_when_adding_to_a_deprecated_filter() {
		add_filter( 'tribe_deprecated', '__return_empty_string' );

		$sut = $this->make_instance();
		$sut->set_deprecated_filters( [ 'tribe_current' => [ '4.3', 'tribe_deprecated' ] ] );
		$sut->deprecate_filters();

		$this->setExpectedDeprecated( 'The tribe_deprecated filter' );
		apply_filters( 'tribe_current', 'some_value' );
	}

	/**
	 * @test
	 * it should trigger a deprecated message when adding to a deprecated action
	 */
	public function it_should_trigger_a_deprecated_message_when_adding_to_a_deprecated_action() {
		add_action( 'tribe_deprecated', '__return_empty_string' );

		$sut = $this->make_instance();
		$sut->set_deprecated_actions( [ 'tribe_current' => [ '4.3', 'tribe_deprecated' ] ] );
		$sut->deprecate_actions();

		$this->setExpectedDeprecated( 'The tribe_deprecated action' );
		do_action( 'tribe_current', 'some_value' );
	}

	/**
	 * @test
	 * it should not trigger a deprecated message if there are no functions on deprecated filter
	 */
	public function it_should_not_trigger_a_deprecated_message_if_there_are_no_functions_on_deprecated_filter() {
		$sut = $this->make_instance();
		$sut->set_deprecated_filters( [ 'tribe_current' => [ '4.3', 'tribe_deprecated' ] ] );
		$sut->deprecate_filters();

		apply_filters( 'tribe_current', 'some_value' );

		$this->assertEmpty( $this->caught_deprecated );
	}

	/**
	 * @test
	 * it should not trigger a deprecated message if there are no functions on deprecated action
	 */
	public function it_should_not_trigger_a_deprecated_message_if_there_are_no_functions_on_deprecated_action() {
		$sut = $this->make_instance();
		$sut->set_deprecated_actions( [ 'tribe_current' => [ '4.3', 'tribe_deprecated' ] ] );
		$sut->deprecate_actions();

		do_action( 'tribe_current', 'some_value' );

		$this->assertEmpty( $this->caught_deprecated );
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Deprecation::class, $sut );
	}

	/**
	 * @return Deprecation
	 */
	private function make_instance() {
		return new Deprecation();
	}

}