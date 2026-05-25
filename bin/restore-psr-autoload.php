<?php
/**
 * Reverts strauss's rewrite of autoload metadata in vendor/composer/installed.json
 * for PSR packages that must remain loadable under their original namespaces
 * (e.g. for guzzlehttp/* installed as dev deps via wp-browser/codeception).
 *
 * Strauss prefixes these packages into vendor-prefixed/ AND rewrites their entries
 * in installed.json to register only the TEC\Common\* namespace pointing at the
 * original vendor/ path. Because the prefixed files live in vendor-prefixed/, that
 * entry is incorrect — and when composer reinstall restores the original files to
 * vendor/, the incorrect mapping causes class re-declaration fatals.
 */

$installed_json = __DIR__ . '/../vendor/composer/installed.json';

if ( ! file_exists( $installed_json ) ) {
	fwrite( STDERR, "installed.json not found at {$installed_json}\n" );
	exit( 0 );
}

$originals = [
	'psr/http-message' => [ 'psr-4' => [ 'Psr\\Http\\Message\\' => 'src/' ] ],
	'psr/http-factory' => [ 'psr-4' => [ 'Psr\\Http\\Message\\' => 'src/' ] ],
	'psr/http-client'  => [ 'psr-4' => [ 'Psr\\Http\\Client\\' => 'src/' ] ],
	'psr/log'          => [ 'psr-4' => [ 'Psr\\Log\\' => 'Psr/Log/' ] ],
	'psr/container'    => [ 'psr-4' => [ 'Psr\\Container\\' => 'src/' ] ],
	'nyholm/psr7'      => [ 'psr-4' => [ 'Nyholm\\Psr7\\' => 'src/' ] ],
];

$data = json_decode( file_get_contents( $installed_json ), true );

if ( ! is_array( $data ) || empty( $data['packages'] ) ) {
	exit( 0 );
}

$patched = 0;

foreach ( $data['packages'] as &$pkg ) {
	if ( empty( $pkg['name'] ) || ! isset( $originals[ $pkg['name'] ] ) ) {
		continue;
	}
	$pkg['autoload'] = $originals[ $pkg['name'] ];
	$patched++;
}
unset( $pkg );

file_put_contents(
	$installed_json,
	json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . "\n"
);

echo "Restored autoload metadata for {$patched} PSR packages in installed.json\n";
