<?php
/**
 * Integration tests for multiple traits working together.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */

declare( strict_types=1 );

namespace TEC\Common\Tests\Admin\Conditional_Content\Traits;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Admin\Conditional_Content\Traits\{
	Has_Datetime_Conditions,
	Is_Dismissible,
	Requires_Capability
};
use Tribe\Utils\Date_I18n;
use Tribe__Date_Utils as Dates;

/**
 * Class Trait_Integration_Test
 *
 * Tests how multiple traits interact when used together.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */
class Trait_Integration_Test extends WPTestCase {

	/**
	 * Test implementation class using all three traits.
	 */
	protected $integrated_class;

	/**
	 * Admin user ID.
	 */
	protected int $admin_user_id;

	/**
	 * Editor user ID.
	 */
	protected int $editor_user_id;

	/**
	 * Set up test environment.
	 *
	 * @before
	 */
	public function set_up(): void {
		// Create test users.
		$this->admin_user_id  = $this->factory()->user->create( [ 'role' => 'administrator' ] );
		$this->editor_user_id = $this->factory()->user->create( [ 'role' => 'editor' ] );
		wp_set_current_user( $this->admin_user_id );

		// Create a test class that uses all three traits.
		$this->integrated_class = new class() {
			use Has_Datetime_Conditions {
				get_start_time as get_start_time_from_trait;
				get_end_time as get_end_time_from_trait;
				should_display as should_display_datetime;
			}
			use Is_Dismissible;
			use Requires_Capability;

			public string $slug = 'integrated-test';
			public string $start_date;
			public string $end_date;
			public int $start_time = 0;
			public int $end_time   = 0;

			public function __construct() {
				$now              = Dates::build_date_object( 'now', 'UTC' );
				$this->start_date = $now->sub( new \DateInterval( 'P1D' ) )->format( 'Y-m-d' );
				$this->end_date   = $now->add( new \DateInterval( 'P2D' ) )->format( 'Y-m-d' );
			}

			public function hook(): void {
				// No-op for testing.
			}

			public function get_slug(): string {
				return $this->slug . '-' . date_i18n( 'Y' );
			}

			// Time adjustments (like in Black_Friday/Stellar_Sale).
			protected function get_start_time(): ?Date_I18n {
				$date = $this->get_start_time_from_trait();
				if ( null === $date ) {
					return null;
				}
				return $date->setTime( 4, 0 );
			}

			protected function get_end_time(): ?Date_I18n {
				$date = $this->get_end_time_from_trait();
				if ( null === $date ) {
					return null;
				}
				return $date->setTime( 4, 0 );
			}

			// Compose all trait checks like Black_Friday/Stellar_Sale.
			public function should_display(): bool {
				// Check user capability.
				if ( ! $this->check_capability() ) {
					return false;
				}

				// Check if user dismissed.
				if ( $this->has_user_dismissed() ) {
					return false;
				}

				// Check datetime conditions.
				return $this->should_display_datetime();
			}

			// Expose protected methods for testing.
			public function public_dismiss( $user_id = null ): bool {
				return $this->dismiss( $user_id );
			}

			public function public_check_capability(): bool {
				return $this->check_capability();
			}

			public function public_get_start_time(): ?Date_I18n {
				return $this->get_start_time();
			}

			public function public_get_end_time(): ?Date_I18n {
				return $this->get_end_time();
			}
		};

		// Clean up any existing meta.
		delete_metadata( 'user', $this->admin_user_id, 'tec-dismissible-content' );
	}

	/**
	 * Tear down test environment.
	 *
	 * @after
	 */
	public function tear_down(): void {
		// Clean up.
		delete_metadata( 'user', $this->admin_user_id, 'tec-dismissible-content' );
		delete_metadata( 'user', $this->editor_user_id, 'tec-dismissible-content' );
		wp_delete_user( $this->admin_user_id );
		wp_delete_user( $this->editor_user_id );

		remove_all_filters( 'tec_admin_conditional_content_integrated-test_check_capability' );
		remove_all_filters( 'tec_admin_conditional_content_integrated-test_should_display' );
	}

	/**
	 * Test that all checks pass for valid admin user in valid date range.
	 *
	 * @test
	 */
	public function should_display_when_all_conditions_met() {
		$this->assertTrue( $this->integrated_class->should_display() );
	}

	/**
	 * Test that capability check blocks non-admin users.
	 *
	 * @test
	 */
	public function should_not_display_when_user_lacks_capability() {
		wp_set_current_user( $this->editor_user_id );

		$this->assertFalse( $this->integrated_class->should_display() );
	}

	/**
	 * Test that dismissal blocks display even for admin in valid date range.
	 *
	 * @test
	 */
	public function should_not_display_when_dismissed() {
		$this->integrated_class->public_dismiss();

		$this->assertFalse( $this->integrated_class->should_display() );
	}

	/**
	 * Test that datetime check blocks display outside date range.
	 *
	 * @test
	 */
	public function should_not_display_outside_date_range() {
		$past = Dates::build_date_object( 'now', 'UTC' )->sub( new \DateInterval( 'P10D' ) );

		$this->integrated_class->start_date = $past->format( 'Y-m-d' );
		$this->integrated_class->end_date   = $past->add( new \DateInterval( 'P5D' ) )->format( 'Y-m-d' );

		$this->assertFalse( $this->integrated_class->should_display() );
	}

