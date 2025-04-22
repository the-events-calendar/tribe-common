<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 * @var array       $section          The section to display.
 */

// Skip FAQ sections and sections without required data
if ( 
    empty( $section ) || 
    ! is_array( $section ) || 
    empty( $section['title'] ) || 
    empty( $section['links'] ) || 
    ! is_array( $section['links'] ) ||
    strtolower( $section['title'] ) === 'faq'
) {
    return;
}

?>

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php echo esc_html( $section['title'] ); ?>
		</h3>
		<?php if ( ! empty( $section['description'] ) ) : ?>
			<p class="tec-settings-form__section-description">
				<?php echo wp_kses_post( $section['description'] ); ?>
			</p>
		<?php endif; ?>
	</div>
	<ul class="tec-help-list__list-expanded">
		<?php foreach ( $section['links'] as $resource ) : ?>
			<?php if ( empty( $resource['title'] ) || empty( $resource['url'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<li>
				<?php if ( ! empty( $resource['icon'] ) ) : ?>
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $resource['icon'] ); ?>"/>
				<?php endif; ?>
				<a href="<?php echo esc_url( $resource['url'] ); ?>">
					<?php echo esc_html( $resource['title'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
