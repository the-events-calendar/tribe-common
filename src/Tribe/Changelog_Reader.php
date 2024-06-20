<?php
/**
 * Class Tribe__Changelog_Reader
 */

/**
 * Class that handles extracting the changelog from the readme.txt file.
 */
class Tribe__Changelog_Reader {

	/**
	 * The number of versions to extract from the changelog.
	 */
	protected int $version_count;

	/**
	 * The path to the readme.txt file.
	 */
	protected string $readme_file;

	/**
	 * Tribe__Changelog_Reader constructor.
	 *
	 * @param int    $version_count The number of versions to extract from the changelog.
	 * @param string $readme_file   The path to the readme.txt file.
	 *
	 * @return void
	 */
	public function __construct( int $version_count = 3, string $readme_file = '' ) {
		$this->version_count = (int) $version_count;
		$this->readme_file   = $readme_file ?: $this->default_readme_file();
	}

	/**
	 * Retrieves the default readme.txt file path.
	 */
	protected function default_readme_file(): string {
		return dirname( Tribe__Main::instance()->plugin_path ) . '/readme.txt';
	}

	/**
	 * Retrieves the changelog from the readme.txt file.
	 *
	 * @return array<string, array<int, string>>
	 */
	public function get_changelog(): array {
		$contents = $this->extract_changelog_section();
		$lines    = explode( "\n", $contents );

		$sections        = [];
		$current_section = '';
		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( substr( $line, 0, 1 ) === '=' ) {
				if ( count( $sections ) >= $this->version_count ) {
					break;
				}
				$current_section              = esc_html( trim( $line, '= ' ) );
				$sections[ $current_section ] = [];
			} elseif ( strlen( $line ) > 0 ) {
				$message                        = trim( $line, '* ' );
				$sections[ $current_section ][] = esc_html( $message );
			}
		}
		return $sections;
	}

	/**
	 * Extracts the changelog section from the readme.txt file.
	 */
	protected function extract_changelog_section(): string {
		$contents = $this->get_readme_file_contents();
		if ( $contents === '' ) {
			return '';
		}

		$start = strpos( $contents, '== Changelog ==' );
		if ( $start === false ) {
			return '';
		}

		$start += strlen( '== Changelog ==' );
		$end    = strpos( $contents, '==', $start );
		if ( $end === false ) {
			$end = strlen( $contents );
		}

		return trim( substr( $contents, $start, $end - $start ) );
	}

	/**
	 * Retrieves the contents of the readme.txt.
	 */
	protected function get_readme_file_contents(): string {
		return file_get_contents( $this->readme_file ) ?: '';
	}
}
