<?php
/**
 * View: Power Automate Integration API Key Fields with key pair.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/power-automate/api/list/fields-generated.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api                 $api             An instance of the Power Automate API handler.
 * @var array<string|mixed> $connection_data The connection data.
 * @var int                 $consumer_id     The unique id used to save The API Key data.
 * @var array<string|mixed> $users           An array of WordPress users to create an API Key for.
 * @var URL                 $url             An instance of the URL handler.
 */

?>

<div
	class="tec-automator-grid tec-automator-grid-row tec-automator-settings-details__container tec-settings-power-automate-api-key-details__container"
	data-consumer-id="<?php echo esc_attr( $consumer_id ); ?>"
>
	<?php
	$this->template(
		'components/read-only',
		[
			'classes_wrap'  => [ 'tec-automator-grid-item', 'tec-settings-power-automate-details-api-key__name-wrap' ],
			'label'         => _x( 'Description', 'Label for the name of the API Key for Power Automate.', 'tribe-common' ),
			'screen_reader' => _x( 'The name for the Power Automate API Key.', 'The screen reader text of the label for the Power Automate API Key name.', 'tribe-common' ),
			'id'            => 'tec_automator_power_automate_name_' . $consumer_id,
			'name'          => "tec_automator_power_automate[]['name']",
			'value'         => $connection_data['name'],
		] 
	);
	?>

	<?php
	$user = get_user_by( 'id', $connection_data['user_id'] );
	$this->template(
		'components/read-only',
		[
			'classes_wrap'  => [ 'tec-automator-grid-item', 'tec-settings-power-automate-details-api-key__user-wrap' ],
			'label'         => _x( 'User', 'Label for the user of the API Key for Power Automate.', 'tribe-common' ),
			'screen_reader' => _x( 'The user for the Power Automate API Key.', 'The screen reader text of the label for the Power Automate API Key user.', 'tribe-common' ),
			'id'            => 'tec_automator_power_automate_user_' . $consumer_id,
			'name'          => "tec_automator_power_automate[]['user']",
			'value'         => $user->user_login,
		] 
	);
	?>

	<?php
	$this->template(
		'components/read-only',
		[
			'classes_wrap'  => [ 'tec-automator-grid-item', 'tec-settings-power-automate-details-api-key__permissions-wrap' ],
			'label'         => _x( 'Permissions', 'Label for the permissions of the API Key for Power Automate.', 'tribe-common' ),
			'screen_reader' => _x( 'The permissions for the Power Automate API Key.', 'The screen reader text of the label for the Power Automate API Key permissions.', 'tribe-common' ),
			'id'            => 'tec_automator_power_automate_permissions_' . $consumer_id,
			'name'          => "tec_automator_power_automate[]['permissions']",
			'value'         => $connection_data['permissions'],
		] 
	);
	?>

	<?php
	$this->template(
		'components/read-only',
		[
			'classes_wrap'  => [ 'tec-automator-grid-item', 'tec-settings-power-automate-details-api-key__last-access-wrap' ],
			'label'         => _x( 'Last Access', 'Label for the last access of the API Key for Power Automate.', 'tribe-common' ),
			'screen_reader' => _x( 'The last access for the Power Automate API Key.', 'The screen reader text of the label for the Power Automate API Key last access.', 'tribe-common' ),
			'id'            => 'tec_automator_power_automate_last_access_' . $consumer_id,
			'name'          => "tec_automator_power_automate[]['last_access']",
			'value'         => str_replace( '|', ' - ', $connection_data['last_access'] ),
		] 
	);
	?>

	<?php
	if ( ! empty( $connection_data['name'] ) && ! empty( $connection_data['consumer_secret'] ) ) {
		$this->template(
			'components/integration/delete-button',
			[
				'consumer_id' => $consumer_id,
				'api_key'     => $connection_data,
				'url'         => $url,
			] 
		);
	}
	?>

	<?php
	if ( ! empty( $connection_data['name'] ) && ! empty( $connection_data['consumer_id'] ) && strpos( $connection_data['consumer_id'], 'ci_' ) === 0 ) {
		$this->template(
			'power-automate/api/components/access',
			[
				'consumer_id' => $consumer_id,
				'api_key'     => $connection_data,
				'url'         => $url,
			] 
		);
	}
	?>
</div>
