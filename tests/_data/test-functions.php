<?php

function tribe_test_insert_post( array $postarr = [], $append = '' ) {
	$postarr['post_title'] .= $append;

	return wp_insert_post( $postarr );
}

function tribe_resolved( $one, $two ) {
	do_action( 'test_action_resolved', $one, $two );
}

function tribe_rejected( $one, $two ) {
	do_action( 'test_action_rejected', $one, $two );
}

function tribe_throwing() {
	throw new RuntimeException( 'fail' );
}
