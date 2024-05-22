<?php

class filesTest extends \Codeception\TestCase\WPTestCase {

	public function tec_is_file_from_plugins_data_provider() {
		return [
			'empty file'             => [
				[ '', 'the-events-calendar.php', 'events-calendar-pro.php' ],
				false
			],
			'empty file, no plugins' => [
				[ '', '' ],
				false
			],
			'empty plugins'          => [
				[ __FILE__ ],
				false
			],
			'file from TEC'          => [
				[ __FILE__, 'the-events-calendar.php' ],
				true
			],
			'tmp file'               => [
				[ sys_get_temp_dir() . '/some-file.php', 'the-events-calendar.php' ],
				false
			]
		];
	}

	/**
	 * @dataProvider tec_is_file_from_plugins_data_provider
	 */
	public function test_tec_is_file_from_plugins( $args, $expected ) {
		$this->assertEquals( $expected, tec_is_file_from_plugins( ...$args ) );
	}
}
