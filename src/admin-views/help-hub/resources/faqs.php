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

if ( empty( $section['faqs'] ) ) {
	return;
}
?>

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php echo esc_html_x( 'FAQs', 'FAQs section title', 'tribe-common' ); ?>
		</h3>
		<p class="tec-settings-form__section-description">
			<?php echo esc_html_x( 'Get quick answers to common questions', 'FAQs section paragraph', 'tribe-common' ); ?>
		</p>
	</div>
	<div class="tec-ui-accordion">
		<?php foreach ( $section['faqs'] as $faq ) : ?>
			<h4><?php echo esc_html( $faq['question'] ); ?></h4>
			<div>
				<p><?php echo esc_html( $faq['answer'] ); ?></p>
				<p><a href="<?php echo esc_url( $faq['link_url'] ); ?>"><?php echo esc_html( $faq['link_text'] ); ?></a></p>
			</div>
		<?php endforeach; ?>
	</div>
</div>
