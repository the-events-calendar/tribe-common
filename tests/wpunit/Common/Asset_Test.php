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

	protected static array $back_up = [];

	/**
	 * @before
	 */
	public function backup_and_set_up() {
		self::$back_up = [
			'hook_prefix' => Config::get_hook_prefix(),
			'version'     => Config::get_version(),
			'path'        => Config::get_path(),
			'relative'    => Config::get_relative_asset_path(),
		];

		Config::set_hook_prefix( 'bork' );
		Config::set_version( '1.0.0' );
		Config::set_path( codecept_root_dir( '/' ) );
		Config::set_relative_asset_path( 'tests/_data/stellar-resources' );
	}

	/**
	 * @after
	 */
	public function restore_backup() {
		$this->remove_assets( 'feature-base' );
		$this->remove_assets( 'feature-editor' );
		$this->remove_assets( 'feature-frontend' );
		Config::reset();

		Config::set_hook_prefix( self::$back_up['hook_prefix'] );
		Config::set_version( self::$back_up['version'] );
		Config::set_path( self::$back_up['path'] );
		Config::set_relative_asset_path( self::$back_up['relative'] );
	}

	protected function add_assets( $slug ) {
		Asset::add( $slug, $slug . '.js' )
			->prefix_asset_directory( false );
		Asset::add( $slug . '-style', $slug . '.css' )
			->prefix_asset_directory( false );
		Stellar_Asset::add( 'stellar-' . $slug, $slug . '.js' )
			->prefix_asset_directory( false );
		Stellar_Asset::add( 'stellar-' . $slug . '-style', $slug . '.css' )
			->prefix_asset_directory( false );
	}

	protected function remove_assets( $slug ) {
		Assets::init()->remove( $slug );
		Assets::init()->remove( $slug . '-style' );
		Assets::init()->remove( 'stellar-' . $slug );
		Assets::init()->remove( 'stellar-' . $slug . '-style' );
	}

	/**
	 * @test
	 */
	public function it_should_return_the_same_thing() {
		Assets::init();

		// Add assets.
		$this->add_assets( 'feature-base' );
		$this->add_assets( 'feature-editor' );
		$this->add_assets( 'feature-frontend' );

		foreach ( [ 'feature-base', 'feature-editor', 'feature-frontend' ] as $slug ) {
			$this->assertEquals(
				Assets::init()->get( $slug )->get_url(),
				Assets::init()->get( 'stellar-' . $slug )->get_url()
			);
			$this->assertEquals(
				Assets::init()->get( $slug . '-style' )->get_url(),
				Assets::init()->get( 'stellar-' . $slug . '-style' )->get_url()
			);
		}
	}

	/**
	 * The test is that the plugins directory is actually in the path ABSPATH . 'foo/'.
	 * But in the location ABSPATH . 'wp-content/' user has created a symlink named plugins which points to ABSPATH . 'foo/'.
	 * Our implementation should return a URL inside the wp-content directory while the asset one should return the true URL outside the plugins directory.
	 *
	 * @test
	 */
	public function it_should_not_return_the_same_thing_when_symlinks() {
		Config::set_path( ABSPATH . 'foo/the-events-calendar/common/' );
		$this->set_fn_return( 'dirname', static fn( $dir, $level = 1 ) => $level !== 4 ? $dir : ABSPATH . 'foo/', true );

		Assets::init();

		// Add assets.
		$this->add_assets( 'feature-base' );
		$this->add_assets( 'feature-editor' );
		$this->add_assets( 'feature-frontend' );

		foreach ( [ 'feature-base', 'feature-editor', 'feature-frontend' ] as $slug ) {
			$this->assertEquals(
				home_url( '/wp-content/plugins/the-events-calendar/common/tests/_data/stellar-resources/' . $slug . '.js' ),
				Assets::init()->get( $slug )->get_url( false )
			);

			$this->assertEquals(
				'/var/www/html/foo/the-events-calendar/common/tests/_data/stellar-resources/' . $slug . '.js',
				Assets::init()->get( 'stellar-' . $slug )->get_url( false )
			);

			$this->assertEquals(
				home_url( '/wp-content/plugins/the-events-calendar/common/tests/_data/stellar-resources/' . $slug . '.css' ),
				Assets::init()->get( $slug. '-style' )->get_url( false )
			);

			$this->assertEquals(
				'/var/www/html/foo/the-events-calendar/common/tests/_data/stellar-resources/' . $slug . '.css',
				Assets::init()->get( 'stellar-' . $slug . '-style' )->get_url( false )
			);
		}
	}
}
