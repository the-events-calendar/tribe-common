<?php
/**
 * View: Zapier Integration Connections.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/connections.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api                 $api     An instance of the Zapier API handler.
 * @var Url                 $url     An instance of the URL handler.
 * @var array<string|mixed> $users   An array of WordPress users to create an API Key for.
 * @var string              $message A message to display above the API Key list on loading.
 */

$keys = $api->get_list_of_api_keys( true );
?>
<fieldset id="tec-field-zapier_token" class="tec-automator-api-fields tribe-field tribe-field-text tribe-size-medium">
	<legend class="tribe-field-label"><?php esc_html_e( 'API Keys', 'tribe-common' ); ?></legend>
	<div class="tec-automator-settings-message__wrap tec-zapier-api-keys-messages">
		<?php
		$this->template(
			'components/message',
			[
				'message' => $message,
				'type'    => 'standard',
			]
		);
		?>
	</div>
	<div class="tec-automator-settings-items__wrap tec-zapier-api-keys-wrap event-automator">
		<?php
		$this->template(
			'zapier/api/list/list',
			[
				'api'   => $api,
				'url'   => $url,
				'keys'  => $keys,
				'users' => $users,
			]
		);
		?>
	</div>
	<div class="tec-zapier-add-wrap">
		<?php
		$this->template(
			'components/integration/add-connection',
			[
				'api' => $api,
				'url' => $url,
			]
		);
		?>
	</div>
</fieldset>
