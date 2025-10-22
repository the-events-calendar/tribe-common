<?php

namespace TEC\Common\SVG;

use Codeception\TestCase\WPTestCase;
use InvalidArgumentException;
use Tribe\Tests\Traits\With_Uopz;

/**
 * Class SVGTest
 *
 * @since TBD
 *
 * @package TEC\Common\SVG
 */
class SVGTest extends WPTestCase {
	use With_Uopz;

	/**
	 * @test
	 */
	public function should_register_namespace_with_string_path() {
		$svg = new SVG();

		$expected_svg = '<svg>test icon</svg>';
		$this->set_fn_return( 'file_exists', fn( $f ) => $f === '/path/to/icons/star.svg', true );
		$this->set_fn_return( 'file_get_contents', $expected_svg );

		$svg->register_namespace( 'test', '/path/to/icons' );

		$this->assertEquals( $expected_svg, $svg->get_svg( 'test/star' ) );
	}

	/**
	 * @test
	 */
	public function should_register_namespace_with_closure_path() {
		$svg = new SVG();

		$expected_svg = '<svg>heart icon</svg>';
		$this->set_fn_return( 'file_exists', fn( $f ) => $f === '/path/to/icons/heart.svg', true );
		$this->set_fn_return( 'file_get_contents', $expected_svg );

		$svg->register_namespace(
			'test',
			function ( $path ) {
				return '/path/to/icons';
			}
		);

		$this->assertEquals( $expected_svg, $svg->get_svg( 'test/heart' ) );
	}

	/**
	 * @test
	 */
	public function should_throw_exception_for_invalid_path_type() {
		$svg = new SVG();

		$this->expectException( InvalidArgumentException::class );

		$svg->register_namespace( 'test', 123 );
	}

	/**
	 * @test
	 */
	public function should_return_empty_string_for_non_existent_file() {
		$svg = new SVG();

		$svg->register_namespace( 'test', '/path/to/icons' );

		$this->assertEquals( '', $svg->get_svg( 'test/non-existent' ) );
	}

	/**
	 * @test
	 */
	public function should_return_empty_string_for_non_existent_namespace() {
		$svg = new SVG();

		$this->assertEquals( '', $svg->get_svg( 'non-existent/star' ) );
	}

	/**
	 * @test
	 */
	public function should_match_longest_namespace_first() {
		$svg = new SVG();

		$short_namespace_svg = '<svg>short namespace icon</svg>';
		$long_namespace_svg = '<svg>long namespace icon</svg>';

		$this->set_fn_return( 'file_exists', fn( $f ) => $f === '/path/to/icons/short.svg' || $f === '/path/to/admin/icons/long.svg', true );

		$this->set_fn_return(
			'file_get_contents',
			function ( $path ) use ( $short_namespace_svg, $long_namespace_svg ) {
				if ( strpos( $path, '/admin/icons/' ) !== false ) {
					return $long_namespace_svg;
				}
				return $short_namespace_svg;
			},
			true
		);

		$svg->register_namespace( 'test', '/path/to/icons' );

		$svg->register_namespace( 'test/admin', '/path/to/admin/icons' );

		$this->assertEquals(
			$long_namespace_svg,
			$svg->get_svg( 'test/admin/settings' )
		);

		$this->assertEquals(
			$short_namespace_svg,
			$svg->get_svg( 'test/star' )
		);
	}

