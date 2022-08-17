<?php
/**
 * View: Zapier Integration API Key Fields with key pair.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/fields-generated.php
 *
 * See more documentation about our views templating system.
 *
 * @since   TBD
 *
 * @version TBD
 *
 * @link    http://evnt.is/1aiy
 *
 * @var int                 $local_id The unique id used to save The API Key data.
 * @var array<string|mixed> $api_key  The API Key data.
 * @var array<string|mixed> $users    An array of WordPress users to create an API Key for.
 * @var URL                 $url      An instance of the URL handler.
 */

?>

<li
	class="tec-settings-integrations-details__container tec-settings-zapier-api-key-details__container"
	data-local-id="<?php echo esc_attr( $local_id ); ?>"
>
	<div class="tec-settings-integrations-details__row">

		<?php
		$this->template( 'zapier/api/components/read-only', [
			'classes_wrap'  => [ 'tec-settings-zapier-details-api-key__name-wrap' ],
			'label'         => _x( 'Description', 'Label for the name of the API Key for Zapier.', 'tribe-common' ),
			'screen_reader' => _x( 'The name for the Zapier API Key.', 'The screen reader text of the label for the Zapier API Key name.', 'tribe-common' ),
			'id'            => "tec_common_zapier_name_" . $local_id,
			'name'          => "tec_common_zapier[]['name']",
			'value'         => $api_key['name'],
		] );
		?>

		<?php
		$user = get_user_by( 'id', $api_key['user_id'] );
		$this->template( 'zapier/api/components/read-only', [
			'classes_wrap'  => [ 'tec-settings-zapier-details-api-key__user-wrap' ],
			'label'         => _x( 'User', 'Label for the user of the API Key for Zapier.', 'tribe-common' ),
			'screen_reader' => _x( 'The user for the Zapier API Key.', 'The screen reader text of the label for the Zapier API Key user.', 'tribe-common' ),
			'id'            => "tec_common_zapier_user_" . $local_id,
			'name'          => "tec_common_zapier[]['user']",
			'value'         => $user->user_login,
		] );
		?>

		<?php
		$this->template( 'zapier/api/components/read-only', [
			'classes_wrap'  => [ 'tec-settings-zapier-details-api-key__permissions-wrap' ],
			'label'         => _x( 'Permissions', 'Label for the permissions of the API Key for Zapier.', 'tribe-common' ),
			'screen_reader' => _x( 'The permissions for the Zapier API Key.', 'The screen reader text of the label for the Zapier API Key permissions.', 'tribe-common' ),
			'id'            => "tec_common_zapier_permissions_" . $local_id,
			'name'          => "tec_common_zapier[]['permissions']",
			'value'         => $api_key['permissions'],
		] );
		?>

		<?php if ( ! empty( $api_key['name'] ) && empty( $api_key['consumer_secret'] ) ) {
			$this->template( 'zapier/api/components/revoke-button', [
				'local_id' => $local_id,
				'api_key'  => $api_key,
				'url'      => $url,
			] );
		} ?>
	</div>
	<?php if ( ! empty( $api_key['name'] ) && ! empty( $api_key['consumer_secret'] ) ) {
		$this->template( 'zapier/api/components/key-pair', [
			'local_id' => $local_id,
			'api_key'  => $api_key,
			'url'      => $url,
		] );
	} ?>
</li>