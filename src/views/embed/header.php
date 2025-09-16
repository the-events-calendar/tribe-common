<?php
/**
 * Embed header template.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/embed/header.php
 *
 * @package TEC/Common
 *
 * @since 6.5.4
 * @version 6.5.4
 *
 * @var Template $this The template object.
 */

use TEC\Common\Template;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<title><?php wp_title(); ?></title>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="profile" href="https://gmpg.org/xfn/11">

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>
<?php
