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

		$post_classes = array();
		if ( get_field( 'brand_closed' ) === true ) {
			$post_classes[] = 'brand-closed';
		}
		?>
		<main>
			<div <?php post_class( $post_classes ); ?>>
				<?php
				if ( 'brand' !== get_post_type() ) {
					the_title( '<h1 class="headline">', '</h1>' );

					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large' );
					}
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
