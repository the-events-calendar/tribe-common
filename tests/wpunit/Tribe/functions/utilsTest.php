<?php
namespace Tribe\functions;

class utilsTest extends \Codeception\TestCase\WPTestCase {

	public function urls() {
		return [
			[ 'http://some.dev', 'foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev', 'foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev', '/foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev', '/foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', 'foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', 'foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', '/foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', '/foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev?bar=baz', 'foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', 'foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz&another=value', 'foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', 'foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev#frag', 'foo', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', 'foo/', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', '/foo', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', '/foo/', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev', 'some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', 'some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', '/some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', '/some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', 'some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', 'some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', '/some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', '/some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev?bar=baz', 'some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', 'some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz&another=value', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev#frag', 'some/foo', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', 'some/foo/', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', '/some/foo', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', '/some/foo/', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
		];
	}

	/**
	 * @test
	 * it should allow appending path to various urls
	 * @dataProvider urls
	 */
	public function it_should_allow_appending_path_to_various_urls( $url, $path, $expected ) {
		$this->assertEquals( $expected, tribe_append_path( $url, $path ) );
	}
}