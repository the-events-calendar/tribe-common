<?php
/**
 * Settings Infobox Template
 *
 * This template displays the AI Chatbot infobox in the Help Hub.
 *
 * @since 6.8.0
 *
 * @var Hub $help_hub The Help Hub class.
 */

?>
<div class="tec-settings-infobox">
	<img class="tec-settings-infobox-logo" src="<?php echo esc_url( $help_hub->get_icon_url( 'stars_icon' ) ); ?>" alt="AI Chatbot logo">
	<h3 class="tec-settings-infobox-title">
		<?php
		echo esc_html_x(
			'Our AI Chatbot is here to help you',
			'AI Chatbot notice title',
			'tribe-common'
		);
		?>
	</h3>
	<p>
		<?php
		echo esc_html_x(
			'You have questions? The TEC Chatbot has the answers.',
			'AI Chatbot section paragraph',
			'tribe-common'
		);
		?>
	</p>
	<p>
		<a data-tab-target="tec-help-tab" href="javascript:void(0)">
			<?php
			echo esc_html_x(
				'Talk to TEC Chatbot',
				'Link to the Help Chatbot',
				'tribe-common'
			);
			?>
		</a>
	</p>
</div>