	/**
	 * @test
	 */
	public function should_handle_hierarchical_namespaces_correctly() {
		$svg = new SVG();

		$part_svg = '<svg>part icon</svg>';
		$part_more_svg = '<svg>part/more icon</svg>';
		$part_more_specific_svg = '<svg>part/more/specific icon</svg>';

		$this->set_fn_return( 'file_exists', fn( $f ) => $f === '/path/to/icons/part.svg' || $f === '/path/to/more/icon.svg' || $f === '/path/to/specific/icon.svg', true );

		$this->set_fn_return(
			'file_get_contents',
			function ( $path ) use ( $part_svg, $part_more_svg, $part_more_specific_svg ) {
				if ( strpos( $path, '/specific/' ) !== false ) {
					return $part_more_specific_svg;
				}
				if ( strpos( $path, '/more/' ) !== false ) {
					return $part_more_svg;
				}
				if ( strpos( $path, '/base/' ) !== false ) {
					return $part_svg;
				}
				return '';
			},
			true
		);

		$svg->register_namespace( 'part/more', '/path/to/more' );
		$svg->register_namespace( 'part', '/path/to/base' );
		$svg->register_namespace( 'part/more/specific', '/path/to/specific' );

		$this->assertEquals(
			$part_more_specific_svg,
			$svg->get_svg( 'part/more/specific/icon' )
		);

		$this->assertEquals(
			$part_more_svg,
			$svg->get_svg( 'part/more/icon' )
		);

		$this->assertEquals(
			$part_svg,
			$svg->get_svg( 'part/icon' )
		);
	}

	/**
	 * @test
	 */
	public function should_not_confuse_similar_namespace_prefixes() {
		$svg = new SVG();

		$part_svg = '<svg>part icon</svg>';
		$part_more_svg = '<svg>part/more icon</svg>';
		$part_more_specific_svg = '<svg>part/more/specific icon</svg>';

		$this->set_fn_return( 'file_exists', fn( $f ) => $f === '/path/to/icons/part.svg' || $f === '/path/to/more/icon.svg' || $f === '/path/to/specific/icon.svg', true );

		$this->set_fn_return(
			'file_get_contents',
			function ( $path ) use ( $part_svg, $part_more_svg, $part_more_specific_svg ) {
				if ( strpos( $path, '/specific/' ) !== false ) {
					return $part_more_specific_svg;
				}
				if ( strpos( $path, '/more/' ) !== false ) {
					return $part_more_svg;
				}
				return $part_svg;
			},
			true
		);

		$svg->register_namespace( 'part', '/path/to/base' );
		$svg->register_namespace( 'part/more', '/path/to/more' );
		$svg->register_namespace( 'part/more/specific', '/path/to/specific' );

		$result = $svg->get_svg( 'part/more/specific/icon' );
		$this->assertEquals( $part_more_specific_svg, $result );
		$this->assertNotEquals( $part_svg, $result );

		$result = $svg->get_svg( 'part/more/icon' );
		$this->assertEquals( $part_more_svg, $result );
		$this->assertNotEquals( $part_svg, $result );
	}

	/**
	 * @test
	 */
	public function should_handle_closure_with_dynamic_paths() {
		$svg = new SVG();

		$admin_svg = '<svg>admin icon</svg>';
		$regular_svg = '<svg>regular icon</svg>';

		$this->set_fn_return( 'file_exists', true );

		$this->set_fn_return(
			'file_get_contents',
			function ( $path ) use ( $admin_svg, $regular_svg ) {
				if ( strpos( $path, '/admin/' ) !== false ) {
					return $admin_svg;
				}
				return $regular_svg;
			},
			true
		);

		$svg->register_namespace(
			'test',
			function ( $path ) {
				if ( str_starts_with( $path, 'admin/' ) ) {
					return '/path/to/admin';
				}
				return '/path/to/icons';
			}
		);

		$this->assertEquals( $admin_svg, $svg->get_svg( 'test/admin/settings' ) );
		$this->assertEquals( $regular_svg, $svg->get_svg( 'test/star' ) );
	}

	/**
	 * @test
	 */
	public function should_return_empty_string_when_closure_throws_exception() {
		$svg = new SVG();

		$svg->register_namespace(
			'test',
			function ( $path ) {
				throw new \Exception( 'Test exception' );
			}
		);

		$this->assertEquals( '', $svg->get_svg( 'test/star' ) );
	}

	/**
	 * @test
	 */
	public function should_handle_paths_with_leading_slash() {
		$svg = new SVG();

		$expected_svg = '<svg>star icon</svg>';
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'file_get_contents', $expected_svg );

