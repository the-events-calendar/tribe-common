<?php

$query_args = [
	'post_type'        => 'tribe_events',
	'page'             => 'tec-events-help-hub',
	'embedded_content' => 'true',
];

$iframe_url = add_query_arg( $query_args, admin_url( 'edit.php' ) );

?>
<div id="iframe-loader" class="loader">Loading...</div>
<iframe src="<?php echo esc_url( $iframe_url ); ?>" frameborder="0" id="tec-settings__support-hub-iframe" class="hidden"></iframe>
