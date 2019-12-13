<?php
namespace Tribe\Utils;

use Tribe__Date_Utils as Dates;
use DateTime;
use DateTimeZone;
use DateTimeImmutable;
use \Codeception\TestCase\WPTestCase;

class Date_I18n_Test extends WPTestCase {
	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_be_instantiatable() {
		$date = new Date_I18n;
		$this->assertInstanceOf( Date_I18n::class, $date );
	}

	public function data_dates_and_timezones() {
		return [
			'America/Sao_Paulo' => [ '2018-02-01 18:00:00', 'America/Sao_Paulo', '2018-02-01 16:00:00' ],
			'America/New_York'  => [ '2018-02-01 18:00:00', 'America/New_York', '2018-02-01 13:00:00' ],
			'Europe/Berlin'     => [ '2018-02-01 18:00:00', 'Europe/Berlin', '2018-02-01 19:00:00' ],
			'Pacific/Honolulu'  => [ '2018-02-01 18:00:00', 'Pacific/Honolulu', '2018-02-01 08:00:00' ],
		];
	}

	/**
	 * @test
	 * @dataProvider data_dates_and_timezones
	 * @group utils
	 */
	public function it_should_properly_include_timezones( $datetime, $timezone, $expected ) {
		$timezone = new DateTimeZone( $timezone );
		$date_object = new Date_I18n;
		$date_object->setTimestamp( strtotime( $datetime ) );
		$date_object->setTimezone( $timezone );

		$this->assertEquals( $expected, $date_object->format_i18n( Dates::DBDATETIMEFORMAT ) );
	}
}
