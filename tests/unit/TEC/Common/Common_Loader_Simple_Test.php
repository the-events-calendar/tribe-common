<?php
/**
 * Simple tests for the Common_Loader class core functionality.
 *
 * @since TBD
 *
 * @package TEC\Common
 */

namespace TEC\Common;

use Codeception\Test\Unit;

/**
 * Class Common_Loader_Simple_Test
 *
 * Tests only the core static methods that don't require file system operations
 * or WordPress functions. These test the version negotiation logic.
 *
 * @since TBD
 */
class Common_Loader_Simple_Test extends Unit {

	/**
	 * Setup before each test.
	 *
	 * @since TBD
	 */
	public function setUp(): void {
		parent::setUp();

		// Clear any existing global state.
		unset( $GLOBALS['tribe-common-info'] );
	}

	/**
	 * Test force_common sets global state correctly.
	 *
	 * @since TBD
	 */
	public function test_force_common_sets_global_state(): void {
		Common_Loader::force_common(
			'/test/plugin/path/common/src/Tribe',
			'6.10.0',
			'Test Plugin'
		);

		$this->assertArrayHasKey( 'tribe-common-info', $GLOBALS );

		$info = $GLOBALS['tribe-common-info'];
		$this->assertEquals( '/test/plugin/path/common/src/Tribe', $info['dir'] );
		$this->assertEquals( '6.10.0', $info['version'] );
		$this->assertEquals( 'Test Plugin', $info['set_by'] );
		$this->assertArrayHasKey( 'set_at', $info );
		$this->assertIsFloat( $info['set_at'] );
	}

	/**
	 * Test force_common overwrites existing version.
	 *
	 * @since TBD
	 */
	public function test_force_common_overwrites_existing_version(): void {
		// Set initial common info.
		$GLOBALS['tribe-common-info'] = [
			'dir'     => '/old/plugin/path/common/src/Tribe',
			'version' => '6.10.0',
			'set_by'  => 'Old Plugin',
			'set_at'  => microtime( true ) - 100,
		];

		Common_Loader::force_common(
			'/forced/plugin/path/common/src/Tribe',
			'6.9.0',
			'Forced Plugin'
		);

		$info = $GLOBALS['tribe-common-info'];
		$this->assertEquals( '/forced/plugin/path/common/src/Tribe', $info['dir'] );
		$this->assertEquals( '6.9.0', $info['version'] );
		$this->assertEquals( 'Forced Plugin', $info['set_by'] );
	}

	/**
	 * Test get_common_info returns current info.
	 *
	 * @since TBD
	 */
	public function test_get_common_info_returns_current_info(): void {
		$expected_info = [
			'dir'     => '/test/plugin/path/common/src/Tribe',
			'version' => '6.10.0',
			'set_by'  => 'Test Plugin',
			'set_at'  => microtime( true ),
		];

		$GLOBALS['tribe-common-info'] = $expected_info;

		$result = Common_Loader::get_common_info();
		$this->assertEquals( $expected_info, $result );
	}

	/**
	 * Test get_common_info returns null when not set.
	 *
	 * @since TBD
	 */
	public function test_get_common_info_returns_null_when_not_set(): void {
		unset( $GLOBALS['tribe-common-info'] );

		$result = Common_Loader::get_common_info();
		$this->assertNull( $result );
	}

	/**
	 * Test version comparison logic by accessing should_use_common via reflection.
	 *
	 * @since TBD
	 *
	 * @dataProvider version_comparison_provider
	 */
	public function test_version_comparison_logic( string $existing_version, string $new_version, bool $should_upgrade ): void {
		// Set initial common info.
		$GLOBALS['tribe-common-info'] = [
			'dir'     => '/existing/plugin/path/common/src/Tribe',
			'version' => $existing_version,
			'set_by'  => 'Existing Plugin',
			'set_at'  => microtime( true ) - 100,
		];

		// Use reflection to access the private should_use_common method.
		$reflection = new \ReflectionClass( Common_Loader::class );
		$method = $reflection->getMethod( 'should_use_common' );
		$method->setAccessible( true );

		$result = $method->invokeArgs( null, [
			'/new/plugin/path/common/src/Tribe',
			$new_version,
			'New Plugin'
		] );

		$this->assertEquals( $should_upgrade, $result );
	}

	/**
	 * Test first registration with empty global state.
	 *
	 * @since TBD
	 */
	public function test_should_use_common_with_empty_global(): void {
		// Ensure no existing global state.
		unset( $GLOBALS['tribe-common-info'] );

		// Use reflection to access the private should_use_common method.
		$reflection = new \ReflectionClass( Common_Loader::class );
		$method = $reflection->getMethod( 'should_use_common' );
		$method->setAccessible( true );

		$result = $method->invokeArgs( null, [
			'/test/plugin/path/common/src/Tribe',
			'6.10.0',
			'Test Plugin'
		] );

		$this->assertTrue( $result );
	}

	/**
	 * Data provider for version comparison tests.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function version_comparison_provider(): array {
		return [
			'minor_upgrade'       => [ '6.10.0', '6.10.1', true ],
			'major_upgrade'       => [ '6.10.0', '7.0.0', true ],
			'patch_upgrade'       => [ '6.10.0', '6.10.0.1', true ],
			'minor_downgrade'     => [ '6.10.1', '6.10.0', false ],
			'major_downgrade'     => [ '7.0.0', '6.10.0', false ],
			'patch_downgrade'     => [ '6.10.0.1', '6.10.0', false ],
			'same_version'        => [ '6.10.0', '6.10.0', false ],
			'alpha_vs_stable'     => [ '6.10.0-alpha', '6.10.0', true ],
			'beta_vs_stable'      => [ '6.10.0-beta', '6.10.0', true ],
			'rc_vs_stable'        => [ '6.10.0-rc1', '6.10.0', true ],
			'stable_vs_alpha'     => [ '6.10.0', '6.10.0-alpha', false ],
			'dev_branch_upgrade'  => [ '6.10.0', '6.10.1-dev', true ],
		];
	}

	/**
	 * Test set_global_common_info method directly.
	 *
	 * @since TBD
	 */
	public function test_set_global_common_info(): void {
		// Use reflection to access the private set_global_common_info method.
		$reflection = new \ReflectionClass( Common_Loader::class );
		$method = $reflection->getMethod( 'set_global_common_info' );
		$method->setAccessible( true );

		$method->invokeArgs( null, [
			'/test/plugin/path/common/src/Tribe',
			'6.10.0',
			'Test Plugin'
		] );

		$this->assertArrayHasKey( 'tribe-common-info', $GLOBALS );

		$info = $GLOBALS['tribe-common-info'];
		$this->assertEquals( '/test/plugin/path/common/src/Tribe', $info['dir'] );
		$this->assertEquals( '6.10.0', $info['version'] );
		$this->assertEquals( 'Test Plugin', $info['set_by'] );
		$this->assertArrayHasKey( 'set_at', $info );
		$this->assertIsFloat( $info['set_at'] );
	}
}
