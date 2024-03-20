<?php

class ActionsCest
{

    public function _before(ActivationTester $I)
    {
    }

    /**
     * It should fire common_loaded action on activation
     *
     * @test
     */
    public function should_fire_common_loaded_action_on_activation(ActivationTester $I)
    {
        $common_actions_logger_code = <<< PHP
function _log_tribe_common_loaded_first(){
    if( ! get_option( '_tribe_common_loaded_on_request' ) ){
        add_option( '_tribe_common_loaded_on_request', \$_SERVER['REQUEST_METHOD'] . ' '.  \$_SERVER['REQUEST_URI'] );
    }
}

add_action( 'tribe_common_loaded', '_log_tribe_common_loaded_first' );
PHP;

        $I->haveMuPlugin('common_actions_logger', $common_actions_logger_code);
        $I->loginAsAdmin();
        $I->amOnPluginsPage();
        $I->activatePlugin('the-events-calendar');
		// In case we get redirected to welcome page on activation.
        $I->amOnPluginsPage();
        $I->seePluginActivated('the-events-calendar');

        $I->seeOptionInDatabase('_tribe_common_loaded_on_request', 'POST /wp-admin/plugins.php');
    }
}
