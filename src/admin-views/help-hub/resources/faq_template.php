<?php
/**
 * Template for displaying FAQ sections in the help hub.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 * @var array       $section          The current section.
 */

// Ensure we have valid input data and at least one FAQ.
if ( empty( $section['faq'] ) || ! is_array( $section['faq'] ) ) {
	return;
}
?>

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php echo esc_html( $section['title'] ); ?>
		</h3>
		<p class="tec-settings-form__section-description">
			<?php echo esc_html( $section['description'] ); ?>
		</p>
	</div>
	<div class="tec-ui-accordion">
		<?php
		foreach ( $section['faq'] as $faq ) :
			// Skip invalid FAQ entries.
			if ( ! is_array( $faq ) || empty( $faq['question'] ) || empty( $faq['answer'] ) ) {
				continue;
			}

			// Ensure we have strings for question and answer.
			$question = is_string( $faq['question'] ) ? $faq['question'] : '';
			$answer   = is_string( $faq['answer'] ) ? $faq['answer'] : '';

			// Skip if required fields are empty after sanitization.
			if ( empty( $question ) || empty( $answer ) ) {
				continue;
			}
			?>
			<h4><?php echo esc_html( $question ); ?></h4>
			<div>
				<p><?php echo esc_html( $answer ); ?></p>
				<?php
				// Only show link if both URL and text are valid strings.
				if (
					! empty( $faq['link_url'] )
					&& ! empty(
						$faq['link_text']
						&& is_string( $faq['link_url'] )
						&& is_string( $faq['link_text'] )
					)
				) :
					?>
					<p><a href="<?php echo esc_url( $faq['link_url'] ); ?>" rel="noopener" target="_blank"><?php echo esc_html( $faq['link_text'] ); ?></a></p>
					<?php
				endif;
				?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
