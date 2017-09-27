<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload_52.php';
require_once dirname(__FILE__) . '/data/test-classes.php';
if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
    require_once dirname(__FILE__) . '/data/namespaced-test-classes.php';
}
require_once dirname(__FILE__) . '/data/test-providers.php';
