<?php

namespace TEC\Tests\Common\Classy;

use TEC\Common\Classy\Controller;
use TEC\Common\Tests\Provider\Controller_Test_Case;
use Tribe\Tests\Traits\With_Uopz;

class Controller_Test extends Controller_Test_Case {
	use With_Uopz;

	protected $controller_class = Controller::class;

	/**
	 * @var array<string, mixed>
	 */
	private array $set_env_vars = [];

	/**
	 * @after
	 */
	public function reset_env_var(): void {
		$key = Controller::DISABLED;
		putenv( "$key=0" );
	}

	/**
	 * @covers Controller::is_active
	 */
	public function test_is_active_by_default(): void {
		$controller = $this->make_controller();

		$this->assertTrue( $controller->is_active() );
	}

	/**
	 * @covers Controller::is_active
	 */
	public function test_is_not_active_when_disabled_constant_true(): void {
		$this->set_const_value( Controller::DISABLED, true );

		$controller = $this->make_controller();

		$this->assertFalse( $controller->is_active() );
	}

	/**
	 * @covers Controller::is_active
	 */
	public function test_is_active_when_disabled_constant_false(): void {
		$this->set_const_value( Controller::DISABLED, false );

		$controller = $this->make_controller();

		$this->assertTrue( $controller->is_active() );
	}

	/**
	 * @covers Controller::is_active
	 */
	public function test_is_not_active_if_disabled_env_var_set(): void {
		putenv( Controller::DISABLED . '=1' );

		$controller = $this->make_controller();

		$this->assertFalse( $controller->is_active() );
	}

	/**
	 * @covers Controller::is_active
	 */
	public function test_is_active_if_enabled_option_set_true(): void {
		update_option( 'tec_common_classy_editor_enabled', '1' );

		$controller = $this->make_controller();

		$this->assertTrue( $controller->is_active() );
	}

	/**
	 * @covers Controller::is_active
	 */
	public function test_is_not_active_if_enabled_option_set_false(): void {
		update_option( 'tec_common_classy_editor_enabled', '0' );

		$controller = $this->make_controller();

		$this->assertFalse( $controller->is_active() );
	}

	/**
	 * @covers Controller::is_active
	 */
	public function test_is_active_if_enabled_filter_set_true(): void {
		add_filter( 'tec_common_classy_editor_enabled', '__return_true' );

		$controller = $this->make_controller();

		$this->assertTrue( $controller->is_active() );
	}

	/**
	 * @covers Controller::is_active
	 */
	public function test_is_not_active_if_enabled_filter_set_false(): void {
		add_filter( 'tec_common_classy_editor_enabled', '__return_false' );

		$controller = $this->make_controller();

		$this->assertFalse( $controller->is_active() );
	}

	/**
	 * @covers Controller::post_uses_classy
	 */
	public function test_post_uses_classy(): void {
		$controller = $this->make_controller();

		$this->assertFalse( $controller->post_uses_classy( 'post' ) );

		add_filter( 'tec_classy_post_types', static fn( array $post_types ): array => [ 'post' ] );

		$this->assertTrue( $controller->post_uses_classy( 'post' ) );
	}

	/**
	 * @covers Controller::filter_block_editor_settings
	 */
	public function test_filter_block_editor_settings(): void {
		$context       = new \WP_Block_Editor_Context();
		$context->post = static::factory()->post->create_and_get();

		$controller = $this->make_controller();

		$this->assertEquals(
			[],
			$controller->filter_block_editor_settings( [], $context )
		);

		add_filter( 'tec_classy_post_types', static fn( array $post_types ): array => [ 'post' ] );

		$this->assertEquals(
			[ 'templateLock' => true ],
			$controller->filter_block_editor_settings( [], $context )
		);
	}

	/**
	 * @covers Controller::get_data
	 */
	public function test_get_data(): void {
		$controller = $this->make_controller();

		add_filter( 'tec_classy_localized_data', function ( array $data ): array {
			$data['my_key'] = 'my_value';

			return $data;
		} );

		$data = $controller->get_data();

		// Pluck the settings.timezoneChoice key, it's really long.
		$this->assertArrayHasKey( 'settings', $data );
		$this->assertArrayHasKey( 'timezoneChoice', $data['settings'] );
		$timezone_choice = $data['settings']['timezoneChoice'];
		// The timezone choice options are controlled by WordPress, we do no particularly care about their shape.
		$this->assertTrue( str_starts_with( $timezone_choice, '<option' ) );
		unset( $data['settings']['timezoneChoice'] );

		codecept_debug( var_export( $data, true ) );

		$this->assertEquals(
			[
				'settings'       =>
					[
						'compactDateFormat'     => 'n/j/Y',
						'dataTimeSeparator'     => ' @ ',
						'dateWithYearFormat'    => 'F j, Y',
						'dateWithoutYearFormat' => 'F j',
						'endOfDayCutoff'        =>
							[
								'hours'   => 0,
								'minutes' => 0,
							],
						'monthAndYearFormat'    => 'F Y',
						'startOfWeek'           => '1',
						'timeFormat'            => 'g:i a',
						'timeInterval'          => 15,
						'timeRangeSeparator'    => ' - ',
						'timezoneString'        => '',
						'defaultCurrency'       =>
							[
								'code'     => 'USD',
								'symbol'   => '$',
								'position' => 'prefix',
							],
						'venuesLimit'           => 1,
					],
				'endOfDayCutoff' =>
					[
						'hours'   => 0,
						'minutes' => 0,
					],
				'my_key'         => 'my_value',
			],
			$data
		);
	}

