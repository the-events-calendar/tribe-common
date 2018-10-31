<?php
/**
 * The Black Friday 2018 admin notice for when only TEC is active.
 *
 * @since TBD
 *
 * @var string $mascot_url The local URL for the notice's mascot image.
 * @var int $end_time The Unix timestamp for the sale's end time.
 */
?>
<div class="tribe-marketing-notice">
	<div class="tribe-notice-icon">
		<img src="<?php echo esc_url( $mascot_url ); ?>" />
	</div>
	<div class="tribe-notice-content">
		<h3>Up to 30% Off!</h3>
		<p>Save big on Events Calendar PRO, Filter Bar, Community Events, and more during our huge Black Friday sale!</p>
		<p><em>(But hurry, because this offer ends on <abbr title="<?php echo date_i18n( 'r', $end_time ); ?>">Monday, November 26th</abbr>.)</em> <a target="_blank" class="button button-primary" href="http://m.tri.be/1a8l">Shop Now</a></p>
	</div>
</div>