<?php

namespace TEC\Common\Editor;

use Codeception\TestCase\WPTestCase;
use TEC\Common\StellarWP\Assets\Assets;

/**
 * Styles in the editor canvas group that load on the editor page also load in the iframed editor canvas.
 */
class Canvas_Styles_Test extends WPTestCase {
	private const CANVAS_STYLE = 'tribe-common-gutenberg-main-styles';

	private const PAGE_ONLY_STYLE = 'tribe-common-gutenberg-vendor-styles';

	/**
	 * @before
	 */
	public function reset_styles(): void {
		foreach ( [ self::CANVAS_STYLE, self::PAGE_ONLY_STYLE ] as $handle ) {
			wp_dequeue_style( $handle );
			Assets::init()->get( $handle )->set_as_unenqueued();
		}
	}

	/**
	 * @after
	 */
	public function reset_screen(): void {
		unset( $GLOBALS['current_screen'] );
	}

	/**
	 * @test
	 */
	public function it_should_put_the_canvas_group_in_the_editor_canvas_and_keep_it_on_the_editor_page(): void {
		$this->open_block_editor();

		do_action( 'enqueue_block_editor_assets' );
		$iframe_styles = _wp_get_iframed_editor_assets()['styles'];

		$this->assertStringContainsString( "id='" . self::CANVAS_STYLE . "-css'", $iframe_styles );
		$this->assertTrue( wp_style_is( self::CANVAS_STYLE, 'enqueued' ) );
	}

	/**
	 * @test
	 */
	public function it_should_leave_styles_outside_the_canvas_group_on_the_editor_page_only(): void {
		$this->open_block_editor();

		do_action( 'enqueue_block_editor_assets' );
		$iframe_styles = _wp_get_iframed_editor_assets()['styles'];

		$this->assertStringNotContainsString( "id='" . self::PAGE_ONLY_STYLE . "-css'", $iframe_styles );
		$this->assertTrue( wp_style_is( self::PAGE_ONLY_STYLE, 'enqueued' ) );
	}

	/**
	 * @test
	 */
	public function it_should_not_put_a_canvas_style_in_the_canvas_when_the_editor_page_did_not_load_it(): void {
		$this->open_block_editor();

		$iframe_styles = _wp_get_iframed_editor_assets()['styles'];

		$this->assertStringNotContainsString( "id='" . self::CANVAS_STYLE . "-css'", $iframe_styles );
	}

	/**
	 * @test
	 */
	public function it_should_not_load_canvas_styles_on_the_front_end(): void {
		/*
		 * Flag the asset as enqueued, as an earlier editor page pass would, to prove the front-end
		 * guard does not rely on that flag alone.
		 */
		Assets::init()->get( self::CANVAS_STYLE )->set_as_enqueued();
		set_current_screen( 'front' );

		do_action( 'enqueue_block_assets' );

		$this->assertFalse( wp_style_is( self::CANVAS_STYLE, 'enqueued' ) );
	}

	private function open_block_editor(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		set_current_screen( 'post' );
		get_current_screen()->is_block_editor( true );
	}
}
