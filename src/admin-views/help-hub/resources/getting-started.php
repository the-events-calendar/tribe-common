<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

_deprecated_file(
	esc_html( basename( __FILE__ ) ),
	'6.8.0',
	'',
	'This template is deprecated. The functionality has been moved to the Section_Builder class.'
);

use TEC\Common\Admin\Help_Hub\Hub;

$section = $help_hub->handle_resource_sections();

if ( empty( $section['getting_started'] ) ) {
	return;
}
?>

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php echo esc_html_x( 'Getting started guides', 'Getting started guide section title', 'tribe-common' ); ?>
		</h3>
		<p class="tec-settings-form__section-description">
			<?php echo esc_html_x( 'Easy to follow step-by-step instructions to make the most out of your calendar.', 'Getting started guide section paragraph', 'tribe-common' ); ?>
		</p>
	</div>
	<ul class="tec-help-list__list-expanded">
		<?php foreach ( $section['getting_started'] as $resource ) : ?>
			<li>
				<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $resource['icon'] ); ?>"/>
				<a href="<?php echo esc_url( $resource['link'] ); ?>">
					<?php echo esc_html( $resource['title'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
