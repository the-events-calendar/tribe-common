<?php
namespace Tribe\Utils;

use Tribe__Date_Utils as Dates;
use DateTime;
use DateTimeImmutable;

class Date_I18n extends DateTime {
	/**
	 * {@inheritDoc}
	 *
	 * @return Date_I18n Localizable variation of DateTime.
	 */
	public static function createFromImmutable( $datetime ) {
		return new self( $datetime->format( Dates::DBDATETIMEFORMAT ), $datetime->getTimezone() );
	}

	/**
	 * Returns a translated string usign the params from this DateTime instance.
	 *
	 * @since  TBD
	 *
	 * @param  string $string Format to be used in the translation.
	 *
	 * @return string         Translated date.
	 */
	public function format_i18n( $string ) {
		$unix_date = $this->format( 'U' );
		$translated = date_i18n( $string, $unix_date );
		return $translated;
	}
}

