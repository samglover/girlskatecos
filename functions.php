<?php

/**
* Unregister Post Tags
*/
function unregister_tags() {
  register_taxonomy( 'post_tag', array() );
}

add_action( 'init', 'unregister_tags' );


/**
* Theme Setup
*/
function theme_setup() {

  add_theme_support( 'custom-logo', array(
    'height'      => 85,
    'width'       => 85,
    'flex-height' => false,
    'flex-width'  => false,
    'header-text' => array( 'site-title', 'site-description' ),
  ) );
  add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'wp-block-styles' );

  add_post_type_support( 'page', 'excerpt' );

  add_editor_style( 'editor-styles.css' );

}

add_action( 'after_setup_theme', 'theme_setup' );


/**
* Register Stylesheets & Scripts
*/
function register_stylesheets_scripts() {

	$template_dir_uri = get_template_directory_uri();

  // Normalize the default styles. From https://github.com/necolas/normalize.css/
	wp_register_style( 'normalize-css', $template_dir_uri . '/css/normalize.css' );
	wp_enqueue_style( 'normalize-css' );

  $cacheBusterCSS = filemtime( get_stylesheet_directory() . '/style.css' );
	wp_register_style( 'stylesheet', $template_dir_uri . '/style.css', array(), $cacheBusterCSS, 'all' );
	wp_enqueue_style( 'stylesheet' );

  // Load consolidated scripts in the footer.
	$cacheBusterFS = filemtime( get_stylesheet_directory() . '/js/footer-scripts.js' );
	wp_register_script( 'footer-scripts', $template_dir_uri . '/js/footer-scripts.js',  array( 'jquery' ), $cacheBusterFS, true );
	wp_enqueue_script( 'footer-scripts' );

}

add_action( 'wp_enqueue_scripts', 'register_stylesheets_scripts' );


function remove_jquery_migrate( $scripts ) {

  if ( !is_admin() && isset( $scripts->registered[ 'jquery' ] ) ) {

    $script = $scripts->registered[ 'jquery' ];

    // Check whether the script has any dependencies.
    if ( $script->deps ) {
      $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
    }

  }

}

add_action( 'wp_default_scripts', 'remove_jquery_migrate' );


/**
* Register Footer Menu
*/
function register_menus() {

	register_nav_menus(
		array(
			'footer-menu' => 'Footer Menu'
		)
	);

}

add_action( 'init', 'register_menus' );


/**
* Breadcrumbs
*/
function get_breadcrumbs() {

  if ( is_front_page() ) { return; }

  if ( is_singular() ) {

    $post_id        = get_the_ID();
    $breadcrumbs[]  = '<span class="breadcrumb last-breadcrumb">' . get_the_title( $post_id ) . '</span>';

    if ( is_singular( 'page' ) ) {

      $parent_id = wp_get_post_parent_id( $post_id );

      while ( $parent_id ) {

        $parent_url   = get_the_permalink( $parent_id );
        $parent_title = get_the_title( $parent_id );

        $breadcrumbs[]  = '<span class="breadcrumb"><a href="' . $parent_url . '">' . $parent_title . '</a></span>';
        $parent_id      = wp_get_post_parent_id( $parent_id );

      }

    }

    if ( is_singular( 'post' ) ) {

      $categories       = get_the_terms( $post_id, 'category' );

      $category_id      = $categories[ 0 ]->term_id;
      $category_url     = get_term_link( $category_id );
      $category_title   = $categories[ 0 ]->name;

      $breadcrumbs[]    = '<span class="breadcrumb"><a href="' . $category_url . '">' . $category_title . '</a></span>';

      $category_parent  = $categories[ 0 ]->parent;

      while ( $category_parent ) {

        $category       = get_term( $category_parent );

        $category_id    = $category->term_id;
        $category_url   = get_term_link( $category_id );
        $category_title = $category->name;

        $breadcrumbs[]  = '<span class="breadcrumb"><a href="' . $category_url . '">' . $category_title . '</a></span>';

        $category_parent  = $category->parent;

      }

    }

  } elseif ( is_archive() ) {

    $breadcrumbs[] = '<span class="breadcrumb last-breadcrumb">' . single_term_title( '', FALSE ) . '</span>';

  }

  $breadcrumbs[]  = '<span class="breadcrumb home-breadcrumb"><a href="' . home_url() . '">Home</a></span>';

  $breadcrumbs    = array_reverse( $breadcrumbs );

  ob_start();

    ?>

    <div id="breadcrumbs" class="small">

      <?php echo implode( ' / ', $breadcrumbs ); ?>

    </div>

    <?php

  echo ob_get_clean();

}


