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

	<a class="section-header" href="<?php echo esc_url( get_term_link( 1 ) ); ?>">
		<?php esc_html_e( 'Blog posts', 'girlskatecos' ); ?>
	</a>
	<?php get_template_part( 'template-parts/loop', 'index' ); ?>
</div>

<?php get_footer(); ?>
