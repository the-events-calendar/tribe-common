<?php
/**
 * The default template for a Tabbed View.
 */
?>

<h2 class="nav-tab-wrapper">
	<?php foreach ( $view->get() as $tab ): ?>
		<?php
		if ( ! $tab->is_visible() ) {
			continue;
		}
		?>
        <a id="<?php echo esc_attr( $tab->get_slug() ); ?>"
           class="nav-tab<?php echo( $tab->is_active() ? ' nav-tab-active' : '' ); ?>"
           href="<?php echo esc_url( $tab->get_url() ); ?>"><?php echo esc_html( $tab->get_label() ); ?>
        </a>
	<?php endforeach; ?>
</h2>
