<?php

namespace TEC\Common\Tests\Service_Providers;

use TEC\Common\Contracts\Service_Provider;

class Custom_Registration_Action_Provider extends Service_Provider {
	public static string $registration_action = 'custom_action';

	public function register() {
	}
}