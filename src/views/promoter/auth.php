<link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
<link rel="stylesheet" href="https://use.typekit.net/pha0nnp.css">
<link id="app" href="https://promoter.dev.tri.be/css/app.css" rel="stylesheet">
<style>
	.page--auth p {
		margin: 16px 0 30px;
	}
</style>
<?php if ( $authorized ) : ?>
	<p>You may now close this window.</p>
<?php else: ?>
	<div class="site-wrap">
		<main id="page-content" class="page page--push page--auth">
			<div class="row row--reduced">
				<div class="promoter-logo">
					<span class="a11y-visual-hide">Promoter</span>
				</div>

				<h1 class="headline__large">Promoter would like to sync with your site</h1>
				<p>Please enter your website's credentials to continue onboarding</p>

				<form method="post">
					<?php if ( ! $logged_in ) : ?>
						<div class="form-control-group">
							<label for="email" class="form-control-label form-control-custom-label">Username/Email</label>
							<input type="text" name="username" placeholder="" class="form-control form-control-custom-style--border-bottom" />
						</div>
						<div class="form-control-group">
							<label for="email" class="form-control-label form-control-custom-label">Password</label>
							<input type="password" name="password" class="form-control form-control-custom-style--border-bottom" />
						</div>
					<?php endif; ?>
					<input type="hidden" value="<?php esc_attr_e( $promoter_key ); ?>" name="promoter_key"/>
					<input type="hidden" value="<?php esc_attr_e( $license_key ); ?>" name="license_key"/>
					<input type="hidden" value="1" name="promoter_authenticate"/>
					<button class="btn btn--blue" type="submit">Authorize Promoter</button>
				</form>
			</div>
		</main>

		<footer class="site-footer site-footer--locked">
			<div class="row">
				<div class="site-footer__logo">
					<span class="a11y-visual-hide">Promoter</span>
				</div>
				<p class="site-footer__meta">©2018 Promoter All rights reserved. <a href="#">Privacy</a> and <a href="#">Terms</a>.
				</p>
			</div>
		</footer>
	</div>
<?php endif; ?>

