<?php
/**
 * Generates the canonical "legacy" CSV snapshot using the ORIGINAL fputcsv()
 * call signature:
 *
 *     fputcsv( $handle, $fields );
 *
 * i.e. relying on fputcsv()'s implicit default delimiter/enclosure/escape.
 *
 * Run this ONCE on PHP 7.4 (where the implicit defaults are exactly
 * ',', '"' and '\\') to (re)produce the committed snapshot that the test
 * compares the new, explicit-argument call against:
 *
 *     php tests/_data/csv-snapshots/generate-legacy-snapshot.php
 *
 * The committed file (file-logger-legacy.csv) is the baseline; the test does
 * NOT run this script. It exists only to document and reproduce the baseline.
 */

$rows = require __DIR__ . '/file-logger-rows.php';

$out = __DIR__ . '/file-logger-legacy.csv';

$handle = fopen( $out, 'wb' );

foreach ( $rows as $row ) {
	// Original signature: no explicit delimiter / enclosure / escape.
	fputcsv( $handle, $row );
}

fclose( $handle );

echo "Wrote legacy snapshot to {$out}\n";
