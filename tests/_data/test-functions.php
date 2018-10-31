<?php

function tribe_test_insert_post( array $postarr = [], $append = '' ) {
	$postarr['post_title'] .= $append;

	return wp_insert_post( $postarr );
}
