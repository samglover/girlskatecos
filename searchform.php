<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
  <label class="screen-reader-text" for="search-field"><?php echo _x( 'Enter search terms.', 'label' ) ?></label>
  <input id="search-field" type="search" name="s" placeholder="<?php echo esc_attr_x( 'Enter search terms.', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" />
  <input type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
</form>
