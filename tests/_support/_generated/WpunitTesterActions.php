<?php  //[STAMP] 558a51694e6460404617b051d1ea8f3b
namespace _generated;

// This class was automatically generated by build task
// You should not change it manually as it will be overwritten on next build
// @codingStandardsIgnoreFile

use Helper\Wpunit;
use Codeception\Module\WPLoader;

trait WpunitTesterActions
{
    /**
     * @return \Codeception\Scenario
     */
    abstract protected function getScenario();

    
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \Codeception\Module\WPLoader::ensureDbModuleCompat()
     */
    public function ensureDbModuleCompat() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('ensureDbModuleCompat', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Calls a list of user-defined actions needed in tests.
     * @see \Codeception\Module\WPLoader::bootstrapActions()
     */
    public function bootstrapActions() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('bootstrapActions', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \Codeception\Module\WPLoader::activatePlugins()
     */
    public function activatePlugins() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('activatePlugins', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Loads the plugins required by the test.
     * @see \Codeception\Module\WPLoader::loadPlugins()
     */
    public function loadPlugins() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('loadPlugins', func_get_args()));
    }
}
