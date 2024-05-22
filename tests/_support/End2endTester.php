<?php

use Tribe\Events\Test\Traits\Container_Aware;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class End2endTester extends \Codeception\Actor
{
    use _generated\End2endTesterActions;
	use Container_Aware;

   /**
    * Define custom actions here
    */
}
