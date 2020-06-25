<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <a <?php post_class( 'card post-card' ); ?> href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">

    <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?>

    <div class="headline-excerpt">

      <h2 class="headline"><?php the_title(); ?></h2>

      <?php $excerpt = get_the_excerpt(); ?>

      <p class="excerpt"><?php echo $excerpt; ?></p>

    </div>

  </a>

  <?php endwhile; ?>

  <?php echo the_posts_pagination(); ?>

<?php else : ?>

  <p class="post">No posts match your query.</p>

<?php endif; ?>
