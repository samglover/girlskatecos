<?php
/**
 * Front-page template
 *
 * @file    front-page.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

get_header();
?>

<div id="content">
	<?php
	sort_search();
	stickers();
	?>

	<h2><?php esc_html_e( 'Posts', 'girlskatecos' ); ?></h2>
	<?php get_template_part( 'template-parts/loop', 'index' ); ?>
</div>

<?php get_footer(); ?>
