<?php
/**
 * View: Power Automate Integration API Key Authorization.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/power-automate/api/authorize-fields.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api                 $api     An instance of the Power Automate API handler.
 * @var Url                 $url     An instance of the URL handler.
 * @var array<string|mixed> $users   An array of WordPress users to create an API Key for.
 * @var string              $message A message to display above the API Key list on loading.
 */

$keys = $api->get_list_of_api_keys( true );
?>
<fieldset id="tec-field-power_automate_token" class="tec-automator-api-fields tribe-field tribe-field-text tribe-size-medium tec-settings-form__element--colspan-2">
	<legend class="tribe-field-label"><?php esc_html_e( 'API Keys', 'tribe-common' ); ?></legend>
	<div class="tec-automator-settings-message__wrap tec-power-automate-api-keys-messages">
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
	<div class="tec-automator-settings-items__wrap tec-power-automate-api-keys-wrap event-automator">
		<?php
		$this->template(
			'power-automate/api/list/list',
			[
				'api'         => $api,
				'url'         => $url,
				'connections' => $keys,
				'users'       => $users,
			]
		);
		?>
	</div>
	<div class="tec-power-automate-add-wrap">
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
