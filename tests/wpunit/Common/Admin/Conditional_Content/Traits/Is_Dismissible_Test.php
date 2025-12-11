<?php
/**
 * Tests for Is_Dismissible trait.
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */

declare( strict_types=1 );

namespace TEC\Common\Tests\Admin\Conditional_Content\Traits;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Admin\Conditional_Content\Traits\Is_Dismissible;

/**
 * Class Is_Dismissible_Test
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */
class Is_Dismissible_Test extends WPTestCase {

	/**
	 * Test implementation class.
	 */
	protected $test_class;

	/**
	 * Test user ID.
	 */
	protected int $user_id;

	/**
	 * Set up test environment.
	 *
	 * @before
	 */
	public function set_up(): void {
		// Create a test user.
		$this->user_id = $this->factory()->user->create( [ 'role' => 'administrator' ] );
		wp_set_current_user( $this->user_id );

		// Create a test class that uses the trait.
		$this->test_class = new class() {
			use Is_Dismissible;

			public string $slug = 'test-dismissible';

			public function get_slug(): string {
				return $this->slug . '-' . date_i18n( 'Y' );
			}

			// Expose protected methods for testing.
			public function public_dismiss( $user_id = null ): bool {
				return $this->dismiss( $user_id );
			}
		};

		// Clean up any existing meta.
		delete_metadata( 'user', $this->user_id, 'tec-dismissible-content' );
	}

	/**
	 * Tear down test environment.
	 *
	 * @after
	 */
	public function tear_down(): void {
		// Clean up.
		delete_metadata( 'user', $this->user_id, 'tec-dismissible-content' );
		wp_delete_user( $this->user_id );
	}

	/**
	 * Test that has_user_dismissed returns false when not dismissed.
	 *
	 * @test
	 */
	public function should_return_false_when_not_dismissed() {
		$this->assertFalse( $this->test_class->has_user_dismissed() );
	}

	/**
	 * Test that dismiss() marks content as dismissed.
	 *
	 * @test
	 */
	public function should_mark_content_as_dismissed() {
		$result = $this->test_class->public_dismiss();

		$this->assertTrue( $result );
		$this->assertTrue( $this->test_class->has_user_dismissed() );
	}

	/**
	 * Test that dismiss() stores timestamp.
	 *
	 * @test
	 */
	public function should_store_dismissal_timestamp() {
		$before = time();
		$this->test_class->public_dismiss();
		$after = time();

		$timestamp = get_user_meta( $this->user_id, 'tec-dismissible-content-time-' . $this->test_class->get_slug(), true );

		$this->assertGreaterThanOrEqual( $before, $timestamp );
		$this->assertLessThanOrEqual( $after, $timestamp );
	}

	/**
	 * Test that dismiss() returns true when already dismissed.
	 *
	 * @test
	 */
	public function should_return_true_when_already_dismissed() {
		$this->test_class->public_dismiss();
		$result = $this->test_class->public_dismiss();

		$this->assertTrue( $result );
	}

	/**
	 * Test that undismiss() removes dismissal.
	 *
	 * @test
	 */
	public function should_remove_dismissal() {
		$this->test_class->public_dismiss();
		$this->assertTrue( $this->test_class->has_user_dismissed() );

		$result = $this->test_class->undismiss();

		$this->assertTrue( $result );
		$this->assertFalse( $this->test_class->has_user_dismissed() );
	}

	/**
	 * Test that undismiss() returns false when not dismissed.
	 *
	 * @test
	 */
	public function should_return_false_when_undismissing_non_dismissed() {
		$result = $this->test_class->undismiss();

		$this->assertFalse( $result );
	}

	/**
	 * Test that dismiss() works with specific user ID.
	 *
	 * @test
	 */
	public function should_dismiss_for_specific_user() {
		$other_user_id = $this->factory()->user->create();

		$this->test_class->public_dismiss( $other_user_id );

		// Current user should not be affected.
		$this->assertFalse( $this->test_class->has_user_dismissed() );

		// Specific user should be dismissed.
		$this->assertTrue( $this->test_class->has_user_dismissed( $other_user_id ) );

		wp_delete_user( $other_user_id );
	}

	/**
	 * Test that undismiss() works with specific user ID.
	 *
	 * @test
	 */
	public function should_undismiss_for_specific_user() {
		$other_user_id = $this->factory()->user->create();

		$this->test_class->public_dismiss( $other_user_id );
		$this->assertTrue( $this->test_class->has_user_dismissed( $other_user_id ) );

		$this->test_class->undismiss( $other_user_id );
		$this->assertFalse( $this->test_class->has_user_dismissed( $other_user_id ) );

		wp_delete_user( $other_user_id );
	}

	/**
	 * Test that get_nonce_action returns correct action string.
	 *
	 * @test
	 */
	public function should_return_correct_nonce_action() {
		$action = $this->test_class->get_nonce_action();

		$this->assertStringContainsString( 'tec-dismissible-content-nonce-', $action );
		$this->assertStringContainsString( 'test-dismissible', $action );
	}

	/**
	 * Test that get_nonce returns a valid nonce.
	 *
	 * @test
	 */
	public function should_return_valid_nonce() {
		$nonce = $this->test_class->get_nonce();

		$this->assertNotEmpty( $nonce );
		$this->assertEquals( 1, wp_verify_nonce( $nonce, $this->test_class->get_nonce_action() ) );
	}

	/**
	 * Test that multiple items can be dismissed for same user.
	 *
	 * @test
	 */
	public function should_track_multiple_dismissed_items() {
		// Create another test instance.
		$another_class = new class() {
			use Is_Dismissible;

			public string $slug = 'another-dismissible';

			public function get_slug(): string {
				return $this->slug . '-' . date_i18n( 'Y' );
			}

			public function public_dismiss( $user_id = null ): bool {
				return $this->dismiss( $user_id );
			}
		};

		$this->test_class->public_dismiss();
		$another_class->public_dismiss();

		$this->assertTrue( $this->test_class->has_user_dismissed() );
		$this->assertTrue( $another_class->has_user_dismissed() );

		// Get all dismissed items.
		$dismissed = get_user_meta( $this->user_id, 'tec-dismissible-content' );
		$this->assertCount( 2, $dismissed );
	}
}
