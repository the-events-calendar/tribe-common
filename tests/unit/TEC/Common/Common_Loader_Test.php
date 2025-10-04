<?php
/**
 * Tests for the Common_Loader class.
 *
 * @since TBD
 *
 * @package TEC\Common
 */

namespace TEC\Common;

use Codeception\Test\Unit;
use Tribe\Tests\Traits\With_Uopz;

/**
 * Class Common_Loader_Test
 *
 * Tests the centralized common library loading and version negotiation.
 *
 * @since TBD
 */
class Common_Loader_Test extends Unit {

	use With_Uopz;

	/**
	 * Setup before each test.
	 *
	 * @since TBD
	 */
	public function setUp(): void {
		parent::setUp();

		// Clear any existing global state.
		unset( $GLOBALS['tribe-common-info'] );

		// Clear the version cache using reflection since it's static.
		$this->reset_static_property( Common_Loader::class, 'version_cache', [] );
	}

	/**
	 * Teardown after each test.
	 *
	 * @since TBD
	 */
	public function tearDown(): void {
		$this->unset_uopz_returns();
		parent::tearDown();
	}

	/**
	 * Helper to reset static properties using reflection.
	 *
	 * @since TBD
	 *
	 * @param string $class    Class name.
	 * @param string $property Property name.
	 * @param mixed  $value    New value.
	 */
	private function reset_static_property( string $class, string $property, $value ): void {
		$reflection = new \ReflectionClass( $class );
		$prop = $reflection->getProperty( $property );
		$prop->setAccessible( true );
		$prop->setValue( null, $value );
	}

	/**
	 * Test registering the first common library sets global state.
	 *
	 * @since TBD
	 */
	public function test_register_first_common_sets_global_state(): void {
		// Mock file_exists to return true.
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'is_readable', true );

		// Mock version extraction to return a version.
		$this->set_class_fn_return(
			Common_Loader::class,
			'get_version_cached',
			'6.10.0'
		);

		$result = Common_Loader::register_common_path(
			'/test/plugin/path',
			'Test Plugin',
			'common/src/Tribe'
		);

		$this->assertTrue( $result );
		$this->assertArrayHasKey( 'tribe-common-info', $GLOBALS );

