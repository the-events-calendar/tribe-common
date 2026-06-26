<?php
/**
 * Guards the PHP-compatibility change made to fputcsv.
 *
 * The fputcsv() call was made explicit about its delimiter / enclosure / escape
 * arguments to stay forward-compatible with PHP 8.4+ (where relying on the
 * implicit default escape character is deprecated):
 *
 *   from: fputcsv( $handle, $fields );
 *   to:   fputcsv( $handle, $fields, ',', '"', '\\' );
 *
 * `file-logger-legacy.csv` is a committed snapshot produced once, on PHP 7.4,
 * by the ORIGINAL (`from`) call — see generate-legacy-snapshot.php. This test
 * re-produces the snapshot using the NEW (`to`) explicit-argument call and
 * asserts the two are byte-for-byte identical.
 */
class Tribe__Log__File_Logger_Csv_Test extends \Codeception\Test\Unit {

	protected function snapshots_dir(): string {
		return __DIR__ . '/../../../_data/csv-snapshots';
	}

	protected function produce_snapshot_with_new_args(): string {
		$rows = require $this->snapshots_dir() . '/file-logger-rows.php';

		$handle = fopen( 'php://temp', 'r+b' );

		foreach ( $rows as $row ) {
			// The `to` signature: delimiter, enclosure and escape passed explicitly.
			fputcsv( $handle, $row, ',', '"', '\\' );
		}

		rewind( $handle );
		$csv = stream_get_contents( $handle );
		fclose( $handle );

		return $csv;
	}

	public function test_new_args_match_legacy_snapshot(): void {
		$legacy = file_get_contents( $this->snapshots_dir() . '/file-logger-legacy.csv' );
		$actual = $this->produce_snapshot_with_new_args();

		$this->assertSame(
			$legacy,
			$actual,
			'Explicit fputcsv() delimiter/enclosure/escape arguments must produce the exact same CSV as the legacy implicit defaults.'
		);
	}
}
