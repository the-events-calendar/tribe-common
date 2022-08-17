<?php
/**
 * View: Zapier Integration API Key Authorization.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/authorize-fields.php
 *
 * See more documentation about our views templating system.
 *
 * @since   TBD
 *
 * @version TBD
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api                 $api     An instance of the Zapier API handler.
 * @var Url                 $url     An instance of the URL handler.
 * @var array<string|mixed> $users   An array of WordPress users to create an API Key for.
 * @var string              $message A message to display above the API Key list on loading.
 */

$keys = $api->get_list_of_keys();
?>
<fieldset id="tec-field-zapier_token" class="tec-meetings-api-fields tribe-field tribe-field-text tribe-size-medium">
	<legend class="tribe-field-label"><?php esc_html_e( 'API Keys', 'tribe-common' ); ?></legend>
	<div class="tec-settings-integrations-message__wrap tec-zapier-api-keys-messages">
		<?php
		$this->template( 'components/message', [
			'message' => $message,
			'type'    => 'standard',
		] );
		?>
	</div>
	<div class="tec-settings-integrations-items__wrap tec-zapier-api-keys-wrap <?php echo is_array( $keys ) && count( $keys ) > 4 ? 'long-list' : ''; ?> tribe-common">
		<?php
		$this->template( 'zapier/api/keys/list', [
			'api'   => $api,
			'url'   => $url,
			'keys'  => $keys,
			'users' => $users,
		] );
		?>
	</div>
	<div class="tec-zapier-add-wrap">
		<?php
		$this->template( 'zapier/api/authorize-fields/add-link', [
			'api' => $api,
			'url' => $url,
		] );
		?>
	</div>
</fieldset>
