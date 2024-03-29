<?php

namespace Template_Tags;

use Tribe__Date_Utils;

class DateTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 */
	public function it_should_convert_string_date_to_timestamp() {
		$date     = '2023-10-10 10:10:10';
		$expected = strtotime( $date );
		$actual   = Tribe__Date_Utils::is_timestamp( tribe_format_date( $date, true, 'U' ) );

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_use_date_format_if_provided() {
		$date = '2023-10-10 10:10:10';

		$this->assertEquals( '2023', tribe_format_date( $date, true, 'Y' ) );
	}

	/**
	 * @test
	 */
	public function it_should_switch_to_en_locale_for_french_ampm_format() {
		// Switch locale to French
		switch_to_locale( 'fr_FR' );

		$formattedDateA = tribe_format_date( '2023-10-10 10:10:10 AM', true, 'g:i A' );
		$formattedDatea = tribe_format_date( '2023-10-10 10:10:10 PM', true, 'g:i a' );

		// Check if date formatting is in English format.
		$this->assertEquals( '10:10 AM', $formattedDateA );
		$this->assertEquals( '10:10 pm', $formattedDatea );

		// Revert locale back to English
		switch_to_locale( 'en_US' );
	}
}

