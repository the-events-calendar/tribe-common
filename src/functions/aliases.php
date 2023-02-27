<?php
/**
 * Registers the library aliases redirecting calls to the `tad_DI52_`, non-namespaced, class format to our namespaced
 * classes.
 */

$aliases = [
    ['TEC\Common\lucatume\DI52\Container', 'tad_DI52_Container'],
    ['TEC\Common\lucatume\DI52\ServiceProvider', 'tad_DI52_ServiceProvider']
];

foreach ( $aliases as list( $class, $alias ) ) {
	$class_ex = class_exists( $alias );
    if ( ! $class_ex ) {
        class_alias( $class, $alias );
    }
	$foo = '';
}
