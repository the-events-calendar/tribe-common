<?php
/**
 * Stellar Sale Promo Conditional Content.
 *
 * @since 6.8.2
 * @deprecated TBD The Stellar Sale promotion has been retired.
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Conditional_Content\Traits\{
	Has_Datetime_Conditions,
	Has_Targeted_Creative_Upsell,
	Is_Dismissible,
	Requires_Capability
};
use Tribe\Utils\Date_I18n;

_deprecated_file( __FILE__, 'TBD', '', 'The Stellar Sale promotion has been retired.' );

/**
 * Set up for Stellar Sale promo.
 *
 * The promotion has been retired and its creatives removed, so every method
 * here returns an inert value and the banner never renders. The class is kept
 * only so that anything still resolving it keeps working.
 *
 * @since 6.8.2
 * @since 6.9.8 Modified to use the Has_Datetime_Conditions trait instead of extending the Datetime_Conditional_Abstract class.
 * @since 6.9.8 Modified to use the Requires_Capability trait.
 * @since 6.9.8 Modified to use the Has_Targeted_Creative_Upsell trait.
 * @since 6.9.8 Modified to use the Is_Dismissible trait.
 * @deprecated TBD The Stellar Sale promotion has been retired.
 */
class Stellar_Sale extends Promotional_Content_Abstract {
	use Has_Datetime_Conditions;
	use Is_Dismissible;
	use Requires_Capability;
	use Has_Targeted_Creative_Upsell;

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
	protected string $background_color = '#1c202f';

	/**
	 * Sale name for display.
	 *
	 * @since 6.8.2
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return string Always an empty string.
	 */
	protected function get_sale_name(): string {
		_deprecated_function( __METHOD__, 'TBD' );

		return '';
	}

	/**
	 * Register actions and filters.
	 *
	 * @since 6.8.2
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return void
	 */
	public function hook(): void {
		_deprecated_function( __METHOD__, 'TBD' );
	}

	/**
	 * Link URL for the promotional content.
	 *
	 * @since 6.8.2
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return string Always an empty string.
	 */
	protected function get_link_url(): string {
		_deprecated_function( __METHOD__, 'TBD' );

		return '';
	}

	/**
	 * Start of the display window.
	 *
	 * @since 6.8.2
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return ?Date_I18n Always null.
	 */
	protected function get_start_time(): ?Date_I18n {
		_deprecated_function( __METHOD__, 'TBD' );

		return null;
	}

	/**
	 * End of the display window.
	 *
	 * @since 6.8.2
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return ?Date_I18n Always null.
	 */
	protected function get_end_time(): ?Date_I18n {
		_deprecated_function( __METHOD__, 'TBD' );

		return null;
	}

	/**
	 * Determines if the promotional content should be displayed.
	 *
	 * @since 6.8.2
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return bool Always false.
	 */
	protected function should_display(): bool {
		_deprecated_function( __METHOD__, 'TBD' );

		return false;
	}

	/**
	 * Check if Event Tickets Plus Seating is licensed and active.
	 *
	 * @since 6.8.3
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return bool Always false.
	 */
	public static function check_seating_license(): bool {
		_deprecated_function( __METHOD__, 'TBD' );

		return false;
	}

	/**
	 * Map of creatives keyed by suite and plugin.
	 *
	 * @since 6.8.2
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return array Always an empty array.
	 */
	protected function get_suite_creative_map(): array {
		_deprecated_function( __METHOD__, 'TBD' );

		return [];
	}

	/**
	 * Get the alt text for the creative.
	 *
	 * @since 6.9.8
	 * @deprecated TBD The Stellar Sale promotion has been retired.
	 *
	 * @return string Always an empty string.
	 */
	protected function get_creative_alt_text(): string {
		_deprecated_function( __METHOD__, 'TBD' );

		return '';
	}
}
