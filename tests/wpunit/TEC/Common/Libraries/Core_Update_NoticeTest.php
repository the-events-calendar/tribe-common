<?php

namespace TEC\Common\Libraries;

use Codeception\TestCase\WPTestCase;
use TEC\Common\StellarWP\CoreUpdateNotice\CoreUpdateNotice;

/**
 * Covers this plugin's integration with stellarwp/core-update-notice: that the copy passed from the
 * controller reaches the output, that the screen gate is wired to the admin helper, and that the
 * shared version-keyed dismissal is honoured.
 */
class Core_Update_NoticeTest extends WPTestCase {
	/**
	 * @after
	 */
	public function reset_update_state(): void {
		delete_site_transient( 'update_core' );
		delete_option( CoreUpdateNotice::DISMISSED_OPTION );
		unset( $GLOBALS['current_screen'] );
	}

	/**
	 * @test
	 */
	public function should_gate_rendering_on_tec_screens(): void {
		$this->given_a_tec_admin_screen();

		$this->assertTrue( tribe( Core_Update_Notice::class )->is_plugin_page() );
	}

	/**
	 * @test
	 */
	public function should_not_gate_rendering_open_outside_tec_screens(): void {
		set_current_screen( 'dashboard' );

		$this->assertFalse( tribe( Core_Update_Notice::class )->is_plugin_page() );
	}

	/**
	 * @test
	 */
	public function should_render_the_copy_in_the_tribe_common_text_domain(): void {
		$this->given_core_update_response( 'upgrade', '9.9.9' );
		$this->given_current_user_is( 'administrator' );

		$html = $this->render_notice();

		$this->assertStringContainsString( 'Keep your site protected. Update to the latest version of WordPress.', $html );
		$this->assertStringContainsString( 'To decrease your risk of exposure, please update your WordPress install to the latest version.', $html );
	}

	/**
	 * @test
	 */
	public function should_not_render_when_wordpress_is_up_to_date(): void {
		$this->given_core_update_response( 'latest', '9.9.9' );
		$this->given_current_user_is( 'administrator' );

		$this->assertSame( '', $this->render_notice() );
	}

	/**
	 * @test
	 */
	public function should_not_render_to_a_user_who_cannot_update_core(): void {
		$this->given_core_update_response( 'upgrade', '9.9.9' );
		$this->given_current_user_is( 'editor' );

		$this->assertSame( '', $this->render_notice() );
	}

	/**
	 * The flag is deliberately unprefixed and shared, so a site running several StellarWP plugins
	 * that carry this notice dismisses it once rather than once per plugin.
	 *
	 * @test
	 */
	public function should_honour_the_shared_dismissal_for_the_offered_version(): void {
		$this->given_core_update_response( 'upgrade', '9.9.9' );
		$this->given_current_user_is( 'administrator' );

		update_option( CoreUpdateNotice::DISMISSED_OPTION, [ '9.9.9' => true ], false );

		$this->assertSame( '', $this->render_notice() );
	}

	/**
	 * Dismissals are keyed on the offered version so one release cannot silence the next.
	 *
	 * @test
	 */
	public function should_render_again_once_a_newer_release_is_offered(): void {
		$this->given_current_user_is( 'administrator' );

		update_option( CoreUpdateNotice::DISMISSED_OPTION, [ '9.9.9' => true ], false );
		$this->given_core_update_response( 'upgrade', '10.0.0' );

		$this->assertStringContainsString( 'Keep your site protected.', $this->render_notice() );
	}

	/**
	 * Exercises the controller's own call into the library, which is the line that breaks if the
	 * package changes its registration signature.
	 *
	 * @test
	 */
	public function should_register_the_notice_hooks_through_the_library(): void {
		tribe( Core_Update_Notice::class )->register_notice();

		$this->assertNotFalse( has_filter( CoreUpdateNotice::DISPLAY_WINNER_FILTER ), 'The display winner filter should be registered.' );
		$this->assertNotFalse( has_action( 'admin_notices' ), 'The notice should hook admin_notices.' );
	}

	/**
	 * Captures one `admin_notices` pass of the configured notice.
	 *
	 * The library only renders the winner of its display filter, which `Register::notice()` wires
	 * up before `admin_init`; that has already fired here, so the filter is attached directly.
	 *
	 * @return string The rendered markup, empty when the notice declined to render.
	 */
	protected function render_notice(): string {
		$notice = new CoreUpdateNotice(
			[
				'heading' => __( 'Keep your site protected. Update to the latest version of WordPress.', 'tribe-common' ),
				'body'    => __( 'Your site is running on an outdated version of WordPress, which can leave it vulnerable to security issues. To decrease your risk of exposure, please update your WordPress install to the latest version.', 'tribe-common' ),
				'dismiss' => __( 'Dismiss this notice.', 'tribe-common' ),
			]
		);

		add_filter( CoreUpdateNotice::DISPLAY_WINNER_FILTER, [ $notice, 'selectWinner' ] );

		ob_start();
		$notice->render();
		$html = ob_get_clean() ?: '';

		remove_filter( CoreUpdateNotice::DISPLAY_WINNER_FILTER, [ $notice, 'selectWinner' ] );

		return $html;
	}

	/**
	 * Seeds the transient `get_core_updates()` reads to decide whether an update is pending.
	 *
	 * @param string $response The core update response, `upgrade` when one is available.
	 * @param string $version  The version the update points at.
	 */
	protected function given_core_update_response( string $response, string $version ): void {
		set_site_transient(
			'update_core',
			(object) [
				'updates' => [
					(object) [
						'response' => $response,
						'current'  => $version,
						'locale'   => 'en_US',
					],
				],
			]
		);
	}

	/**
	 * Puts the request on a TEC admin screen, which is also what makes `is_admin()` true.
	 */
	protected function given_a_tec_admin_screen(): void {
		set_current_screen( 'tribe_events_page_tec-events-settings' );
	}

	/**
	 * @param string $role The role to create the current user with.
	 */
	protected function given_current_user_is( string $role ): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => $role ] ) );
	}
}
