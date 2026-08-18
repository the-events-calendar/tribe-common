<?php
require_once __DIR__ . '/../../tribe-autoload.php';

$functions = __DIR__ . '/../../src/functions';
foreach ( glob( $functions . '/*.php', GLOB_NOSORT ) as $file ) {
	require_once $file;
}

/*
 * slic mounts an `auto_prepend_file` that registers a Closure in the pre-boot
 * `$GLOBALS['wp_filter']` array to disable the object cache drop-in. WordPress never
 * loads in this suite, so the entry is inert here, but PHPUnit snapshots `$GLOBALS`
 * before every test and a Closure cannot be serialized, which errors the whole suite.
 */
foreach ( $GLOBALS['wp_filter'] ?? [] as $hook_name => $priorities ) {
	foreach ( $priorities as $priority => $callbacks ) {
		foreach ( $callbacks as $callback_id => $callback ) {
			if ( ! is_array( $callback ) || ! ( ( $callback['function'] ?? null ) instanceof Closure ) ) {
				continue;
			}

			unset( $GLOBALS['wp_filter'][ $hook_name ][ $priority ][ $callback_id ] );
		}
	}
}
