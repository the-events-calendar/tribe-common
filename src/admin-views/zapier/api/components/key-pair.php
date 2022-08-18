<?php
/**
 * View: Zapier Integration - API Key Pair.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/components/key-pair.php
 *
 * See more documentation about our views templating system.
 *
 * @since   TBD
 *
 * @version TBD
 *
 * @link    http://evnt.is/1aiy
 *
 * @var int                 $consumer_id The unique id used to save The API Key data.
 * @var array<string|mixed> $api_key  The API Key data.
 * @var Url                 $url      An instance of the URL handler.
 */

?>
<div class="tec-settings-integrations-details__row">
	<?php
	$this->template( 'zapier/api/components/read-only', [
		'classes_wrap'  => [ 'tec-settings-zapier-details-api-key__consumer-id-wrap' ],
		'label'         => _x( 'Consumer ID', 'Label for the consumer id of the API Key for Zapier.', 'tribe-common' ),
		'screen_reader' => _x( 'The consumer id for the Zapier API Key.', 'The screen reader text of the label for the Zapier API Key consumer id.', 'tribe-common' ),
		'id'            => "tec_common_zapier_consumer_id_" . $consumer_id,
		'name'          => "tec_common_zapier[]['consumer_id']",
		'value'         => $api_key['consumer_id'],
	] );

	$this->template( 'zapier/api/components/read-only', [
		'classes_wrap'  => [ 'tec-settings-zapier-details-api-key__consumer-secret-wrap' ],
		'label'         => _x( 'Consumer Secret', 'Label for the consumer secret of the API Key for Zapier.', 'tribe-common' ),
		'screen_reader' => _x( 'The consumer secret for the Zapier API Key.', 'The screen reader text of the label for the Zapier API Key consumer secret.', 'tribe-common' ),
		'id'            => "tec_common_zapier_consumer_secret_" . $consumer_id,
		'name'          => "tec_common_zapier[]['consumer_secret']",
		'value'         => $api_key['consumer_secret'],
	] );
	?>
</div>