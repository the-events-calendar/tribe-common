<?php
/**
 * Stellar Sale Promo Conditional Content.
 *
 * @since 6.8.2
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

/**
 * Set up for Stellar Sale promo.
 *
 * @since 6.8.2
 */
class Stellar_Sale extends Promotional_Content_Abstract {

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $slug = 'stellar-sale';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $start_date = 'July 29th';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $end_date = 'August 5th';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $background_color = '#1c202f';

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
		return 'https://evnt.is/1bdv';
	}
}
