<?php
/**
 * Template for displaying FAQ sections in the help hub.
 *
 * @var array  $section     The section data containing FAQs.
 * @var string $title       The section title.
 * @var string $description The section description.
 */

// Ensure we have valid input data.
if ( ! isset( $section ) || ! is_array( $section ) ) {
	return;
}

if ( ! isset( $section['faqs'] ) || ! is_array( $section['faqs'] ) ) {
	return;
}

// Ensure we have at least one FAQ.
if ( empty( $section['faqs'] ) ) {
	return;
}

// Sanitize title and description.
$title = isset( $title ) && is_string( $title ) ? $title : esc_html__( 'FAQs', 'tribe-common' );
$description = isset( $description ) && is_string( $description ) ? $description : esc_html__( 'Get quick answers to common questions', 'tribe-common' );
?>

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php echo esc_html( $title ); ?>
		</h3>
		<p class="tec-settings-form__section-description">
			<?php echo esc_html( $description ); ?>
		</p>
	</div>
	<div class="tec-ui-accordion">
		<?php 
		foreach ( $section['faqs'] as $faq ) :
			// Skip invalid FAQ entries.
			if ( ! is_array( $faq ) || empty( $faq['question'] ) || empty( $faq['answer'] ) ) {
				continue;
			}

			// Ensure we have strings for question and answer.
			$question = is_string( $faq['question'] ) ? $faq['question'] : '';
			$answer = is_string( $faq['answer'] ) ? $faq['answer'] : '';

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
				if ( isset( $faq['link_url'], $faq['link_text'] ) 
					&& is_string( $faq['link_url'] ) 
					&& is_string( $faq['link_text'] )
					&& ! empty( $faq['link_url'] )
					&& ! empty( $faq['link_text'] )
				) :
					$link_url = esc_url( $faq['link_url'] );
					// Double check we got a valid URL back.
					if ( ! empty( $link_url ) ) :
				?>
					<p><a href="<?php echo $link_url; ?>"><?php echo esc_html( $faq['link_text'] ); ?></a></p>
				<?php 
					endif;
				endif;
				?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
