<?php

namespace Tribe;

use Tribe__Editor as Editor;
use Tribe\Tests\Traits\With_Uopz;

class EditorTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

	function setUp() {
		parent::setUp();

		$this->editor = $this->make_instance();
	}

	public function tearDown() {
		unset( $this->editor );

		// then
		parent::tearDown();
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
	 * @return Editor
	 */
	protected function make_instance() {
		return new Editor( [] );
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
		tribe_update_option( 'toggle_blocks_editor', '1' );
		$this->assertTrue( $this->editor->should_load_blocks() );
	}

	/**
	 * @test
	 */
	public function test_should_not_load_blocks_if_toggled_off() {
		tribe_update_option( 'toggle_blocks_editor', '0' );
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
		tribe_update_option( 'toggle_blocks_editor', '1' );
		$this->assertFalse( $this->editor->is_classic_editor() );
	}

	/**
	 * @test
	 */
	public function test_is_classic_editor_when_not_toggled() {
		tribe_update_option( 'toggle_blocks_editor', '0' );
		$this->assertTrue( $this->editor->is_classic_editor() );
	}

	/**
	 * @test
	 */
	public function test_default_is_events_using_blocks() {
		$this->assertFalse( $this->editor->is_events_using_blocks() );
	}

	/**
	 * @test
	 */
	public function test_is_events_using_blocks_toggled() {
		tribe_update_option( 'toggle_blocks_editor', '1' );
		$this->assertTrue( $this->editor->is_events_using_blocks() );
	}

	/**
	 * @test
	 */
	public function test_is_events_using_blocks_toggled_off() {
		tribe_update_option( 'toggle_blocks_editor', '0' );
		$this->assertFalse( $this->editor->is_events_using_blocks() );
	}

	/**
	 * @test
	 */
	public function test_is_events_using_blocks_filtered_true() {
		add_filter( 'tribe_is_using_blocks', '__return_true' );
		$this->assertTrue( $this->editor->is_events_using_blocks() );
	}

	/**
	 * @test
	 */
	public function test_is_events_using_blocks_filtered_false() {
		add_filter( 'tribe_is_using_blocks', '__return_false' );
		$this->assertFalse( $this->editor->is_events_using_blocks() );
	}
}
