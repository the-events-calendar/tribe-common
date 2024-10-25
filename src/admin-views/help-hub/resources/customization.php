<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main              The main common object.
 * @var array       $status_values     Contains the user's telemetry and license status.
 * @var array       $keys              Contains the chat keys for support services.
 * @var array       $icons             Contains URLs for various support hub icons.
 * @var array       $links             Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice            The admin notice HTML for the chatbot callout.
 * @var string      $template_variant  The template variant, determining which template to display.
 * @var array       $resource_sections An array of data to display in the Resource section.
 */

if ( empty( $resource_sections['customizations'] ) ) {
	return;
}
?>

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php echo esc_html_x( 'Customization guides', 'Customization guides section title', 'tribe-common' ); ?>
		</h3>
		<p class="tec-settings-form__section-description">
			<?php echo esc_html_x( 'Tips and tricks on making your calendar just the way you want it.', 'Customization guides section paragraph', 'tribe-common' ); ?>
		</p>
	</div>
	<ul class="tec-help-list__list-expanded">
		<?php foreach ( $resource_sections['customizations'] as $guide ) : ?>
			<li>
				<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $guide['icon'] ); ?>"/>
				<a href="<?php echo esc_url( $guide['link'] ); ?>">
					<?php echo esc_html( $guide['title'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
