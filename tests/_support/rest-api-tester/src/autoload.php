<?php
function trap_autoload( $class ) {
	$prefix = 'Tribe__RAP__';
	if ( strpos( $class, $prefix ) === 0 ) {
		$class_path = str_replace( array( $prefix, '__' ), array(
			'',
			DIRECTORY_SEPARATOR,
		), $class );
		/** @noinspection PhpIncludeInspection */
		require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $class_path . '.php';

		return true;
	}

	return false;
}

spl_autoload_register( 'trap_autoload' );
