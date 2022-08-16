<?php
/**
 * View: Zapier Integration API Key list.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/authorize-fields/add-link
 *
 * See more documentation about our views templating system.
 *
 * @since   TBD
 *
 * @version TBD
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api                 $api   An instance of the Zapier API handler.
 * @var Url                 $url   An instance of the URL handler.
 * @var array<string|mixed> $keys  An array of api keys to authenticate with Zapier.
 * @var array<string|mixed> $users An array of WordPress users to create an api key for.
 */

if ( empty( $keys ) ) {
	$this->template( 'zapier/api/components/fields', [
		'local_id' => $api->get_unique_id(),
		'api_key'     => [
			'name'         => '',
			'page_id'      => '',
			'access_token' => '',
			'expiration'   => '',
		],
		'url'      => $url,
		'users'    => $users,
	] );

	return;
}
?>
<?php foreach ( $keys as $local_id => $api_key ) : ?>
	<?php
	$this->template( 'zapier/components/fields', [
		'local_id' => $local_id,
		'api_key'     => $api_key,
		'url'      => $url,
		'users'    => $users,
	] );
	?>
<?php endforeach; ?>
