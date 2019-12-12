<?php
namespace Tribe\Utils;

use Tribe__Date_Utils as Dates;
use DateTime;
use DateTimeImmutable;

class Date_I18n_Immutable extends DateTimeImmutable {
	/**
	 * {@inheritDoc}
	 *
	 * @return Date_I18n_Immutable Localizable variation of DateTimeImmutable.
	 */
	public static function createFromMutable( $datetime ) {
		return new self( $datetime->format( Dates::DBDATETIMEFORMAT ), $datetime->getTimezone() );
	}

	/**
	 * Returns a translated string usign the params from this Immutable DateTime instance.
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

