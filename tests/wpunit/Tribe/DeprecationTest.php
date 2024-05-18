<?php
namespace Tribe;

use Tribe__Deprecation as Deprecation;

class DeprecationTest extends \Codeception\TestCase\WPTestCase {

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
		remove_filter('tribe_current', '__return_empty_string');
		remove_action('tribe_current', '__return_empty_string');
		remove_filter('tribe_deprecated', '__return_empty_string');
		remove_action('tribe_deprecated', '__return_empty_string');
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
	 * it should trigger a deprecated notice when calling deprecated filter
	 *
	 * Test we can be stupid.
	 */
	public function it_should_trigger_a_deprecated_notice_when_calling_deprecated_filter() {
		$sut = $this->make_instance();
		$sut->set_deprecated_filters( [ 'tribe_current' => [ '4.3', 'tribe_deprecated' ] ] );
		$sut->deprecate_filters();

		$this->setExpectedDeprecated( 'The tribe_deprecated filter' );

		apply_filters( 'tribe_deprecated', 'some_value' );
	}

	/**
	 * @test
	 * it should trigger a deprecated notice when calling deprecated action
	 *
	 * Test we can be stupid.
	 */
	public function it_should_trigger_a_deprecated_notice_when_calling_deprecated_action() {
		$sut = $this->make_instance();
		$sut->set_deprecated_actions( [ 'tribe_current' => [ '4.3', 'tribe_deprecated' ] ] );
		$sut->deprecate_actions();

		$this->setExpectedDeprecated( 'The tribe_deprecated action' );

		do_action( 'tribe_deprecated', 'some_value' );
	}

	/**
	 * @test
	 * it should not trigger any deprecated notice when calling the new filter
	 */
	public function it_should_not_trigger_any_deprecated_notice_when_calling_the_new_filter() {
		$sut = $this->make_instance();
		$sut->set_deprecated_filters( [ 'tribe_current' => [ '4.3', 'tribe_deprecated' ] ] );
		$sut->deprecate_filters();

		apply_filters( 'tribe_current', 'some_value' );
	}

	/**
	 * @test
	 * it should not trigger any deprecated notice when calling the new action
	 */
	public function it_should_not_trigger_any_deprecated_notice_when_calling_the_new_action() {
		$sut = $this->make_instance();
		$sut->set_deprecated_actions( [ 'tribe_current' => [ '4.3', 'tribe_deprecated' ] ] );
		$sut->deprecate_actions();

		do_action( 'tribe_current', 'some_value' );
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