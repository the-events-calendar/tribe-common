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
	 *
	 * @var string
	 */
	protected string $slug = 'black-friday';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $start_date = 'November 26th';

	/**
	 * @inheritdoc
	 *
	 * @var string
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

	/**
	 * @inheritdoc
	 */
	protected function get_suite_creative_map(): array {
		return [
			'events'  => [
				'default' => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() ),
					'link_url'         => $this->get_link_url(),
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Default Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
			],
			'tickets' => [
				'default' => [
					'image_url'         => tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() ),
					'narrow_image_url'  => tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() ),
					'sidebar_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
					'link_url'          => $this->get_link_url(),
					'alt_text'          => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Default Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
			],
		];
	}
}
