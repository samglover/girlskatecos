<?php
/**
 * Index loop template
 *
 * @file    loop-index.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<a <?php post_class( 'card post-card' ); ?> href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'medium' );
			}
			?>

			<div class="headline-excerpt">
				<h2 class="headline"><?php the_title(); ?></h2>
				<p class="excerpt">
					<?php echo wp_kses_post( get_the_excerpt() ); ?>
				</p>
			</div>
		</a>
		<?php
	endwhile;

	echo wp_kses_post( the_posts_pagination() );
else :
	?>
	<p class="post">No posts match your query.</p>
	<?php
endif;
