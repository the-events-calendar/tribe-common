<link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
<link rel="stylesheet" href="https://use.typekit.net/pha0nnp.css">
<link id="app" href="https://promoter.theeventscalendar.com/css/app.css" rel="stylesheet">
<style>
	.page--auth p {
		margin: 16px 0 30px;
	}
</style>

<?php if ( $authorized ) : ?>
	<p><?php esc_html_e( 'You may now close this window.', 'tribe-common' ); ?>/p>
	<?php return; ?>
<?php endif; ?>

<div class="site-wrap">
	<main id="page-content" class="page page--push page--auth">
		<div class="row row--reduced">
			<div class="promoter-logo">
				<span class="a11y-visual-hide"><?php esc_html_e( 'Promoter', 'tribe-common' ); ?>/span>
			</div>

			<h1 class="headline__large"><?php esc_html_e( 'Promoter would like to sync with your site', 'tribe-common' ); ?></h1>
			<p><?php esc_html_e( 'Please enter your website\'s credentials to continue onboarding', 'tribe-common' ); ?></p>

			<form method="post">
				<?php if ( ! $logged_in ) : ?>
					<div class="form-control-group">
						<label for="email" class="form-control-label form-control-custom-label"><?php esc_html_e( 'Username/Email', 'tribe-common' ); ?></label>
						<input type="text" name="username" placeholder="" class="form-control form-control-custom-style--border-bottom" />
					</div>
					<div class="form-control-group">
						<label for="email" class="form-control-label form-control-custom-label"><?php esc_html_e( 'Password', 'tribe-common' ); ?></label>
						<input type="password" name="password" class="form-control form-control-custom-style--border-bottom" />
					</div>
				<?php endif; ?>
				<input type="hidden" value="<?php echo esc_attr( $promoter_key ); ?>" name="promoter_key"/>
				<input type="hidden" value="<?php echo esc_attr( $license_key ); ?>" name="license_key"/>
				<input type="hidden" value="1" name="promoter_authenticate"/>
				<button class="btn btn--blue" type="submit"><?php esc_html_e( 'Authorize Promoter', 'tribe-common' ); ?></button>
			</form>
		</div>
	</main>

	<footer class="site-footer site-footer--locked">
		<div class="row">
			<div class="site-footer__logo">
				<span class="a11y-visual-hide"><?php esc_html_e( 'Promoter', 'tribe-common' ); ?></span>
			</div>
			<p class="site-footer__meta">
				&copy;<?php echo esc_html( date_i18n( 'Y' ) ); ?>
				<?php esc_html_e( 'Promoter All rights reserved.', 'tribe-common' ); ?>
				<a href="https://promoter.theeventscalendar.com/privacy"><?php esc_html_e( 'Privacy', 'tribe-common' ); ?></a>
				<?php esc_html_e( 'and', 'tribe-common' ); ?>
				<a href="https://promoter.theeventscalendar.com/terms"><?php esc_html_e( 'Terms', 'tribe-common' ); ?></a>.
			</p>
		</div>
	</footer>
</div>
