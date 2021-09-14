<?php
/**
 * The Virtual Events Sale admin notice.
 *
 * @since TBD
 *
 * @var string $cta_url The short URL for black friday.
 */
?>
<div class="tribe-marketing-notice__ve-sale">
	<div class="tribe-marketing-notice__ve-sale__wrap">
		<div class="tribe-marketing-notice__ve-sale__title-wrap">
			<div class="tribe-marketing-notice__ve-sale__ad-title">
				<span class="tribe-marketing-notice__ve-sale__red">S</span>
				<span class="tribe-marketing-notice__ve-sale__blue">A</span>
				<span class="tribe-marketing-notice__ve-sale__yellow">L</span>
				<span class="tribe-marketing-notice__ve-sale__green">E</span>
			</div>
		</div>
		<div class="tribe-marketing-notice__ve-sale__copy">
			Get 20% off the Virtual Events Add-On with coupon code 
			<span class="tribe-marketing-notice__ve-sale__code">virtual-2021</span>
			<span class="tribe-marketing-notice__ve-sale__limit"> for a limited time only!</span>
		</div>
		<div class="tribe-marketing-notice__ve-sale__button">
			<a 
				target="_blank" 
				rel="noopener noreferrer"
				href="<?php echo esc_url( $cta_url ); ?>" 
				class="tribe-marketing-notice__ve-sale__link">
					Order Now
			</a>
		</div>
	</div>
</div>
