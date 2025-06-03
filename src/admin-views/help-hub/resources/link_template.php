<?php
/**
 * Template for rendering a resource section in the Help Hub.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 * @var array       $section          The section to display.
 */

if ( empty( $section['links'] ) || ! is_array( $section['links'] ) ) {
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
	<?php
	$this->template( 'help-hub/resources/section-links' );
	?>
</div>
