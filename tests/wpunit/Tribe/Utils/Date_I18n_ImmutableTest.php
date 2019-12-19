<?php
namespace Tribe\Utils;

use Codeception\TestCase\WPTestCase;
use DateTimeZone;
use Tribe__Date_Utils as Dates;

class Date_I18n_Immutable_Test extends WPTestCase {
	/**
	 * The value of `date_default_timezone_get` before the tests.
	 *
	 * @var string
	 */
	protected static $date_default_timezone_backup;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		static::$date_default_timezone_backup = date_default_timezone_get();
	}

	public static function tearDownAfterClass() {
		date_default_timezone_set( static::$date_default_timezone_backup );
		parent::tearDownAfterClass();
	}

	public function data_dates_and_timezones() {
		$timezones         = [
			'UTC',
			'America/Sao_Paulo',
			'Europe/Berlin',
			'Pacific/Honolulu',
		];
		$input_to_expected = [
			'2018-02-01 18:00:00' => '2018-02-01 18:00:00',
		];
		foreach ($timezones as $default_timezone){
			foreach ($timezones as $date_timezone){
				foreach ($input_to_expected as $input => $expected){
					{
						yield $date_timezone . ' on ' . $default_timezone . ' default timezone' =>
						[ $input,$date_timezone,$default_timezone, $expected];
					};
				}
			}
		}
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_be_instantiatable() {
		$date = new Date_I18n_Immutable;
		$this->assertInstanceOf( Date_I18n_Immutable::class, $date );
	}

	/**
	 * @test
	 * @dataProvider data_dates_and_timezones
	 * @group utils
	 */
	public function it_should_retain_timezone_and_timestamp_when_created_from_mutable_object( $datetime, $timezone,$date_default_timezone,$expected ) {
		date_default_timezone_set( $date_default_timezone );

		$this->assertEquals( $date_default_timezone, date_default_timezone_get() );

		$timezone    = new DateTimeZone( $timezone );
		$mutable = new Date_I18n( $datetime, $timezone );
		$immutable = Date_I18n_Immutable::createFromMutable( $mutable);

		$this->assertEquals( $mutable->getTimestamp(), $immutable->getTimestamp() );
		$this->assertEquals( $expected, $immutable->format_i18n( Dates::DBDATETIMEFORMAT ) );
		$this->assertEquals( $timezone, $immutable->getTimezone() );
	}
}
