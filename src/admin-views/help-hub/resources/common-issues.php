<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

$section = $help_hub->handle_resource_sections();

if ( empty( $section['common_issues'] ) ) {
	return;
}
?>

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php echo esc_html_x( 'Common issues', 'Common issues section title', 'tribe-common' ); ?>
		</h3>
		<p class="tec-settings-form__section-description">
			<?php
			printf(
			/* translators: %s is the link to the AI Chatbot */
				esc_html__( 'Having trouble? Find solutions to common issues or ask our %s.', 'tribe-common' ),
				'<a href="javascript:void(0)" data-tab-target="tec-help-tab">' . esc_html__( 'AI Chatbot', 'tribe-common' ) . '</a>'
			);
			?>
		</p>

	</div>
	<ul class="tec-help-list__list-expanded">
		<?php foreach ( $section['common_issues'] as $issue ) : ?>
			<li>
				<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $issue['icon'] ); ?>"/>
				<a href="<?php echo esc_url( $issue['link'] ); ?>">
					<?php echo esc_html( $issue['title'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
