<?php if ( $authorized ) : ?>
    <p>You may now close this window.</p>
<?php else: ?>
    <form method="post">
		<?php if ( ! $logged_in ) : ?>
            <input type="text" name="username" placeholder="Username/Email"/>
            <input type="password" name="password"/>
		<?php endif; ?>
        <input type="hidden" value="<?php esc_attr_e( $promoter_key ); ?>" name="promoter_key"/>
        <input type="hidden" value="<?php esc_attr_e( $license_key ); ?>" name="license_key"/>
        <input type="hidden" value="1" name="promoter_authenticate"/>
        <button type="submit">Authorize Promoter</button>
    </form>
<?php endif; ?>

