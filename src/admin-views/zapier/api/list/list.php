<?php
/**
 * View: Zapier Integration API Key list.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/list/list.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api                 $api   An instance of the Zapier API handler.
 * @var Url                 $url   An instance of the URL handler.
 * @var array<string|mixed> $keys  An array of API Keys to authenticate with Zapier.
 * @var array<string|mixed> $users An array of WordPress users to create an API Key for.
 */

$this->template( 'zapier/api/list/list-header', [] );

if ( empty( $keys ) ) {
	$this->template(
		'zapier/api/list/connection-new',
		[
			'api'             => $api,
			'consumer_id'     => $api->get_random_hash( 'ci_' ),
			'connection_data' => [
				'name'         => '',
				'page_id'      => '',
				'access_token' => '',
				'expiration'   => '',
			],
			'users'           => $users,
			'url'             => $url,
		] 
	);

	return;
}
?>
<?php foreach ( $keys as $consumer_id => $connection_data ) : ?>
	<?php
	$this->template(
		'zapier/api/list/connection-saved',
		[
			'api'             => $api,
			'connection_data' => $connection_data,
			'consumer_id'     => $consumer_id,
			'users'           => [],
			'url'             => $url,
		] 
	);
	?>
	<?php
endforeach; 