	public static function disable_block_editor_welcome_screen_data_provider(): array {
		return [
//			'wrong meta key'                           => [
//				function ( int $user, string $meta_key ) {
//					// Start from a user that does not have previous persisted preferences meta.
//					delete_user_meta( $user, $meta_key );
//					// Create a post type that is supported.
//					$post_id = static::factory()->post->create( [ 'post_type' => 'page' ] );
//
//					return [
//						'post_id'    => $post_id,
//						'meta_value' => [ 'foo' => 'bar' ],
//						'meta_key'   => 'not-this-one',
//						'single'     => true,
//						'expected'   => [ 'foo' => 'bar' ],
//					];
//				}
//			],
//			'not single'                               => [
//				function ( int $user, string $meta_key ) {
//					// Start from a user that does not have previous persisted preferences meta.
//					delete_user_meta( $user, $meta_key );
//					// Create a post type that is supported.
//					$post_id = static::factory()->post->create( [ 'post_type' => 'page' ] );
//
//					return [
//						'post_id'    => $post_id,
//						'meta_value' => [ 'foo' => 'bar' ],
//						'meta_key'   => $meta_key,
//						'single'     => false,
//						'expected'   => [ 'foo' => 'bar' ],
//					];
//				}
//			],
//			'not a supported post type'                => [
//				function ( int $user, string $meta_key ) {
//					// Start from a user that does not have previous persisted preferences meta.
//					delete_user_meta( $user, $meta_key );
//					// Create a post type that is supported.
//					$post_id = static::factory()->post->create( [ 'post_type' => 'post' ] );
//
//					return [
//						'post_id'    => $post_id,
//						'meta_value' => [ 'foo' => 'bar' ],
//						'meta_key'   => $meta_key,
//						'single'     => true,
//						'expected'   => [ 'foo' => 'bar' ],
//					];
//				}
//			],
//			'meta value not null and not an array'     => [
//				function ( int $user, string $meta_key ) {
//					// Start from a user that does not have previous persisted preferences meta.
//					delete_user_meta( $user, $meta_key );
//					// Create a post type that is supported.
//					$post_id = static::factory()->post->create( [ 'post_type' => 'page' ] );
//
//					return [
//						'post_id'    => $post_id,
//						'meta_value' => '__test__',
//						'meta_key'   => $meta_key,
//						'single'     => true,
//						'expected'   => '__test__'
//					];
//				}
//			],
//			'core/edit-post key not set in meta value' => [
//				function ( int $user, string $meta_key ) {
//					// Start from a user that does not have previous persisted preferences meta.
//					delete_user_meta( $user, $meta_key );
//					// Create a post type that is supported.
//					$post_id = static::factory()->post->create( [ 'post_type' => 'page' ] );
//
//					return [
//						'post_id'    => $post_id,
//						'meta_value' => [ 'foo' => 'bar' ],
//						'meta_key'   => $meta_key,
//						'single'     => true,
//						'expected'   => [
//							0 => [
//								'foo'            => 'bar',
//								'core/edit-post' => [
//									'welcomeGuide' => false,
//								],
//							]
//						],
//					];
//				}
//			],
			'core/edit-post set in meta value' => [
				function ( int $user, string $meta_key ) {
					// Start from a user that does not have previous persisted preferences meta.
					delete_user_meta( $user, $meta_key );
					// Create a post type that is supported.
					$post_id = static::factory()->post->create( [ 'post_type' => 'page' ] );

					return [
						'post_id'    => $post_id,
						'meta_value' => [
							'foo' => 'bar' ,
							'core/edit-post' => [
								'some_key' => 'some_value',
							],
						],
						'meta_key'   => $meta_key,
						'single'     => true,
						'expected'   => [
							0 => [
								'foo'            => 'bar',
								'core/edit-post' => [
									'some_key' => 'some_value',
									'welcomeGuide' => false,
								],
							]
						],
					];
				}
			],
		];
	}

	/**
	 * @dataProvider disable_block_editor_welcome_screen_data_provider
	 */
	public function test_disable_block_editor_welcome_screen( \Closure $fixture ): void {
		$user = static::factory()->user->create( [ 'role' => 'administrator' ] );
		wp_set_current_user( $user );
		global $wpdb;
		$meta_key = "{$wpdb->prefix}persisted_preferences";
		[
			$post_id,
			$meta_value,
			$meta_key,
			$single,
			$expected
		] = array_values( $fixture( $user, $meta_key ) );
		// Filter the supported post types to support pages, but not posts.
		add_filter( 'tec_classy_post_types', static fn() => [ 'page' ] );
		// Set up the request as if we're editing a specific post, if any.
		if ( $post_id ) {
			$_GET['post_type'] = get_post_type( $post_id );
			$_GET['post']      = $post_id;
		}
		// The context will cache resolved locations, refresh it.
		tribe_context()->refresh('post_type');

		$controller = $this->make_controller();
		$controller->register();

		$this->assertEquals(
			$expected,
			apply_filters( 'get_user_metadata', $meta_value, $user, $meta_key, $single )
		);
	}
}
