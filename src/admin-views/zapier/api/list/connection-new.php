<?php
/**
 * View: Zapier Integration API Key Fields.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/list/fields.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api                 $api         An instance of the Zapier API handler.
 * @var array<string|mixed> $connection_data The connection data.
 * @var int                 $consumer_id The unique id used to save The API Key data.
 * @var array<string|mixed> $users       An array of WordPress users to create an API Key for.
 * @var URL                 $url         An instance of the URL handler.
 */

?>

<div
	class="tec-automator-grid tec-automator-grid-row tec-automator-settings-details__container tec-settings-zapier-api-key-details__container"
	data-consumer-id="<?php echo esc_attr( $consumer_id ); ?>"
>
	<?php
	$this->template(
		'components/text',
		[
			'classes_wrap'  => [ 'tec-automator-grid-item', 'tec-settings-zapier-details-api-key__name-wrap' ],
			'classes_label' => [ 'screen-reader-text', 'tec-settings-zapier-details-api-key__name-label' ],
			'classes_input' => [ 'tec-automator-settings-details__input', 'tec-automator-settings-details__name-input', 'tec-settings-zapier-details-api-key__name-input' ],
			'label'         => _x( 'Description', 'Label for the name of the API Key for Zapier.', 'tribe-common' ),
			'id'            => 'tec_automator_zapier_name_' . $consumer_id,
			'name'          => "tec_automator_zapier[]['name']",
			'placeholder'   => _x( 'Enter an API Key description', 'The placeholder for the Zapier API Key name.', 'tribe-common' ),
			'screen_reader' => _x( 'Enter an API Key description.', 'The screen reader text of the label for the Zapier API Key name.', 'tribe-common' ),
			'value'         => $connection_data['name'],
			'attrs'         => [],
		] 
	);
	?>

	<?php $this->template( 'components/dropdown', $users ); ?>

	<?php
	$this->template(
		'components/read-only',
		[
			'classes_wrap'  => [ 'tec-automator-grid-item', 'tec-settings-zapier-details-api-key__permissions-wrap' ],
			'label'         => _x( 'Permissions', 'Label for the permissions of the API Key for Zapier.', 'tribe-common' ),
			'screen_reader' => _x( 'The permissions for the Zapier API Key.', 'The screen reader text of the label for the Zapier API Key permissions.', 'tribe-common' ),
			'id'            => 'tec_automator_zapier_permissions_' . $consumer_id,
			'name'          => "tec_automator_zapier[]['permissions']",
			'value'         => 'Read',
		] 
	);
	?>

	<div class="tec-automator-grid-item"></div>

	<div class="tec-automator-grid-item tec-settings-zapier-details__actions tec-settings-zapier-details__api-key-save">
		<?php
		if ( empty( $connection_data['name'] ) || empty( $connection_data['has_pair'] ) ) {
			$this->template(
				'zapier/api/components/generate-button',
				[
					'api_key' => $connection_data,
					'url'     => $url,
				] 
			);
		}
		?>
	</div>
</div>
