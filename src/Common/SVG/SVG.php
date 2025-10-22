<?php
/**
 * SVG class
 *
 * @since TBD
 *
 * @package TEC\Common\SVG
 */

declare( strict_types=1 );

namespace TEC\Common\SVG;

use InvalidArgumentException;
use Closure;

/**
 * SVG class
 *
 * @since TBD
 *
 * @package TEC\Common\SVG
 */
class SVG {
	/**
	 * The namespaces and their paths.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private array $namespaces = [];

	/**
	 * Register a namespace for a given path.
	 *
	 * @since TBD
	 *
	 * @param string         $namespace The namespace to register.
	 * @param string|Closure $path      The path to the SVG files.
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException If the $path is not a string or a Closure.
	 */
	public function register_namespace( string $namespace, $path ): void {
		if ( ! is_string( $path ) && ! $path instanceof Closure ) {
			throw new InvalidArgumentException( 'The $path must be a string or a Closure.' );
		}

		$this->namespaces[ $namespace ] = $path;
	}

	/**
	 * Get the SVG code for a given icon.
	 *
	 * @since TBD
	 *
	 * @param string $icon The icon to get the SVG code for.
	 *
	 * @return string The SVG code for the given icon.
	 */
	public function get_svg( string $namespaced_path ): string {
		foreach ( $this->namespaces as $namespace => $path ) {
			if ( ! str_starts_with( $namespaced_path, $namespace ) ) {
				continue;
			}

			$path_without_namespace = ltrim( substr( $namespaced_path, strlen( $namespace ) ), '/' );

			$path = trailingslashit( $path instanceof Closure ? $path( $path_without_namespace ) : $path );

			$full_path = $path . trailingslashit( $path_without_namespace );

			if ( file_exists( $full_path ) ) {
				return file_get_contents( $full_path );
			}

			break;
		}

		return '';
	}
}
