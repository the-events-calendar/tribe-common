<?php
/**
 * View: Zapier Integration - API Key Pair.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/components/key-pair.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var int                 $consumer_id The unique id used to save The API Key data.
 * @var array<string|mixed> $api_key     The API Key data.
 * @var Url                 $url         An instance of the URL handler.
 */

$message_classes = [ 'tec-settings-zapier-details-api-key__message-wrap' ];
$message_title   = _x( 'API Authentication Details', 'Label for the consumer id and secret section.', 'tribe-common' );
$message         = esc_html_x( 'Please copy the consumer id and secret below. Once you leave the page they will no longer be available.', 'Consumer id and secret only show once help text for Zapier API.', 'tribe-common' );
?>
<div class="tec-automator-grid-full-width">
	<div class="tec-automator-api-key__wrap">
		<div
			<?php tribe_classes( $message_classes ); ?>
		>
			<div class="tec-automator-settings-api-key-title">
				<?php echo esc_html( $message_title ); ?>
			</div>
			<span class="dashicons dashicons-info"></span><?php echo wp_kses_post( $message ); ?>
		</div>

		<?php
		$this->template(
			'zapier/api/components/key',
			[
				'classes_wrap'  => [ 'tec-settings-zapier-details-api-key__consumer-id-wrap' ],
				'label'         => _x( 'Consumer ID', 'Label for the consumer id of the API Key for Zapier.', 'tribe-common' ),
				'screen_reader' => '',
				'id'            => 'tec_automator_zapier_consumer_id_' . $consumer_id,
				'name'          => "tec_automator_zapier[]['consumer_id']",
				'value'         => $api_key['consumer_id'],
			]
		);

		$this->template(
			'zapier/api/components/key',
			[
				'classes_wrap'  => [ 'tec-settings-zapier-details-api-key__consumer-secret-wrap' ],
				'label'         => _x( 'Consumer Secret', 'Label for the consumer secret of the API Key for Zapier.', 'tribe-common' ),
				'screen_reader' => '',
				'id'            => 'tec_automator_zapier_consumer_secret_' . $consumer_id,
				'name'          => "tec_automator_zapier[]['consumer_secret']",
				'value'         => $api_key['consumer_secret'],
			]
		);
		?>
	</div>
</div>
