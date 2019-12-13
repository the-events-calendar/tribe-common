<?php
/**
 * Extends DateTimeImmutable and includes translation capabilities.
 *
 * @package Tribe\Utils
 * @since   TBD
 */
namespace Tribe\Utils;

use Tribe__Date_Utils as Dates;
use DateTime;
use DateTimeImmutable;

/**
 * Class Date i18n Immutable
 *
 * @package Tribe\Utils
 * @since   TBD
 */
class Date_I18n_Immutable extends DateTimeImmutable {
	/**
	 * {@inheritDoc}
	 *
	 * @return Date_I18n_Immutable Localizable variation of DateTimeImmutable.
	 */
	public static function createFromMutable( $datetime ) {
		$date_object = new self;
		$date_object = $date_object->setTimestamp( $datetime->getTimestamp() );
		$date_object = $date_object->setTimezone( $datetime->getTimezone() );
		return $date_object;
	}

	/**
	 * Returns a translated string usign the params from this Immutable DateTime instance.
	 *
	 * @since  TBD
	 *
	 * @param  string $date_format Format to be used in the translation.
	 *
	 * @return string         Translated date.
	 */
	public function format_i18n( $date_format ) {
		$unix_with_tz = strtotime( $this->format( Dates::DBDATETIMEFORMAT ) );
		$translated   = date_i18n( $date_format, $unix_with_tz );
		return $translated;
	}
}
