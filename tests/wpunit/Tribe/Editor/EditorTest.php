<?php

namespace Tribe;

use Tribe__Editor as Editor;

class EditorTest extends \Codeception\TestCase\WPTestCase {

	function setUp() {
		parent::setUp();
		tribe( 'cache' )->reset();

		$this->editor = $this->make_instance();
	}

	public function tearDown() {
		unset( $this->editor );

		// then
		parent::tearDown();
	}

	/**
	 * @return Editor
	 */
	protected function make_instance() {
		return new Editor( [] );
	}

	/* Utility Functions */
	public function tec_blocks_on() {
		tribe_update_option( $this->editor::$blocks_editor_key, '1' );
	}

	public function tec_blocks_off() {
		tribe_update_option( $this->editor::$blocks_editor_key, '0' );
	}

	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Editor::class, $this->make_instance() );
	}

	/**
	 * @test
	 */
	public function test_should_not_load_blocks_by_default() {
		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * @test
	 */
	public function test_should_load_blocks_if_toggled() {
		$this->tec_blocks_on();
		$this->assertTrue( $this->editor->should_load_blocks() );
	}

	/**
	 * @test
	 */
	public function test_should_not_load_blocks_if_toggled_off() {
		$this->tec_blocks_off();
		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * @test
	 */
	public function test_is_classic_editor_by_default() {
		$this->assertTrue( $this->editor->is_classic_editor() );
	}

	/**
	 * @test
	 */
	public function test_is_not_classic_editor_when_toggled() {
		$this->tec_blocks_on();
		$this->assertFalse( $this->editor->is_classic_editor() );
	}

	/**
	 * @test
	 */
	public function test_is_classic_editor_when_toggled_off() {
		$this->tec_blocks_off();
		$this->assertTrue( $this->editor->is_classic_editor() );
	}

	/**
	 * @test
	 */
	public function test_default_is_events_using_blocks_is_false() {
		$this->assertFalse( $this->editor->is_events_using_blocks() );
	}

	/**
	 * @test
	 */
	public function test_is_events_using_blocks_toggled() {
		$this->tec_blocks_on();
		$this->assertTrue( $this->editor->is_events_using_blocks() );
	}

	/**
	 * @test
	 */
	public function test_is_events_using_blocks_toggled_off() {
		$this->tec_blocks_off();
		$this->assertFalse( $this->editor->is_events_using_blocks() );
	}

	/**
	 * @test
	 */
	public function test_is_events_using_blocks_filtered_true() {
		add_filter( 'tribe_editor_should_load_blocks', '__return_true' );
		add_filter( 'tribe_events_blocks_editor_is_on', '__return_true' );
		$this->assertTrue( $this->editor->is_events_using_blocks() );
		remove_filter( 'tribe_editor_should_load_blocks', '__return_true' );
		remove_filter( 'tribe_events_blocks_editor_is_on', '__return_true' );
	}

	/**
	 * @test
	 */
	public function test_is_events_using_blocks_filtered_false() {
		add_filter( 'tribe_editor_should_load_blocks', '__return_false' );
		add_filter( 'tribe_events_blocks_editor_is_on', '__return_false' );
		$this->assertFalse( $this->editor->is_events_using_blocks() );
		remove_filter( 'tribe_editor_should_load_blocks', '__return_false' );
		remove_filter( 'tribe_events_blocks_editor_is_on', '__return_false' );
	}
}
