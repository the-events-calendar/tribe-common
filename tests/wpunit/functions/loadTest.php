<?php

use PHPUnit\Framework\Assert;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Tests\Traits\With_Uopz;

class loadTest extends \Codeception\TestCase\WPTestCase {
	use SnapshotAssertions;
	use With_Uopz;

	/**
	 * @test
	 */
	public function should_decline_to_load_the_plugin_if_common_is_not_loaded() {
		$this->set_fn_return( 'function_exists', static function ( $function ) {
			return $function === 'tribe_register_provider' ? true : function_exists( $function );
		}, true );
		$this->set_fn_return( 'class_exists', static function ( $class ) {
			return $class === 'Tribe__Abstract_Plugin_Register' ? false : class_exists( $class );
		}, true );

		$this->set_fn_return( 'did_action', static function ( $hook ) {
			return $hook === 'tribe_common_loaded' ? false : did_action( $hook );
		}, true );
		$this->set_fn_return( 'doing_action', static function ( $hook ) {
			return $hook === 'tribe_common_loaded' ? false : doing_action( $hook );
		}, true );

		$this->assertFalse( tec_automator_preload() );

		$this->assertEquals( 10, has_action(
			'admin_notices', 'tec_automator_show_fail_message'
		) );
		$this->assertEquals( 10, has_action(
			'network_admin_notices', 'tec_automator_show_fail_message'
		) );
		$this->assertFalse( has_action(
			'tribe_common_loaded', 'tec_automator_load'
		) );
	}

	/**
	 * @test
	 */
	public function should_decline_to_load_if_plugin_register_class_not_exist() {
		$this->set_fn_return( 'function_exists', static function ( $function ) {
			return $function === 'tribe_register_provider' ? true : function_exists( $function );
		}, true );
		$this->set_fn_return( 'class_exists', static function ( $class ) {
			return $class === 'Tribe__Abstract_Plugin_Register' ? false : class_exists( $class );
		}, true );

		$this->set_fn_return( 'did_action', static function ( $hook ) {
			return $hook === 'tribe_common_loaded' ? false : did_action( $hook );
		}, true );
		$this->set_fn_return( 'doing_action', static function ( $hook ) {
			return $hook === 'tribe_common_loaded' ? false : doing_action( $hook );
		}, true );

		$this->assertFalse( tec_automator_preload() );

		$this->assertEquals( 10, has_action(
			'admin_notices', 'tec_automator_show_fail_message'
		) );
		$this->assertEquals( 10, has_action(
			'network_admin_notices', 'tec_automator_show_fail_message'
		) );
		$this->assertFalse( has_action(
			'tribe_common_loaded', 'tec_automator_load'
		) );
	}

	/**
	 * @test
	 */
	public function should_not_show_notices_if_user_cannot_activate_plugins() {
		$this->set_fn_return( 'current_user_can', static function ( $cap ) {
			return $cap === 'activate_plugins' ? false : current_user_can( $cap );
		}, true );

		ob_start();
		tec_automator_show_fail_message();
		$html = ob_get_clean();

		$this->assertEmpty( $html );
	}

	/**
	 * @test
	 */
	public function should_show_a_notice_if_user_can_activate_plugins() {
		$this->set_fn_return( 'current_user_can', static function ( $cap ) {
			return $cap === 'activate_plugins' ? true : current_user_can( $cap );
		}, true );

		ob_start();
		tec_automator_show_fail_message();
		$html = ob_get_clean();

		$this->assertMatchesHtmlSnapshot( $html );
	}

	/**
	 * @test
	 */
	public function should_remove_options_on_uninstallation() {
		$expected_names  = [ 'pue_install_key_event-automator' ];
		$deleted_options = [];
		$this->set_fn_return( 'delete_option', static function ( $name ) use ( $expected_names, &$deleted_options ) {
			if ( in_array( $name, $expected_names, true ) ) {
				$deleted_options[] = $name;

				return;
			}

			return delete_option( $name );
		}, true );

		tec_automator_uninstall();

		$this->assertEqualSets( $expected_names, $deleted_options );
	}

	/**
	 * @test
	 */
	public function should_load_translations_with_word_press_function_if_common_not_available() {
		$called   = false;
		$lang_dir = basename( dirname( __DIR__, 3 ) ) . '/lang';
		$this->set_fn_return( 'class_exists', static function ( string $class ): bool {
			return $class === 'Tribe__Main' ? false : class_exists( $class );
		}, true );
		$this->set_fn_return( 'load_plugin_textdomain',
			static function ( string $domain, bool $deprecated, string $rel_path ) use ( $lang_dir, &$called ) {
				Assert::assertEquals( $domain, 'event-automator' );
				Assert::assertEquals( $lang_dir, $rel_path );
				$called = true;
			}, true );

		tec_automator_load_text_domain();
		$this->assertTrue( $called, 'load_plugin_textdomain function should have been called.' );
	}

	/**
	 * @test
	 */
	public function should_load_translations_using_common_if_available() {
		$called           = false;
		$lang_dir         = basename( dirname( __DIR__, 3 ) ) . '/lang';
		$mock_common_main = $this->makeEmpty( Tribe__Main::class, [
			'load_text_domain' => static function ( string $domain, string $rel_path ) use ( $lang_dir, &$called ) {
				Assert::assertEquals( $domain, 'event-automator' );
				Assert::assertEquals( $lang_dir, $rel_path );
				$called = true;
			},
		] );
		$this->set_fn_return( 'Tribe__Main', 'instance', $mock_common_main );

		tec_automator_load_text_domain();
		$this->assertTrue( $called, 'load_plugin_textdomain function should have been called.' );
	}
}
