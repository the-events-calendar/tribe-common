<?php

$query_args = [
	'post_type'        => 'tribe_events',
	'page'             => 'tec-events-help-hub',
	'embedded_content' => 'true',
];

$iframe_url = add_query_arg( $query_args, admin_url( 'edit.php' ) );

?>
<div class="tec-settings__support-hub-iframe-container">
	<div id="tec-settings__support-hub-iframe-loader" class="loader">
		<div class="spinner-container">
			<div class="spinner is-active"></div>
		</div>
	</div>
	<iframe src="<?php echo esc_url( $iframe_url ); ?>" frameborder="0" id="tec-settings__support-hub-iframe" class="hidden"></iframe>
</div>
