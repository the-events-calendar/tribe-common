<?php
/**
 * View: Integration - Delete Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/components/integration/delete-button.php
 *
 * See more documentation about our views templating system.
 *
 * @since   1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api $api         An instance of the integration API handler.
 * @var int $consumer_id The unique id used to save The API Key data.
 * @var URL $url         An instance of the URL handler.
 */

$revoke_link  = $url->to_delete_connection_link( $consumer_id );
$revoke_label = esc_html_x( 'Delete', 'Removes a connection from the list of integration connections.', 'tribe-common' )
?>
<div class="tec-automator-grid-item tec-automator-settings-details-action__delete-wrap">
	<button
		class="tec-automator-settings-details-action__revoke"
		type="button"
		data-ajax-revoke-url="<?php echo esc_url( $revoke_link ); ?>"
		data-confirmation="<?php echo esc_attr( $api->get_confirmation_to_delete_connection() ); ?>"
	>
		<?php echo esc_html( $revoke_label ); ?>
	</button>
</div>
