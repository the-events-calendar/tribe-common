<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

/**
 * Filter the resources tab title
 *
 * @since TBD
 *
 * @param string $hub_title The default resources tab title
 */
$hub_title = apply_filters( 'tec_help_hub_resources_title', _x( 'Resources', 'Resources tab title', 'tribe-common' ) );

/**
 * Filter the resources tab description
 *
 * @since TBD
 *
 * @param string $description The default resources tab description
 */
$description = apply_filters(
	'tec_help_hub_resources_description',
	sprintf(
	/* translators: %1$s is the link to the Knowledgebase. */
		__( 'Help on setting up, customizing, and troubleshooting your calendar. See our %1$s for in-depth content.', 'tribe-common' ),
		'<a href="https://evnt.is/1bbw" rel="noopener noreferrer" target="_blank">' . __( 'Knowledgebase', 'tribe-common' ) . '</a>'
	)
);

/**
 * Filter the resources tab notice content
 *
 * @since TBD
 *
 * @param string $notice_content The default notice content
 */
$notice_content = apply_filters(
	'tec_help_hub_resources_notice',
	sprintf(
	// translators: Placeholders are for the `a` tag that displays a link.
		_x(
			'To find the answer to all your questions use the %1$sTEC Chatbot%2$s',
			'The callout notice to try the chatbot with a link to the page',
			'tribe-common'
		),
		'<a data-tab-target="tec-help-tab" href="#">',
		'</a>'
	)
);
?>
<div class="tribe-settings-form form">
	<div class="tec-settings-form">
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
		<?php
		$sections = $help_hub->handle_resource_sections();
		foreach ( $sections as $slug => $section ) {
			// Determine which template to use based on section type.
			$template_name = 'help-hub/resources/resource_template';

			// Check if this is a FAQ section.
			if ( isset( $section['type'] ) && 'faq' === $section['type'] ) {
				$template_name = 'help-hub/resources/faq_template';
			}

			$this->template( $template_name, [ 'section' => $section ] );
		}
		?>

		<div class="tec-settings-infobox">
			<img class="tec-settings-infobox-logo" src="<?php echo esc_url( $help_hub->get_icon_url( 'stars_icon' ) ); ?>" alt="AI Chatboat logo">
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
	</div>
</div>
<?php $this->template( "help-hub/resources/sidebar/{$template_variant}" ); ?>
