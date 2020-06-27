<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <main>

    <div <?php post_class(); ?>>

    <?php if ( ! get_field( 'show_brand_details' ) && has_post_thumbnail() ) { ?>

      <div id="brand-logo">
        <div class="sticker">
          <?php the_post_thumbnail( 'medium' ); ?>
        </div>
      </div>

    <?php } ?>

    <?php the_content(); ?>

    <?php wp_link_pages(); ?>

    <?php get_date_meta(); ?>

    </div>

  </main>

<?php endwhile; endif; ?>
