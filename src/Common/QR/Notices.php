<?php
/**
 * The Notices class for the QR module.
 *
 * @since 6.6.0
 *
 * @package TEC\Common\QR
 */

namespace TEC\Common\QR;

/**
 * Class Notices
 *
 * @since 6.6.0
 *
 * @package TEC\Common\QR
 */
class Notices {

	/**
	 * Registers the notices for the QR code handling.
	 *
	 * @since 6.6.0
	 *
	 * @return void
	 */
	public function register_admin_notices(): void {
		tribe_notice(
			'tec-qr-dependency-notice',
			[ $this, 'get_dependency_notice_contents' ],
			[
				'type'    => 'warning',
				'dismiss' => 1,
				'wrap'    => 'p',
			],
			[ $this, 'should_display_dependency_notice' ]
		);
	}

	/**
	 * Determines if the Notice for QR code dependencies should be visible
	 *
	 * @since 6.6.0
	 *
	 * @return bool
	 */
	public function should_display_dependency_notice(): bool {
		// Only attempt to check the page if the user can't use the QR codes.
		if ( tribe( Controller::class )->can_use() ) {
			return false;
		}

		$active_page = tribe_get_request_var( 'page' );

		if ( $active_page ) {
			$valid_pages = [];

			/**
			 * Filter the valid pages for the QR code dependency notice.
			 *
			 * @since 6.6.0
			 *
			 * @param array<string> $valid_pages The valid pages for the QR code notice.
			 */
			$valid_pages = apply_filters( 'tec_qr_notice_valid_pages', $valid_pages );

			if ( in_array( $active_page, $valid_pages, true ) ) {
				return true;
			}
		}

		$post_type = tribe_get_request_var( 'post_type' );

		if ( $post_type ) {
			$valid_post_types = [];

			/**
			 * Filter the valid post types for the QR code dependency notice.
			 *
			 * @since 6.6.0
			 *
			 * @param array<string> $valid_post_types The valid post types for the QR code notice.
			 */
			$valid_post_types = apply_filters( 'tec_qr_notice_valid_post_types', $valid_post_types );

			if ( in_array( $post_type, $valid_post_types, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Gets the notice for the QR code dependency.
	 *
	 * @since 6.6.0
	 *
	 * @return string
	 */
	public function get_dependency_notice_contents(): string {
		$html  = '<h2>' . esc_html__( 'QR codes for events/tickets not available.', 'tribe-common' ) . '</h2>';
		$html .= esc_html__( 'In order to have QR codes for your events and/or tickets you will need to have both the `php_gd2` and `gzuncompress` PHP extensions installed on your server. Please contact your hosting provider.', 'tribe-common' );
		$html .= ' <a target="_blank" href="https://evnt.is/event-tickets-qr-support">' . esc_html__( 'Learn more.', 'tribe-common' ) . '</a>';

		return $html;
	}
}
