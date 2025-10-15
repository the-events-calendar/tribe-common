<?php
/**
 * Black Friday Promo Conditional Content.
 *
 * @since 6.3.0
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Conditional_Content\Traits\{
	Has_Datetime_Conditions,
	Has_Generic_Upsell_Opportunity,
	Requires_Capability
};

/**
 * Set up for Black Friday promo.
 *
 * @since 6.3.0
 * @since TBD Modified to use the Has_Datetime_Conditions trait instead of extending the Datetime_Conditional_Abstract class.
 * @since TBD Modified to use the Requires_Capability trait.
 * @since TBD Modified to use the Has_Generic_Upsell_Opportunity.
 * @since TBD Modified to remove dismissible functionality.
 */
class Black_Friday extends Promotional_Content_Abstract {
	use Has_Datetime_Conditions {
		should_display as should_display_datetime;
	}
	use Requires_Capability;
	use Has_Generic_Upsell_Opportunity;

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
	 * @var int
	 */
	protected int $start_time = 4;

	/**
	 * @inheritdoc
	 *
	 * @var int
	 */
	protected int $end_time = 4;

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
	protected string $background_color = '#111';

	/**
	 * @inheritdoc
	 */
	protected function get_sale_name(): string {
		return __( 'Black Friday Sale', 'tribe-common' );
	}

	/**
	 * @inheritdoc
	 */
	public function hook(): void {
		// no-op.
	}

	/**
	 * @inheritdoc
	 */
	protected function get_link_url(): string {
		return 'https://evnt.is/tec-bf-2025';
	}

	/**
	 * @inheritdoc
	 */
	protected function should_display(): bool {
		// Check if hidden by filter.
		if ( tec_should_hide_upsell( $this->get_slug() ) ) {
			return false;
		}

		// Check user capability (from Requires_Capability trait).
		if ( ! $this->check_capability() ) {
			return false;
		}

		// Check datetime conditions (from Has_Datetime_Conditions trait).
		if ( ! $this->should_display_datetime() ) {
			return false;
		}

		// Don't show if there are no upsell opportunities.
		return $this->has_upsell_opportunity();
	}

	/**
	 * Get the alt text for the creative.
	 *
	 * @since TBD
	 *
	 * @return string The alt text.
	 */
	protected function get_creative_alt_text(): string {
		// Fallback to default behavior.
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		return sprintf(
			/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
			esc_html__(
				'%1$s %2$s for The Events Calendar 30%% off plugins, add-ons, bundles, everything!.',
				'tribe-common'
			),
			$year,
			$sale_name
		);
	}
}
