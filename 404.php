<?php get_header(); ?>

  <div id="content">

    <h1>404 Not Found</h1>

    <p>Sorry, I know this isn't what you were hoping to see. The page you were looking for is probably gone, but you can try searching for it just to make sure:</p>

    <?php get_search_form(); ?>

    <p>If that doesn't work, maybe you can find something else on the <a href="<?php echo home_url(); ?>">front page</a>.</p>

  </div>

<?php get_footer(); ?>
