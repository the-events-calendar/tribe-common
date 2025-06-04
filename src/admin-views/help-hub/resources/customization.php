<?php
/**
 * @deprecated 6.8.0 This template is deprecated. The functionality has been moved to the Section_Builder class.
 *             See TEC\Events\Admin\Help_Hub\TEC_Hub_Resource_Data for the new implementation.
 */

_deprecated_file(
	esc_html( basename( __FILE__ ) ),
	'6.8.0',
	'',
	'This template is deprecated. The functionality has been moved to the Section_Builder class.'
);
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

$section = $help_hub->handle_resource_sections();

if ( empty( $section['customizations'] ) ) {
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
		<?php foreach ( $section['customizations'] as $guide ) : ?>
			<li>
				<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $guide['icon'] ); ?>"/>
				<a href="<?php echo esc_url( $guide['link'] ); ?>">
					<?php echo esc_html( $guide['title'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
