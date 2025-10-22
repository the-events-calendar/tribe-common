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
	 * @param string         $name_space The namespace to register.
	 * @param string|Closure $path       The path to the SVG files.
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException If the $path is not a string or a Closure.
	 */
	public function register_namespace( string $name_space, $path ): void {
		if ( ! is_string( $path ) && ! $path instanceof Closure ) {
			throw new InvalidArgumentException( 'The $path must be a string or a Closure.' );
		}

		$this->namespaces[ $name_space ] = $path;
	}

	/**
	 * Get the SVG code for a given icon.
	 *
	 * @since TBD
	 *
	 * @param string $namespaced_path The namespaced path to the SVG file.
	 *
	 * @return string The SVG code for the given icon.
	 */
	public function get_svg( string $namespaced_path ): string {
		uksort(
			$this->namespaces,
			// We want to sort the namespaces by longest to shortest.
			function ( $a, $b ) {
				return strlen( $b ) - strlen( $a );
			}
		);

		foreach ( $this->namespaces as $name_space => $path ) {
			if ( ! str_starts_with( $namespaced_path, $name_space ) ) {
				continue;
			}

			$path_without_namespace = ltrim( substr( $namespaced_path, strlen( $name_space ) ), '/' );

			$path = trailingslashit( $path instanceof Closure ? (string) $path( $path_without_namespace ) : $path );

			$full_path = $path . untrailingslashit( $path_without_namespace ) . '.svg';

			if ( file_exists( $full_path ) ) {
				$svg = file_get_contents( $full_path ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown

				return $svg ? $svg : '';
			}

			break;
		}

		return '';
	}
}
