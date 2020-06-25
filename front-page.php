<?php get_header(); ?>

<div id="content">

  <?php if ( !is_paged() ) { ?>

    <div class="section-header">Brands</div>

    <div class="stickers grid-cols-4">

      <?php get_brand_pages( 11185 ); ?>

      <script type='text/javascript'>

  			let stickers			= document.getElementsByClassName( 'sticker' );

  			Array.prototype.forEach.call( stickers, function( sticker ) {

  				let rotation = Math.random() * ( 2 - -2) + -2;
  				sticker.style.transform = 'rotate( ' + rotation + 'deg )';

  			});

  		</script>

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
