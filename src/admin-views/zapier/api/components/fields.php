<?php
/**
 * View: Zapier Integration API Key Fields.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/fields.php
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
 * @var array<string|mixed> $users    An array of WordPress users to create an api key for.
 * @var URL                 $url      An instance of the URL handler.
 */

?>

<li
	class="tec-settings-integrations-details__container tec-settings-zapier-api-key-details__container"
	data-local-id="<?php echo esc_attr( $local_id ); ?>"
>
	<div class="tec-settings-integrations-details__row">
		<?php
		$this->template( 'zapier/api/components/text', [
			'classes_input' => [ 'tec-settings-integrations-details__input', 'tec-settings-integrations-details__name-input', 'tec-settings-zapier-details__api-key-name-input' ],
			'classes_wrap'  => [ 'tec-settings-zapier-details__api-key-name' ],
			'label'         => _x( 'Description', 'Label for the name of the api key for Zapier.', 'tribe-common' ),
			'id'            => "tec_common_zapier_name_" . $local_id,
			'name'          => "tec_common_zapier[]['name']",
			'placeholder'   => _x( 'Enter an api key description', 'The placeholder for the Zapier api key name.', 'tribe-common' ),
			'screen_reader' => _x( 'Enter an api key description.', 'The screen reader text of the label for the Zapier api key name.', 'tribe-common' ),
			'value'         => $api_key['name'],
		] );
		?>

		<?php $this->template( 'components/dropdown', $users ); ?>

		<?php
		$this->template( 'zapier/api/components/read-only', [
			'classes_wrap'  => [ 'tec-settings-zapier-details__api-key-name' ],
			'label'         => _x( 'Permissions', 'Label for the permissions of the api key for Zapier.', 'tribe-common' ),
			'screen_reader' => _x( 'The permissions for the Zapier api key.', 'The screen reader text of the label for the Zapier api key permissions.', 'tribe-common' ),
			'id'            => "tec_common_zapier_permissions_" . $local_id,
			'name'          => "tec_common_zapier[]['permissions']",
			'value'         => 'Read',
		] );
		?>

		<div class="tec-settings-zapier-details__actions tec-settings-zapier-details__api-key-save">
			<?php if ( empty( $api_key['name'] ) || empty( $api_key['has_pair'] ) ) {
				$this->template( 'zapier/api/components/generate-button', [
					'api_key' => $api_key,
					'url'     => $url,
				] );
			} ?>
		</div>
		<?php if ( ! empty( $api_key['name'] ) && ! empty( $api_key['has_pair'] ) ) {
			$this->template( 'zapier/api/components/revoke-button', [
				'local_id' => $local_id,
				'api_key'  => $api_key,
				'url'      => $url,
			] );
		} ?>
	</div>
	<?php if ( ! empty( $api_key['name'] ) && ! empty( $api_key['has_pair'] ) ) {
		/*			$this->template( 'zapier/api/components/key-pair', [
						'local_id' => $local_id,
						'api_key'     => $api_key,
						'url'      => $url,
					] );*/
	} ?>
</li>