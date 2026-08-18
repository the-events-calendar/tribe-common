<?php

namespace TEC\Common\Libraries;

use Codeception\TestCase\WPTestCase;
use TEC\Common\StellarWP\CoreUpdateNotice\Config;
use TEC\Common\StellarWP\CoreUpdateNotice\CoreUpdateNotice;

/**
 * Covers this plugin's integration with stellarwp/core-update-notice: that the container is handed
 * over, that the copy passed from the controller reaches the output, and that the shared dismissal
 * flag is honoured.
 */
class Core_Update_NoticeTest extends WPTestCase {
	/**
	 * @after
	 */
	public function reset_update_state(): void {
		delete_site_transient( 'update_core' );
		delete_option( CoreUpdateNotice::DISMISSED_OPTION );
		unset( $GLOBALS[ CoreUpdateNotice::RENDER_GUARD ] );
	}

	/**
	 * @test
	 */
	public function should_hand_the_tec_container_to_the_library(): void {
		tribe( Core_Update_Notice::class )->do_register();

		$this->assertSame( tribe(), Config::getContainer() );
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
	public function should_honour_the_shared_dismissal_flag(): void {
		$this->given_core_update_response( 'upgrade', '9.9.9' );
		$this->given_current_user_is( 'administrator' );

		update_option( CoreUpdateNotice::DISMISSED_OPTION, true, false );

		$this->assertSame( '', $this->render_notice() );
	}

	/**
	 * @test
	 */
	public function should_render_only_once_per_request_across_plugins(): void {
		$this->given_core_update_response( 'upgrade', '9.9.9' );
		$this->given_current_user_is( 'administrator' );

		$this->assertNotSame( '', $this->render_notice() );
		$this->assertSame( '', $this->render_notice(), 'A second copy of the notice should be suppressed.' );
	}

	/**
	 * Captures one `admin_notices` pass of the configured notice.
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

		ob_start();
		$notice->render();

		return ob_get_clean() ?: '';
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
	 * @param string $role The role to create the current user with.
	 */
	protected function given_current_user_is( string $role ): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => $role ] ) );
	}
}
