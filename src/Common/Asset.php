<?php
/**
 * TEC Implementation of StellarWP Asset.
 *
 * @since TBD
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use TEC\Common\StellarWP\Assets\Asset as Stellar_Asset;

/**
 * Extending TEC\Common\StellarWP\Assets\Asset in order to allow following
 * possible symlinks in an asset's path.
 *
 * @since TBD
 */
class Asset extends Stellar_Asset {
	/**
	 * Gets the root path for the resource considering the resource is inside a PLUGIN
	 * and that it could be a symlink.
	 *
	 * @since TBD
	 *
	 * @return ?string
	 */
	public function get_root_path(): ?string {
		return str_replace( trailingslashit( dirname( __DIR__, 4 ) ), trailingslashit( WP_PLUGIN_DIR ), parent::get_root_path() );
	}
}
