<?php
namespace Tribe\Utils;

use Tribe__Date_Utils as Dates;
use DateTime;
use DateTimeZone;
use DateTimeImmutable;
use \Codeception\TestCase\WPTestCase;

class Date_I18n_Immutable_Test extends WPTestCase {
	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_be_instantiatable() {
		$date = new Date_I18n_Immutable;
		$this->assertInstanceOf( Date_I18n_Immutable::class, $date );
	}

	public function data_dates_and_timezones() {
		return [
			'America/Sao_Paulo' => [ '2018-02-01 18:00:00', 'America/Sao_Paulo', '2018-02-01 18:00:00' ],
			'America/New_York'  => [ '2018-02-01 18:00:00', 'America/New_York', '2018-02-01 18:00:00' ],
			'Europe/Berlin'     => [ '2018-02-01 18:00:00', 'Europe/Berlin', '2018-02-01 18:00:00' ],
			'Pacific/Honolulu'  => [ '2018-02-01 18:00:00', 'Pacific/Honolulu', '2018-02-01 18:00:00' ],
		];
	}

	/**
	 * @test
	 * @dataProvider data_dates_and_timezones
	 * @group utils
	 */
	public function it_should_retain_timezone_and_timestamp_when_created_from_mutable_object( $datetime, $timezone, $expected ) {
		$timezone = new DateTimeZone( $timezone );
		$date_object = new Date_I18n_Immutable( $datetime, $timezone );
		$date_object = Date_I18n_Immutable::createFromMutable( $date_object );

		$this->assertEquals( $expected, $date_object->format_i18n( Dates::DBDATETIMEFORMAT ) );
		$this->assertEquals( $timezone, $date_object->getTimezone() );
	}
}
