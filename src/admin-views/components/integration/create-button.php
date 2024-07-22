<?php
/**
 * View: Integration Connection - Create Button
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/components/integration/create-button.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var int                 $consumer_id The unique id used to save the connection.
 * @var Url                 $url     An instance of the URL handler.
 */

$add_link  = $url->to_create_access_link( $consumer_id );
$add_label = _x( 'Create', 'Create a integration connection access token or consumer secret.', 'tribe-common' );
?>
<button
	class="tec-automator-settings-details-action__generate button-primary"
	type="button"
	data-ajax-generate-url="<?php echo esc_url( $add_link ); ?>"
	data-generate-error="<?php echo esc_attr_x( 'Description or User missing. Please add a description and select a user before create the access information.', 'An error message that the description or user is missing when creating access information for an integration connection. ', 'tribe-common' ); ?>"
>
	<span>
		<?php echo esc_html( $add_label ); ?>
	</span>
</button>
