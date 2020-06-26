<?php get_header(); ?>

<div id="content">

  <?php if ( !is_paged() ) { ?>

    <div class="stickers grid-cols-4">
      <?php get_brand_pages( 11185 ); ?>
    </div>

  <?php } ?>

  <a class="section-header" href="<?php echo get_term_link( 9 ); ?>">Reviews<?php if ( is_paged() ) { echo ', page ' . get_query_var( 'paged' ); } ?></a>

  <div class="grid-cols-2">

    <?php get_posts_by_category_slug( 'reviews' ); ?>

  </div>


  <a class="section-header" href="<?php echo get_term_link( 1 ); ?>">Blog Posts<?php if ( is_paged() ) { echo ', page ' . get_query_var( 'paged' ); } ?></a>

  <?php get_posts_by_category_slug( 'blog-posts' ); ?>

</div>

<?php get_footer(); ?>
