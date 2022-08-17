<?php
/**
 * View: Zapier Integration add API Key fields link.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/authorize-fields/add-link
 *
 * See more documentation about our views templating system.
 *
 * @since   TBD
 *
 * @version TBD
 *
 * @link    http://evnt.is/1aiy
 *
 * @var Api $api An instance of the Zapier API handler.
 * @var Url $url An instance of the URL handler.
 */

$add_link      = $url->to_add_api_key_link();
$connect_label = _x( 'Add API Key', 'Label to add API Key fields for Zapier.', 'tribe-common' );

$classes = [
	'button'                                        => true,
	'tec-settings-integrations__add-account-button' => true,
	'tec-setings-zapier__add-api-key-fields-button'        => true,
];
?>
<a
	href="<?php echo esc_url( $add_link ); ?>"
	<?php tribe_classes( $classes ); ?>
>
	<span class="dashicons dashicons-plus"></span>
	<?php echo esc_html( $connect_label ); ?>
</a>
