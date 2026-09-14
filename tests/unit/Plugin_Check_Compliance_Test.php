<?php
/**
 * Static guards for the WordPress.org Plugin Check findings fixed in SOFT-4417.
 *
 * These read the shipped source files rather than executing them, so they run in the
 * `unit` suite without WordPress and catch a regression before the release scan does.
 */

class Plugin_Check_Compliance_Test extends \Codeception\Test\Unit {
	/**
	 * The patterns Plugin Check's direct-access check accepts.
	 *
	 * @see WordPress\Plugin_Check\Checker\Checks\Plugin_Repo\Direct_File_Access_Check
	 *
	 * @var string[]
	 */
	private $guard_patterns = [
		"/defined\s*\(\s*['\"](?:ABSPATH|WPINC)['\"]\s*\)\s*(?:\|\||or)\s*(?:exit|die)/i",
		"/if\s*\(\s*!\s*defined\s*\(\s*['\"](?:ABSPATH|WPINC)['\"]\s*\)\s*\)\s*(?:\{|exit|die)/i",
	];

	/**
	 * @return string The common root directory, with a trailing slash.
	 */
	private function root(): string {
		return dirname( __DIR__, 2 ) . '/';
	}

	/**
	 * @param string $dir Directory to walk, relative to the common root.
	 *
	 * @return string[] Absolute paths of the PHP files under the directory.
	 */
	private function php_files( string $dir ): array {
		$files    = [];
		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $this->root() . $dir ) );

		foreach ( $iterator as $file ) {
			if ( $file->isFile() && 'php' === $file->getExtension() ) {
				$files[] = $file->getPathname();
			}
		}

		sort( $files );

		return $files;
	}

	/**
	 * @test
	 */
	public function every_template_and_procedural_file_refuses_direct_access(): void {
		// Plugin Check treats files holding only class definitions as safe; these directories hold the procedural files it flags.
		$files = [ $this->root() . 'tribe-autoload.php' ];
		foreach ( [ 'src/views', 'src/admin-views', 'src/functions', 'src/deprecated' ] as $dir ) {
			$files = array_merge( $files, $this->php_files( $dir ) );
		}

		$missing = [];

		foreach ( $files as $file ) {
			$source  = file_get_contents( $file ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown -- Reads a local source file.
			$guarded = false;

			foreach ( $this->guard_patterns as $pattern ) {
				if ( preg_match( $pattern, $source ) ) {
					$guarded = true;
					break;
				}
			}

			if ( ! $guarded ) {
				$missing[] = str_replace( $this->root(), '', $file );
			}
		}

		$this->assertSame( [], $missing, 'PHP files without an ABSPATH guard: ' . implode( ', ', $missing ) );
	}

	/**
	 * @test
	 */
	public function review_links_are_not_pre_filtered(): void {
		$offenders = [];

		foreach ( $this->php_files( 'src' ) as $file ) {
			if ( false !== strpos( file_get_contents( $file ), 'reviews/?filter=5' ) ) {
				$offenders[] = str_replace( $this->root(), '', $file );
			}
		}

		$this->assertSame( [], $offenders, 'Files linking to pre-filtered 5-star reviews: ' . implode( ', ', $offenders ) );
	}

}
