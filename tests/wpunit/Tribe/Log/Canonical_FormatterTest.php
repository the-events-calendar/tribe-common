<?php

namespace Tribe\Log;

use TEC\Common\Monolog\Logger;

class Canonical_FormatterTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should correctly format a record that comes w/o context
	 *
	 * @test
	 */
	public function should_correctly_format_a_record_that_comes_w_o_context() {
		$record = [
			'channel' => 'default',
			'level'      => Logger::ERROR,
			'level_name' => 'ERROR',
			'message'    => 'test test test',
			'context'    => [],
			'extra'      => [],
		];

		$formatter = new Canonical_Formatter();
		$formatted = $formatter->format( $record );

		$expected = 'tribe.default.ERROR: test test test';
		$this->assertEquals( $expected, $formatted );
	}

	/**
	 * It should correctly format a record w/ context
	 *
	 * @test
	 */
	public function should_correctly_format_a_record_w_context() {
		$context = [
			'one'           => 23,
			'two'           => [ 'foo' => 'bar', 'bar' => 89 ],
			'three'         => 'four',
			'd_and_d'       => true,
			'not_encodable' => fopen( __FILE__, 'rb' )
		];
		$record  = [
			'channel' => 'default',
			'level'      => Logger::ERROR,
			'level_name' => 'ERROR',
			'message'    => 'test',
			'context'    => $context,
			'extra'      => [],
		];

		$formatter = new Canonical_Formatter();
		$formatted = $formatter->format( $record );

		$expected = 'tribe-canonical-line channel=default level=error source=test one=23 ' .
			'two="{\"foo\":\"bar\",\"bar\":89}" three=four d_and_d=true not_encodable=malformed';
		$this->assertEquals( $expected, $formatted );
	}
}
