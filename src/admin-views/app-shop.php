<div id="tribe-app-shop" class="wrap">

	<div class="header">
		<h1><?php esc_html_e( 'Tribe Event Add-Ons', 'tribe-common' ); ?></h1>
	</div>

	<div class="content-wrapper">
		<?php

		if ( ! empty( $banner ) ) {
			$banner_markup = '';
			if ( property_exists( $banner, 'top_banner_url' ) && ! empty( $banner->top_banner_url ) ) {
				$banner_markup = sprintf( "<img src='%s'/>", esc_url( $banner->top_banner_url ) );
			}
			if ( property_exists( $banner, 'top_banner_link' ) && ! empty( $banner->top_banner_link ) ) {
				$banner_markup = sprintf( "<a href='%s' target='_blank'>%s</a>", esc_url( $banner->top_banner_link ), $banner_markup );
			}
			echo $banner_markup;
		}

		if ( empty( $products ) ) {
			?>
			<a class="button button-primary" href="https://theeventscalendar.com/products/?utm_campaign=in-app&utm_source=addonspage&utm_medium=noload"><?php _e( 'Get Add-Ons', 'tribe-common' ); ?></a>
			<?php
		}
		else {
			$category = null;
			$i = 0;
			foreach ( (array) $products as $product ) {
				if ( $product->category != $category ) {
					if ( $category !== null ) { ?>
						</div>
					<?php } ?>

					<div class="addon-grid">

					<?php
					$category = $product->category;
				}
				?>
				<div class="tribe-addon<?php echo ( $i % 4 == 0 ) ? ' first tribe-clearfix' : '';?>">
					<div class="thumb">
						<a href="<?php echo esc_url( $product->permalink ); ?>"><img src="<?php echo esc_attr( $product->featured_image_url ); ?>" /></a>
					</div>
					<div class="caption">
						<h4><a href="<?php echo esc_url( $product->permalink ); ?>"><?php echo $product->title; ?></a></h4>

						<div class="description">
							<p><?php echo $product->description; ?></p>
						</div>
						<div class="meta">
							<?php
							if ( $product->version ) {
								echo sprintf( '<strong>%s</strong>: %s<br/>', esc_html__( 'Version', 'tribe-common' ), esc_html( $product->version ) );
							}
							if ( $product->last_update ) {
								echo sprintf( '<strong>%s</strong>: %s<br/>', esc_html__( 'Last Update', 'tribe-common' ), esc_html( $product->last_update ) );
							}
							?>
						</div>
						<a class="button button-primary" href="<?php echo esc_url( $product->permalink ); ?>">Get This Add-on</a>
					</div>
				</div>

				<?php
				$i++;
			}
		}
		?>
	</div>
</div>
