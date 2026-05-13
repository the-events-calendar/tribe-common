<?php
// Here you can initialize variables that will be available to your tests

// Act like a premium plugin
add_filter( 'lw_harbor/premium_plugin_exists', '__return_true' );
