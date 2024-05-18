<?php

namespace Tribe\Utils;

use Tribe\Utils\Strings;

class StringTest extends \Codeception\TestCase\WPTestCase {

	/**
	 */
	public function test_string_replace_first() {
		$string      = '<div><div><span><p></p></span></div></div>';
		$replacement = '<div>%%REPLACEMENT%%';
		$result = '<div>%%REPLACEMENT%%<div><span><p></p></span></div></div>';

		$modified_string = Strings::replace_first( '<div>', $replacement, $string );

		$this->assertEquals( $result, $modified_string );
	}

	/**
	 */
	public function test_string_replace_last() {
		$string      = '<div><p><div><span><p></p></span></div></p></div>';
		$replacement = '%%REPLACEMENT%%</div>';
		$result = '<div><p><div><span><p></p></span></div></p>%%REPLACEMENT%%</div>';

		$modified_string = Strings::replace_last( '</div>', $replacement, $string );

		$this->assertEquals( $result, $modified_string );
	}
}
