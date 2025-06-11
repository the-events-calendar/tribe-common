<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 * @var array       $sections          The sections to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

$template_map = [
	'link' => 'help-hub/resources/link_template',
	'faq'  => 'help-hub/resources/faq_template',
];

/**
 * Filter the resources tab title
 *
 * @since 6.8.0
 *
 * @param string $hub_title The default resources tab title
 */
$hub_title = apply_filters( 'tec_help_hub_resources_title', _x( 'Resources', 'Resources tab title', 'tribe-common' ) );

/**
 * Filter the resources tab description
 *
 * @since 6.8.0
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
 * @since 6.8.0
 *
 * @param string $notice_content The default notice content
 */
$notice_content = apply_filters(
	'tec_help_hub_resources_notice',
	sprintf(
	// translators: Placeholders are for the opening and closing anchor tags.
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
		<?php

		$template_values = [
			'hub_title'      => $hub_title,
			'description'    => $description,
			'notice_content' => $notice_content,
			'help_hub'       => $help_hub,
		];

		$this->set_values( (array) $template_values ?? [] );
		$this->template( 'help-hub/resources/resource-heading' );

		foreach ( $sections as $slug => $section ) {
			$template_type = $section['type'] ?? 'link';
			$template_name = $template_map[ $template_type ] ?? $template_map['link'];

			$this->template( $template_name, [ 'section' => $section ] );
		}
		$this->template( 'help-hub/resources/settings-infobox' );

		?>
	</div>
</div>
<?php $this->template( "help-hub/resources/sidebar/{$template_variant}" ); ?>
