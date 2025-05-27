<?php

namespace TEC\Tests\Events\Classy;

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
					],
				'endOfDayCutoff' =>
					[
						'hours'   => 0,
						'minutes' => 0,
					],
				'my_key'         => 'my_value',
			]
			,
			$data
		);
	}
}