/**
* Brand Pages loop
*/
function get_brand_pages( $page_id = null ) {

  if ( ! $page_id ) { $page_id = get_the_ID(); }

  $args = array(
    'order'           => 'ASC',
    'orderby'			    => 'title',
    'post_type'       => 'page',
    'posts_per_page'  => -1,
    'post__not_in'    => array( 8 ),
  );

  $brand_pages_query = new WP_Query( $args );

  ob_start();

    if ( $brand_pages_query->have_posts() ) : while ( $brand_pages_query->have_posts() ) : $brand_pages_query->the_post();

      ?>

      <a <?php post_class( 'sticker' ); ?> href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?>
      </a>

      <?php

    endwhile; endif;

  echo ob_get_clean();

}


function get_posts_by_category_slug( $category = null ) {

  if ( $category ) {
    $args = array(
      'category_name' => $category,
    );
  }

  $posts_query = new WP_Query( $args );

  ob_start();

    if ( $posts_query->have_posts() ) : while ( $posts_query->have_posts() ) : $posts_query->the_post();

    ?>

    <a <?php post_class( 'card page-card' ); ?> href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">

      <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?>

      <div class="headline-excerpt">

        <h2 class="headline"><?php the_title(); ?></h2>

        <?php if ( ! has_term( 'reviews', 'category' ) ) { ?>
          <?php $excerpt = get_the_excerpt(); ?>
          <p class="excerpt"><?php echo $excerpt; ?></p>
        <?php } ?>

      </div>

    </a>

    <?php

    endwhile; endif;

  echo ob_get_clean();

}


/**
* Get the Post Date
*/
function get_date_meta() {

  $published_date = get_the_time( 'F jS, Y' );
  $updated_date   = get_the_modified_date( 'F jS, Y' );

  $date = '<p class="date">Published on ' . $published_date . '.';

    if ( $published_date !== $updated_date ) {
      $date .= ' Last updated ' . $updated_date . '.';
    }

  $date .= '</p>';

  echo $date;

}


/**
* Adds a shortcode for outputting the brand details.
*/
function gsc_brand_details() {

  global $post;

  if ( get_field( 'show_brand_details' ) ) {

    $brand = get_the_title();

    $schema                   = new stdClass();
    $schema->{ '@context' }   = 'https://schema.org';
    $schema->{ '@type' }      = 'Brand';
    $schema->name             = $brand;

    if ( version_compare( phpversion(), '7.1', '>=' ) ) {
	    ini_set( 'serialize_precision', -1 );
		}

		$schema_json  = '<script type="application/ld+json">' . PHP_EOL;
		$schema_json .= wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
		$schema_json .= '</script>' . PHP_EOL;

    ob_start();

      ?>

      <div id="brand-details" class="card<?php if ( has_post_thumbnail() ) { echo ' has-brand-logo'; } ?>">

        <div class="details">

          <h1 class="headline"><span itemprop="name"><?php echo $brand; ?></span></h1>

          <?php if ( get_field( 'owner' ) == true ) { ?>

            <?php $owner = get_field( 'owner' ); ?>

            <h4>Owner(s)</h4>
            <p><?php echo $owner; ?></p>

          <?php } ?>

          <h4>Links</h4>

          <ul>

            <?php if ( get_field( 'shop_url' ) == true ) { ?>

              <?php $shop_url= get_field( 'shop_url' ); ?>

              <li><a href="<?php echo $shop_url; ?>">Shop</a></li>

            <?php } ?>

            <?php if ( get_field( 'instagram' ) == true ) { ?>

              <?php $ig= get_field( 'instagram' ); ?>

              <li><a href="<?php echo $ig; ?>">Instagram</a></li>

            <?php } ?>

          </ul>

          <h4>Products</h3>

          <?php if ( get_field( 'products' ) == true ) { ?>

            <?php $products = get_field( 'products' ); ?>

            <ul>

              <?php foreach ( $products as $product ) { ?>

                <li><?php echo $product; ?></li>

              <?php } ?>

            </ul>

          <?php } ?>

          <?php echo $schema_json; ?>

        </div>

        <?php if ( has_post_thumbnail() ) { ?>

          <div class="sticker">
            <?php the_post_thumbnail( 'medium' ); ?>
          </div>

        <?php } ?>

      </div>

      <?php

    return ob_get_clean();

  }

}

add_shortcode( 'brand-details', 'gsc_brand_details' );


/**
* Adds the [brand-details] shortcode if it it isn't already in the content.
*/
function gsc_brand_details_default_shortcode( $content ) {

  if ( get_field( 'show_brand_details' ) && ! has_shortcode( $content, 'brand-details' ) ) {
    $content = do_shortcode( '[brand-details]' ) . $content;
  }

  return $content;

}

add_filter( 'the_content', 'gsc_brand_details_default_shortcode' );


