<?php
/**
 * View: Integration Endpoint Dashboard list.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/dashboard/endpoints/list.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,array> $endpoints An array of the integration's endpoints.
 * @var Endpoints_Manager   $manager   The Endpoint Manager instance.
 * @var Url                 $url       The URLs handler for the integration.
 */

$this->template( 'dashboard/endpoints/list-header', [] );


foreach ( $endpoints as $endpoint ) : ?>
	<?php
	$this->template(
		'dashboard/endpoints/endpoint',
		[
			'endpoint' => $endpoint,
			'manager'  => $manager,
			'url'      => $url,
		] 
	);
	?>
	<?php
endforeach; 
