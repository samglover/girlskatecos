<?php
/**
 * "Stickers"
 *
 * @file    frontend/stickers.php
 * @package Girl_Skate_Cos
 * @since   1.1.0
 */

/**
 * Outputs brand "stickers"
 */
function stickers() {
	$args = array(
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => 'show_in_brand_list',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'show_in_brand_list',
				'compare' => '=',
				'value'   => true,
			),
		),
		'orderby'        => 'rand',
		'post_type'      => 'page',
		'posts_per_page' => -1,
	);

	$brand_pages_query = new WP_Query( $args );

	if ( $brand_pages_query->have_posts() ) :
		?>
		<div id="stickers" class="stickers grid-cols-4">
			<?php while ( $brand_pages_query->have_posts() ) : ?>
				<?php $brand_pages_query->the_post(); ?>
				<a 
					<?php post_class( 'sticker' ); ?>
					href="<?php the_permalink(); ?>"
					rel="bookmark"
					data-brand-name="<?php the_title(); ?>"
				>
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'medium' );
					}
					?>
				</a>
			<?php endwhile; ?>
			<p id="no-stickers" class="hidden" title="<?php esc_attr_e( 'No cos. found.', 'girlskatecos' ); ?>">
				<?php esc_html_e( 'No cos. found.', 'girlskatecos' ); ?>
			</p>
		</div>
		<?php
	endif;
}