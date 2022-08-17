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
 * @var array<string|mixed> $keys  An array of API Keys to authenticate with Zapier.
 * @var array<string|mixed> $users An array of WordPress users to create an API Key for.
 */

if ( empty( $keys ) ) {
	$this->template( 'zapier/api/components/fields-new', [
		'local_id' => $api->get_random_hash( 'ci_' ),
		'api_key'  => [
			'name'         => '',
			'page_id'      => '',
			'access_token' => '',
			'expiration'   => '',
		],
		'users'    => $users,
		'url'      => $url,
	] );

	return;
}
?>
<?php foreach ( $keys as $local_id => $api_key ) : ?>
	<?php
	$this->template( 'zapier/components/fields-generated', [
		'local_id' => $local_id,
		'api_key'  => $api_key,
		'users'    => [],
		'url'      => $url,
	] );
	?>
<?php endforeach; ?>
