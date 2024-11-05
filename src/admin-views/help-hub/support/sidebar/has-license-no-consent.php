<?php
/**
 * The template that displays the support hub sidebar.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

?>
<div class="tec-settings-form__sidebar tec-help-resources__sidebar">
	<?php $this->template( 'help-hub/shared-sidebar-has-license-no-consent' ); ?>
</div>
