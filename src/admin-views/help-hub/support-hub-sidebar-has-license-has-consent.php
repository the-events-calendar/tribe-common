<?php
/**
 * The template that displays the support hub sidebar.
 *
 * @var Tribe__Main $main      The main common object.
 * @var bool $is_opted_in      Whether the user has opted in to telemetry.
 * @var bool $is_license_valid Whether the user has any valid licenses.
 */

$stars_icon_url = tribe_resource_url( 'images/icons/stars.svg', false, null, $main );
$chat_icon_url  = tribe_resource_url( 'images/icons/chat-bubble.svg', false, null, $main );

?>

<div class="tec-settings-form__sidebar tec-help-resources__sidebar">
	<div class="tec-settings__sidebar-inner">


		<div class="tec-settings__sidebar-icon-wrap">
			<div>
				<img class="tec-settings-infobox-logo"
					src="<?php echo esc_url( $stars_icon_url ); ?>"
					alt="AI chat bot logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
				<h2>
					<?php
					echo esc_html_x(
						'Talk to our support team',
						'Talk to support sidebar header',
						'tribe-common'
					);
					?>
				</h2>
				<p>
					<?php
					echo esc_html_x(
						'If you still need help contact us. Our Support team is available to help you out 5 days a week:',
						'Contact support paragraph',
						'tribe-common'
					);
					?>
				</p>
				<p>
					<?php
					echo esc_html_x(
						'Mon-Fri from 9:00 - 20:00 PST',
						'Support hours',
						'tribe-common'
					);
					?>
				</p>
				<p>
					<a data-open-support-chat="" href="javascript:void(0)">
						<?php
						echo esc_html_x(
							'Contact support',
							'Contact support link',
							'tribe-common'
						);
						?>
					</a>
				</p>
			</div>
		</div>
	</div>
</div>
