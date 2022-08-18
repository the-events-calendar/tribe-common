<?php
/**
 * View: Zapier Integration - Revoke Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/components/revoke-button.php
 *
 * See more documentation about our views templating system.
 *
 * @since   TBD
 *
 * @version TBD
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api                 $api         An instance of the Zapier API handler.
 * @var array<string|mixed> $api_key     The API Key data.
 * @var int                 $consumer_id The unique id used to save The API Key data.
 * @var URL                 $url         An instance of the URL handler.
 */

$revoke_link  = $url->to_revoke_api_key_link( $consumer_id );
$revoke_label = _x( 'Revoke', 'Removes a zapier page from the list of Zapier live pages.', 'tribe-common' )
?>
<div class="tec-common-integrations-details-action__revoke-wrap tec-common-zapier-details-action__revoke-wrap">
	<button
		class="dashicons dashicons-trash tec-common-integrations-details-action__revoke tec-common-zapier-details-action__revoke"
		type="button"
		data-ajax-revoke-url="<?php echo $revoke_link; ?>"
		data-confirmation="<?php echo $api->get_confirmation_to_revoke_api_key(); ?>"
	>
		<span class="screen-reader-text">
			<?php echo esc_html( $revoke_label ); ?>
		</span>
	</button>
</div>