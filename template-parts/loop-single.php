<?php
/**
 * Single post loop template
 *
 * @file    loop-single.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<main>
			<div <?php post_class(); ?>>
				<?php
				if ( ! is_page() ) {
					the_title( '<h1 class="headline">', '</h1>' );
				}

				if ( ! get_field( 'show_brand_details' ) && has_post_thumbnail() ) {
					?>
					<div id="brand-logo">
						<div class="sticker">
							<?php the_post_thumbnail( 'large' ); ?>
						</div>
					</div>
					<?php
				}

				the_content();
				wp_link_pages();
				get_date_meta();
				?>
			</div>
		</main>
		<?php
	endwhile;
endif;
