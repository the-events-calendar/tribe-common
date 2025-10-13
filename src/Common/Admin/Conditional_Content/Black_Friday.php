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
	Is_Dismissible,
	Requires_Capability
};
use Tribe\Utils\Date_I18n;

/**
 * Set up for Black Friday promo.
 *
 * @since 6.3.0
 */
class Black_Friday extends Promotional_Content_Abstract {
	use Has_Datetime_Conditions {
		get_start_time as get_start_time_from_trait;
		get_end_time as get_end_time_from_trait;
		should_display as should_display_datetime;
	}
	use Is_Dismissible;
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
	public function hook(): void {
		// Register AJAX dismiss handler from Is_Dismissible trait.
		add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );
	}

	/**
	 * @inheritdoc
	 */
	protected function get_link_url(): string {
		return 'https://evnt.is/tec-bf-2024';
	}

	/**
	 * Override to set time to 4:00 AM UTC.
	 *
	 * @since 6.3.0
	 *
	 * @return ?Date_I18n
	 */
	protected function get_start_time(): ?Date_I18n {
		$date = $this->get_start_time_from_trait();
		if ( null === $date ) {
			return null;
		}

		return $date->setTime( 4, 0 );
	}

	/**
	 * Override to set time to 4:00 AM UTC.
	 *
	 * @since 6.3.0
	 *
	 * @return ?Date_I18n
	 */
	protected function get_end_time(): ?Date_I18n {
		$date = $this->get_end_time_from_trait();
		if ( null === $date ) {
			return null;
		}

		return $date->setTime( 4, 0 );
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

		// Check if user dismissed (from Is_Dismissible trait).
		if ( $this->has_user_dismissed() ) {
			return false;
		}

		// Check datetime conditions (from Has_Datetime_Conditions trait).
		if ( ! $this->should_display_datetime() ) {
			return false;
		}

		// Don't show if there are no upsell opportunities.
		return $this->has_upsell_opportunity();
	}
}