/**
* Renders the star rating.
*/
function gsc_star( $star_width = '100%' ) {

  $star = '<svg class="star" style="width: ' . $star_width . '%" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="40" viewBox="0 0 24 24" width="40"><g><path d="M0,0h24v24H0V0z" fill="none"/><path d="M0,0h24v24H0V0z" fill="none"/></g><g><path d="M12,17.27l4.15,2.51c0.76,0.46,1.69-0.22,1.49-1.08l-1.1-4.72l3.67-3.18c0.67-0.58,0.31-1.68-0.57-1.75l-4.83-0.41 l-1.89-4.46c-0.34-0.81-1.5-0.81-1.84,0L9.19,8.63L4.36,9.04c-0.88,0.07-1.24,1.17-0.57,1.75l3.67,3.18l-1.1,4.72 c-0.2,0.86,0.73,1.54,1.49,1.08L12,17.27z"/></g></svg>';

  return $star;

}

function gsc_star_rating( $rating = null ) {

  if ( is_null( $rating ) ) { return; }

  ob_start();

    ?>

    <div class="star-rating-wrapper" itemprop="reviewCount" content="1">

      <div class="stars">

        <?php

        $i = 0;

        while ( $i < 5 ) {

          echo gsc_star();

          $i++;

        }

        ?>

        <div class="star-rating" style="width: <?php echo $rating / 5 * 100; ?>%">

          <?php

          $stars        = explode( '.', $rating );
          $full_stars   = $stars[ 0 ];
          $partial_star = isset( $stars[ 1 ] ) ? $stars[ 1 ] / 1 * 100 . '%' : 0;

          $i = 0;

          while ( $i < $full_stars ) {

            echo gsc_star();

            $i++;

          }

          echo gsc_star( $partial_star );

          ?>

        </div>

      </div>

      <span class="star-rating-label entry-meta"><span itemprop="ratingValue"><?php echo $rating; ?></span>/5 stars</span>

    </div>

    <?php

  return ob_get_clean();

}


/**
* Adds product reviews.
*/
function gsc_product_review( $content ) {

  if ( is_single() && get_field( 'gsc_show_product_rating' ) && have_rows( 'gsc_product_rating' ) ) :

    $product_rating = array();

    while ( have_rows( 'gsc_product_rating' ) ) : the_row();

      $product_rating[ 'brand' ]        = get_sub_field( 'gsc_brand_name' );            // Page ID.
      $product_rating[ 'name' ]         = get_sub_field( 'gsc_product_name' );
      $product_rating[ 'type' ]         = get_sub_field( 'gsc_product_type' );
      $product_rating[ 'manufacturer' ] = get_sub_field( 'gsc_product_manufacturer' );
      $product_rating[ 'rating' ]       = get_sub_field( 'gsc_product_rating' );
      $product_rating[ 'pros' ]         = get_sub_field( 'gsc_product_pros' );
      $product_rating[ 'cons' ]         = get_sub_field( 'gsc_product_cons' );

    endwhile;

    $schema                   = new stdClass();
    $schema->{ '@context' }   = 'https://schema.org';
    $schema->{ '@type' }      = 'Product';
    $schema->name             = $product_rating[ 'name' ];
    $schema->description      = $product_rating[ 'type' ];

    $schema->brand              = new stdClass();
    $schema->brand->{ 'type' }  = 'Organization';
    $schema->brand->name        = get_the_title( $product_rating[ 'brand' ] );

    $schema->review               = new stdClass();
    $schema->review->{ '@type' }  = 'Review';
    $schema->review->reviewBody   = 'Pros: ' . strip_tags( $product_rating[ 'pros' ] ) . ' Cons: ' . strip_tags( $product_rating[ 'cons' ] );

    $schema->review->author               = new stdClass();
    $schema->review->author->{ '@type' }  = 'Person';
    $schema->review->author->name         = get_the_author_meta( 'display_name' );

    $schema->review->reviewRating               = new stdClass();
    $schema->review->reviewRating->{ '@type' }  = 'Rating';
    $schema->review->reviewRating->ratingValue  = $product_rating[ 'rating' ];

    if ( version_compare( phpversion(), '7.1', '>=' ) ) {
	    ini_set( 'serialize_precision', -1 );
		}

		$schema_json  = '<script type="application/ld+json">' . PHP_EOL;
		$schema_json .= wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
		$schema_json .= '</script>' . PHP_EOL;


    ob_start();

      ?>

      <h2>Rating</h2>
      <div id="product-rating-box" class="card">
        <h3 itemprop="description"><a href="<?php echo get_the_permalink( $product_rating[ 'brand' ] ); ?>"><?php echo get_the_title( $product_rating[ 'brand' ] ); ?></a> <?php echo $product_rating[ 'name' ]; ?></h3>
        <?php echo gsc_star_rating( $product_rating[ 'rating' ] ); ?>
        <h4>Manufacturer</h4>
        <p><?php echo $product_rating[ 'manufacturer' ]; ?></p>
          <h4>Pros</h4>
          <?php echo $product_rating[ 'pros' ]; ?>
          <h4>Cons</h4>
          <?php echo $product_rating[ 'cons' ]; ?>
        <?php echo $schema_json; ?>
      </div>

      <?php

    $rating = ob_get_clean();

    $content .= $rating;

  endif;

  return $content;

}

add_filter( 'the_content', 'gsc_product_review' );
