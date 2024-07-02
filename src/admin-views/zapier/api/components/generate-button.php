<?php
/**
 * View: Zapier Integration - Generate Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/components/generate-button.php
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
 * @var array<string|mixed> $api_key The API Key data.
 * @var Url                 $url     An instance of the URL handler.
 */

$add_link  = $url->to_create_access_link( $consumer_id );
$add_label = _x( 'Generate', 'Generate a Zapier API Key pair.', 'tribe-common' );
?>
<button
	class="tec-automator-settings-details-action__generate tec-settings-zapier-detailsaction__generate button-primary"
	type="button"
	data-ajax-generate-url="<?php echo esc_url( $add_link ); ?>"
	data-generate-error="<?php echo esc_attr_x( 'Description or User missing. Please add a description and select a user before generating a key pair.', 'An error message that the description or user is missing when generating a key pair for Zapier.', 'tribe-common' ); ?>"
>
	<span>
		<?php echo esc_html( $add_label ); ?>
	</span>
</button>
