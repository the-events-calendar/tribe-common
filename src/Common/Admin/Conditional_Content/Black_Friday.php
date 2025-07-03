<?php
/**
 * Black Friday Promo Conditional Content.
 *
 * @since 6.3.0
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

/**
 * Set up for Black Friday promo.
 *
 * @since 6.3.0
 */
class Black_Friday extends Promotional_Content_Abstract {

	/**
	 * @inheritdoc
	 */
	protected string $slug = 'black-friday';

	/**
	 * @inheritdoc
	 */
	protected string $start_date = 'November 26th';

	/**
	 * @inheritdoc
	 */
	protected string $end_date = 'December 3rd';

	/**
	 * Background color for the promotional content.
	 * Must match the background color of the image.
	 *
	 * @since 6.8.2
	 *
	 * @var string
	 */
	protected string $background_color = '#000';

	/**
	 * @inheritdoc
	 */
	protected function get_sale_name(): string {
		return __( 'Black Friday Sale', 'tribe-common' );
	}

	/**
	 * @inheritdoc
	 */
	protected function get_link_url(): string {
		return 'https://evnt.is/tec-bf-2024';
	}
}
