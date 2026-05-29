<?php
/**
 * Preserve the unprefixed PSR packages dev/test code needs, across a Strauss run.
 *
 * Strauss prefixes a set of PSR packages into vendor-prefixed/ (so production code
 * such as stellarwp/harbor can use the TEC\Common\Psr\* copies) and, with
 * delete_vendor_files enabled, deletes the original vendor/ copies. That is correct
 * for production, but the test stack (guzzle, pulled in via codeception/wp-browser)
 * needs the ORIGINAL, unprefixed Psr\* / Nyholm\* classes.
 *
 * The previous approach ran `composer reinstall` to restore those originals, but
 * Strauss 0.26.x de-registers the packages from composer's view (composer no longer
 * considers them installed), so the reinstall matches nothing and the originals stay
 * deleted — autoload then points at missing files and dev/test fatals with e.g.
 * "Interface 'Psr\Http\Message\UriInterface' not found".
 *
 * Instead we copy the originals aside before Strauss runs and copy them back after,
 * then restore their original autoload metadata in installed.json. Both modes are
 * only invoked when COMPOSER_DEV_MODE=1, so production builds keep the lean,
 * fully-prefixed vendor tree.
 *
 * Usage:
 *   php bin/psr-http-dev.php backup    # before Strauss
 *   php bin/psr-http-dev.php restore   # after Strauss
 */

$mode   = $argv[1] ?? '';
$vendor = dirname( __DIR__ ) . '/vendor';
$backup = $vendor . '/.psr-http-dev-backup';

/**
 * Packages that must remain loadable under their original namespaces for dev/test,
 * mapped to the autoload metadata Strauss overwrites (and that we restore).
 */
$packages = [
	'psr/http-message' => [ 'psr-4' => [ 'Psr\\Http\\Message\\' => 'src/' ] ],
	'psr/http-factory' => [ 'psr-4' => [ 'Psr\\Http\\Message\\' => 'src/' ] ],
	'psr/http-client'  => [ 'psr-4' => [ 'Psr\\Http\\Client\\' => 'src/' ] ],
	'psr/log'          => [ 'psr-4' => [ 'Psr\\Log\\' => 'Psr/Log/' ] ],
	'psr/container'    => [ 'psr-4' => [ 'Psr\\Container\\' => 'src/' ] ],
	'nyholm/psr7'      => [ 'psr-4' => [ 'Nyholm\\Psr7\\' => 'src/' ] ],
];

/**
 * Recursively copy a directory tree.
 */
function psr_http_dev_rcopy( string $src, string $dst ): void {
	if ( ! is_dir( $src ) ) {
		return;
	}

	@mkdir( $dst, 0755, true );

	$items = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $src, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::SELF_FIRST
	);

	foreach ( $items as $item ) {
		$target = $dst . '/' . $items->getSubPathName();

		if ( $item->isDir() ) {
			@mkdir( $target, 0755, true );
			continue;
		}

		copy( $item->getPathname(), $target );
	}
}

/**
 * Recursively delete a directory tree.
 */
function psr_http_dev_rrmdir( string $dir ): void {
	if ( ! is_dir( $dir ) ) {
		return;
	}

	$items = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST
	);

	foreach ( $items as $item ) {
		$item->isDir() ? @rmdir( $item->getPathname() ) : @unlink( $item->getPathname() );
	}

	@rmdir( $dir );
}

if ( 'backup' === $mode ) {
	psr_http_dev_rrmdir( $backup );

	$count = 0;
	foreach ( array_keys( $packages ) as $name ) {
		if ( is_dir( "$vendor/$name" ) ) {
			psr_http_dev_rcopy( "$vendor/$name", "$backup/$name" );
			$count++;
		}
	}

	echo "psr-http-dev: backed up {$count} PSR packages before Strauss.\n";
	exit( 0 );
}

if ( 'restore' === $mode ) {
	// 1. Restore the original package files Strauss deleted.
	$restored = 0;
	foreach ( array_keys( $packages ) as $name ) {
		if ( is_dir( "$backup/$name" ) && ! is_dir( "$vendor/$name" ) ) {
			psr_http_dev_rcopy( "$backup/$name", "$vendor/$name" );
			$restored++;
		}
	}

	psr_http_dev_rrmdir( $backup );

	// 2. Restore the original autoload metadata in installed.json. Strauss rewrites
	//    these entries to the TEC\Common\* prefix, which would mis-map the restored
	//    originals; dump-autoload (run next by the composer script) then regenerates
	//    a correct autoloader from this.
	$installed_json = "$vendor/composer/installed.json";
	if ( is_file( $installed_json ) ) {
		$data = json_decode( file_get_contents( $installed_json ), true );

		if ( is_array( $data ) && ! empty( $data['packages'] ) ) {
			foreach ( $data['packages'] as &$pkg ) {
				if ( ! empty( $pkg['name'] ) && isset( $packages[ $pkg['name'] ] ) ) {
					$pkg['autoload'] = $packages[ $pkg['name'] ];
				}
			}
			unset( $pkg );

			file_put_contents(
				$installed_json,
				json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . "\n"
			);
		}
	}

	echo "psr-http-dev: restored {$restored} PSR packages after Strauss.\n";
	exit( 0 );
}

fwrite( STDERR, "Usage: php bin/psr-http-dev.php backup|restore\n" );
exit( 1 );
