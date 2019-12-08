<?php
namespace Tribe\Utils;

class Date_I18n_Immutable extends \DateTimeImmutable {
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
		$formatted = $this->format( $string );
		$unix_date =$this->format( 'U' );
		$translated = date_i18n( $string, $unix_date );
		return $translated;
	}
}

