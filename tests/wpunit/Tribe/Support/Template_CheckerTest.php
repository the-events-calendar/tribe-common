<?php
namespace Tribe\Support;

use Codeception\TestCase\WPTestCase;
use Tribe__Support__Template_Checker as Template_Checker;

class Template_CheckerTest extends WPTestCase {
	/** @var \Tribe__Support__Template_Checker */
	protected $template_checker;

	public function setUp() {
		parent::setUp();
		$test_data_dir = codecept_data_dir();

		$this->template_checker = new Template_Checker(
			'2.1',
			$test_data_dir . '/plugin-views',
			$test_data_dir . '/theme-views'
		);
	}

	public function test_detects_shipped_views() {
		$found_views    = $this->template_checker->get_views();
		$expected_views = [
			'primary.php'                             => '2.1',
			'secondary.php'                           => '',
			'templates/dummy-invalid-template-01.php' => '',
			'templates/dummy-invalid-template-02.php' => '',
			'templates/dummy-invalid-template-03.php' => '',
			'templates/dummy-invalid-template-04.php' => '',
			'templates/dummy-template.php'            => '',
			'templates/dummy-valid-template-01.php'   => '',
			'templates/dummy-valid-template-03.php'   => '',
			'templates/dummy-valid-template-02.php'   => '',
			'templates/etc/dummy-template.php'        => '',
			'modules/alpha.php'                       => '1.4',
			'modules/beta.php'                        => '',
			'modules/gamma.php'                       => '',
			'modules/delta.php'                       => '2.1',
			'modules/epsilon.php'                     => '1.9',
		];

		$this->assertTrue(
			$this->arrays_contain_same_keys_and_values( $found_views, $expected_views ),
			'Detect all plugin views and their version numbers match what we expect to find'
		);
	}

	public function test_detects_shipped_versioned_views() {
		$found_views    = $this->template_checker->get_versioned_views();
		$expected_views = [
			'primary.php'         => '2.1',
			'modules/alpha.php'   => '1.4',
			'modules/delta.php'   => '2.1',
			'modules/epsilon.php' => '1.9',
		];

		$this->assertTrue(
			$this->arrays_contain_same_keys_and_values( $found_views, $expected_views ),
			'Detect expected range of versioned plugin views and their version numbers match what we expect to find'
		);
	}

	public function test_detects_shipped_just_updated_views() {
		$found_views    = $this->template_checker->get_views_tagged_this_release();
		$expected_views = [
			'primary.php'       => '2.1',
			'modules/delta.php' => '2.1',
		];

		$this->assertTrue(
			$this->arrays_contain_same_keys_and_values( $found_views, $expected_views ),
			'Detect expected range of plugin views tagged with version 2.1'
		);
	}

	public function test_detects_overrides() {
		$found_views    = $this->template_checker->get_overrides();
		$expected_views = [
			'primary.php'       => '2.1',
			'modules/alpha.php' => '',
			'modules/delta.php' => '2.0',
		];

		$this->assertTrue(
			$this->arrays_contain_same_keys_and_values( $found_views, $expected_views ),
			'Detect all theme overrides and ensure their version numbers match what we expect to find'
		);
	}

	public function test_detects_outdated_overrides() {
		// First, only look for (outdated) overrides that have a version tag
		$found_views    = $this->template_checker->get_outdated_overrides();
		$expected_views = [
			'modules/delta.php' => '2.0',
		];

		$this->assertTrue(
			$this->arrays_contain_same_keys_and_values( $found_views, $expected_views ),
			'Detect all theme overrides tagged with an earlier version than the current plugin version'
		);

		// Second, look for overrides that are tagged with an earlier-than-current version or have no version tag
		$found_views    = $this->template_checker->get_outdated_overrides( true );
		$expected_views = [
			'modules/alpha.php' => '',
			'modules/delta.php' => '2.0',
		];

		$this->assertTrue(
			$this->arrays_contain_same_keys_and_values( $found_views, $expected_views ),
			'Detect all theme overrides tagged with an earlier version than the current plugin version or that have no version tag'
		);
	}

	/**
	 * Determines if both arrays contain the same keys and values, regardless
	 * of order.
	 *
	 * @param  array $array_a
	 * @param  array $array_b
	 * @return bool
	 */
	protected function arrays_contain_same_keys_and_values( $array_a, $array_b ) {
		if ( count( $array_a ) !== count( $array_b ) ) {
			return false;
		}

		foreach ( $array_a as $key => $value ) {
			if ( ! isset( $array_b[$key] ) || $array_b[$key] !== $value ) {
				return false;
			}
		}

		return true;
	}
}