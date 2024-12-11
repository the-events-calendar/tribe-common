<?php

use TEC\Tickets\Commerce\Provider as Commerce_Provider;

putenv( 'TEC_TICKETS_COMMERCE=1' );
putenv( 'TEC_DISABLE_LOGGING=1' );

tribe_register_provider( Commerce_Provider::class );
