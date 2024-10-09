<?php
/**
 * The template that displays the resources sidebar.
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
		<h2>
			<?php
			_ex(
				'Our TEC support hub now offers an improved help experience',
				'Help page resources sidebar header',
				'tribe-common'
			);
			?>
		</h2>
		<p>
			<?php
			_ex(
				'Our Help page is better than ever with the addition of:',
				'Describes why consent is beneficial',
				'tribe-common'
			);
			?>
		</p>

		<div class="tec-settings__sidebar-icon-wrap">
			<div>
				<img class="tec-settings-infobox-logo"
					 src="<?php echo esc_url( $stars_icon_url ); ?>"
					 alt="AI chat bot logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
				<h3>
					<?php
					_ex(
						'AI Chatbot',
						'AI Chatbot sidebar header',
						'tribe-common'
					);
					?>
				</h3>
				<p>
					<?php
					_ex(
						'Here to provide quick answers to your questions. It’s never been easier to find the right resource.',
						'AI Chatbot support sidebar paragraph',
						'tribe-common'
					);
					?>
				</p>
			</div>
		</div>
		<div class="tec-settings__sidebar-icon-wrap">
			<div>
				<img class="tec-settings-infobox-logo"
					 src="<?php echo esc_url( $chat_icon_url ); ?>"
					 alt="Support chat logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
				<h3>
					<?php
					_ex(
						'Talk to our support team',
						'Get support sidebar header',
						'tribe-common'
					);
					?>
				</h3>
				<p>
					<?php
					_ex(
						'Our Support team is available to help you out 5 days a week:',
						'Live support sidebar paragraph',
						'tribe-common'
					);
					?>
				</p>
				<p>
					<strong>
						<?php
						_ex(
							'Mon-Fri from 9:00 - 20:00 PST',
							'Live support hours',
							'tribe-common'
						);
						?>
					</strong>
				</p>
			</div>
		</div>
	</div>
</div>
