<?php
/**
 * Stellar Sale Promo Conditional Content.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

/**
 * Set up for Stellar Sale promo.
 *
 * @since TBD
 */
class Stellar_Sale extends Promotional_Content_Abstract {

	/**
	 * @inheritdoc
	 */
	protected string $slug = 'stellar-sale';

	/**
	 * @inheritdoc
	 */
	protected string $start_date = 'March 15th';

	/**
	 * @inheritdoc
	 */
	protected string $end_date = 'March 22nd';

	/**
	 * @inheritdoc
	 */
	protected string $background_color = 'teal';

	/**
	 * @inheritdoc
	 */
	public function hook(): void {
		add_action( 'tec_settings_sidebar_start', [ $this, 'include_sidebar_section' ] );
		add_action( 'tribe_settings_below_tabs', [ $this, 'include_tickets_settings_section' ] );
		add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );
	}

	protected function should_display(): bool {
		return false;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_sale_name(): string {
		return __( 'Stellar Sale', 'tribe-common' );
	}

	/**
	 * @inheritdoc
	 */
	protected function get_link_url(): string {
		return 'https://evnt.is/tec-stellar-sale-2024';
	}
}
