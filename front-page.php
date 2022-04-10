<?php get_header(); ?>

  <div id="content">
    <?php
      if ( ! is_paged() ) {
        get_stickers();
      }
    ?>

    <a class="section-header" href="<?php echo get_term_link( 9 ); ?>">Reviews<?php if ( is_paged() ) { echo ', page ' . get_query_var( 'paged' ); } ?></a>
    <div class="grid-cols-2">
      <?php get_posts_by_category_slug( 'reviews' ); ?>
    </div>

    <a class="section-header" href="<?php echo get_term_link( 1 ); ?>">Blog Posts<?php if ( is_paged() ) { echo ', page ' . get_query_var( 'paged' ); } ?></a>
    <?php get_posts_by_category_slug( 'blog-posts' ); ?>
  </div>

<?php get_footer(); ?>
