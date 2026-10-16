<?php

namespace TEC\Common\Libraries;

use TEC\Common\Tests\Provider\Controller_Test_Case;

class Shepherd_Test extends Controller_Test_Case {
	protected $controller_class = Shepherd::class;

	public function test_front_end_request_does_not_load_upgrade_php(): void {
		global $table_prefix;

		/*
		 * On multisite, upgrade.php loads wp-admin/includes/ms.php, and a later plain require of ms.php
		 * fatals on check_upload_size().
		 */
		$env     = [
			'WORDPRESS_DB_HOST'      => DB_HOST,
			'WORDPRESS_DB_NAME'      => DB_NAME,
			'WORDPRESS_DB_USER'      => DB_USER,
			'WORDPRESS_DB_PASSWORD'  => DB_PASSWORD,
			'WORDPRESS_TABLE_PREFIX' => $table_prefix,
		];
		$command = '';
		foreach ( $env as $name => $value ) {
			$command .= $name . '=' . escapeshellarg( $value ) . ' ';
		}
		$command .= implode(
			' ',
			array_map( 'escapeshellarg', [ PHP_BINARY, codecept_data_dir( 'front-end-request-loads-upgrade.php' ), ABSPATH ] )
		);

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec -- The check needs a process the bootstrap has not touched.
		exec( $command . ' 2>&1', $output, $exit_code );
		$output = implode( "\n", $output );

		$this->assertSame( 0, $exit_code, $output );

		$result = json_decode( substr( $output, strrpos( $output, '{' ) ), true );

		$this->assertTrue( $result['shepherd_registered'] ?? null, $output );
		$this->assertFalse( $result['upgrade_loaded'], $output );
	}
}
