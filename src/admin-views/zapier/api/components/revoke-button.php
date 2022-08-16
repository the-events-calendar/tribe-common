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
 * @var int                 $local_id The unique id used to save The API Key data.
 * @var array<string|mixed> $api_key  The API Key data.
 * @var Url                 $url      An instance of the URL handler.
 */

$revoke_link  = $url->to_revoke_api_key_link( $local_id );
$revoke_label = _x( 'Revoke', 'Removes a zapier page from the list of Zapier live pages.', 'tribe-common' )
?>
<div class="tec-settings-zapier-details__actions tec-settings-zapier-details__revoke">
	<button
		class="dashicons dashicons-trash tec-settings-integrations-details__revoke tec-settings-zapier-details__revoke"
		type="button"
		data-ajax-revoke-url="<?php echo $revoke_link; ?>"
		<?php echo empty( $api_key['name'] ) || empty( $api_key['has_pair'] ) ? 'disabled' : ''; ?>
	>
		<span class="screen-reader-text">
			<?php echo esc_html( $revoke_label ); ?>
		</span>
	</button>
</div>