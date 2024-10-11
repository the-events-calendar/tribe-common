<?php
/**
 * Black Friday Conditional template.
 *
 * @since TBD
 *
 * @var string $image_src Where the image is located.
 * @var string $link      Where the image should link to.
 */

$year = date_i18n( 'Y' );

?>

<a
    class="black-friday-promo"
    href="<?php echo esc_url( $link ); ?>"
    target="_blank"
    rel="noopener nofollow"
>
    <img
        src="<?php echo esc_url( $image_src ); ?>"
        alt="<?php echo sprintf( esc_attr_x( '%1$s Black Friday Sale for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Black Friday Ad', 'tribe-common' ), $year ); ?>"
        class="black-friday-promo__branding-image"
    />
</a>
