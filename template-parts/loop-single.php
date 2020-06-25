<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <main>

    <div <?php post_class(); ?>>

    <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?>

    <?php if ( ! has_shortcode( $post->post_content, 'brand-details' ) ) { do_shortcode( '[brand-details]' ); } ?>

    <?php the_content(); ?>

    <?php wp_link_pages(); ?>

    <?php get_date_meta(); ?>

    </div>

  </main>

<?php endwhile; endif; ?>
