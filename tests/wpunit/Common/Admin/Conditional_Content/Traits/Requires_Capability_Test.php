<?php
/**
 * Tests for Requires_Capability trait.
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */

declare( strict_types=1 );

namespace TEC\Common\Tests\Admin\Conditional_Content\Traits;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Admin\Conditional_Content\Traits\Requires_Capability;

/**
 * Class Requires_Capability_Test
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */
class Requires_Capability_Test extends WPTestCase {

	/**
	 * Test implementation class.
	 */
	protected $test_class;

	/**
	 * Admin user ID.
	 */
	protected int $admin_user_id;

	/**
	 * Editor user ID.
	 */
	protected int $editor_user_id;

	/**
	 * Subscriber user ID.
	 */
	protected int $subscriber_user_id;

	/**
	 * Set up test environment.
	 *
	 * @before
	 */
	public function set_up(): void {
		// Create test users with different roles.
		$this->admin_user_id      = $this->factory()->user->create( [ 'role' => 'administrator' ] );
		$this->editor_user_id     = $this->factory()->user->create( [ 'role' => 'editor' ] );
		$this->subscriber_user_id = $this->factory()->user->create( [ 'role' => 'subscriber' ] );

		// Create a test class that uses the trait.
		$this->test_class = new class() {
			use Requires_Capability;

			protected string $slug = 'test-content';

			// Expose protected methods for testing.
			public function public_check_capability(): bool {
				return $this->check_capability();
			}

			public function public_get_required_capability(): string {
				return $this->get_required_capability();
			}
		};
	}

	/**
	 * Tear down test environment.
	 *
	 * @after
	 */
	public function tear_down(): void {
		// Clean up users.
		wp_delete_user( $this->admin_user_id );
		wp_delete_user( $this->editor_user_id );
		wp_delete_user( $this->subscriber_user_id );

		// Clean up filters.
		remove_all_filters( 'tec_admin_conditional_content_test-content_check_capability' );
	}

	/**
	 * Test that get_required_capability returns default capability.
	 *
	 * @test
	 */
	public function should_return_default_capability() {
		$capability = $this->test_class->public_get_required_capability();

		$this->assertEquals( 'manage_options', $capability );
	}

	/**
	 * Test that admin user has required capability.
	 *
	 * @test
	 */
	public function should_allow_admin_user() {
		wp_set_current_user( $this->admin_user_id );

		$this->assertTrue( $this->test_class->public_check_capability() );
	}

	/**
	 * Test that editor user does not have required capability.
	 *
	 * @test
	 */
	public function should_not_allow_editor_user() {
		wp_set_current_user( $this->editor_user_id );

		$this->assertFalse( $this->test_class->public_check_capability() );
	}

	/**
	 * Test that subscriber user does not have required capability.
	 *
	 * @test
	 */
	public function should_not_allow_subscriber_user() {
		wp_set_current_user( $this->subscriber_user_id );

		$this->assertFalse( $this->test_class->public_check_capability() );
	}

	/**
	 * Test that required capability can be filtered.
	 *
	 * @test
	 */
	public function should_allow_filtering_required_capability() {
		// Change required capability to 'edit_posts' (which editors have).
		add_filter(
			'tec_admin_conditional_content_test-content_check_capability',
			function () {
				return 'edit_posts';
			}
		);

		wp_set_current_user( $this->editor_user_id );

		$this->assertTrue( $this->test_class->public_check_capability() );
	}

	/**
	 * Test that get_required_capability can be overridden in subclass.
	 *
	 * @test
	 */
	public function should_allow_overriding_required_capability() {
		$custom_class = new class() {
			use Requires_Capability;

			protected string $slug = 'test-content';

			protected function get_required_capability(): string {
				return 'edit_posts';
			}

			public function public_check_capability(): bool {
				return $this->check_capability();
			}
		};

		wp_set_current_user( $this->editor_user_id );

		$this->assertTrue( $custom_class->public_check_capability() );
	}

	/**
	 * Test that super admin has required capability (multisite).
	 *
	 * @test
	 */
	public function should_allow_super_admin() {
		if ( ! is_multisite() ) {
			$this->markTestSkipped( 'Multisite not enabled' );
		}

		// Grant super admin capability.
		grant_super_admin( $this->admin_user_id );
		wp_set_current_user( $this->admin_user_id );

		$this->assertTrue( $this->test_class->public_check_capability() );
	}

	/**
	 * Test that capability check works with custom capability.
	 *
	 * @test
	 */
	public function should_work_with_custom_capability() {
		// Add a custom capability to editor.
		$editor = get_user_by( 'id', $this->editor_user_id );
		$editor->add_cap( 'view_custom_content' );

		$custom_class = new class() {
			use Requires_Capability;

			protected string $slug = 'test-content';

			protected function get_required_capability(): string {
				return 'view_custom_content';
			}

			public function public_check_capability(): bool {
				return $this->check_capability();
			}
		};

		wp_set_current_user( $this->editor_user_id );

		$this->assertTrue( $custom_class->public_check_capability() );

		// Clean up.
		$editor->remove_cap( 'view_custom_content' );
	}

	/**
	 * Test that filter receives instance as parameter.
	 *
	 * @test
	 */
	public function should_pass_instance_to_filter() {
		$received_instance = null;

		add_filter(
			'tec_admin_conditional_content_test-content_check_capability',
			function ( $cap, $instance ) use ( &$received_instance ) {
				$received_instance = $instance;

				return $cap;
			},
			10,
			2
		);

		wp_set_current_user( $this->admin_user_id );
		$this->test_class->public_check_capability();

		$this->assertSame( $this->test_class, $received_instance );
	}

	/**
	 * Test that no user returns false.
	 *
	 * @test
	 */
	public function should_return_false_when_no_user() {
		wp_set_current_user( 0 );

		$this->assertFalse( $this->test_class->public_check_capability() );
	}
}
