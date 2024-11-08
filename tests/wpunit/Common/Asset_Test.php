<?php

namespace TEC\Common;

use Codeception\TestCase\WPTestCase;
use TEC\Common\StellarWP\Assets\Asset as Stellar_Asset;
use TEC\Common\StellarWP\Assets\Assets;
use TEC\Common\StellarWP\Assets\Config;
use Tribe\Tests\Traits\With_Uopz;

/**
 * Class Opt_InTest
 *
 * @since 5.1.13
 *
 * @package TEC\Common\Telemetry
 */
class Asset_Test extends WPTestCase {
	use With_Uopz;

	protected $request_args = [];

	/**
	 * @before
	 */
	public function set_up() {
		Config::set_hook_prefix( 'bork' );
		Config::set_version( '1.0.0' );
		Config::set_path( dirname( __DIR__, 2 ) );
		Config::set_relative_asset_path( 'tests/_data/stellar-resources' );
	}

	/**
	 * @after
	 */
	public function after() {
		Config::reset();
	}

	public function add_assets( $slug ) {
		$slug = 'test-' . $slug;
		Asset::add( $slug, $slug . '.js' )
			->prefix_asset_directory( false )
			->register();
		Asset::add( $slug . '-style', $slug . '.css' )
			->prefix_asset_directory( false )
			->register();
		Stellar_Asset::add( 'stellar-' . $slug, $slug . '.js' )
			->prefix_asset_directory( false )
			->register();
		Stellar_Asset::add( 'stellar-' . $slug . '-style', $slug . '.css' )
			->prefix_asset_directory( false )
			->register();
	}

	/**
	 * @test
	 */
	public function it_should_return_the_same_thing() {
		Assets::init();

		// Add assets.
		array_map( [ $this, 'add_assets' ], range( 1, 3 ) );

		foreach ( range( 1, 3 ) as $i ) {
			$this->assertEquals(
				Assets::init()->get( 'test-' . $i )->get_url(),
				Assets::init()->get( 'stellar-test-' . $i )->get_url()
			);
			$this->assertEquals(
				Assets::init()->get( 'test-' . $i . '-style' )->get_url(),
				Assets::init()->get( 'stellar-test-' . $i . '-style' )->get_url()
			);
		}
	}

	/**
	 * @test
	 */
	public function it_should_not_return_the_same_thing_when_symlinks() {
		Assets::init();

		// Add assets.
		array_map( [ $this, 'add_assets' ], range( 1, 3 ) );

		// The test is that the plugins directory is actually in the path ABSPATH . 'foo/'.
		// But in the location ABSPATH . 'wp-content/' user has created a symlink named plugins which points to ABSPATH . 'foo/'.
		// Our implementation should return a URL inside the wp-content directory while the asset one should return the true URL outside the plugins directory.
		$this->set_fn_return( 'dirname', static fn( $dir, $level = 1 ) => $level !== 4 ? $dir : ABSPATH . 'foo/', true );

		foreach ( range( 1, 3 ) as $i ) {
			$this->assertEquals(
				str_replace( 'the-events-calendar.php/', '', plugins_url( 'common/tests/_data/stellar-resources/test-' . $i . '.js', \Tribe__Events__Main::instance()->plugin_file ) ),
				Assets::init()->get( 'test-' . $i )->get_url()
			);

			$this->assertEquals(
				home_url( '/foo/common/tests/_data/stellar-resources/test-' . $i . '.js', \Tribe__Events__Main::instance()->plugin_file ),
				Assets::init()->get( 'stellar-test-' . $i )->get_url()
			);

			$this->assertEquals(
				str_replace( 'the-events-calendar.php/', '', plugins_url( 'common/tests/_data/stellar-resources/test-' . $i . '.css', \Tribe__Events__Main::instance()->plugin_file ) ),
				Assets::init()->get( 'test-' . $i . '-style' )->get_url()
			);

			$this->assertEquals(
				home_url( '/foo/common/tests/_data/stellar-resources/test-' . $i . '.css', \Tribe__Events__Main::instance()->plugin_file ),
				Assets::init()->get( 'stellar-test-' . $i . '-style' )->get_url()
			);
		}
	}
}
