<?php
/**
 * The template that displays the help page.
 *
 * @var Tribe__Main $main              The main common object.
 * @var array       $status            Contains the user's telemetry and license status.
 * @var array       $keys              Contains the chat keys for support services.
 * @var array       $icons             Contains URLs for various support hub icons.
 * @var array       $links             Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice            The admin notice HTML for the chatbot callout.
 * @var string      $template_variant  The template variant, determining which template to display.
 * @var array       $resource_sections An array of data to display in the Resource section.
 */

$hub_tabs = [
	[
		'target'   => 'tec-help-tab',
		'class'    => 'tec-nav__tab--active',
		'label'    => __( 'Support Hub', 'the-events-calendar' ),
		'id'       => 'tec-help-tab',
		'template' => 'help-hub/support/support-hub',
	],
	[
		'target'   => 'tec-resources-tab',
		'class'    => '',
		'label'    => __( 'Resources', 'the-events-calendar' ),
		'id'       => 'tec-resources-tab',
		'template' => 'help-hub/resources/resources',
	],
];
?>
<div class="tribe_settings wrap tec-events-admin-settings">
	<div class="tribe-notice-wrap">
		<div class="wp-header-end"></div>
	</div>
	<h1>
		<img
			class="tribe-events-admin-title__logo"
			src="<?php echo esc_url( tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, $main ) ); ?>"
			alt="<?php esc_attr_e( 'The Events Calendar logo', 'the-events-calendar' ); ?>"
			role="presentation"
			id="tec-settings-logo"
		/>
		Help
	</h1>
	<dialog id="tec-settings-nav-modal" class="tec-settings-form__modal" aria-labelledby="tec-settings-nav-modal-title" aria-modal="true" role="dialog">
		<div class="tec-modal__content">
			<div class="tec-modal__header">
				<h2 id="tec-settings-nav-modal-title" class="screen-reader-text"><?php esc_html_e( 'Settings Navigation', 'tribe-common' ); ?></h2>
				<button id="tec-settings-nav-modal-close" class="tec-modal__control tec-modal__control--close" data-modal-close aria-label="<?php esc_attr_e( 'Close settings navigation', 'tribe-common' ); ?>">
					<span class="screen-reader-text"><?php esc_html_e( 'Close', 'tribe-common' ); ?></span>
				</button>
			</div>
			<nav class="tec-settings__nav-wrapper" aria-label="<?php esc_attr_e( 'Help Hub Navigation', 'tribe-common' ); ?>">
				<ul class="tec-nav" role="tablist">
					<?php foreach ( $hub_tabs as $index => $hub_tab ) : ?>
						<li
							data-tab-target="<?php echo esc_attr( $hub_tab['target'] ); ?>"
							class="tec-nav__tab <?php echo esc_attr( $hub_tab['class'] ); ?>"
							role="tab"
							id="tab-<?php echo esc_attr( $index ); ?>"
							aria-controls="<?php echo esc_attr( $hub_tab['target'] ); ?>"
						>
							<a class="tec-nav__link"><?php echo esc_html( $hub_tab['label'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>
	</dialog>
	<div class="tec-nav__modal-controls">
		<button
			id="tec-settings-nav-modal-open"
			class="tec-modal__control tec-modal__control--open"
			aria-controls="tec-settings-nav-modal"
			aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Open settings navigation', 'tribe-common' ); ?>"
		>
			<span><?php echo esc_html( $hub_tabs[0]['label'] ); ?></span>
			<img
				class="tec-modal__control-icon"
				src="<?php echo esc_url( tribe_resource_url( 'images/icons/hamburger.svg', false, null, Tribe__Main::instance() ) ); ?>"
				alt="<?php esc_attr_e( 'Open settings navigation', 'tribe-common' ); ?>"
			>
		</button>
	</div>
	<nav class="tec-settings__nav-wrapper" aria-label="<?php esc_attr_e( 'Main Help Hub Navigation', 'tribe-common' ); ?>">
		<ul class="tec-nav" role="tablist">
			<?php foreach ( $hub_tabs as $index => $hub_tab ) : ?>
				<li
					data-tab-target="<?php echo esc_attr( $hub_tab['target'] ); ?>"
					class="tec-nav__tab <?php echo esc_attr( $hub_tab['class'] ); ?>"
					role="tab"
					id="tab-main-<?php echo esc_attr( $index ); ?>"
					aria-controls="<?php echo esc_attr( $hub_tab['target'] ); ?>"
				>
					<a class="tec-nav__link"><?php echo esc_html( $hub_tab['label'] ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<div id="tec-help-hub-tab-containers" class="tec-tab-parent-container">
		<?php foreach ( $hub_tabs as $index => $hub_tab ) : ?>
			<div
				id="<?php echo esc_attr( $hub_tab['id'] ); ?>"
				class="tec-tab-container <?php echo 0 === $index ? '' : 'hidden'; ?>"
				role="tabpanel"
				aria-labelledby="tab-<?php echo esc_attr( $index ); ?>"
				data-link-title="<?php echo esc_attr( $hub_tab['label'] ); ?>"
			>
				<?php $this->template( $hub_tab['template'] ); ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>

