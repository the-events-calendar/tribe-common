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
 * @var int                 $local_id The unique id used to save The API Key data.
 * @var array<string|mixed> $api_key  The API Key data.
 * @var Url                 $url      An instance of the URL handler.
 */

$connect_status = _x(
	'Not Connected',
	'The status of the Page\'s access token.',
	'tribe-common'
);
$connect_instructions = _x(
	'click "Continue with Zapier" to authorize it.',
	'The message to display if a Faceook Page is not connected.',
	'tribe-common'
);

$connected_message = sprintf(
	'<span class="warning">%1$s</span>, %2$s',
	esc_html( $connect_status ),
	esc_html( $connect_instructions )
);

if ( $api_key['access_token'] ) {
	$connect_status = _x(
		'Connected',
		'The status of the Page\'s access token.',
		'tribe-common'
		);
	$connect_instructions = _x(
		'token expires:',
		'The message to display if a Faceook Page is connected.',
		'tribe-common'
	);
	$clear_link_label = _x(
		'clear token',
		'The label of the link to clear the token.',
		'tribe-common'
	);

	$connected_message = sprintf(
		'<span class="success">%1$s</span>, %2$s  %3$s - <a class="tec-settings-zapier-details__clear-access" href="%4$s">%5$s</a>',
		esc_html( $connect_status ),
		esc_html( $connect_instructions ),
		esc_html( $api_key['expiration'] ),
		$url->to_clear_access_page_link(),
		esc_html( $clear_link_label )
	);
}
$expiration = $api_key['expiration'];
?>
<div class="tec-settings-integrations-details__row">
	<div class="tec-settings-zapier-details__page-expiration">
		<div class="tec-settings-zapier-details__page-expiration-text">
			<strong>
				<?php echo esc_html_x( 'Status: ', 'The label of the status of the Page\'s access token.', 'tribe-common' ); ?>
			</strong>
			<?php echo $connected_message; ?>
		</div>
	</div>
</div>