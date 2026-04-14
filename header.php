<?php
/**
 * Header template
 *
 * @file    header.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="google-site-verification" content="zOYbKlCTmi15V0VUqTzFI2tkjfyg1lJIVj9OdZd7qL4" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<a class="skip-link screen-reader-text" href="#content">
		<?php esc_html_e( 'Skip to content', 'cpt-theme' ); ?>
	</a>
	<?php get_template_part( 'template-parts/site-header' ); ?>
