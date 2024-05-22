<?php

namespace Tribe;

use Tribe__Editor as Editor;
use Tribe\Editor\Compatibility\Classic_Editor as Classic_Editor;
use Tribe\Tests\Traits\With_Uopz;

class Classic_EditorTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

	/**
	 * Set up.
	 *
	 * @since 4.15.1
	 */
	function setUp() {
		parent::setUp();

		$this->classic_editor = $this->make_instance();
		$this->editor = $this->make_editor_instance();
	}

	/**
	 * Tear down.
	 *
	 * @since 4.15.1
	 */
	public function tearDown() {
		// Set options back to defaults (nothing set).
		$this->clear_all();



		// then
		parent::tearDown();
	}

	/* Utility Functions */

	/**
	 * @return Editor
	 */
	protected function make_editor_instance() {
		return new Editor();
	}

	/**
	 * @return Classic_Editor
	 */
	protected function make_instance() {
		return new Classic_Editor();
	}

	/**
	 * Use this to ensure no settings bleed!
	 *
	 * @since 4.15.1
	 */
	public function clear_all() {
		delete_option( $this->classic_editor::$classic_option_key );
		delete_option( $this->classic_editor::$user_choice_key );
		tribe_remove_option( $this->editor::$blocks_editor_key );

		remove_filter( 'tribe_editor_classic_is_active', function() {
			return $this->classic_editor::is_classic_option_active();
		} );
		$this->clear_user_override();
		remove_filter( 'tribe_events_blocks_editor_is_on', '__return_false' );
		remove_filter( 'tribe_events_blocks_editor_is_on', '__return_true' );
		tribe( 'cache' )->reset();
	}

	/**
	 * This tells Editor the option is active via the filter.
	 * Since we aren't installing the actual plugin.
	 *
	 * @since 4.15.1
	 *
	 * @return void
	 */
	public function mock_classic_editor() {
		add_filter( 'tribe_events_blocks_editor_is_on', '__return_false' );
		add_filter( 'tribe_editor_should_load_blocks', function() {
			return ! $this->classic_editor::is_classic_option_active();
		} );
	}

	/**
	 * Sets Classic Editor to "classic".
	 *
	 * @since 4.15.1
	 */
	public function set_classic() {
		update_option( $this->classic_editor::$classic_option_key, 'classic' );
	}

	/**
	 * Sets Classic Editor to "replace" (old value).
	 *
	 * @since 4.15.1
	 */
	public function set_legacy_classic() {
		update_option( $this->classic_editor::$classic_option_key, 'replace' );
	}

	/**
	 * Sets Classic Editor to "blocks".
	 *
	 * @since 4.15.1
	 */
	public function set_blocks() {
		update_option( $this->classic_editor::$classic_option_key, 'blocks' );
	}

	/**
	 * Sets Classic Editor user choice option to on ("allow").
	 *
	 * @since 4.15.1
	 */
	public function choice_on() {
		update_option( $this->classic_editor::$user_choice_key, 'allow' );
	}

	/**
	 * Sets Classic Editor user choice option to off ("disallow").
	 *
	 * @since 4.15.1
	 */
	public function choice_off() {
		update_option( $this->classic_editor::$user_choice_key, 'disallow' );
	}

	/**
	 * Mocks the user override value as set to block.
	 *
	 * @since 4.15.1
	 */
	public function user_override_set_block() {
		add_filter( 'tec_classic_editor_user_profile_override_value', function() {
			return $this->classic_editor::$block_term;
		} );

		add_filter(
			'get_user_metadata',
			function( $value, $object_id, $meta_key, $single, $meta_type ) {
				if ( $this->classic_editor::$user_meta_choice_key !== $meta_key ) {
					return $value;
				}

				return true;
			},
			97,
			5
		);
	}

	/**
	 * Mocks the user override value as set to classic.
	 *
	 * @since 4.15.1
	 */
	public function user_override_set_classic() {
		add_filter( 'tec_classic_editor_user_profile_override_value', function() {
			return $this->classic_editor::$classic_term;
		} );

		add_filter(
			'get_user_metadata',
			function( $value, $object_id, $meta_key, $single, $meta_type ) {
				if ( $this->classic_editor::$user_meta_choice_key !== $meta_key ) {
					return $value;
				}

				return false;
			},
			97,
			5
		);
	}


	/**
	 * Clears the mocked user override value.
	 *
	 * @since 4.15.1
	 */
	public function clear_user_override() {
		remove_all_filters( 'tec_classic_editor_user_profile_override_value' );
		remove_all_filters( 'get_user_metadata', 97 );
	}

	/**
	 * Imitates checking TEC blocks toggle.
	 *
	 * @since 4.15.1
	 */
	public function tec_blocks_on() {
		tribe_update_option( $this->editor::$blocks_editor_key, '1' );
	}

	/**
	 * Imitates actively unchecking TEC blocks toggle.
	 *
	 * @since 4.15.1
	 */
	public function tec_blocks_off() {
		tribe_update_option( $this->editor::$blocks_editor_key, '0' );
	}

	/* Tests */

	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function test_instantiatable() {
		$this->assertInstanceOf( Classic_Editor::class, $this->make_instance() );
	}

	/**
	 * Should not load blocks by default.
	 * (Default) settings:
	 *     TEC blocks off
	 *     CE set to classic
	 *
	 * @test
	 */
	public function test_should_not_load_blocks_by_default() {
		$this->clear_all();
		$this->set_classic();
		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * Should not load blocks.
	 * Settings:
	 *     TEC blocks unset
	 *     CE set to classic
	 *
	 * @test
	 */
	public function test_should_not_load_blocks_with_ce_classic_and_tec_blocks_default() {
		$this->clear_all();
		$this->set_classic();
		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * Should not load blocks.
	 * Settings:
	 *     TEC blocks on
	 *     CE set to classic
	 *
	 * @test
	 */
	public function test_should_not_load_blocks_with_ce_classic_and_tec_blocks_on() {
		$this->clear_all();
		$this->tec_blocks_on();
		$this->set_classic();

		// This tells Editor the option is active via the filter.
		$this->mock_classic_editor();

		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * Should not load blocks with CE on classic and TEC blocks toggled off.
	 * Settings:
	 *     TEC blocks off
	 *     CE set to classic
	 *
	 * @test
	 */
	public function test_should_not_load_blocks_with_ce_classic_and_tec_blocks_toggled_off() {
		$this->clear_all();
		$this->tec_blocks_off();
		$this->set_classic();
		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * Should not load blocks with CE set to blocks and TEC blocks default (off).
	 * Settings:
	 *     TEC blocks unset
	 *     CE set to blocks
	 *
	 * @test
	 */
	public function test_should_not_load_blocks_with_ce_blocks_and_tec_blocks_default() {
		$this->clear_all();
		$this->set_blocks();
		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * Should not load blocks.
	 * Settings:
	 *     TEC blocks off
	 *     CE set to blocks
	 *
	 * @test
	 */
	public function test_should_not_load_blocks_with_ce_blocks_and_tec_blocks_off() {
		$this->clear_all();
		$this->tec_blocks_off();
		$this->set_blocks();
		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * Should load blocks.
	 * Settings:
	 *     TEC blocks on
	 *     CE set to blocks
	 *
	 * @test
	 */
	public function test_should_load_blocks_with_ce_blocks_and_tec_blocks_on() {
		$this->clear_all();
		$this->tec_blocks_on();
		$this->set_blocks();
		$this->assertTrue( $this->editor->should_load_blocks() );
	}

	/**
	 * Should not load blocks.
	 * Settings:
	 *     TEC blocks on
	 *     CE set to classic
	 *     CE choice enabled
	 *     User choice unset
	 *
	 * @test
	 */
	public function test_should_not_load_blocks_with_ce_classic_user_choice_enabled_and_tec_blocks_on() {
		$this->clear_all();
		$this->tec_blocks_on();
		$this->set_classic();
		$this->choice_on();

		// This tells Editor the option is active via the filter.
		$this->mock_classic_editor();

		$this->assertFalse( $this->editor->should_load_blocks() );
	}

	/**
	 * Should load blocks.
	 * Settings:
	 *     TEC blocks on
	 *     CE set to blocks
	 *     CE choice enabled
	 *     User choice set to blocks
	 *
	 * @test
	 */
	public function test_should_load_blocks_with_ce_blocks_user_choice_block_and_tec_blocks_on() {
		$this->clear_all();
		$this->tec_blocks_on();
		$this->set_blocks();
		$this->choice_on();
		$this->user_override_set_block();
		$this->assertTrue( $this->editor->should_load_blocks() );
	}

	/**
	 * Should not load blocks.
	 * Settings:
	 *     TEC blocks on
	 *     CE set to blocks
	 *     CE choice enabled
	 *     User choice set to classic
	 *
	 * @test
	 */
	public function test_should_not_load_blocks_with_ce_blocks_user_choice_classic_and_tec_blocks_on() {
		$this->clear_all();
		$this->tec_blocks_on();
		$this->set_blocks();
		$this->choice_on();
		$this->user_override_set_classic();
		$this->assertTrue( $this->editor->should_load_blocks() );
	}

}
