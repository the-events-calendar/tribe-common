<?php
/**
 * The default template for a Tabbed View.
 *
 * @var Tribe__Tabbed_View $view
 */

/** @var Tribe__Tabbed_View__Tab[] $tribe_tabs */
$tribe_tabs = $view->get_visibles();
?>

<?php if ( count( $tribe_tabs ) <= 1 ) : ?>
	<?php $reset_label = reset( $tribe_tabs )->get_label(); ?>
	<h1><?php echo esc_html( $reset_label ); ?></h1>
<?php else : ?>
	<div class="tabbed-view-wrap wrap">
		<?php if ( $view->get_label() ) : ?>
			<h1>
				<?php echo esc_html( $view->get_label() ); ?>
				<?php
					/**
					 * Add an action to render content after text label.
					 *
					 * @since 4.12.17
					 *
					 * @param Tribe__Tabbed_View $view Tabbed View Object.
					 */
					do_action( 'tribe_tabbed_view_heading_after_text_label', $view );
				?>
			</h1>
		<?php endif; ?>

		<nav class="nav-tab-wrapper">
			<ul class="tec-nav-tabs">
				<?php foreach ( $tribe_tabs as $tribe_tab ) : ?>
					<li class="nav-tab<?php echo $tribe_tab->is_active() ? ' nav-tab-active' : ''; ?>"">
						<a id="<?php echo esc_attr( $tribe_tab->get_slug() ); ?>"
							class="nav-link"
							href="<?php echo esc_url( $tribe_tab->get_url() ); ?>"><?php echo esc_html( $tribe_tab->get_label() ); ?>
						</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</nav>
	</div>
<?php endif; ?>
