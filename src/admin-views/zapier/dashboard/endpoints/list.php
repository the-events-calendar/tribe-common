<?php
/**
 * View: Zapier Integration Endpoint Dashboard list.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/dashboard/endpoints/list.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,array> $endpoints An array of the Zapier endpoints.
 * @var Endpoints_Manager   $manager   The Endpoint Manager instance.
 * @var Url                 $url       The URLs handler for the integration.
 */

$this->template( 'zapier/dashboard/endpoints/list-header', [] );


foreach ( $endpoints as $endpoint ) : ?>
	<?php
	$this->template(
		'zapier/dashboard/endpoints/endpoint',
		[
			'endpoint' => $endpoint,
			'manager'  => $manager,
			'url'      => $url,
		] 
	);
	?>
	<?php
endforeach; 
