<?php
/**
 * Handles tribe-common library loading and version negotiation.
 *
 * @since TBD
 *
 * @package TEC\Common
 */

namespace TEC\Common;

/**
 * Class Common_Loader
 *
 * Centralizes the logic for determining which tribe-common library to use
 * when multiple plugins (TEC, ET, etc.) are active.
 *
 * @since TBD
 */
class Common_Loader {

	/**
	 * Regex pattern to extract VERSION constant from Main.php files.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private static $common_version_regex = "/const\s+VERSION\s*=\s*'([^']+)'/m";

	/**
	 * Cache for version lookups to avoid repeated file reads.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private static $version_cache = [];

	/**
	 * Registers a plugin's common library path and version for consideration.
	 *
	 * This method should be called by each plugin (TEC, ET, etc.) during their
	 * initialization to participate in the common library version negotiation.
	 *
	 * @since TBD
	 *
	 * @param string $plugin_path    Path to the plugin's root directory.
	 * @param string $plugin_name    Name of the plugin for debugging.
	 * @param string $common_subpath Relative path to common dir (usually 'common/src/Tribe').
	 *
	 * @return bool True if this plugin's common was selected, false otherwise.
	 */
	public static function register_common_path( $plugin_path, $plugin_name, $common_subpath = 'common/src/Tribe' ) {
		$full_common_path = trailingslashit( $plugin_path ) . $common_subpath;
		$main_file_path   = $full_common_path . '/Main.php';

		// Validate path exists and is readable.
		if ( ! file_exists( $main_file_path ) || ! is_readable( $main_file_path ) ) {
			self::log_debug( "Common path not accessible: {$main_file_path} (from {$plugin_name})" );
			return false;
		}

		// Get version from cache or file.
		$version = self::get_version_cached( $main_file_path );

		if ( false === $version ) {
			self::log_debug( "Could not extract version from: {$main_file_path} (from {$plugin_name})" );
			return false;
		}

		// Check if we should use this common library.
		if ( self::should_use_common( $full_common_path, $version, $plugin_name ) ) {
			self::set_global_common_info( $full_common_path, $version, $plugin_name );
			return true;
		}

		return false;
	}

	/**
	 * Determines if the provided common library should be used.
	 *
	 * @since TBD
	 *
	 * @param string $common_path Path to the common directory.
	 * @param string $version     Version of this common library.
	 * @param string $plugin_name Name of plugin providing this common.
	 *
	 * @return bool True if this common should be used.
	 */
	private static function should_use_common( $common_path, $version, $plugin_name ) {
		$current_info = $GLOBALS['tribe-common-info'] ?? null;

		// If no common is set yet, use this one.
		if ( empty( $current_info ) ) {
			self::log_debug( "No common set yet, using {$plugin_name} v{$version}" );
			return true;
		}

		// If this version is newer, use it.
		if ( version_compare( $current_info['version'], $version, '<' ) ) {
			self::log_debug( "Upgrading common from {$current_info['version']} to {$version} (from {$plugin_name})" );
			return true;
		}

		self::log_debug( "Keeping existing common v{$current_info['version']}, {$plugin_name} v{$version} is not newer" );
		return false;
	}

	/**
	 * Sets the global common info with thread-safe approach.
	 *
	 * @since TBD
	 *
	 * @param string $common_path Path to common directory.
	 * @param string $version     Version string.
	 * @param string $plugin_name Plugin name that provided this common.
	 */
	private static function set_global_common_info( $common_path, $version, $plugin_name ) {
		$GLOBALS['tribe-common-info'] = [
			'dir'     => $common_path,
			'version' => $version,
			'set_by'  => $plugin_name,
			'set_at'  => microtime( true ),
		];

		self::log_debug( "Global common info set: {$common_path} v{$version} by {$plugin_name}" );
	}

	/**
	 * Gets version from file with caching.
	 *
	 * @since TBD
	 *
	 * @param string $main_file_path Path to Main.php file.
	 *
	 * @return string|false Version string or false on failure.
	 */
	private static function get_version_cached( $main_file_path ) {
		$cache_key = md5( $main_file_path );

		// Return cached version if available.
		if ( isset( self::$version_cache[ $cache_key ] ) ) {
			return self::$version_cache[ $cache_key ];
		}

		// Read and extract version.
		$version = self::extract_version_from_file( $main_file_path );

		// Cache the result (including false for failed extractions).
		self::$version_cache[ $cache_key ] = $version;

		return $version;
	}

	/**
	 * Extracts version constant from Main.php file efficiently.
	 *
	 * @since TBD
	 *
	 * @param string $file_path Path to Main.php file.
	 *
	 * @return string|false Version string or false on failure.
	 */
	private static function extract_version_from_file( $file_path ) {
		$handle = fopen( $file_path, 'r' );
		if ( ! $handle ) {
			return false;
		}

		$lines_read = 0;
		$max_lines  = 100; // VERSION constant should be near the top.

		while ( ( $line = fgets( $handle ) ) && $lines_read < $max_lines ) {
			if ( preg_match( self::$common_version_regex, $line, $matches ) ) {
				fclose( $handle );
				return $matches[1];
			}
			$lines_read++;
		}

		fclose( $handle );
		return false;
	}

	/**
	 * Handles missing common library error consistently.
	 *
	 * @since TBD
	 *
	 * @param string $plugin_name   Name of plugin that failed to load common.
	 * @param string $expected_path Path where common was expected.
	 */
	public static function handle_missing_common( $plugin_name, $expected_path ) {
		$message = sprintf(
			/* translators: %1$s: plugin name, %2$s: expected path */
			esc_html__( '%1$s could not locate the tribe-common library at %2$s. Please ensure the plugin is properly installed.', 'tribe-common' ),
			$plugin_name,
			$expected_path
		);

		// Log the error.
		self::log_error( $message );

		// Show admin notice.
		add_action( 'admin_notices', function() use ( $message ) {
			echo '<div class="notice notice-error"><p>' . esc_html( $message ) . '</p></div>';
		} );

		add_action( 'network_admin_notices', function() use ( $message ) {
			echo '<div class="notice notice-error"><p>' . esc_html( $message ) . '</p></div>';
		} );
	}

	/**
	 * Gets current common library information.
	 *
	 * @since TBD
	 *
	 * @return array|null Common info array or null if not set.
	 */
	public static function get_common_info() {
		return $GLOBALS['tribe-common-info'] ?? null;
	}

	/**
	 * Forces a specific common library to be used.
	 *
	 * This method is intended for emergency fallback scenarios.
	 *
	 * @since TBD
	 *
	 * @param string $common_path Path to common directory.
	 * @param string $version     Version string.
	 * @param string $plugin_name Plugin name forcing this common.
	 */
	public static function force_common( $common_path, $version, $plugin_name ) {
		self::log_debug( "FORCING common: {$common_path} v{$version} by {$plugin_name}" );
		self::set_global_common_info( $common_path, $version, $plugin_name );
	}

	/**
	 * Debug logging helper.
	 *
	 * @since TBD
	 *
	 * @param string $message Debug message.
	 */
	private static function log_debug( $message ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'TRIBE_COMMON_DEBUG' ) && TRIBE_COMMON_DEBUG ) {
			error_log( '[Tribe Common Loader] ' . $message );
		}
	}

	/**
	 * Error logging helper.
	 *
	 * @since TBD
	 *
	 * @param string $message Error message.
	 */
	private static function log_error( $message ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[Tribe Common Loader ERROR] ' . $message );
		}
	}
}
