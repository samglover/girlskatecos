<?php
/**
 * Index template
 *
 * @file    index.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

get_header();
?>

<div id="content">
<?php if ( is_archive() ) { ?>
	<h1 class="headline"><?php echo single_term_title( '', false ); ?></h1>
<?php } ?>

<?php if ( is_search() ) { ?>
	<h1>Search Results for "<?php echo get_search_query(); ?>"</h1>
	<?php get_search_form(); ?>
<?php } ?>

<?php get_template_part( 'template-parts/loop', 'index' ); ?>
</div>

<div id="pagination">
<?php echo wp_kses_post( paginate_links() ); ?>
</div>

<?php get_footer(); ?>
