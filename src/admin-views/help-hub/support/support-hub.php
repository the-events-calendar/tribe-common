<?php
/**
 * The template that displays the help hub tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

?>
<div class="tribe-settings-form form">
	<div class="tec-settings-form">
		<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal">
			<h2 class="tec-settings-form__section-header">
				<?php
				echo esc_html_x(
					'TEC Support Hub',
					'Help page Support Hub title',
					'tribe-common'
				);
				?>
			</h2>
			<p class="tec-settings-form__section-description">
				<?php
				echo esc_html_x(
					'Help on setting up, customizing and troubleshooting your calendar.',
					'Help page Support Hub header paragraph',
					'tribe-common'
				);
				?>
			</p>
		</div>
		<div class="tec-settings-form__content-section">
			<div>
				<?php
				$this->template( 'help-hub/support/iframe' );
				?>
			</div>

		</div>
	</div>
</div>
<?php
$this->template( "help-hub/support/sidebar/{$template_variant}" );
?>
