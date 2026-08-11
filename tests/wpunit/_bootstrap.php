<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Autoload;

tests_add_filter( 'tribe_common_log_to_wpcli', '__return_false' );

/*
 * Prevent `tribe_exit()` from terminating the test run.
 *
 * Any code path that redirects and exits (like the maybe_redirect_to_guided_setup_on_activation()
 * function in src/Events/Admin/Onboarding/Controller.php) could kill the PHP process and
 * silently abandon every remaining test in the suite.
 */
tests_add_filter( 'tribe_exit', static fn() => '__return_true' );

tec_common_tests_fake_transactions_enable();

Autoload::addNamespace( '\\TEC\\Common\\Integrations\\', __DIR__ . '/_data/classes/Integrations' );
