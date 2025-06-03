<?php
/**
 * Resource Heading Template
 *
 * This template displays the heading section of the Help Hub resources.
 *
 * @since 6.8.0
 *
 * @var string $hub_title    The title of the resources section.
 * @var string $description  The description of the resources section.
 * @var string $notice_content The notice content for the chatbot.
 * @var Help_Hub_Data_Interface $help_hub The Help Hub data instance.
 */

?>
<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal">
	<h2 class="tec-settings-form__section-header">
		<?php echo esc_html( $hub_title ); ?>
	</h2>
	<p class="tec-settings-form__section-description">
		<?php echo wp_kses_post( $description ); ?>
	</p>
	<?php
	echo wp_kses( $help_hub->generate_notice_html( $notice_content, 'tec-common-help-chatbot-notice' ), 'post' );
	?>
</div>
