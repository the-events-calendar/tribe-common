<?php

namespace Tribe\Common\Tests;


class Test_Class {
	public function insert_post( array $postarr = [] ) {
		return wp_insert_post( $postarr );
	}
}