		$info = $GLOBALS['tribe-common-info'];
		$this->assertEquals( '/test/plugin/path/common/src/Tribe', $info['dir'] );
		$this->assertEquals( '6.10.0', $info['version'] );
		$this->assertEquals( 'Test Plugin', $info['set_by'] );
		$this->assertArrayHasKey( 'set_at', $info );
	}

	/**
	 * Test newer version replaces older version.
	 *
	 * @since TBD
	 */
	public function test_newer_version_replaces_older_version(): void {
		// Set initial common info with older version.
		$GLOBALS['tribe-common-info'] = [
			'dir'     => '/old/plugin/path/common/src/Tribe',
			'version' => '6.9.0',
			'set_by'  => 'Old Plugin',
			'set_at'  => microtime( true ) - 100,
		];

		// Mock file_exists to return true.
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'is_readable', true );

		// Mock version extraction to return a newer version.
		$this->set_class_fn_return(
			Common_Loader::class,
			'get_version_cached',
			'6.10.0'
		);

		$result = Common_Loader::register_common_path(
			'/new/plugin/path',
			'New Plugin',
			'common/src/Tribe'
		);

		$this->assertTrue( $result );

		$info = $GLOBALS['tribe-common-info'];
		$this->assertEquals( '/new/plugin/path/common/src/Tribe', $info['dir'] );
		$this->assertEquals( '6.10.0', $info['version'] );
		$this->assertEquals( 'New Plugin', $info['set_by'] );
	}

	/**
	 * Test older version does not replace newer version.
	 *
	 * @since TBD
	 */
	public function test_older_version_does_not_replace_newer_version(): void {
		// Set initial common info with newer version.
		$GLOBALS['tribe-common-info'] = [
			'dir'     => '/new/plugin/path/common/src/Tribe',
			'version' => '6.10.0',
			'set_by'  => 'New Plugin',
			'set_at'  => microtime( true ) - 100,
		];

		// Mock file_exists to return true.
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'is_readable', true );

		// Mock version extraction to return an older version.
		$this->set_class_fn_return(
			Common_Loader::class,
			'get_version_cached',
			'6.9.0'
		);

		$result = Common_Loader::register_common_path(
			'/old/plugin/path',
			'Old Plugin',
			'common/src/Tribe'
		);

		$this->assertFalse( $result );

		// Global state should remain unchanged.
		$info = $GLOBALS['tribe-common-info'];
		$this->assertEquals( '/new/plugin/path/common/src/Tribe', $info['dir'] );
		$this->assertEquals( '6.10.0', $info['version'] );
		$this->assertEquals( 'New Plugin', $info['set_by'] );
	}

	/**
	 * Test same version does not replace existing version.
	 *
	 * @since TBD
	 */
	public function test_same_version_does_not_replace_existing_version(): void {
		// Set initial common info.
		$GLOBALS['tribe-common-info'] = [
			'dir'     => '/first/plugin/path/common/src/Tribe',
			'version' => '6.10.0',
			'set_by'  => 'First Plugin',
			'set_at'  => microtime( true ) - 100,
		];

		// Mock file_exists to return true.
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'is_readable', true );

		// Mock version extraction to return the same version.
		$this->set_class_fn_return(
			Common_Loader::class,
			'get_version_cached',
			'6.10.0'
		);

		$result = Common_Loader::register_common_path(
			'/second/plugin/path',
			'Second Plugin',
			'common/src/Tribe'
		);

		$this->assertFalse( $result );

		// Global state should remain unchanged.
		$info = $GLOBALS['tribe-common-info'];
		$this->assertEquals( '/first/plugin/path/common/src/Tribe', $info['dir'] );
		$this->assertEquals( '6.10.0', $info['version'] );
		$this->assertEquals( 'First Plugin', $info['set_by'] );
	}

	/**
	 * Test missing main file returns false.
	 *
	 * @since TBD
	 */
	public function test_missing_main_file_returns_false(): void {
		// Mock file_exists to return false.
		$this->set_fn_return( 'file_exists', false );

		$result = Common_Loader::register_common_path(
			'/nonexistent/plugin/path',
			'Nonexistent Plugin',
			'common/src/Tribe'
		);

		$this->assertFalse( $result );
		$this->assertArrayNotHasKey( 'tribe-common-info', $GLOBALS );
	}

	/**
	 * Test unreadable main file returns false.
	 *
	 * @since TBD
	 */
	public function test_unreadable_main_file_returns_false(): void {
		// Mock file_exists to return true but is_readable to return false.
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'is_readable', false );

		$result = Common_Loader::register_common_path(
			'/unreadable/plugin/path',
			'Unreadable Plugin',
			'common/src/Tribe'
		);

		$this->assertFalse( $result );
		$this->assertArrayNotHasKey( 'tribe-common-info', $GLOBALS );
	}

	/**
	 * Test version extraction failure returns false.
	 *
	 * @since TBD
	 */
	public function test_version_extraction_failure_returns_false(): void {
		// Mock file_exists to return true.
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'is_readable', true );

		// Mock version extraction to return null (failure).
		$this->set_class_fn_return(
			Common_Loader::class,
			'get_version_cached',
			null
		);

		$result = Common_Loader::register_common_path(
			'/bad/plugin/path',
			'Bad Plugin',
			'common/src/Tribe'
		);

		$this->assertFalse( $result );
		$this->assertArrayNotHasKey( 'tribe-common-info', $GLOBALS );
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
	 * Test version comparison edge cases.
	 *
	 * @since TBD
	 *
	 * @dataProvider version_comparison_provider
	 */
	public function test_version_comparison_edge_cases( string $existing_version, string $new_version, bool $should_upgrade ): void {
		// Set initial common info.
		$GLOBALS['tribe-common-info'] = [
			'dir'     => '/existing/plugin/path/common/src/Tribe',
			'version' => $existing_version,
			'set_by'  => 'Existing Plugin',
			'set_at'  => microtime( true ) - 100,
		];

		// Mock file_exists to return true.
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'is_readable', true );

		// Mock version extraction to return the new version.
		$this->set_class_fn_return(
			Common_Loader::class,
			'get_version_cached',
			$new_version
		);

		$result = Common_Loader::register_common_path(
			'/new/plugin/path',
			'New Plugin',
			'common/src/Tribe'
		);

		$this->assertEquals( $should_upgrade, $result );

		$info = $GLOBALS['tribe-common-info'];
		if ( $should_upgrade ) {
			$this->assertEquals( '/new/plugin/path/common/src/Tribe', $info['dir'] );
			$this->assertEquals( $new_version, $info['version'] );
			$this->assertEquals( 'New Plugin', $info['set_by'] );
		} else {
			$this->assertEquals( '/existing/plugin/path/common/src/Tribe', $info['dir'] );
			$this->assertEquals( $existing_version, $info['version'] );
			$this->assertEquals( 'Existing Plugin', $info['set_by'] );
		}
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
}
