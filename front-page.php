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
	<?php get_stickers(); ?>

	<a class="section-header" href="<?php echo esc_url( get_term_link( 1 ) ); ?>">Blog Posts</a>
	<?php get_posts_by_category_slug( 'blog-posts' ); ?>
	
	<a class="section-header" href="<?php echo esc_url( get_term_link( 9 ) ); ?>">Reviews</a>
	<div class="grid-cols-2">
		<?php get_posts_by_category_slug( 'reviews' ); ?>
	</div>

</div>

<?php get_footer(); ?>
