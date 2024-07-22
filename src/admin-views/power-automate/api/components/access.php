<?php
/**
 * View: Integration - Access Field.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/power-automate/api/components/access.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var int                 $consumer_id The unique id used to save The API Key data.
 * @var array<string|mixed> $api_key     The API Key data.
 * @var Url                 $url         An instance of the URL handler.
 */

$message_classes = [ 'tec-settings-power-automate-details-api-key__message-wrap' ];
$message_title   = _x( 'API Authentication Details', 'Label for the consumer id and secret section.', 'tribe-common' );
$message         = esc_html_x( 'Please copy the consumer id and secret below. Once you leave the page they will no longer be available.', 'Consumer id and secret only show once help text for Power Automate API.', 'tribe-common' );
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
			'power-automate/api/components/token',
			[
				'classes_wrap'  => [ 'tec-settings-power-automate-connection-details__access-token-wrap' ],
				'label'         => _x( 'Access Token', 'Label for the access token connection for Power Automate.', 'tribe-common' ),
				'screen_reader' => '',
				'id'            => 'tec_automator_power_automate_access_token_' . $consumer_id,
				'name'          => "tec_automator_power_automate[]['access_token']",
				'value'         => $api_key['access_token'],
			]
		);
		?>
	</div>
</div>
