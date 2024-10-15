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
$optin_url      = admin_url( 'edit.php?page=tec-events-settings&tab=general-debugging-tab&post_type=tribe_events#tribe-field-opt-in-status' );

?>

<div class="tec-settings-form__sidebar tec-help-resources__sidebar">
	<div class="tec-settings__sidebar-inner">
		<h2>
			<?php
			echo esc_html_x(
				'Our TEC support hub now offers an improved help experience',
				'Help page resources sidebar header',
				'tribe-common'
			);
			?>
		</h2>
		<p>
			<?php
			echo esc_html_x(
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
					echo esc_html_x(
						'AI Chatbot',
						'AI Chatbot sidebar header',
						'tribe-common'
					);
					?>
				</h3>
				<p>
					<?php
					echo esc_html_x(
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
					echo esc_html_x(
						'In-app priority live support',
						'Get support sidebar header',
						'tribe-common'
					);
					?>
				</h3>
				<p>
					<?php
					echo esc_html_x(
						'Get access to our agents or generate a support ticket from right here.',
						'Live support sidebar paragraph',
						'tribe-common'
					);
					?>
				</p>
			</div>
		</div>
		<p>
			<?php
			echo esc_html_x(
				'To enhance your experience, we require your consent to collect and share some of your website’s data with our AI chatbot. ',
				'Opt in sidebar paragraph',
				'tribe-common'
			);
			?>
		</p>
		<p>
			<a class="button-secondary" href="<?php echo esc_url( $optin_url ); ?>">
				<?php
				echo esc_html_x(
					'Manage my data sharing consent',
					'Button to manage opt in status',
					'tribe-common'
				);
				?>
			</a>
		</p>
	</div>
</div>
