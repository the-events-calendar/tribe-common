<?php
/**
 * Template for Black Friday Promo.
 *
 * @since TBD
 */
?>

<?php
$images_dir =  \Tribe__Main::instance()->plugin_url . 'src/resources/images/';
$branding_url = $images_dir . 'logo/tec-brand.svg';
$promo_bkg = $images_dir . 'promos/bf-promo.png';
?>

<style>
	.promo-wrapper {
		align-items: flex-start;
		display: flex;
		flex-direction: column-reverse;
		justify-content: space-between;
	}

	.black-friday-promo {
		background: url( <?php echo $promo_bkg ?>) no-repeat center center;
		border-radius: 10px;
		display: grid;
		grid-template-areas:
		"space promo-content";
		grid-template-columns: auto 150px;
		height: 150px;
		margin: 10px 0;
		max-width: 100%;
		width: 450px;
	}

	.black-friday-promo__content {
		grid-area: promo-content;
		padding-top: 8px;
		text-align: center;
	}

	.black-friday-promo__text {
		color: #0F1031;
		font-family: monospace;
		font-size: 16px;
		line-height: 1;
		text-transform: uppercase;
	}

	.wp-core-ui .black-friday-promo__button {
		background: #3D54FF;
		border-color: transparent;
		border-radius: 20px;
		color: white;
		font-size: 12px;
		height: 34px;
		line-height: 32px;
		min-height: unset;
		width: 115px;
	}

	.wp-core-ui .black-friday-promo__button:hover {
		background: #1c39bb;
		border-color: transparent;
		color: white;
	}

	.promo-tec-branding img {
		max-width: 390px;
		width: 100%;
	}

	@media (min-width: 1024px) {
		.promo-wrapper {
			align-items: center;
			flex-direction: row;
		}

		.promo-tec-branding {
			padding-right: 10px;
			width: calc(100% - 450px);
		}
	}
</style>

<div class="promo-wrapper">
	<div class="promo-tec-branding">
		<img
			src="<?php echo esc_url( $branding_url ); ?>"
			alt="<?php echo esc_attr( 'The Events Calendar brand logo', 'tribe-common' ); ?>"
		/>
	</div>
	<div class="black-friday-promo">
		<div class="black-friday-promo__content">
			<p class="black-friday-promo__text">Our biggest<br/> sale of the<br/> year ends<br/> soon</p>
			<a href="https://evnt.is/1aqi" class="button black-friday-promo__button">
				<?php echo esc_html( 'Save now', 'tribe-common' ); ?>
			</a>
		</div>
	</div>
</div>