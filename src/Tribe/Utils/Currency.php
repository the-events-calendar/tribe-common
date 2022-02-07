<?php
/**
 * Utility functions for currency.
 *
 * @package Tribe\Utils
 * @since   TBD
 */
namespace Tribe\Utils;

use Tribe\Traits\With_Locale;

/**
 * Class Date i18n
 *
 * @package Tribe\Utils
 * @since   4.11.0
 */
class Currency  {
	use With_Locale;

	private static $symbol;

	private static $places;

	private static $thousands;

	private static $decimal;

	public function get_symbol() {
		return static::$symbol;
	}
}
