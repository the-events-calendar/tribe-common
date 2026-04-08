<?php
/**
 * TEC Implementation of StellarWP Asset.
 *
 * @since 6.3.2
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use TEC\Common\StellarWP\Assets\Asset as Stellar_Asset;
use TEC\Common\StellarWP\Assets\Assets;

/**
 * Extending TEC\Common\StellarWP\Assets\Asset in order to allow following
 * possible symlinks in an asset's path.
 *
 * @since 6.3.2
 */
class Asset extends Stellar_Asset {
	/**
	 * Gets the root path for the resource considering the resource is inside a PLUGIN
	 * and that it could be a symlink.
	 *
	 * @since 6.3.2
	 *
	 * @return ?string
	 */
	public function get_root_path(): ?string {
		return str_replace( trailingslashit( dirname( __DIR__, 4 ) ), trailingslashit( WP_PLUGIN_DIR ), parent::get_root_path() );
	}

	/**
	 * Registers an asset.
	 *
	 * @param string      $slug      The asset slug.
	 * @param string      $file      The asset file path.
	 * @param string|null $version   The asset version.
	 * @param string|null $root_path The path to the root of the plugin.
	 */
	public static function add( string $slug, string $file, string $version = null, $root_path = null ) {
		return Assets::init()->add( new self( $slug, $file, $version, $root_path ) );
	}
}
