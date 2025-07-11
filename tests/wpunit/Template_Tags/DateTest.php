<?php

namespace Template_Tags;

use Tribe__Date_Utils;

class DateTest extends \Codeception\TestCase\WPTestCase {

	protected function setUp() {
		parent::setUp();
	}

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

	public static function tribe_get_end_date_data_provider(): array {
		return [
			'null timezone' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							null,
							'June 1, 2022, 11:00 am',
						];
					}
				],
			'UTC timezone' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'UTC',
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'UTC+5 timezone' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'UTC+5',
							'June 1, 2022, 2:00 pm',
						];
					}
				],
			'empty string timezone' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'',
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'negative UTC offset' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'UTC-8',
							'June 1, 2022, 1:00 am',
						];
					}
				],
			'named timezone America/New_York' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'America/New_York',
							'June 1, 2022, 5:00 am',
						];
					}
				],
			'named timezone Asia/Tokyo' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'Asia/Tokyo',
							'June 1, 2022, 6:00 pm',
						];
					}
				],
			'invalid timezone string' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'Invalid/Timezone',
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'numeric zero timezone' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							0,
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'boolean false timezone' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							false,
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'UTC with decimal offset' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'UTC+5.5',
							'June 1, 2022, 2:30 pm',
						];
					}
				],
			'GMT timezone format' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'GMT',
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'GMT with positive offset' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'GMT+3',
							'June 1, 2022, 12:00 pm',
						];
					}
				],
			'whitespace only timezone' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'   ',
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'special characters timezone' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'@#$%',
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'very long timezone string' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							str_repeat('A', 200),
							'June 1, 2022, 9:00 am',
						];
					}
				],
			'extreme positive UTC offset' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'UTC+24',
							'June 2, 2022, 9:00 am',
						];
					}
				],
			'extreme negative UTC offset' =>
				[
					function() {
						return [
							tribe_events()->set_args( [ 'timezone' => 'Europe/Zurich', 'title' => 'Test', 'status' => 'publish', 'start_date' => '2022-06-01 10:00:00', 'duration' => HOUR_IN_SECONDS ]  )->create(),
							true,
							'F j, Y, g:i a',
							'UTC-24',
							'May 31, 2022, 9:00 am',
						];
					}
				],
		];
	}

	/**
	 * @dataProvider tribe_get_end_date_data_provider
	 */
	public function test_tribe_get_end_date( \Closure $fixture ) {
		[ $event, $display_time, $date_format, $timezone, $expected ] = $fixture();

		$actual = tribe_get_end_date( $event, $display_time, $date_format, $timezone );

		$this->assertEquals( $expected, $actual );
	}
}