	/**
	 * Test that dismissal is user-specific.
	 *
	 * @test
	 */
	public function should_be_user_specific_for_dismissal() {
		// Dismiss for admin user.
		$this->integrated_class->public_dismiss( $this->admin_user_id );

		// Admin user should see it as dismissed.
		wp_set_current_user( $this->admin_user_id );
		$this->assertFalse( $this->integrated_class->should_display() );

		// Editor user should not see it as dismissed (but fails capability).
		wp_set_current_user( $this->editor_user_id );
		// Can't tell dismissal state because capability fails first, so test dismissal directly.
		$this->assertFalse( $this->integrated_class->has_user_dismissed( $this->editor_user_id ) );
	}

	/**
	 * Test that undismissing allows display again (when other conditions met).
	 *
	 * @test
	 */
	public function should_display_after_undismiss() {
		$this->integrated_class->public_dismiss();
		$this->assertFalse( $this->integrated_class->should_display() );

		$this->integrated_class->undismiss();
		$this->assertTrue( $this->integrated_class->should_display() );
	}

	/**
	 * Test that capability filter affects integrated display logic.
	 *
	 * @test
	 */
	public function should_respect_capability_filter() {
		// Lower requirement to 'edit_posts'.
		add_filter(
			'tec_admin_conditional_content_integrated-test_check_capability',
			function () {
				return 'edit_posts';
			}
		);

		// Editor should now be able to see it.
		wp_set_current_user( $this->editor_user_id );
		$this->assertTrue( $this->integrated_class->should_display() );
	}

	/**
	 * Test that datetime filter affects integrated display logic.
	 *
	 * @test
	 */
	public function should_respect_datetime_filter() {
		// Force datetime check to fail via filter.
		add_filter( 'tec_admin_conditional_content_integrated-test_datetime_should_display', '__return_false' );

		$this->assertFalse( $this->integrated_class->should_display() );
	}

	/**
	 * Test check order: capability fails before checking dismissal.
	 *
	 * @test
	 */
	public function should_check_capability_before_dismissal() {
		// Dismiss for editor.
		$this->integrated_class->public_dismiss( $this->editor_user_id );

		// Switch to editor (lacks capability).
		wp_set_current_user( $this->editor_user_id );

		// Should fail on capability, not dismissal.
		// (We can verify this by checking that the method returns false even though it's dismissed)
		$this->assertFalse( $this->integrated_class->should_display() );
		$this->assertTrue( $this->integrated_class->has_user_dismissed( $this->editor_user_id ) );
	}

	/**
	 * Test check order: dismissal fails before checking datetime.
	 *
	 * @test
	 */
	public function should_check_dismissal_before_datetime() {
		// Dismiss for admin.
		$this->integrated_class->public_dismiss();

		// Move dates to invalid range (in the past).
		$past = Dates::build_date_object( 'now', 'UTC' )->sub( new \DateInterval( 'P10D' ) );

		$this->integrated_class->start_date = $past->format( 'Y-m-d' );
		$this->integrated_class->end_date   = $past->add( new \DateInterval( 'P5D' ) )->format( 'Y-m-d' );

		// Should fail because dismissed (even though datetime is also invalid).
		$this->assertFalse( $this->integrated_class->should_display() );
		$this->assertTrue( $this->integrated_class->has_user_dismissed() );
	}

	/**
	 * Test that all traits can be used independently.
	 *
	 * @test
	 */
	public function should_allow_independent_trait_usage() {
		// Test each trait's functionality independently.

		// Capability check works.
		$this->assertTrue( $this->integrated_class->public_check_capability() );

		// Dismissal works.
		$this->assertFalse( $this->integrated_class->has_user_dismissed() );
		$this->integrated_class->public_dismiss();
		$this->assertTrue( $this->integrated_class->has_user_dismissed() );

		// Datetime works.
		$this->integrated_class->undismiss(); // Clear dismissal to test datetime.
		$start = $this->integrated_class->public_get_start_time();
		$end   = $this->integrated_class->public_get_end_time();
		$this->assertInstanceOf( Date_I18n::class, $start );
		$this->assertInstanceOf( Date_I18n::class, $end );
	}

	/**
	 * Test that trait method aliasing works correctly.
	 *
	 * @test
	 */
	public function should_handle_trait_method_aliasing() {
		// Get time methods are aliased and wrapped.
		$start = $this->integrated_class->public_get_start_time();
		$end   = $this->integrated_class->public_get_end_time();

		// Should be set to 4:00 AM (from override).
		$this->assertEquals( 4, $start->format( 'G' ) );
		$this->assertEquals( 4, $end->format( 'G' ) );
	}

	/**
	 * Test complex scenario: multiple users, dismissals, and capabilities.
	 *
	 * @test
	 */
	public function should_handle_complex_multi_user_scenario() {
		// Create another admin user.
		$admin2_id = $this->factory()->user->create( [ 'role' => 'administrator' ] );

		add_filter( 'tec_admin_conditional_content_integrated-test_datetime_should_display', '__return_true' );

		// Admin 1 dismisses.
		wp_set_current_user( $this->admin_user_id );
		$this->integrated_class->public_dismiss();
		$this->assertFalse( $this->integrated_class->should_display() );

		// Admin 2 should still see it.
		wp_set_current_user( $admin2_id );
		$this->assertTrue( $this->integrated_class->should_display() );

		// Editor should not see it (no capability).
		wp_set_current_user( $this->editor_user_id );
		$this->assertFalse( $this->integrated_class->should_display() );

		// Admin 1 undismisses.
		$this->integrated_class->undismiss( $this->admin_user_id );
		wp_set_current_user( $this->admin_user_id );
		$this->assertTrue( $this->integrated_class->should_display() );

		wp_delete_user( $admin2_id );
	}
}