		$svg->register_namespace( 'test', '/path/to/icons' );

		// Both with and without leading slash should work (ltrim removes it)
		$this->assertEquals( $expected_svg, $svg->get_svg( 'test/star' ) );
		$this->assertEquals( $expected_svg, $svg->get_svg( 'test//star' ) );
	}

	/**
	 * @test
	 */
	public function should_handle_nested_paths() {
		$svg = new SVG();

		$expected_svg = '<svg>nested icon</svg>';
		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'file_get_contents', $expected_svg );

		$svg->register_namespace( 'test', '/path/to/base' );

		$this->assertEquals( $expected_svg, $svg->get_svg( 'test/admin/icons/settings' ) );
	}

	/**
	 * @test
	 */
	public function should_handle_multiple_namespace_registrations() {
		$svg = new SVG();

		$icons_svg = '<svg>icons svg</svg>';
		$admin_svg = '<svg>admin svg</svg>';

		$this->set_fn_return( 'file_exists', true );

		$this->set_fn_return(
			'file_get_contents',
			function ( $path ) use ( $icons_svg, $admin_svg ) {
				if ( strpos( $path, '/admin/' ) !== false ) {
					return $admin_svg;
				}
				return $icons_svg;
			},
			true
		);

		$svg->register_namespace( 'icons', '/path/to/icons' );
		$svg->register_namespace( 'admin', '/path/to/admin' );

		$this->assertEquals( $icons_svg, $svg->get_svg( 'icons/star' ) );
		$this->assertEquals( $admin_svg, $svg->get_svg( 'admin/settings' ) );
	}

	/**
	 * @test
	 */
	public function should_handle_empty_svg_file() {
		$svg = new SVG();

		$this->set_fn_return( 'file_exists', true );
		$this->set_fn_return( 'file_get_contents', false );

		$svg->register_namespace( 'test', '/path/to/icons' );

		$this->assertEquals( '', $svg->get_svg( 'test/empty' ) );
	}

	/**
	 * @test
	 */
	public function should_override_namespace_when_registered_again() {
		$svg = new SVG();

		$first_path_svg = '<svg>first path icon</svg>';
		$second_path_svg = '<svg>second path icon</svg>';

		$this->set_fn_return( 'file_exists', true );

		$this->set_fn_return( 'file_get_contents', $first_path_svg );
		$svg->register_namespace( 'test', '/path/to/icons' );

		$this->assertEquals( $first_path_svg, $svg->get_svg( 'test/star' ) );

		$this->set_fn_return( 'file_get_contents', $second_path_svg );
		$svg->register_namespace( 'test', '/path/to/admin' );

		$this->assertEquals( $second_path_svg, $svg->get_svg( 'test/settings' ) );
	}

	/**
	 * @test
	 */
	public function should_ensure_sorting_by_namespace_length_during_registration() {
		$svg = new SVG();

		$this->set_fn_return( 'file_exists', true );

		$short_svg = '<svg>short</svg>';
		$medium_svg = '<svg>medium</svg>';
		$long_svg = '<svg>long</svg>';

		$this->set_fn_return(
			'file_get_contents',
			function ( $path ) use ( $short_svg, $medium_svg, $long_svg ) {
				if ( strpos( $path, '/long/' ) !== false ) {
					return $long_svg;
				}
				if ( strpos( $path, '/medium/' ) !== false ) {
					return $medium_svg;
				}
				return $short_svg;
			},
			true
		);

		$svg->register_namespace( 'a', '/path/to/short' );
		$svg->register_namespace( 'a/b/c', '/path/to/long' );
		$svg->register_namespace( 'a/b', '/path/to/medium' );

		$this->assertEquals( $long_svg, $svg->get_svg( 'a/b/c/icon' ) );
		$this->assertEquals( $medium_svg, $svg->get_svg( 'a/b/icon' ) );
		$this->assertEquals( $short_svg, $svg->get_svg( 'a/icon' ) );
	}
}
