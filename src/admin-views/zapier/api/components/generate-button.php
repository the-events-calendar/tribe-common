<?php
/**
 * View: Zapier Integration - Generate Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/components/generate-button.php
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
 * @var array<string|mixed> $api_key The API Key data.
 * @var Url                 $url     An instance of the URL handler.
 */

$add_link  = $url->to_generate_api_key_pair( $consumer_id );
$add_label = _x( 'Generate Key Pair', 'Generate a Zapier API Key pair.', 'tribe-common' );
?>
<button
	class="tec-settings-integrations-details-action__generate tec-settings-zapier-detailsaction__generate button-primary"
	type="button"
	data-ajax-generate-url="<?php echo $add_link; ?>"
	<?php echo empty( $api_key['name'] ) || empty( $api_key['has_pair'] ) ? 'disabled' : ''; ?>
>
	<span>
		<?php echo esc_html( $add_label ); ?>
	</span>
</button>